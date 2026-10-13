<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Exceptions\ExcepcionValidacion;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\Comuna;
use App\Models\Ficha;
use App\Models\FichaMontoMensual;
use App\Models\GrupoFamiliar;
use App\Models\Personas;
use App\Models\Usuario;
use App\Sequence;
use App\Utiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;
use DateTime;
use DateTimeZone;

class Supervision extends IngresoRegistro
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION;
    public $nombreSolicitud = 'Actualización por Supervisión';

    public $firmaJefeHogar = true;

    public $grupoFamiliarVigente;
    public $beneficiariosVigente;
    public $grupoFamiliarSolicitudActual;

    public $beneficiariosSolicitudActual;
    public $beneficiariosSolicitud;
    public $solTieneMontoMensual;
    private $requiereCamposObligatorios;
    private $requiereV1Obligatorio;
    private $requiereModulosESO;
    private $requiereVivienda;
    private $requiereLocalizacion;
    private $requiereEncuestador;
    private $requiereObservacion;
    private $requiereValidacionesExtra;
    private $modulos;

    protected $requiereDocumentosAdjuntos;

    public function editarSolicitud()
    {
        if($this->validaPersonasFallecidas()) {
            $this->validacionGuardadoSolicitud();
            $this->validarCamposRequeridos();
            $this->validacionExtraIntegrante();
            $this->validarRespuestasEducacion();
            $this->validarRespuestasIngreso();
            $this->validarRespuestasSalud();
            $this->validarRespuestasVivienda();
            $this->validarRespuestasLocalidad();
            $this->validacionDatosEncuestador();
            $this->validarAdjuntosRequeridos();
        }

        // Si hay errores detectados anteriormente los retorna
        if (!empty($this->errors)) {
            throw new ExcepcionReglaNegocio($this->errors);
        }

        $this->verificarFirmaSimple();
        $this->guardarSolicitud([]);
        $this->generarPdfFirmaSimple();
    }

    public function enDigitacion()
    {
        $this->requiereCamposObligatorios = true;
        $this->requiereV1Obligatorio = true;
        $this->requiereVivienda = true;
        $this->requiereLocalizacion = true;
        $this->requiereModulosESO = true;
        $this->requiereValidacionesExtra = true;

        $this->editarSolicitud();
    }

    public function pendienteDocumentos()
    {
        $this->requiereCamposObligatorios = false;
        if (self::esMunicipal()) {
            $this->requiereCamposObligatorios = true;
        }
        $this->editarSolicitud();
    }

    public function pendienteResolver()
    {
        $this->requiereCamposObligatorios = true;
        $this->requiereModulosESO = true;
        $this->requiereV1Obligatorio = true;
        $this->requiereLocalizacion = true;
        $this->requiereVivienda = true;
        $this->requiereEncuestador = true;
        $this->requiereDocumentosAdjuntos = true;
        $this->requiereValidacionesExtra = true;

        $this->editarSolicitud();
    }

    public function devolverGestion()
    {
        $estadoActual = $this->solicitud->ID_ESTADO;
        if ($estadoActual == Constante::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL
            && in_array($this->accion, [
                Constante::ACCION_GESTION_VA_ESTADO_3
            ])
        ) {
            $this->requiereObservacion = true;
        }
        $this->requiereCamposObligatorios = true;
        $this->requiereModulosESO = true;
        $this->requiereV1Obligatorio = true;
        $this->requiereLocalizacion = true;
        $this->requiereVivienda = true;
        $this->requiereEncuestador = true;
        $this->requiereDocumentosAdjuntos = true;

        $this->editarSolicitud();
    }

    public function aprobar()
    {
        parent::aprobar();
    }

    public function desistir()
    {
        parent::desistir();
    }

    public function rechazar()
    {
        parent::rechazar();
    }

    protected function cargarDataTramite()
    {
        $this->solTieneMontoMensual = FichaIngresoMensual::check($this->solicitud->FECHA_CREACION,
            $this->solicitud->FECHA_CREACION_CIUDADANO);

        $this->grupoFamiliarSolicitudActual = GrupoFamiliar::findByIdGrupoFamiliar($this->solicitud->DATO_NUEVO);
        if (!empty($this->grupoFamiliarSolicitudActual)) {
            $this->beneficiariosSolicitudActual = BeneficiarioGrupoFamiliar::findBeneficiariosFichasByIdSolicitud($this->solicitud->ID_SOLICITUD,
                $this->solTieneMontoMensual);
        }

        $this->grupoFamiliarVigente = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->solicitud->RUN_CIUDADANO,
            false);
        if (!empty($this->grupoFamiliarVigente)) {
            $this->beneficiariosVigente = BeneficiarioGrupoFamiliar::findBeneficiariosByGrupoFamiliar($this->grupoFamiliarVigente);
        }
        if (!empty($this->beneficiariosSolicitudActual)) {
            foreach ($this->beneficiariosSolicitudActual as $key => $integrente) {
                $this->modulos['educacion'] = (self::consultaModulo($this->solicitud->ID_SOLICITUD, $integrente->PER_RUN, 1) == 3) ? true : false;
                $this->modulos['salud'] = (self::consultaModulo($this->solicitud->ID_SOLICITUD, $integrente->PER_RUN, 2) == 3) ? true : false;
                $this->modulos['ocupacion_ingreso'] = (self::consultaModulo($this->solicitud->ID_SOLICITUD, $integrente->PER_RUN, 3) == 3) ? true : false;
                $this->modulos['vivienda'] = (self::consultaModulo($this->solicitud->ID_SOLICITUD, $integrente->PER_RUN, 4) == 3) ? true : false;

                $this->beneficiariosSolicitudActual[$key]->MODULOS_MODIFICADOS = $this->modulos;
            }
            $this->grupoFamiliarSolicitudActual->MODULO_VIVIENDA_MODIFICADO = $this->modulos['vivienda'];

        }

        /* migracion de la logica original. (de dudosa veracidad)

        $ficha = Ficha::findDetallePersonaByIdSolicitud($this->solicitud->ID_SOLICITUD, $this->solTieneMontoMensual);
        $beneficiariosVigente = [Ficha::findDetallePersonaByIdPersona($ficha->FICHA_PERSONA, $this->solTieneMontoMensual)];
        foreach ($beneficiariosVigente as $beneficiario) {
            $beneficiario->INSTITUCIONALIZADO = Institucionalizados::evaluarExistenciaInstitucionalizados($beneficiario);
        }
        $this->beneficiariosVigente = $beneficiariosVigente;  */
    }

    protected function obtenerPersonasAsociadas()
    {
        $this->personasAsociadas = $this->beneficiariosSolicitudActual;
    }

    public function agendado()
    {
        // TODO: Implement agendado() method.
    }

    protected function guardarSolicitud(array $data)
    {
        $idSolicitud = null;
        $idPadreSolicitud = null;
        $idGrupoFamiliar = null;

        DB::beginTransaction();
        try {
            if (empty($this->solicitud)) {
                $this->solTieneMontoMensual = FichaIngresoMensual::check(now(), now());
                //solicitud nueva
                if ((new DatosDeSolicitud())->existeSolicitudDeTipo($this->tipoSolicitud, $this->solicitante->PER_RUN)) {
                    throw ExcepcionValidacion::new(Constante::MENSAJE_ERROR_YA_EXISTE_SOLICITUD_MISMO_TIPO,
                        Constante::STATUS_409_ERROR_CONFLICT);
                }
                $idGrupoFamiliar = Sequence::nextValue('seq_grupofamiliapk');
                $this->obtenerRegistroSolicitud($idGrupoFamiliar, $idGrupoFamiliar, null);

                if ($this->solicitud->save()) {
                    self::fillGrupoFamiliar($idGrupoFamiliar);
                    if ($this->solicitud->VIGENTE == Constante::VIGENTE_1_VIGENTE
                        && !isset($this->grupoFamiliarSolicitudActual->GRUPFAMI_NUEVOFOLIO)
                    ) {
                        $this->grupoFamiliarSolicitudActual->GRUPFAMI_NUEVOFOLIO = Sequence::nextValue('seq_nuevofolio');
                    }
                    if ($this->grupoFamiliarSolicitudActual->save()) {
//                        $edades = array_column($this->input->integrantes,'edad','id');
//                        foreach ($this->beneficiariosSolicitudActual as $beneficiario) {
                        foreach ($this->input->get('integrantes') as $integrante) {
                            $beneficiario = self::fillBeneficiario($integrante);
                            if (empty($this->beneficiariosSolicitudActual)) {
                                $this->beneficiariosSolicitudActual = [];
                            }
                            array_push($this->beneficiariosSolicitudActual, $beneficiario);
                            if ($beneficiario->save()) {
                                $ficha = self::fillFicha($integrante);
                                $ficha->save();

                                FichaIngresoMensual::guardarFichaMontoMensual($integrante->toArray(),
                                    $ficha->FICHA_ID,
                                    $this->solicitud->ID_SOLICITUD,
                                    $integrante->id);
//                                self::guardarFichaMontoMensual($fichaId, $integrante);
                            } else {
                                throw ExcepcionConsistencia::new('Error de validación');
                            }
                        }
                    } else {
                        DB::rollback();
                        throw new ExcepcionConsistencia(
                            str_replace(
                                '%parte%',
                                'Grupo Familiar',
                                Constante::MENSAJE_ERROR_GUARDADO_DATOS_SOLICITUD),
                            Constante::STATUS_409_ERROR_CONFLICT);
                    }
                    if ((new DatosDeSolicitud())->existeSolicitudDeTipo($this->solicitud->ID_TIPO_SOLICITUD, $this->solicitante->PER_RUN, $this->solicitud->ID_SOLICITUD)) {
//                        DB::rollback();
                        throw new ExcepcionConsistencia(Constante::MENSAJE_ERROR_YA_EXISTE_SOLICITUD_MISMO_TIPO, Constante::STATUS_409_ERROR_CONFLICT);
//                        throw new RshException(Constante::MENSAJE_ERROR_YA_EXISTE_SOLICITUD_MISMO_TIPO, Constante::STATUS_409_ERROR_CONFLICT);
                    }
//                    DB::commit();
                } else {
                    DB::rollback();
                    throw new ExcepcionConsistencia(
                        str_replace(
                            '%parte%',
                            'Solicitud',
                            Constante::MENSAJE_ERROR_GUARDADO_DATOS_SOLICITUD),
                        Constante::STATUS_409_ERROR_CONFLICT);
                }
            } else {
                //solicitud existente
                $this->obtenerRegistroSolicitud(
                    $this->solicitud->DATO_ACTUAL,
                    $this->solicitud->DATO_NUEVO,
                    null);
                $this->solTieneMontoMensual = FichaIngresoMensual::check($this->solicitud->FECHA_CREACION,
                    $this->solicitud->FECHA_CREACION_CIUDADANO);
                //$ficha = $beneficiario->Ficha()->where('vigente', 1)
                $observacion = $this->observacion;
                if ($this->requiereObservacion && !empty($observacion)) {
                    $this->solicitud->COMENTARIO = $observacion;
                }
                // logica movida fuera del if de más abajo ya que los grupos familiares
                // llegan a municipal sin folio, y debe evitarse. tomando en cuenta el vigente
                if ($this->solicitud->VIGENTE == Constante::VIGENTE_1_VIGENTE
                    && !isset($this->grupoFamiliarSolicitudActual->GRUPFAMI_NUEVOFOLIO)
                ) {
                    $this->grupoFamiliarSolicitudActual->GRUPFAMI_NUEVOFOLIO = Sequence::nextValue('seq_nuevofolio');
                }

                if (!in_array($this->accion, [
                    Constante::ACCION_AGENDAR_VA_ESTADO_11_SUBESTADO_14,
                    Constante::ACCION_AGENDAR_ETAPA1_VA_ESTADO_11_SUBESTADO_14,
                    Constante::ACCION_AGENDADO_VA_ESTADO_2
                ])) {
                    if ($this->accion == Constante::ACCION_GESTION_VA_ESTADO_3 &&
                        $this->solicitud->ID_ESTADO == Constante::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL
                    ) {
                        $historialParaObtenerMunicipalAnterior = DB::table('ab_solicitud_historial h')
                            ->join('ab_usuario u', 'u.rut_usuario', '=', 'h.id_usuario_asignado')
                            ->where('id_solicitud', $this->solicitud->ID_SOLICITUD)
                            ->whereIn('h.id_estado', [
                                Constante::ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS,
                                Constante::ESTADO_SOLICITUD_2_AGENDADO,
                                Constante::ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL,
                                Constante::ESTADO_SOLICITUD_11_EN_DIGITACION
                            ])
                            ->where('id_perfil', '<>', Constante::PERFIL_25_CHILE_ATIENDE_EJECUTOR)
                            ->orderBy('fecha_asignacion')
                            ->select('h.id_usuario_asignado', 'h.id_estado', 'h.fecha_asignacion')
                            ->get();
                        $historialParaObtenerMunicipalAnteriorArray = count($historialParaObtenerMunicipalAnterior) ? $historialParaObtenerMunicipalAnterior : [];
                        $ultimoRegistroHistorial = count($historialParaObtenerMunicipalAnteriorArray) - 1;    // posicion del último registro en historial
                        if (isset($historialParaObtenerMunicipalAnterior[$ultimoRegistroHistorial]->ID_USUARIO_ASIGNADO)) {
                            $this->solicitud->ID_USUARIO_ASIGNADO = $historialParaObtenerMunicipalAnterior[$ultimoRegistroHistorial]->ID_USUARIO_ASIGNADO;
                        }
                    }

                    if ($this->accion == Constante::ACCION_RESOLVER_VA_ESTADO_4) {
                        $this->solicitud->NUEVOFOLIO = isset($this->grupoFamiliarSolicitudActual->GRUPFAMI_NUEVOFOLIO) ?
                            $this->grupoFamiliarSolicitudActual->GRUPFAMI_NUEVOFOLIO :
                            null;
                        $asignadoActual = Usuario::where('rut_usuario', $this->solicitud->ID_USUARIO_ASIGNADO)
                            ->select('id_perfil')
                            ->get();

                        if ($asignadoActual->count() === 0
                            || !in_array($asignadoActual->first()->ID_PERFIL, [
                                Constante::PERFIL_12_ENCARGADO_COMUNAL,
                                Constante::PERFIL_29_FUNFA
                            ])) {
                            $this->solicitud->ID_USUARIO_ASIGNADO = (new Comuna())->obtenerEncargadoComunal($this->solicitud->COM_ID);
                        }
                    }
                }
                if ($this->solicitud->save()) {
                    //todo: modificar GF, Beneficiarios y todo lo demas
                    self::fillGrupoFamiliar(null);
                    if ($this->grupoFamiliarSolicitudActual->save()) {
                        $beneficiariosParaEliminar = self::getBeneficiariosParaEliminar(
                            $this->input->integrantes
                        );
                        foreach ($this->input->integrantes as $integrante) {
                            $beneficiario = self::fillBeneficiario($integrante);
//                            $beneficiario->bnfgrupfami_vigente = $data['bnfgrupfami_vigente'];
//                            $beneficiario->fillable($this->filledBeneficiario); //especifica cuales campos se van a llenar
//                            $beneficiario->fill($integrante); //llena con los campos especificados anteriormente, tomados del array $integrantes
                            $beneficiario->save();

                            $fichaIntegrante = self::fillFicha($integrante);
//                            $filledFicha = array_merge(
//                                $this->filledFicha,
//                                $this->filledFichaEducacion,
//                                $this->filledFichaSalud,
//                                $this->filledFichaIngreso
//                            );
//                            $fichaIntegrante->ficha_vigente = $data['ficha_vigente'];
//                            $fichaIntegrante->fillable($filledFicha); //especifica cuales campos se van a llenar
//                            $fichaIntegrante->fill($integrante); //llena con los campos especificados anteriormente, tomados del array $integrantes
                            $fichaIntegrante->save();
                            FichaIngresoMensual::actualizarFichaMontoMensual(
                                $integrante->toArray(),
                                $fichaIntegrante->FICHA_ID,
                                $this->solicitud->ID_SOLICITUD,
                                Constante::VIGENTE_2_PENDIENTE,
                                $this->solicitud->FECHA_CREACION,
                                $this->solicitud->FECHA_CREACION_CIUDADANO,
                                $integrante->id
                            );
                        }
                        self::deleteBeneficiariosNoUsados($beneficiariosParaEliminar, $this->solicitud->ID_SOLICITUD);
                    } else {
                        DB::rollback();
                        throw new ExcepcionConsistencia(
                            str_replace(
                                '%parte%',
                                'Grupo Familiar',
                                Constante::MENSAJE_ERROR_GUARDADO_DATOS_SOLICITUD),
                            Constante::STATUS_409_ERROR_CONFLICT);
                    }
                } else {
                    DB::rollback();
                    throw new ExcepcionConsistencia(
                        str_replace(
                            '%parte%',
                            'Solicitud',
                            Constante::MENSAJE_ERROR_GUARDADO_DATOS_SOLICITUD),
                        Constante::STATUS_409_ERROR_CONFLICT);
                }
            }
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            Log::error($ex);
            throw $ex;
        }
    }

    protected function actualizarRegistrosAprobacion()
    {
        // Se hace el cambio de vigente en grupo, beneficiarios, fichas, fichas monto mensual
//        logger("PAUSA");

        if ($this->grupoFamiliarSolicitudActual) {
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_VIGENTE = Constante::VIGENTE_1_VIGENTE;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_CALCULOACTIVO = Constante::CALCULO_ACTIVO_1_APROBADO;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_ESTADO = Constante::ESTADO_GRUPO_FAMILIAR_7_APROBADO;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_FECHA_MODIFICACION = date('Ymd');
            $this->grupoFamiliarSolicitudActual->save();
            if ($this->beneficiariosSolicitudActual) {
                foreach ($this->beneficiariosSolicitudActual as $integrante) {
                    //desvincula integrante
                    $rd = DesvinculacionIntegrante::desvinculaIntegrante($integrante->BNFGRUPFAMI_PERSONA, $this->solicitud);

                    $integrante->BNFGRUPFAMI_VIGENTE = Constante::VIGENTE_1_VIGENTE;
                    $integrante->BNFGRUPFAMI_CALCULOACTIVO = Constante::CALCULO_ACTIVO_1_APROBADO;
                    $integrante->BNFGRUPFAMI_FECHA_INGRESO = date('Y-m-d H:i:s');
                    $integrante->save();

                    $fichavieja = $integrante->Ficha()
                        ->where('ficha_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                        ->first();
                    if ($fichavieja) {
                        $fichavieja->FICHA_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
                        $fichavieja->save();
                    }
                    $ficha = $integrante->Ficha()
                        ->where('ficha_idsolicitud', $this->solicitud->ID_SOLICITUD)
                        ->first();
                    if($ficha){
                        $ficha->FICHA_CALCULOACTIVO = Constante::CALCULO_ACTIVO_1_APROBADO;
                        $ficha->FICHA_VIGENTE = Constante::VIGENTE_1_VIGENTE;
                        $ficha->FICHA_FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
                        $ficha->FICHA_FECHAACTUALIZACION = date('Ymd');
                        $ficha->save();
                    }

                    $solTieneMontoMensual = FichaIngresoMensual::check($this->solicitud->FECHA_CREACION,
                        $this->solicitud->FECHA_CREACION_CIUDADANO);

                    if ($solTieneMontoMensual) {
//                        $fichaMontoMensual = new FichaMontoMensual();
                        $fichaMontoMensualVieja = $integrante->FichaMontoMensual()
                            ->where('mensual_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                            ->first();
                        if ($fichaMontoMensualVieja) {
                            $fichaMontoMensualVieja->MENSUAL_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
                            $fichaMontoMensualVieja->save();
                        }
                        $fichaMontoMensual = $integrante->FichaMontoMensual()
                            ->where('mensual_idsolicitud', $this->solicitud->ID_SOLICITUD)
                            ->first();
                        if ($fichaMontoMensual){
                            $fichaMontoMensual->MENSUAL_CALCULOACTIVO = Constante::CALCULO_ACTIVO_1_APROBADO;
                            $fichaMontoMensual->MENSUAL_VIGENTE = Constante::VIGENTE_1_VIGENTE;
//                        $fichaMontoMensual->ficha_fuente = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
//                        $fichaMontoMensual->ficha_fechaactualizacion = date('Ymd');
                            $fichaMontoMensual->save();
                        }else{
                            throw new ExcepcionConsistencia('Falla al guardar ficha Monto Mensual: '. $fichaMontoMensual);
                        }

                    }
                }
            }
        }
    }

    private function fillGrupoFamiliar($idGrupoFamiliar)
    {
        $runValidacion = ($this->usuario)
            ? $this->usuario->RUT_USUARIO
            : $this->solicitante->PER_RUN;
        $zonaHorariaChileContinental = new DateTimeZone('America/Santiago');
        $fechaActual = new DateTime("now", $zonaHorariaChileContinental);
//        $canal = $this->input->get('canal');
        $fechaValidacionDir = null;
        $fechaValidacionAct = null;
        if ($this->canal == Constante::CANAL_1_STRING_MINUS_MUNICIPAL) {
            $fechaValidacionDir = $fechaActual;
            $fechaValidacionAct = $fechaActual;
        }
        if (empty($this->grupoFamiliarSolicitudActual)) {
            $this->grupoFamiliarSolicitudActual = new GrupoFamiliar();
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_ID = $idGrupoFamiliar;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_ESTADO = Constante::ESTADO_GRUPO_FAMILIAR_1_SOLICITADO;
        }

        $this->grupoFamiliarSolicitudActual->GRUPFAMI_COMUNAINE = $this->input->get('comuna');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_LOCALIDAD = $this->input->get('localidad');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_UNIDADVECINAL = $this->input->get('unidad-vecinal');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_CODIGOAH = $this->input->get('agrupacion-habitacional');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_C_CALLE = $this->input->get('codigo-calle');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_N_CALLE =
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_C_CALLE == Constante::ID_CALLE_10999_NO_REGISTRADA
                ? $this->input->get('nombre-calle') : '';
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_NUMDOMICILIO =
            Utiles::RemoverEspecialChar($this->input->get('numero-domicilio'));
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_SITIO =
            Utiles::RemoverEspecialChar($this->input->get('sitio'));
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_BLOCK =
            Utiles::RemoverEspecialChar($this->input->get('block'));
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_DPTO =
            Utiles::RemoverEspecialChar($this->input->get('departamento'));
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_CASA =
            Utiles::RemoverEspecialChar($this->input->get('casa'));
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_REFERENCIA = $this->input->get('referencia');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_NUMCLIENTELUZ = $this->input->get('numero-cliente-luz');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_NUMCLIENTEAGUA = $this->input->get('numero-cliente-agua');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_IDCOMPLUZ = $this->input->get('compania-luz');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_IDCOMPAGUA = $this->input->get('compania-agua');
        $this->grupoFamiliarSolicitudActual->COORDENADA_Y = $this->input->get('latitud');
        $this->grupoFamiliarSolicitudActual->COORDENADA_X = $this->input->get('longitud');
        $this->grupoFamiliarSolicitudActual->FECHA_GEO =
            $this->grupoFamiliarSolicitudActual->GRUPFAMI_C_CALLE == Constante::ID_CALLE_10999_NO_REGISTRADA
                ? date('Ymd') : '';
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_TIPOVIVIENDA = $this->input->get('v1');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_SITUACIONSITIO = $this->input->get('v2');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_SITUACIONVIVIENDA = $this->input->get('v3');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_FUENTEAGUA = $this->input->get('v4');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_DISTRIBUCIONAGUA = $this->input->get('v5');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_SISTEMABANO = $this->input->get('v6');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_DORMITORIOSOCUPADOS = $this->input->get('v7');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_PRINCIPALOCUPANTE = $this->input->get('v8');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALMURO = $this->input->get('v9');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALMUROESTADO = $this->input->get('v9-estado');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALPISO = $this->input->get('v10');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALPISOESTADO = $this->input->get('v10-estado');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALTECHO = $this->input->get('v11');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_MATERIALTECHOESTADO = $this->input->get('v11-estado');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_FECHA_VALIDACION_DIR = $fechaValidacionDir;
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_FECHA_VALIDACION_ACT = $fechaValidacionAct;
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_RUT_VALIDACION_ACT = $runValidacion;
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_FECHA_MODIFICACION = date('Ymd');
        $this->grupoFamiliarSolicitudActual->GRUPFAMI_RUN_ENCUESTADOR = $this->input->get('run-encuestador');

        $fechaEncuesta = $this->input->get('fecha-encuesta');
        if (in_array($fechaEncuesta, ['', '00/00/0000'])) {
            $fechaEncuesta = null;
        }

        $this->grupoFamiliarSolicitudActual->GRUPFAMI_FECHA_ENCUESTA = (isset($fechaEncuesta)) ? Utiles::fechaDateToNumber($fechaEncuesta) : null;
    }

    private function fillBeneficiario($integrante)
    {
        $beneficiario = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $integrante->id)
            ->where('bnfgrupfami_idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->first();

        if (is_null($beneficiario)) {
            $beneficiario = new BeneficiarioGrupoFamiliar();
            $beneficiario->BNFGRUPFAMI_ID = Sequence::nextValue('seq_benefgrupfampk');
            $beneficiario->BNFGRUPFAMI_PERSONA = $integrante->id;
            $beneficiario->BNFGRUPFAMI_GRUPOFAMILIAR = $this->grupoFamiliarSolicitudActual->GRUPFAMI_ID;
            $beneficiario->BNFGRUPFAMI_IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
            $beneficiario->BNFGRUPFAMI_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $beneficiario->BNFGRUPFAMI_FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $beneficiario->BNFGRUPFAMI_VIGENTE = Constante::VIGENTE_2_PENDIENTE;
        }

        $beneficiario->BNFGRUPFAMI_PARENTESCO = $integrante->get('parentesco'); // si no se especifica por defecto viene en 99
        $beneficiario->BNFGRUPFAMI_FECHA = '';
        $beneficiario->BNFGRUPFAMI_FECHAEXPIRACION = '';
        $beneficiario->BNFGRUPFAMI_INFORMANTE = $integrante->get('informante');
        $beneficiario->BNFGRUPFAMI_FECHA_INGRESO = '';
        $beneficiario->BNFGRUPFAMI_FECHA_MODIFICACION = '';
        return $beneficiario;
    }

    private function fillFicha($integrante)
    {
        $ficha = Ficha::where('ficha_persona', $integrante->id)
            ->where('ficha_idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->first();

        if (is_null($ficha)) {
            $fichaId = Sequence::nextValue('SEQ_FICHAPK');
            $ficha = new Ficha();
            $ficha->FICHA_ID = $fichaId;
            $ficha->FICHA_IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
            $ficha->FICHA_VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $ficha->FICHA_CALCULOACTIVO = Constante::CALCULO_ACTIVO_2_PENDIENTE;
            $ficha->FICHA_FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $ficha->FICHA_PERSONA = $integrante->id;
        }

        if ($this->grupoFamiliarSolicitudActual->GRUPFAMI_INSTITUCIONALIZADO != Constante::INSTITUCIONALIZADO_1_INSTITUCIONALIZADO) {
            $ficha->FICHA_INSTITUCIONALIZADO = Constante::INSTITUCIONALIZADO_0_NO_INSTITUCIONALIZADO;
        }

        $ficha->FICHA_PUEBLOINDIGENA = $integrante->get('pueblo-originario');
        $ficha->FICHA_NUMPAREJA = $integrante->get('pareja');

        $ficha->FICHA_ASISTEESTABEDUCACIONAL = $integrante->get('e1');
//        $ficha->FICHA_NOASISTEMOTIVO = $integrante->get('e2');
        $ficha->FICHA_CURSO = $integrante->get('e3');
        $ficha->FICHA_TIPOEDUCACION = $integrante->get('e4');
        $ficha->FICHA_CONDICIONDISCAPACIDAD = $integrante->get('s1');
        $ficha->FICHA_DIFICVISUAL = $integrante->get('s1a', 2);
        $ficha->FICHA_DIFICAUDITIVA = $integrante->get('s1b', 2);
        $ficha->FICHA_DIFICHABLA = $integrante->get('s1c', 2);
        $ficha->FICHA_DIFICFISICA = $integrante->get('s1d', 2);
        $ficha->FICHA_DIFICMENTAL = $integrante->get('s1e', 2);
        $ficha->FICHA_DIFICPSIQUICA = $integrante->get('s1f', 2);
        $ficha->FICHA_CALLESINAYUDA = $integrante->get('s2a');
        $ficha->FICHA_MEDICOSINAYUDA = $integrante->get('s2b');
        $ficha->FICHA_ASEOSINAYUDA = $integrante->get('s2c');
        $ficha->FICHA_DESPLAZAMIENTOENCASA = $integrante->get('s2d');
        $ficha->FICHA_CTRLESFINTERES = $integrante->get('s2e');

        $ficha->FICHA_TRABAJA = $integrante->get('o1');
        $ficha->FICHA_OCUPACIONACTUAL = $integrante->get('o2');
        $ficha->FICHA_CODIGOSRAMA = $integrante->get('o3');
        $ficha->FICHA_TEMPORALIDADPERMANENCIA = $integrante->get('o4');
        $ficha->FICHA_CODIGOCONTRATO = $integrante->get('o5');
        $ficha->FICHA_RELACIONCONTRACTUAL = $integrante->get('o6');
        $ficha->FICHA_HORASTRABAJO = $integrante->get('o7');
        $ficha->FICHA_BUSCOTRABAJO = $integrante->get('o8');
        $ficha->FICHA_NOBUSCOTRABAJOMOTIVO = $integrante->get('o9');

        $ficha->FICHA_INGRESOOTROS = $integrante->get('y3');
        if ($this->solTieneMontoMensual) {
            $ficha->FICHA_INGRESOANUAL =
                intval($integrante->get('y1-m1')) +
                intval($integrante->get('y1-m2')) +
                intval($integrante->get('y1-m3')) +
                intval($integrante->get('y1-m4')) +
                intval($integrante->get('y1-m5')) +
                intval($integrante->get('y1-m6')) +
                intval($integrante->get('y1-m7')) +
                intval($integrante->get('y1-m8')) +
                intval($integrante->get('y1-m9')) +
                intval($integrante->get('y1-m10')) +
                intval($integrante->get('y1-m11')) +
                intval($integrante->get('y1-m12'));

            $ficha->FICHA_INGRESOJUBILACION =
                intval($integrante->get('y2-m1')) +
                intval($integrante->get('y2-m2')) +
                intval($integrante->get('y2-m3')) +
                intval($integrante->get('y2-m4')) +
                intval($integrante->get('y2-m5')) +
                intval($integrante->get('y2-m6')) +
                intval($integrante->get('y2-m7')) +
                intval($integrante->get('y2-m8')) +
                intval($integrante->get('y2-m9')) +
                intval($integrante->get('y2-m10')) +
                intval($integrante->get('y2-m11')) +
                intval($integrante->get('y2-m12'));
        } else {
            $ficha->FICHA_INGRESOANUAL = $integrante->get('y1');
            $ficha->FICHA_INGRESOJUBILACION = $integrante->get('y2');
        }

        if ($integrante->edad < Constante::EDAD_15_PUEDE_TRABAJAR) {
            $ficha->FICHA_INGRESOANUAL = null;
            $ficha->FICHA_INGRESOOTROS = null;
        }
        return $ficha;
    }

    private function validarRespuestasEducacion()
    {
        if ($this->requiereModulosESO) {
            $validador = new ValidadorEducacion();
            foreach ($this->input->integrantes as $integrante) {
                if (!$validador->validarRespuestasEducacion($integrante)) {
                    $this->addErrorWrapper($validador->errors);
                }
            }
        }
    }

    private function validarCamposRequeridos()
    {
        // logica detras de este metodo es solo validar lo directamente obligatorio para la solicitud
        // posteriormente entra cada metodo de validación independiente que se encargar de velar por
        // validaciones según las respuestas y lógicas individuales

        if ($this->requiereCamposObligatorios) {
            // reglas requeridas de localidad, acá solo se valida si es requerido
            // y que venga consistente en tipo de dato y valores posibles, solo si es required
            // para los required_if no se puede validar en este lugar.
            $reglas = [
                'comuna' => 'required|integer|min:1|max:99999999',
                'localidad' => 'required|integer|min:1|max:99999999',
                'codigo-calle' => 'required|numeric|between:' . implode(',', Constante::$CODIGO_CALLE_1_Y_99999999999999999999_POSIBLES_RESPUESTAS_NUMERIC),
                'nombre-calle' => 'required_if:codigo-calle,10999',
                'referencia' => 'required|string|min:15|max:400',
                'agrupacion-habitacional' => 'required|integer|max:999999999',
                'unidad-vecinal' => 'required|integer|min:0|max:9999999999',
            ];

            $validator = Validator::make($this->input->all(), $reglas);
            if ($validator->fails()) {
                $this->collectError($validator->messages(), Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO);
            }

            if ($this->requiereV1Obligatorio) {
                $inputValidator = $this->input->all();
                $rules['v1'] = 'required|integer|in:' . implode(',', Constante::$RESPUESTA_V1_1_A_11_POSIBLES_RESPUESTAS);
                $mensaje['v1'] = 'Valor de V1 incorrecto, requiere valores entre ' . Utiles::InicioYFin(Constante::$RESPUESTA_V1_1_A_11_POSIBLES_RESPUESTAS);
                $validator = Validator::make($inputValidator, $rules, $mensaje);
                if ($validator->fails()) {
                    $this->collectError($validator->messages(), Constante::ERR_NEGOCIO_V1_INVALIDO);
                }
            }

            if ($this->requiereModulosESO) {
                $integrantes = $this->input->integrantes;
                if (!empty($integrantes)) {
                    foreach ($integrantes as $integrante) {
                        $rules = [
                            'e1' => 'required',
//                            'e2' => 'required_if:e1,' . Constante::RESPUESTA_E1_2_NO,
                            'e3' => 'required_if:e1,' . Constante::RESPUESTA_E1_2_NO,
                            'e4' => 'required_if:e1,' . Constante::RESPUESTA_E1_2_NO,
                            's1' => 'required',
                            's1a' => 'required_if:s1,' . Constante::RESPUESTA_S1_1_SI,
                            's1b' => 'required_if:s1,' . Constante::RESPUESTA_S1_1_SI,
                            's1c' => 'required_if:s1,' . Constante::RESPUESTA_S1_1_SI,
                            's1d' => 'required_if:s1,' . Constante::RESPUESTA_S1_1_SI,
                            's1e' => 'required_if:s1,' . Constante::RESPUESTA_S1_1_SI,
                            's1f' => 'required_if:s1,' . Constante::RESPUESTA_S1_1_SI,
                        ];
                        $validator = Validator::make($integrante->toArray(), $rules);
                        if ($validator->fails()) {
                            $this->collectError($validator->messages(),
                                Constante::ERR_VALIDACION_INTEGRANTE_DATOS_FALTANTES,
                                $integrante->runTitular,
                                $integrante->runTitular);
                        }
                    }
                } else {
                    $this->collectError('No se encontraron integrantes en la solicitud',
                        Constante::ERR_VALIDACION_ERROR_NO_SE_ENCONTRARON_INTEGRANTES_EN_LA_SOLICITUD);
                }
            }
        }
        if ($this->requiereObservacion) {
            $reglas = [
                'observacion' => 'required'
            ];
            $validator = Validator::make(['observacion' => $this->observacion], $reglas);
            if ($validator->fails()) {
                $this->collectError($validator->messages(), Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO);
            }
        }
    }

    private function validarRespuestasIngreso()
    {
        if ($this->requiereModulosESO) {
            $validador = new ValidadorIngreso();
            foreach ($this->input->integrantes as $integrante) {
                if (!$validador->validarRespuestasIngreso($integrante)) {
                    $this->addErrorWrapper($validador->errors);
                }
            }
        }
    }

    private function validarRespuestasSalud()
    {
        if ($this->requiereModulosESO) {
            $validador = new ValidadorSalud();
            foreach ($this->input->integrantes as $integrante) {
                if (!$validador->validarRespuestasSalud($integrante)) {
                    $this->addErrorWrapper($validador->errors);
                }
            }
        }
    }

    private function validarRespuestasVivienda()
    {
        if ($this->requiereVivienda) {
            $validador = new ValidadorVivienda();
            if (!$validador->validarRespuestasVivienda($this->input)) {
                $this->addErrorWrapper($validador->errors);
            }
        }
    }

    private function validarRespuestasLocalidad()
    {
        if ($this->requiereLocalizacion) {
            $validador = new ValidadorLocalidad();
            $requeridosAdicionales = [];

            $requeridosAdicionales['nombreCalleRequerido'] = false;
            $requeridosAdicionales['campoRequeridoAH'] = true;
            $requeridosAdicionales['campoRequeridoUV'] = true;
            $requeridosAdicionales['campoRequeridoReferencia'] = true;
            $requeridosAdicionales['campoRequeridoComuna'] = true;

            // Cuando ya no corresponde a un estado de borrador
            // o bien el campo viene Vacio o Nulo, comienza a solicitar
            // obligatoriedad (required) en los campos

            if (in_array($this->accion, [
                Constante::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3,
                Constante::ACCION_GESTION_VA_ESTADO_3,
                Constante::ACCION_APROBAR_VA_ESTADO_5
            ])
            ) {
                $requeridosAdicionales['campoRequeridoReferencia'] = false;
            }

            if (in_array($this->accion, [
                Constante::ACCION_GESTION_VA_ESTADO_3,
                Constante::ACCION_APROBAR_VA_ESTADO_5
            ])) {
                $requeridosAdicionales['campoRequeridoComuna'] = false;
            }

            /**
             * Se definio que el campo numero es campo obligatorio,
             * inclusive para aquellos casos que el usuario viva en una direccion sin numero
             * para ello, debe ser enviado "S/N" Sin Numero.
             */
            if ($this->canal == Constante::CANAL_5_STRING_MINUS_SUPERVISION) {
                $requeridosAdicionales['campoRequeridoAH'] = false;
                $requeridosAdicionales['campoRequeridoUV'] = false;
            }

            if ($this->input->get('codigo-calle') == Constante::ID_CALLE_10999_NO_REGISTRADA) {
                $requeridosAdicionales['nombreCalleRequerido'] = false;
            }
            if (!$validador->validarRespuestasLocalidad($this->input, true, $requeridosAdicionales)) {
                $this->addErrorWrapper($validador->errors);
            }
        }
    }

    private function getBeneficiariosParaEliminar($arrayIntegrantesNuevos)
    {
        $arrayPerIdBeneficiariosActuales = array_column($this->beneficiariosSolicitudActual->toArray(), 'BNFGRUPFAMI_PERSONA');
        $arrayPerIdBeneficiariosNuevos = array_column($arrayIntegrantesNuevos, 'id');
        $beneficiariosParaEliminar = array_diff($arrayPerIdBeneficiariosActuales, $arrayPerIdBeneficiariosNuevos);
        return $beneficiariosParaEliminar;
    }

    private function deleteBeneficiariosNoUsados($beneficiariosParaEliminar, $idSolicitud)
    {
        foreach ($beneficiariosParaEliminar as $perIdIntegranteParaEliminar) {
            $integranteParaEliminar = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $perIdIntegranteParaEliminar)
                ->where('bnfgrupfami_idsolicitud', $idSolicitud)
                ->first();
            $fichasParaEliminar = Ficha::where('ficha_persona', $perIdIntegranteParaEliminar)
                ->where('ficha_idsolicitud', $idSolicitud)
                ->get();
            foreach ($fichasParaEliminar as $ficha) {
                $FichaIngresosMensualesParaEliminar = FichaMontoMensual::where('mensual_idsolicitud', $idSolicitud)
                    ->where('mensual_id_persona', $perIdIntegranteParaEliminar)
                    ->get();
                foreach ($FichaIngresosMensualesParaEliminar as $fichaIngresosMensuales) {
                    $fichaIngresosMensuales->delete();
                }
                $ficha->delete();
            }
            $integranteParaEliminar->delete();
        }
    }

    public function validacionDatosEncuestador()
    {
        if ($this->requiereEncuestador) {
            $validador = new ValidadorEncuestador();
            $fechaSolicitud = is_null($this->solicitud->FECHA_CREACION_CIUDADANO)
                ? $this->solicitud->FECHA_CREACION
                : $this->solicitud->FECHA_CREACION_CIUDADANO;
            if (!$validador->validarEncuestador($this->input, $fechaSolicitud)) {
                $this->addErrorWrapper($validador->errors);
            }
        }
    }

    public function validarAdjuntosRequeridos()
    {
        if ($this->requiereDocumentosAdjuntos) {
            if (!(new Adjuntos)->validarDocumentosRequeridos($this->solicitud)) {
                throw new ExcepcionReglaNegocio('No se han adjuntado todos los documentos requeridos.',
                    Constante::ERR_NEGOCIO_ADJUNTOS_REQUERIDOS);
            }
        }
    }

    private function validacionExtraIntegrante()
    {
        $validador = new ValidadorExtraIntegrante();
        if ($this->requiereValidacionesExtra) {
            if (!$validador->validacionParejaParentesco($this->input->integrantes)) {
                $this->addErrorWrapper($validador->errors);
            }
            if (!$validador->validacionInformante($this->input->integrantes, true)) {
                $this->addErrorWrapper($validador->errors);
            }
            if (!$validador->validacionPuebloOriginario($this->input->integrantes)) {
                $this->addErrorWrapper($validador->errors);
            }
        }
    }

    private function validaPersonasFallecidas()
    {
        $validador = new ValidadorExtraIntegrante();
        if (!$validador->personaFallecida($this->input->integrantes)) {
            $this->addErrorWrapper($validador->errors);
            return false;
        }
        return true;
    }

    // Se sobreescribe ya que en la solicitud de ingreso al RSH no necesariamente existe
    // un grupo vigente para extraer la comuna, por ende se toma la comuna del input
    public function leerComunaDesdeGrupoFamiliarCiudadano($runCiudadano, $tipoSolicitud)
    {
        return $this->input->get('comuna');
    }

    /********************
     * Consulta Modulo
     ********************
     * 1 = Modulo Educacion;
     * 2 = Modulo Salud;
     * 3 = Modulo Ocupacion/Ingreso;
     * 4 = Modulo Vivienda;
     */
    static function consultaModulo($id_sol, $run_per, $modulo)
    {
        $persona = Personas::where('per_run', $run_per)
            ->first();

        if ($persona) {

            if ($modulo == 1) {

                $sql = "SELECT fn.* FROM fichas fv
                              JOIN fichas fn ON fv.ficha_persona = fn.ficha_persona
                              WHERE fv.ficha_calculoactivo = " . Constante::CALCULO_ACTIVO_1_APROBADO . "
                                AND fn.FICHA_IDSOLICITUD = " . $id_sol . "
                                AND fv.ficha_persona = " . $persona->PER_ID . "
                                AND nvl(fv.ficha_asisteestabeducacional,0) = nvl(fn.ficha_asisteestabeducacional,0)
                                AND nvl(fv.ficha_noasistemotivo,0) = nvl(fn.ficha_noasistemotivo,0)
                                AND nvl(fv.ficha_curso,0) = nvl(fn.ficha_curso,0)
                                AND nvl(fv.ficha_tipoeducacion,0) = nvl(fn.ficha_tipoeducacion,0)";

                $resultado = DB::select($sql);

            }

            if ($modulo == 2) {

                $sql = "SELECT fn.* FROM fichas fv
                             JOIN fichas fn ON fv.ficha_persona = fn.ficha_persona
                             WHERE fv.ficha_calculoactivo = " . Constante::CALCULO_ACTIVO_1_APROBADO . "
                                 AND fn.FICHA_IDSOLICITUD = " . $id_sol . "
                                 AND fv.ficha_persona = " . $persona->PER_ID . "
                                 AND fv.ficha_condiciondiscapacidad = fn.ficha_condiciondiscapacidad
                                 AND fv.ficha_dificvisual = fn.ficha_dificvisual
                                 AND fv.ficha_dificauditiva = fn.ficha_dificauditiva
                                 AND fv.ficha_difichabla = fn.ficha_difichabla
                                 AND fv.ficha_dificfisica = fn.ficha_dificfisica
                                 AND fv.ficha_dificmental = fn.ficha_dificmental
                                 AND fv.ficha_dificpsiquica = fn.ficha_dificpsiquica
                                 AND fv.ficha_callesinayuda = fn.ficha_callesinayuda
                                 AND fv.ficha_medicosinayuda = fn.ficha_medicosinayuda
                                 AND fv.ficha_aseosinayuda = fn.ficha_aseosinayuda
                                 AND fv.ficha_desplazamientoencasa = fn.ficha_desplazamientoencasa
                             AND fv.ficha_ctrlesfinteres = fn.ficha_ctrlesfinteres";
                $resultado = DB::select($sql);

            }
            if ($modulo == 3) {

                $sql = "SELECT fn.* FROM fichas fv
                             JOIN fichas fn ON fv.ficha_persona = fn.ficha_persona
                             WHERE fv.ficha_calculoactivo = " . Constante::CALCULO_ACTIVO_1_APROBADO . "
                                 AND fn.FICHA_IDSOLICITUD = " . $id_sol . "
                                 AND fv.ficha_persona = " . $persona->PER_ID . "
                                 AND fv.ficha_trabaja = fn.ficha_trabaja
                                 AND fv.ficha_ocupacionactual = fn.ficha_ocupacionactual
                                 AND fv.ficha_codigosrama = fn.ficha_codigosrama
                                 AND fv.ficha_temporalidadpermanencia = fn.ficha_temporalidadpermanencia
                                 AND fv.ficha_codigocontrato = fn.ficha_codigocontrato
                                 AND fv.ficha_relacioncontractual = fn.ficha_relacioncontractual
                                 AND fv.ficha_horastrabajo = fn.ficha_horastrabajo
                                 AND fv.ficha_buscotrabajo = fn.ficha_buscotrabajo
                                 AND fv.ficha_nobuscotrabajomotivo = fn.ficha_nobuscotrabajomotivo
                                 AND fv.ficha_ingresoanual = fn.ficha_ingresoanual
                                 AND fv.ficha_ingresojubilacion = fn.ficha_ingresojubilacion
                                 AND fv.ficha_ingresootros = fn.ficha_ingresootros";
                $resultado = DB::select($sql);

            }
            if ($modulo == 4) {

                $sql = "SELECT gn.* FROM grupos_familiares gv
                             JOIN grupos_familiares gn ON gv.GRUPFAMI_NUEVOFOLIO = gn.GRUPFAMI_NUEVOFOLIO
                             WHERE gv.GRUPFAMI_CALCULOACTIVO = 1
                                 AND gn.GRUPFAMI_IDSOLICITUD = " . $id_sol . "
                                 AND nvl(gv.grupfami_tipovivienda,0) = nvl(gn.grupfami_tipovivienda,0)
                                 AND nvl(gv.grupfami_situacionsitio,0) = nvl(gn.grupfami_situacionsitio,0)
                                 AND nvl(gv.grupfami_situacionvivienda,0) = nvl(gn.grupfami_situacionvivienda,0)
                                 AND nvl(gv.grupfami_fuenteagua,0) = nvl(gn.grupfami_fuenteagua,0)
                                 AND nvl(gv.grupfami_distribucionagua,0) = nvl(gn.grupfami_distribucionagua,0)
                                 AND nvl(gv.grupfami_sistemabano,0) = nvl(gn.grupfami_sistemabano,0)
                                 AND nvl(gv.grupfami_dormitoriosocupados,0) = nvl(gn.grupfami_dormitoriosocupados,0)
                                 AND nvl(gv.grupfami_principalocupante,0) = nvl(gn.grupfami_principalocupante,0)
                                 AND nvl(gv.grupfami_materialmuro,0) = nvl(gn.grupfami_materialmuro,0)
                                 AND nvl(gv.grupfami_materialmuroestado,0) = nvl(gn.grupfami_materialmuroestado,0)
                                 AND nvl(gv.grupfami_materialpiso,0) = nvl(gn.grupfami_materialpiso,0)
                                 AND nvl(gv.grupfami_materialpisoestado,0) = nvl(gn.grupfami_materialpisoestado,0)
                                 AND nvl(gv.grupfami_materialtecho,0) = nvl(gn.grupfami_materialtecho,0)
                                 AND nvl(gv.grupfami_materialtechoestado,0) = nvl(gn.grupfami_materialtechoestado,0)";
                $resultado = DB::select($sql);
            }

            if ($resultado)
                return 2; // son iguales
            else
                return 3; // no son iguales
        } else {
            return 1; // no se consigue persona
        }

    }
}
