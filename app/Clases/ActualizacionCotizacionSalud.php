<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionEjecucion;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Http\Inputs\TramiteInput;
use App\Models\ActualizacionMeses;
use App\Models\Comuna;
use App\Models\GrupoFamiliar;
use App\Models\Personas;
use App\Sequence;
use App\Utiles;
use BD;
use App\Models\ValorUf;
use App\Models\Isapre;
use App\Models\Carga;
use Illuminate\Support\Facades\DB;

class ActualizacionCotizacionSalud extends TramiteArc
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD;
    public $nombreSolicitud = 'de Cotización de Salud';
    public $tituloFormulario = 'Solicitud de Actualización del Valor';
    public $isapre = null;
    public $carga = null;

    protected $periodoActual;

    public function __construct(TramiteInput $input = null)
    {
        parent::__construct($input);
        if (!empty($input)) {
            $this->periodoActual = $this->obtenerPeriodo();
        }
    }

    public function editarSolicitud()
    {
        $data = $this->generaData();
        $this->validaciones($data);
        $this->verificarFirmaSimple();
        $this->guardarSolicitud($data);
        $this->generarPdfFirmaSimple();
    }

    protected function cargarDataTramite()
    {
        $this->isapre = Isapre::where('run', $this->solicitud->RUN_INTEGRANTE)
            ->where('idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->first();

        $this->carga = Carga::where('run_carga', $this->solicitud->RUN_INTEGRANTE)
            ->where('idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->first();

        if (!($this->isapre) && !($this->carga)) {
            throw new ExcepcionConsistencia('Error administrativo, no se encontró la información de las Cargas o Isapre.', Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
        }


        if ($this->solicitud->ID_SUB_TIPO_SOLICITUD == Constante::SUB_TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD_1_NUEVO_VALOR_DE_COTIZACION_SALUD) {
            if ($this->isapre) {
                $fechaCambio = date_create_from_format('Ym', $this->isapre->FECHA_CAMBIO);
                $fechaCambio->modify("last day of previous month");

                $subSentencia = DB::table('VALOR_UF')
                    ->where(DB::raw('EXTRACT(YEAR FROM FECHA)'), $fechaCambio->format('Y'))
                    ->select(DB::raw('MIN(ABS(FECHA-TO_DATE(\'' . $fechaCambio->format('Y-m-d 00:00:00') . '\', \'yyyy-mm-dd HH24:MI:SS\'))) diff'));

                $queryTotal = ValorUf::join(DB::raw("({$subSentencia->toSql()}) VINT"), 'VINT.DIFF', '=',
                    DB::raw("ABS(VALOR_UF.FECHA-TO_DATE('" . $fechaCambio->format('Y-m-d 00:00:00') . "', 'yyyy-mm-dd HH24:MI:SS'))"))
                    ->mergeBindings($subSentencia)
                    ->first();

                $this->isapre->VALOR_UF_CALCULO = isset($queryTotal->VALOR_UF_DIA)?$queryTotal->VALOR_UF_DIA:null;
            }
            if ($this->carga) {
                $fechaCambio = date_create_from_format('Ym', $this->carga->FECHA_CAMBIO);
                $fechaCambio->modify("last day of previous month");

                $subSentencia = DB::table('VALOR_UF')
                    ->where(DB::raw('EXTRACT(YEAR FROM FECHA)'), $fechaCambio->format('Y'))
                    ->select(DB::raw('MIN(ABS(FECHA-TO_DATE(\'' . $fechaCambio->format('Y-m-d 00:00:00') . '\', \'yyyy-mm-dd HH24:MI:SS\'))) diff'));

                $queryTotal = ValorUf::join(DB::raw("({$subSentencia->toSql()}) VINT"), 'VINT.DIFF', '=',
                    DB::raw("ABS(VALOR_UF.FECHA-TO_DATE('" . $fechaCambio->format('Y-m-d 00:00:00') . "', 'yyyy-mm-dd HH24:MI:SS'))"))
                    ->mergeBindings($subSentencia)
                    ->first();

                $this->carga->VALOR_UF_CALCULO = isset($queryTotal->VALOR_UF_DIA)?$queryTotal->VALOR_UF_DIA:null;
            }
        }
    }
    protected function consultarTitular()
    {
        if ($this->solicitud->RUN_INTEGRANTE != $this->solicitud->RUN_CIUDADANO) {
            $runTitular = $this->solicitud->RUN_INTEGRANTE;
        }else{
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
    private function validaciones($data)
    {
        if (!$data['subtipo']) {
            throw new ExcepcionReglaNegocio('El campo subtipo es obligatorio.',Constante::ERR_NEGOCIO_VALOR_SUB_TIPO_REQUERIDO);
        }
        if (!$data['run-titular']) {
            throw new ExcepcionReglaNegocio('El campo run-titular es obligatorio.',Constante::ERR_NEGOCIO_VALOR_RUN_TITULAR_REQUERIDO);
        }
        if ($data['subtipo'] == Constante::SUB_TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD_1_NUEVO_VALOR_DE_COTIZACION_SALUD) {
            if ((!$data['monto'] || !$data['monto-uf']) && $this->accion != Constante::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3) {
                throw new ExcepcionReglaNegocio('El campo monto y monto-uf es obligatorio.',Constante::ERR_NEGOCIO_VALOR_MONTO_Y_MONTO_UF_REQUERIDO);
            }
        }
    }
    protected function generaData()
    {
        $data = [];
        $data['subtipo'] = $this->input->get('subtipo');
        $data['run-titular'] = $this->titular->PER_RUN;
        if ($this->input->get('subtipo') == Constante::SUB_TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD_1_NUEVO_VALOR_DE_COTIZACION_SALUD
            && $this->solicitud
        ) {
            $data['anio'] = date("Y", strtotime($this->solicitud->FECHA_CREACION));
            $data['mes'] = date("m", strtotime($this->solicitud->FECHA_CREACION));
        } else {
            $data['anio'] = $this->input->get('anio');
            $data['mes'] = $this->input->get('mes');
        }
        $data['monto'] = $this->input->get('monto');
        $data['monto-uf'] = $this->input->get('monto-uf');

        return $data;
    }

    protected function guardarSolicitud(array $data)
    {
        DB::beginTransaction();
        try{
            if (is_null($this->solicitud)) {
                $grupoFamiliar = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->solicitante->PER_RUN, false);
                if ($grupoFamiliar->count() == 0) {
                    throw new ExcepcionReglaNegocio('Error administrativo, no se encontra grupo familiar vigente y activo.',Constante::ERR_NEGOCIO_ERROR_NO_SE_ENCONTRA_GRUPO_FAMILIAR_VIGENTE_Y_ACTIVO);
                }
                $dato_nuevo = $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;
                $dato_actual = $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;
                $gf_nuevo_folio = $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;
                $this->obtenerRegistroSolicitud($dato_nuevo, $dato_actual, $gf_nuevo_folio);

                $this->crearDataTramite();
                //cambio de la solucitud segun caso correspondiente
                $this->solicitud->ID_SUB_TIPO_SOLICITUD = $this->input->get('subtipo'); // para solicituar adjuntar los documentos
                $this->solicitud->RUN_INTEGRANTE = $this->titular->PER_RUN;
                $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
            } else {
                $this->actualizaDataTramite();
            }
            $this->solicitud->save();
            $this->configurarCargaInformacionDeTramite();
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }

    }

    protected function crearDataTramite()
    {
        $isapre = Isapre::where('run', $this->titular->PER_RUN)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('periodo_rsh', $this->periodoActual->periodo)
            ->first();

        $carga = Carga::where('run_carga', $this->titular->PER_RUN)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('periodo_rsh', $this->periodoActual->periodo)
            ->first();

        if (is_null($isapre) && is_null($carga)) {
            throw new ExcepcionReglaNegocio('Error administrativo, no se encontró la información de las Cargas o Isapre.',Constante::ERR_NEGOCIO_DEBE_CONTENER_INFORMACION_DE_CARGAS_O_ISAPRE);
        }

        if (!is_null($this->solicitud)) {
            try {
                if ($isapre) {
                    $this->isapre = $isapre->replicate();
                    $this->isapre->ISA_COT_ID = Sequence::nextValue('seq_ab_complemento_salud');
                    $this->isapre->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
                    $this->isapre->VIGENTE = Constante::VIGENTE_2_PENDIENTE;
                    $this->isapre->CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
                    $this->isapre->IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
                    $this->isapre->C_TACTUALIZACION_SALUD = $this->input->get('subtipo');
                    if ($this->input->get('subtipo') == Constante::SUB_TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD_1_NUEVO_VALOR_DE_COTIZACION_SALUD) {
                        $this->isapre->MONTO_PLAN = $this->input->get('monto');
                        $this->isapre->MONTO_PLAN_UF = $this->input->get('monto-uf');
                        $this->isapre->FECHA_CAMBIO = date("Y", strtotime($this->solicitud->FECHA_CREACION)) . date("m",
                                strtotime($this->solicitud->FECHA_CREACION));
                    }
                    if ($this->input->get('subtipo') == Constante::SUB_TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD_2_DESAFILIACION_DE_ISAPRE) {
                        $this->isapre->FECHA_DESAFILIACION = $this->input->get('anio') . $this->input->get('mes');
                    }
                    $this->isapre->save();
                }

                if ($carga) {
                    $this->carga = $carga->replicate();
                    $this->carga->ISA_CARG_ID = Sequence::nextValue('seq_ab_complemento_salud');
                    $this->carga->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
                    $this->carga->VIGENTE = Constante::VIGENTE_2_PENDIENTE;
                    $this->carga->CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
                    $this->carga->IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
                    $this->carga->C_TACTUALIZACION_SALUD = $this->input->get('subtipo');
                    if ($this->input->get('subtipo') == Constante::SUB_TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD_1_NUEVO_VALOR_DE_COTIZACION_SALUD) {
                        $this->carga->MONTO_PLAN = $this->input->get('monto');
                        $this->carga->MONTO_PLAN_UF = $this->input->get('monto-uf');
                        $this->carga->FECHA_CAMBIO = date("Y", strtotime($this->solicitud->FECHA_CREACION)) . date("m",
                                strtotime($this->solicitud->FECHA_CREACION));
                    }
                    if ($this->input->get('subtipo') == Constante::SUB_TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD_2_DESAFILIACION_DE_ISAPRE) {
                        $this->carga->FECHA_DESAFILIACION = $this->input->get('anio') . $this->input->get('mes');
                    }
                    $this->carga->save();
                }
            } catch (Throwable $ex) {
                Log::error('Complemento Ingreso : ' . $ex);
                throw new ExcepcionEjecucion('Error al grabar actualización salud',Constante::ERR_EJECUCION_ERROR_CONSULTA_SQL);
            }
        } else {
            throw new ExcepcionEjecucion('Error al crear solicitud de actualización salud',Constante::ERR_EJECUCION_ERROR_CONSULTA_SQL);
        }
    }

    protected function actualizaDataTramite()
    {
        if ($this->isapre) {
            unset($this->isapre->VALOR_UF_CALCULO);
            $this->isapre->C_TACTUALIZACION_SALUD = $this->input->get('subtipo');
            if ($this->input->get('subtipo') == Constante::SUB_TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD_1_NUEVO_VALOR_DE_COTIZACION_SALUD) {
                $this->isapre->MONTO_PLAN = $this->input->get('monto');
                $this->isapre->FECHA_CAMBIO = date("Y", strtotime($this->solicitud->FECHA_CREACION)) . date("m",
                        strtotime($this->solicitud->FECHA_CREACION));
            }
            if ($this->input->get('subtipo') == Constante::SUB_TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD_2_DESAFILIACION_DE_ISAPRE) {
                $this->isapre->FECHA_DESAFILIACION = $this->input->get('anio') . $this->input->get('mes');
            }
            $this->isapre->save();
        }

        if ($this->carga) {
            unset($this->carga->VALOR_UF_CALCULO);
            $this->carga->C_TACTUALIZACION_SALUD = $this->input->get('subtipo');
            if ($this->input->get('subtipo') == Constante::SUB_TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD_1_NUEVO_VALOR_DE_COTIZACION_SALUD) {
                $this->carga->MONTO_PLAN = $this->input->get('monto');
                $this->carga->MONTO_PLAN_UF = $this->input->get('monto-uf');
                $this->carga->FECHA_CAMBIO = date("Y", strtotime($this->solicitud->FECHA_CREACION)) . date("m",
                        strtotime($this->solicitud->FECHA_CREACION));
            }
            if ($this->input->get('subtipo') == Constante::SUB_TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD_2_DESAFILIACION_DE_ISAPRE) {
                $this->carga->FECHA_DESAFILIACION = $this->input->get('anio') . $this->input->get('mes');
            }
            $this->carga->save();
        }
    }
    public function enDigitacion(){
        $this->editarSolicitud();
    }

    public function actualizarRegistrosAprobacion()
    {
        $sqlUpdate1 = Isapre::where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('run', $this->solicitud->RUN_CIUDADANO)
            ->update(['vigente' => Constante::VIGENTE_2_PENDIENTE]);

        $sqlUpdate2 = Isapre::where('idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->update(['vigente' => Constante::VIGENTE_1_VIGENTE]);
    }
    public function agendado(){
        return null;
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
            if (!self::esMunicipal() && !self::esChileAtiende()) { // ToDo revisar vigente para no municipal
                $this->solicitud->VIGENTE = Constante::$vigenteSegunAccionNoMunicipal[$this->accion];
            }
            if (self::ingresaAMunicipal()) {
                $this->solicitud->FECHA_CREACION = $this->fechaActual;

                if ($this->solicitud->VIGENTE == Constante::VIGENTE_1_VIGENTE) {
                    $comuna = self::leerComunaDesdeGrupoFamiliarCiudadano($this->titular->PER_RUN, $this->tipoSolicitud);
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
    public function pendienteDocumentos(){
        $this->editarSolicitud();
    }

    protected function obtenerPeriodo()
    {
        $periodo = ActualizacionMeses::orderBy('PERIODO_RSH', 'desc')
            ->first();
        return $this->formatearPeriodo($periodo);
    }

    protected function obtenerDetallePeriodo($periodoRsh)
    {
        $periodo = ActualizacionMeses::where('PERIODO_RSH', $periodoRsh)
            ->first();
        return $this->formatearPeriodo($periodo);
    }

    protected function formatearPeriodo(ActualizacionMeses $periodo)
    {
        $out = (object)[
            'periodo' => $periodo->PERIODO_RSH,
            'meses' => []
        ];
        for ($i = 1; $i <= 12; $i++) {
            $out->meses[$i] = (object)[
                'anio' => substr($periodo->{'MES_' . $i . '_ACTUALIZA'}, 0, -2),
                'nMes' => intval(substr($periodo->{'MES_' . $i . '_ACTUALIZA'}, 4)),
                'mes' => Utiles::obtenerMes(substr($periodo->{'MES_' . $i . '_ACTUALIZA'}, 4))
            ];
        }
        return $out;
    }

    protected function definirTitular(TramiteInput $input)
    {
        $titular = null;
        $runTitular = (!is_null($input->tipoSolicitud->{$this->tipoSolicitud}->runTitular)) ?
            $input->tipoSolicitud->{$this->tipoSolicitud}->runTitular :
            $input->runCiudadano;

        if ($this->solicitante->PER_RUN == $runTitular) {
            $titular = $this->solicitante;
        } else {
            $titular = Personas::findByRun($runTitular);
        }
        return $titular;
    }

}
