<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Constantes\ConstanteEstado;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Http\Inputs\TramiteInput;
use App\Models\ActualizacionMeses;
use App\Models\Comuna;
use App\Models\PersonasExport;
use App\Models\RJuridicos;
use App\Models\Objeto;
use App\Models\Personas;
use App\Models\Solicitud;
use App\Models\VW_DIRECTEMAR;
use App\Models\VWDetalleTasacionVehiculos;
use App\Models\VWPropiedad;
use App\Sequence;
use App\Utiles;
use Illuminate\Support\Facades\DB;
use Throwable;

class ActualizacionPropiedadJuridico extends TramiteArc
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA;
    public $tituloFormulario = 'Solicitud de Actualización de Participación';
    public $nombreSolicitud = 'en Empresa o Sociedad';

    public $sociedad;
    protected $periodoActual;

    public function __construct(TramiteInput $input = null)
    {
        parent::__construct($input);
        if (!empty($input)) {
            $this->periodoActual = $this->obtenerPeriodo();
        }
    }

    protected function obtenerPeriodo()
    {
        $periodo = ActualizacionMeses::orderBy('PERIODO_RSH', 'desc')
            ->first();

        return $this->formatearPeriodo($periodo);
    }

    protected function formatearPeriodo(ActualizacionMeses $periodo)
    {
        $out = (object)[
            'periodo' => $periodo->PERIODO_RSH,
            'meses' => [],
        ];
        for ($i = 1; $i <= 12; $i++) {
            $out->meses[$i] = (object)[
                'anio' => substr($periodo->{'MES_' . $i . '_ACTUALIZA'}, 0, -2),
                'nMes' => intval(substr($periodo->{'MES_' . $i . '_ACTUALIZA'}, 4)),
                'mes' => Utiles::obtenerMes(substr($periodo->{'MES_' . $i . '_ACTUALIZA'}, 4)),
            ];
        }

        return $out;
    }

    protected function cargarDataTramite()
    {
        $this->sociedad = RJuridicos::where('r_jur_idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->first();
        if ($this->sociedad) {
            $this->sociedad->MOTIVO = Constante::$SUB_TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA_STRING[$this->sociedad->R_JUR_ACTUALIZACION];
            $this->sociedad->PERIODO_EVENTO_ANIO = substr($this->sociedad->R_JUR_FECHAEVENTO, 0, -2);
            $this->sociedad->PERIODO_VENTA_MES = intval(substr($this->sociedad->R_JUR_FECHAEVENTO, 4));
        }
    }

    protected function consultarTitular()
    {
        if ($this->solicitud->RUN_INTEGRANTE != $this->solicitud->RUN_CIUDADANO) {
            $runTitular = $this->solicitud->RUN_INTEGRANTE;
        } else {
            $runTitular = $this->solicitud->RUN_CIUDADANO;
        }
        $this->titular = Personas::findByRun($runTitular);
    }

    protected function obtenerPersonasAsociadas()
    {
        if ($this->solicitud->RUN_INTEGRANTE != $this->solicitud->RUN_CIUDADANO) {
            $this->agregarFirmante($this->titular);
        }
        $this->agregarPersonasAsociadas($this->titular);
    }


    //TODO
    protected function guardarSolicitud(array $data)
    {
        $vehiculo = $data['vehiculo'];
        $runIntegrante = $data['runIntegrante'];
        $idMotivo = $data['idMotivo'];
        $periodo = $data['periodo'];

        $nuevoFolio = $this->obtenerNuevoFolio();
        $datoNuevo = null;
        $datoActual = $vehiculo->VEH_ID;

        DB::beginTransaction();
        try {
            $this->obtenerRegistroSolicitud($datoActual, $datoNuevo, $nuevoFolio);

            $vehiculo_new = null;
            if (empty($this->vehiculo)) {
                $vehiculo_new = $vehiculo->replicate();
                unset($vehiculo_new->RN);
                $vehiculo_new->VEH_ID = Sequence::nextValue('seq_detalle_vehiculos_pk');
            } else {
                $vehiculo_new = $this->vehiculo;
                unset($vehiculo_new->MOTIVO);
                unset($vehiculo_new->PERIODO_VENTA_ANIO);
                unset($vehiculo_new->PERIODO_VENTA_MES);
            }

            $this->solicitud->RUN_INTEGRANTE = $runIntegrante;
            $this->solicitud->DATO_NUEVO = $vehiculo_new->VEH_ID;
            $this->solicitud->ID_SUB_TIPO_SOLICITUD = $idMotivo; // para solicitar adjuntar los documentos

            $vehiculo_new->C_TACTUALIZACION_VEHICULO = $idMotivo;
            $vehiculo_new->VEH_DET_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $vehiculo_new->VEH_DET_VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $vehiculo_new->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $vehiculo_new->VEH_VENTA_PERIODO = $periodo;
            $vehiculo_new->VEH_DET_PERIODO = $this->periodoActual->periodo;
            $vehiculo_new->VEH_DET_IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
            $vehiculo_new->C_TRECTIFICACION_VEHICULO = null;
            $vehiculo_new->save();

            $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
            $this->solicitud->save();
            $this->configurarCargaInformacionDeTramite();
            DB::commit();

        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }


    //TODO
    public function editarSolicitud()
    {
        $this->validarPendienteDocumentos();

        $runIntegrante = $this->titular->PER_RUN;
        $idVehiculo = $this->input->get('id-vehiculo');
        $periodo = $this->input->anio . str_pad($this->input->mes, 2, '0', STR_PAD_LEFT);
        $motivo = $this->input->motivo;
        $idMotivo = array_search($motivo,
            Constante::$SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL_STRING);

        $this->verificarFirmaSimple();
        $this->guardarSolicitud([
            'runIntegrante' => $runIntegrante,
            'periodo' => $periodo,
            'idMotivo' => $idMotivo,
            'vehiculo' => $this->obtenerVehiculo($idVehiculo),
        ]);
        $this->generarPdfFirmaSimple();
    }

    //TODO
    protected function validarPendienteDocumentos()
    {

    }

    public function enDigitacion()
    {
        return $this->editarSolicitud();
    }

    public function pendienteDocumentos()
    {
        return $this->editarSolicitud();
    }


    //TODO
    public function pendienteResolver()
    {
        $vigenteActual = $this->solicitud->VIGENTE;
        if (!(new Adjuntos)->validarDocumentosRequeridos($this->solicitud)) {
            throw new ExcepcionReglaNegocio('No se han adjuntado todos los documentos requeridos.',
                Constante::ERR_NEGOCIO_ADJUNTOS_REQUERIDOS);
        }
        $this->editarSolicitud();

        DB::beginTransaction();
        try {
            if (!self::esMunicipal() && !self::esChileAtiende()) {
                $this->solicitud->VIGENTE = Constante::$vigenteSegunAccionNoMunicipal[$this->accion];
            }
            if (self::ingresaAMunicipal()) {
                $this->solicitud->FECHA_CREACION = $this->fechaActual;

                if ($this->solicitud->VIGENTE == Constante::VIGENTE_1_VIGENTE) {
                    $comuna = self::leerComunaDesdeGrupoFamiliarCiudadano($this->titular->PER_RUN,
                        $this->tipoSolicitud);
                    $usuarioEncargado = Comuna::obtenerEncargadoComunalParaCiudadano($comuna);
                    $this->solicitud->ID_USUARIO_ASIGNADO = $usuarioEncargado;
                    $this->solicitud->FECHA_ASIGNACION = $this->fechaActual;
                }
            }
            $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;
            $this->solicitud->ID_ESTADO = $this->flujo[$this->solicitud->ID_ESTADO][$this->accion][self::ESTADO];
            $this->solicitud->update();
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }

        /*$vigenteNuevo = $this->solicitud->VIGENTE;
        if ($vigenteActual == Constante::VIGENTE_3_SOLICITUD_BORRADOR &&
            $vigenteNuevo == Constante::VIGENTE_1_VIGENTE
        ) {
            $metodo = 'POST';
            $url = '/enviarmail';
            $datosformulario = array('idsolicitud' => $this->solicitud->ID_SOLICITUD);
            try {
                Utiles::enviandoCorreo($metodo, $url, $datosformulario, 5);
            } catch (Throwable $ex) {
                Log::error($ex->getMessage() . ' Function->pendienteResolver(ArcTramite), Error al enviar correo de notificación para solicitud: ' .$this->solicitud->id_solicitud);
            }
        }*/
    }

    public function devolverGestion()
    {
        if (empty($this->observacion)) {
            throw new ExcepcionReglaNegocio('El campo observacion es requerido.',
                Constante::ERR_NEGOCIO_CAMPO_DESCRIPCION_OBLIGATORIO);
        }
        DB::beginTransaction();
        try {
            $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;
            $this->solicitud->ID_ESTADO = $this->flujo[$this->solicitud->ID_ESTADO][$this->accion]['estado'];
            $this->solicitud->OBS_SOLICITUD = $this->observacion;
            $this->solicitud->save();
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function actualizarRegistrosAprobacion()
    {

    }

    public function agendado()
    {
        return null;
    }


    public static function obtenerSociedades($runSocio,$listaRunSociedades = false)
    {
        $sociedad = array();

        $data = RJuridicos::select('r_jur_id', 'run_sociedad')
            ->where('run_socio', $runSocio)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('r_jur_calculoactivo', Constante::VIGENTE_1_VIGENTE)
            ->get();

        foreach ($data as $empresa) {
            $solicitud = Solicitud::where('RUN_CIUDADANO',$runSocio)
                ->where('id_tipo_solicitud', Constante::TIPO_SOLICITUD_80_RECTIFICACION_DE_PARTICIPACION_JURIDICA)
                ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                ->whereNotIn('id_estado', ConstanteEstado::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
                ->where('dato_actual',$empresa['R_JUR_ID'])
                ->first();
            if (!isset($solicitud)){
                if ($listaRunSociedades == false){
                    $sociedad[$empresa['R_JUR_ID']] = [];
                    $sociedad[$empresa['R_JUR_ID']]['datos-sociedad']['id-juridico'] = $empresa['R_JUR_ID'];
                    $sociedad[$empresa['R_JUR_ID']]['datos-sociedad']['run-sociedad'] = $empresa['RUN_SOCIEDAD'];
                    $sociedad[$empresa['R_JUR_ID']]['datos-sociedad']['run-ofuscado'] = substr_replace($empresa['RUN_SOCIEDAD'],
                        'XXXX', 0, 4);
                }else{
                    $sociedad[] = $empresa['RUN_SOCIEDAD'];
                }
            }
        }

        return $sociedad;
    }

    public static function obtenerSocios($runSociedad)
    {
        $socios = array();
        $dataJuridica = RJuridicos::select('r_jur_id', 'run_socio')
            ->where('run_sociedad', $runSociedad)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('r_jur_calculoactivo', Constante::VIGENTE_1_VIGENTE)
            ->get();

        foreach ($dataJuridica as $socio) {
            $socios[] = $socio['RUN_SOCIO'];
        }
        return $socios;
    }

    public static function obtenerPropiedadesTerrestres($runSociedad, $runSocio)
    {
        $terrestres = [];

        $sqlVehiculo = VWDetalleTasacionVehiculos:: where('run_sociedad', $runSociedad)
            ->whereNotNull('veh_det_patente')
            ->where('veh_det_run', $runSocio)
            ->where('r_juridico', Constante::VIGENTE_1_VIGENTE)
            ->where('veh_det_vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('veh_det_calculoactivo', Constante::VIGENTE_1_VIGENTE)
            ->get();

        foreach ($sqlVehiculo as $listado) {
            $terrestres[$listado->VEH_ID] = [];
            $terrestres[$listado->VEH_ID]['id'] = $listado->VEH_ID;
            $terrestres[$listado->VEH_ID]['marca'] = $listado->VEH_DET_MARCA;
            $terrestres[$listado->VEH_ID]['modelo'] = $listado->VEH_DET_MODELO;
            $terrestres[$listado->VEH_ID]['patente'] = $listado->VEH_DET_PATENTE;
            $terrestres[$listado->VEH_ID]['anio'] = $listado->VEH_DET_ANNIO_FABRICACION;
            $terrestres[$listado->VEH_ID]['tasacion'] = $listado->VEH_DET_TASACION;
            $terrestres[$listado->VEH_ID]['fecha-fuente'] = $listado->FECHAFUENTE;
            $terrestres[$listado->VEH_ID]['run-sociedad'] = $listado->RUN_SOCIEDAD;
            $terrestres[$listado->VEH_ID]['run-sociedad-ofuscado'] = $listado->RUN_SOCIEDAD_OFUSCADO;
        }

        return $terrestres;
    }

    public static function obtenerPropiedadesMaritimas($runSociedad, $runSocio)
    {
        $maritimos = [];

        $sqlVehiculo = VW_DIRECTEMAR::where('run_sociedad', $runSociedad)
            ->where('run', $runSocio)
            ->where('r_juridico', Constante::VIGENTE_1_VIGENTE)
            ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->get();

        foreach ($sqlVehiculo as $listado) {
            $maritimo['id'] = $listado->ID_DIRECTEMAR;
            $maritimo['id-movil'] = $listado->ID_MOVIL;
            $maritimo['matricula-movil'] = $listado->NRMATRICULA;
            $maritimo['nombre-movil'] = $listado->NMMOVIL;
            $maritimo['codigo-actividad'] = $listado->CDTPACTIVIDAD;
            $maritimo['tipo-actividad'] = $listado->GLTPACTIVIDAD;
            $maritimo['codigo-puerto'] = $listado->CDPTOMATRICULA;
            $maritimo['puerto-matricula'] = $listado->PUERTOMATRICULA;
            $maritimo['run-sociedad'] = $listado->RUN_SOCIEDAD;
            $maritimo['run-sociedad-ofuscado'] = $listado->RUN_SOCIEDAD_OFUSCADO;
            $maritimos[$listado->ID_DIRECTEMAR] = $maritimo;
        }
        return $maritimos;
    }

    public static function obtenerPropiedades($runSociedad, $runSocio)
    {
        $propiedades = [];

        $sqlPropiedad = VWPropiedad::where('run_sociedad', $runSociedad)
            ->where('rut', $runSocio)
            ->where('r_juridico', Constante::VIGENTE_1_VIGENTE)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('calculoactivo', Constante::VIGENTE_1_VIGENTE)
            ->get();

        foreach ($sqlPropiedad as $listado) {
            $propiedades[$listado->SII_PROP_DET_ID] = [];
            $propiedades[$listado->SII_PROP_DET_ID]['rol-comuna'] = $listado->ROL_COMUNA;
            $propiedades[$listado->SII_PROP_DET_ID]['rol-manzana'] = $listado->ROL_MANZANA;
            $propiedades[$listado->SII_PROP_DET_ID]['rol-predio'] = $listado->ROL_PREDIO;
            $propiedades[$listado->SII_PROP_DET_ID]['direccion-ofuscada'] = $listado->DIRECCION_OFUSCADA;
            $propiedades[$listado->SII_PROP_DET_ID]['rol'] = $listado->ROL;
            $propiedades[$listado->SII_PROP_DET_ID]['tasacion'] = $listado->TASACION;
            $propiedades[$listado->SII_PROP_DET_ID]['fecha-fuente'] = $listado->FECHAFUENTE;
            $propiedades[$listado->SII_PROP_DET_ID]['run-sociedad'] = $listado->RUN_SOCIEDAD;
            $propiedades[$listado->SII_PROP_DET_ID]['run-sociedad-ofuscado'] = $listado->RUN_SOCIEDAD_OFUSCADO;
        }

        return $propiedades;
    }

    public static function contarBienes($terrestre = null, $maritimo = null, $propiedad = null)
    {
        $listado = [];
        $recuentoBienes = 0;
        $listado['vehiculo-terrestre'] = count($terrestre);
        $listado['vehiculo-maritimo'] = count($maritimo);
        $listado['bien-raiz'] = count($propiedad);

        foreach ($listado as $item) {
            $recuentoBienes += $item;
        }
        $listado['total-bienes'] = $recuentoBienes;

        return ($listado);
    }

    public static function bloqueoSolicitud($runSocio, $terrestre, $maritimo, $propiedad, $sociedad, $listaSocios)
    {
        $bloqueoSolicitud = array();
        $bloqueoSolicitud['puede-realizar'] = true;
        $bloqueoSolicitud['mensaje-ciudadano'] = array();

        $mensajeTerrestre = self::bloqueoSolicitudTerrestre($terrestre, $runSocio);
        $mensajeMaritimo = self::bloqueoSolicitudMaritimo($maritimo, $runSocio);
        $mensajePropiedades = self::bloqueoSolicitudPropiedades($propiedad, $listaSocios, $runSocio);
        $mensajeSolicitud79 = self::bloqueoSolicitudExistente($sociedad, $runSocio);

        $mensajeCiudadano = array_merge($mensajeTerrestre, $mensajeMaritimo, $mensajePropiedades, $mensajeSolicitud79);

        if (!empty($mensajeCiudadano)) {
            $bloqueoSolicitud['puede-realizar'] = false;
            $bloqueoSolicitud['mensaje-ciudadano'] = $mensajeCiudadano;
        }

        return $bloqueoSolicitud;
    }

    public static function bloqueoSolicitudTerrestre($listado, $socio)
    {
        $mensaje = array();

        if (count($listado) > 0) {
            $idTerrestre = array();

            foreach ($listado as $terrestres) {
                $idTerrestre[] = $terrestres['id'];
            }

            $existeSolicitud = Solicitud::whereIn('dato_actual', $idTerrestre)
                ->whereIn('id_tipo_solicitud', [
                    Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL,
                    Constante::TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO
                ])
                ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                    [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                ->select('id_sub_tipo_solicitud', 'id_tipo_solicitud', 'run_ciudadano')
                ->get();

            if (!is_null($existeSolicitud)) {
                foreach ($existeSolicitud as $solicitud) {
                    if ($solicitud->ID_TIPO_SOLICITUD == Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL
                        && in_array($solicitud->ID_SUB_TIPO_SOLICITUD,
                            array(
                                Constante::SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL_1_VENTA,
                                Constante::SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL_2_CANCELACION
                            ))) {
                        if ($socio == $solicitud->RUN_CIUDADANO) {
                            $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_TERRESTRE_PROPIA_EN_TRAMITE_ACTUALIZACION;
                            break;
                        } else {
                            $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_TERRESTRE_SOCIEDAD_EN_TRAMITE_ACTUALIZACION;
                            break;
                        }
                    } elseif ($solicitud->ID_TIPO_SOLICITUD == Constante::TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO
                        && in_array($solicitud->ID_SUB_TIPO_SOLICITUD,
                            array(
                                Constante::SUB_TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO_1,
                                Constante::SUB_TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO_2_ACREDITAR_EL_ERROR_EN_EL_MODELO_MARCA,
                                Constante::SUB_TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO_3_ACREDITAR_EL_ERROR_DEL_ANNO_DEL_VEHICULO
                            ))) {
                        if ($socio == $solicitud->RUN_CIUDADANO) {
                            $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_TERRESTRE_PROPIA_EN_TRAMITE_RECTIFICACION;
                            break;
                        } else {
                            $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_TERRESTRE_SOCIEDAD_EN_TRAMITE_RECTIFICACION;
                            break;
                        }
                    }
                }
            }
        }
        return $mensaje;
    }

    public static function bloqueoSolicitudMaritimo($listado, $socio)
    {
        $mensaje = array();
        if (count($listado) > 0) {
            $idMaritimo = array();

            foreach ($listado as $listados) {
                $idMaritimo[] = $listados['id'];
            }

            $existeSolicitud = Solicitud::whereIn('dato_actual', $idMaritimo)
                ->whereIn('id_tipo_solicitud', [
                    Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL,
                    Constante::TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO
                ])
                ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                    [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                ->select('id_sub_tipo_solicitud', 'id_tipo_solicitud', 'run_ciudadano')
                ->get();

            if (!is_null($existeSolicitud)) {
                foreach ($existeSolicitud as $solicitud) {
                    if ($solicitud->ID_TIPO_SOLICITUD == Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL
                        && $solicitud->ID_SUB_TIPO_SOLICITUD == Constante::SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_MARITIMO_3_VENTA) {

                        if ($socio == $solicitud->RUN_CIUDADANO) {
                            $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_MARINA_PROPIA_EN_TRAMITE_ACTUALIZACION;
                            break;
                        } else {
                            $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_MARINA_SOCIEDAD_EN_TRAMITE_ACTUALIZACION;
                            break;
                        }

                    } elseif ($solicitud->ID_TIPO_SOLICITUD == Constante::TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO
                        && $solicitud->ID_SUB_TIPO_SOLICITUD == Constante::SUB_TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_MARITIMO_4_VENTA
                    ) {
                        if ($socio == $solicitud->RUN_CIUDADANO->RUN_CIUDADANO) {
                            $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_MARINA_PROPIA_EN_TRAMITE_RECTIFICACION;
                            break;
                        } else {
                            $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_MARINA_SOCIEDAD_EN_TRAMITE_RECTIFICACION;
                            break;
                        }
                    }
                }
            }
        }
        return $mensaje;
    }

    public static function bloqueoSolicitudPropiedades($listado, $listaSocios, $runSocio)
    {
        $mensaje = array();
        if (count($listado) > 0) {
            $rolPropiedades = array();
            $existeSolicitud = array();

            foreach ($listado as $listados) {
                $rolPropiedades[] = $listados['rol'];
            }

            $solicitudes = Solicitud::whereIn('run_ciudadano', $listaSocios)
                ->join('ever_sii_propiedades_det', 'ab_solicitud.id_solicitud', '=',
                    'ever_sii_propiedades_det.idsolicitud')
                ->whereIn('id_tipo_solicitud', array(
                    Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ,
                    Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ,
                    Constante::TIPO_SOLICITUD_75_RECTIFICACION_DE_DOMINIO_DE_BIEN_RAIZ,
                    Constante::TIPO_SOLICITUD_81_COMPLEMENTO_DE_SOCIEDAD_SIN_LUCRO
                ))
                ->whereNotIn('id_estado', array_merge(Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS,
                    [Constante::ESTADO_SOLICITUD_17_CONFIRMADO]))
                ->select('ID_TIPO_SOLICITUD', 'ID_SUB_TIPO_SOLICITUD', 'DATO_ACTUAL',
                    'DATO_NUEVO', 'RUN_CIUDADANO', 'ROL')
                ->get();

            if (count($solicitudes) > 0) {
                foreach ($solicitudes as $item) {
                    if (in_array($item->ROL, $rolPropiedades)) {
                        $existeSolicitud[] = $item;
                    }
                }
                if (count($existeSolicitud) > 0) {
                    foreach ($existeSolicitud as $item) {
                        if (in_array($item->ID_TIPO_SOLICITUD, [
                            Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ,
                            Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ
                        ])) {
                            if ($item->RUN_CIUDADANO == $runSocio) {
                                $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_PROPIA_EN_TRAMITE_ACTUALIZACION;
                                break;
                            } else {
                                $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_SOCIEDAD_EN_TRAMITE_ACTUALIZACION;
                                break;
                            }
                        } elseif ($item->ID_TIPO_SOLICITUD == Constante::TIPO_SOLICITUD_75_RECTIFICACION_DE_DOMINIO_DE_BIEN_RAIZ
                        ) {
                            if ($item->RUN_CIUDADANO == $runSocio) {
                                $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_PROPIA_EN_TRAMITE_RECTIFICACION;
                                break;
                            } else {
                                $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_SOCIEDAD_EN_TRAMITE_RECTIFICACION;
                                break;
                            }

                        } elseif ($item->ID_TIPO_SOLICITUD == Constante::TIPO_SOLICITUD_81_COMPLEMENTO_DE_SOCIEDAD_SIN_LUCRO
                        ) {
                            if ($item->RUN_CIUDADANO == $runSocio) {
                                $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_PROPIA_EN_TRAMITE_SIN_LUCRO;
                                break;
                            } else {
                                $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_SOCIEDAD_EN_TRAMITE_SIN_LUCRO;
                                break;
                            }

                        }
                    }
                }
            }
        }
        return $mensaje;
    }

    public static function bloqueoSolicitudExistente($sociedad, $runSocio)
    {
        $mensaje = array();
        $sqlSolicitud = DatosDeSolicitud::busquedaSolicitudexRunJuridicoConBorrador($runSocio);
        if (count($sqlSolicitud) > 0) {
            foreach ($sqlSolicitud as $item) {
                if ( $item->ID_TIPO_SOLICITUD == Constante::TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA){
                    if ($item->RUN_SOCIEDAD == $sociedad['datos-sociedad']['run-sociedad']) {
                        if ($item->RUN_CIUDADANO == $runSocio) {
                            $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_PROPIA_EN_TRAMITE_ACTUALIZACION;
                            break;
                        } else {
                            $mensaje[] = Constante::MENSAJE_SOLICITUD_JURIDICA_SOCIEDAD_EN_TRAMITE_ACTUALIZACION;
                            break;
                        }
                    }
                }
            }
        }
        return $mensaje;
    }

    public static function bloqueoSolicitudRectificacionExistente($socio)
    {
        $estadoJuridico = false;
        $mesesAgregar = 12;

        if (isset($socio) && $socio->P_JURIDICA == Constante::PERSONA_JURIDICA) {
            $existesolicitud = Solicitud::where('RUN_CIUDADANO', $socio->PER_RUN)
                ->where('id_tipo_solicitud', Constante::TIPO_SOLICITUD_80_RECTIFICACION_DE_PARTICIPACION_JURIDICA)
                ->where('vigente', Constante::VIGENTE_SOLICITUD_1_VIGENTE)
                ->where('id_estado', ConstanteEstado::ESTADO_SOLICITUD_10_EN_NIVEL_CENTRAL)
                ->whereRaw('ADD_MONTHS(fecha_modificacion, ' . $mesesAgregar . ') >= SYSDATE')
                ->first();
            if (!isset($existesolicitud)) {
                $estadoJuridico = true;
            }
        }

        return $estadoJuridico;
    }
}
