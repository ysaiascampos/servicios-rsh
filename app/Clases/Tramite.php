<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionEjecucion;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Exceptions\ExcepcionValidacion;
use App\Http\Inputs\MetricaInput;
use App\Http\Inputs\ServiceInput;
use App\Http\Inputs\TramiteInput;
use App\Models\Comuna;
use App\Models\GrupoFamiliar;
use App\Models\GrupoFamiliarExport;
use App\Models\Objeto;
use App\Models\Personas;
use App\Models\PersonasExport;
use App\Models\NNARepresentanteMigrante;
use App\Models\MigranteNacionalidad;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\Solicitud;
use App\Models\SolicitudAdjuntos;
use App\Models\Usuario;
use App\Sequence;
use App\Utiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class Tramite extends FlujoTramite
{
    use ValidacionTrait;
    use PreguntasYRespuestasESOVTrait;

    public $tipoSolicitud;
    public $nombreSolicitud;

    protected $fechaActual;
    protected $validadorClaveUnica;
    protected $validacionfirmaSimple;
    protected $requiereDocumentosAdjuntos;

    public $usuario;
    public $titular;
    public $solicitante;
    public $encargado;
    public $firmantes;
    public $personasAsociadas;
    public $adjuntos;
    public $numeroRegistro;
    public $juridico;

    /**
     * @var GrupoFamiliar
     */
    public $grupoFamiliarVigente;

    public $tituloFormulario;
    public $firmaJefeHogar;
    public $otrasFirmas;

    public $aprobacionTercero;
    public $validacionporTerceros;
    protected $observacion;
    /**
     * @var ServiceInput
     */
    protected $input;
    /**
     * @var MetricaInput
     */
    protected $metricas;

    /**
     * @param TramiteInput|null $input
     * @throws ExcepcionConsistencia
     * @throws ExcepcionValidacion
     * @throws Throwable
     */
    public function __construct(TramiteInput $input = null)
    {
        $this->fechaActual = date('Y-m-d H:i:s');

        if (!empty($input)) {

            $this->accion = $input->accion;
            $this->canal = $input->canal;
            $this->origenRegistro = $input->origenRegistro;

            $this->usuario = $this->obtenerUsuario($input->runUsuario);
            $this->solicitante = $this->obtenerSolicitante($input->runCiudadano);
            $this->titular = $this->definirTitular($input);
            $this->observacion = $input->observacion;
            $this->metricas = $input->metricas;
            $this->input = !is_null($input->tipoSolicitud) ? $input->tipoSolicitud->{$this->tipoSolicitud} : null;

            $this->validadorClaveUnica = new ValidacionClaveUnica();
            $this->validacionfirmaSimple = 0;
            if (
                $this->tipoSolicitud != Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO
                && $this->tipoSolicitud != Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA
            ) {
                $this->numeroRegistro = (new TramiteExtends)->obtenerNumeroRegistro($this->solicitante);
            }
            $this->validacionporTerceros = null;
        }
    }

    /**
     * Instancia automaticamente la sub clase adecuada en función del tipo de solicitud.
     * @param TramiteInput|null $input
     * @param null $idSolicitud
     * @return Tramite
     * @throws ExcepcionConsistencia
     * @throws ExcepcionValidacion
     * @throws Throwable
     */
    public static function instanciarSubClase(TramiteInput $input = null, $idSolicitud = null): Tramite
    {
        $solicitud = null;
        $tipoSolicitud = null;
        if (!empty($idSolicitud)) {
            $solicitud = Solicitud::where('ID_SOLICITUD', $idSolicitud)->first();
            if (empty($solicitud)) {
                throw new ExcepcionConsistencia(
                    'No se encuentra la solicitud ' . $idSolicitud,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
                );
            }
            $tipoSolicitud = $solicitud->ID_TIPO_SOLICITUD;
        }
        if (!empty($input)) {
            $tipoSolicitud = $input->tipoSolicitud->getTipo();
            if (!empty($solicitud)) {
                if ($tipoSolicitud != $solicitud->ID_TIPO_SOLICITUD) {
                    throw ExcepcionValidacion::new("El campo 'tipo-solicitud' no corresponde a la solicitud")
                        ->setOrigen('tipo-solicitud');
                }
            }
        }
        if (!array_key_exists($tipoSolicitud, Constante::$clasesTramitePorTipoSolicitud)) {
            throw ExcepcionValidacion::new('Tipo de solicitud no permitido')
                ->setOrigen('tipo-solicitud');
        }
        $claseTramite = Constante::$clasesTramitePorTipoSolicitud[$tipoSolicitud];
        /**
         * @var Tramite $tramite
         */
        $tramite = new $claseTramite($input);

        if (!empty($solicitud)) {
            $tramite->setSolicitud($solicitud);
            $tramite->configurarCargaInformacionDeTramite();
        }
        $tramite->configurarCargaInformacionInicial();
        return $tramite;
    }

    public function configurarCargaInformacionInicial()
    {
        $this->cargarDataGrupoFamiliarVigente();
    }

    public function configurarCargaInformacionDeTramite()
    {
        $this->cargarDataTramite();
        $this->consultarTitular();
        $this->obtenerPersonasAsociadas();
    }

    protected abstract function cargarDataTramite();

    protected abstract function obtenerPersonasAsociadas();

    protected abstract function guardarSolicitud(array $data);

    protected abstract function actualizarRegistrosAprobacion();

    public function setSolicitud(Solicitud $solicitud)
    {
        $this->solicitud = $solicitud;
        if ($solicitud->MIGRANTE != 1) {
            $this->solicitante = $this->obtenerSolicitante($solicitud->RUN_CIUDADANO);
        } else {
            if ($solicitud->ID_TIPO_SOLICITUD == Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA) {
                $this->solicitante = $this->obtenerSolicitanteIngresoNNA($solicitud->RUN_CIUDADANO);
            } else {
                $this->solicitante = $this->obtenerSolicitanteNNA($solicitud->RUN_CIUDADANO);
            }
        }

        $this->adjuntos = $this->obtenerAdjuntos($solicitud->ID_SOLICITUD);
        $this->encargado = $this->obtenerEncargado();
        $this->numeroRegistro = (new TramiteExtends)->obtenerNumeroRegistro($this->solicitante);
        $this->firmantes = [
            $this->solicitante
        ];
    }

    /**
     * @param $datoActual
     * @param $datoNuevo
     * @param $nuevoFolio
     * @throws Throwable
     */
    protected function obtenerRegistroSolicitud($datoActual, $datoNuevo, $nuevoFolio)
    {
        $usuarioEncargado = null;
        $rutSolicitante = $this->solicitante->PER_RUN;

        if (self::esMunicipal()) {
            $vigente = Constante::VIGENTE_1_VIGENTE;
            $comuna = self::leerComunaDeUsuario();
            $rutUsuarioActual = $this->usuario->RUT_USUARIO;
            $usuarioEncargado = $this->usuario->RUT_USUARIO;
        } else {
            $comuna = $this->leerComunaDesdeGrupoFamiliarCiudadano(
                $this->solicitante->PER_RUN,
                $this->tipoSolicitud
            );
            if (self::esChileAtiende()) {
                $vigente = Constante::VIGENTE_1_VIGENTE;
                $rutUsuarioActual = $this->usuario->RUT_USUARIO;
                $usuarioEncargado = $this->usuario->RUT_USUARIO;
            } else {
                // ToDo revisar vigente para no municipal
                $vigente = Constante::$vigenteSegunAccionNoMunicipal[$this->accion];
                $rutUsuarioActual = $rutSolicitante;
                if ($vigente == Constante::VIGENTE_1_VIGENTE) {
                    $usuarioEncargado = Comuna::obtenerEncargadoComunalParaCiudadano($comuna);
                }
            }
        }

        if (is_null($this->solicitud)) {

            $estadoActual = Constante::ESTADO_SOLICITUD_FICTICIO_CREACION_SOLICITUD;

            $this->solicitud = new Solicitud();
            $this->solicitud->ID_SOLICITUD = Sequence::nextValue('seq_ab_solicitud');
            $this->solicitud->ID_PADRE_SOLICITUD = Sequence::nextValue('seq_ab_solicitud_padre');
            $this->solicitud->ID_TIPO_SOLICITUD = $this->tipoSolicitud;
            $this->solicitud->SOLICITUD = $this->nombreSolicitud;
            $this->solicitud->ID_FUENTE_NUEVA = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $this->solicitud->ID_FUENTE_ACTUAL = null;
            $this->solicitud->ID_USUARIO_CREACION = $rutUsuarioActual;
            $this->solicitud->FECHA_CREACION = $this->fechaActual;
            $this->solicitud->RUN_CIUDADANO = $rutSolicitante;
            $this->solicitud->COM_ID = $comuna;
            $this->solicitud->CANAL = $this->canal;
            $this->solicitud->ORIGEN_REGISTRO = $this->origenRegistro;

            if ($this->origenRegistro == Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA) {
                // viene de origen 2
                $this->solicitud->FECHA_CREACION_CIUDADANO = $this->fechaActual;
            }
        } else {

            $estadoActual = $this->solicitud->ID_ESTADO;
            if (self::ingresaAMunicipal()) {
                $this->solicitud->FECHA_CREACION = $this->fechaActual;
            }
        }

        $this->solicitud->ID_ESTADO = $this->flujo[$estadoActual][$this->accion][self::ESTADO];

        $this->solicitud->EMAIL_CIUDADANO = $this->solicitante->PER_EMAIL;
        $this->solicitud->FONO_CIUDADANO = $this->solicitante->PER_TELEFONO;
        $this->solicitud->FONO_CIUDADANO2 = $this->solicitante->PER_CELULAR;
        $this->solicitud->ID_USUARIO_MODIFICACION = $rutUsuarioActual;
        $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;

        $this->solicitud->DATO_NUEVO = $datoNuevo;
        $this->solicitud->DATO_ACTUAL = $datoActual;
        $this->solicitud->NUEVOFOLIO = $nuevoFolio;
        $this->solicitud->ID_USUARIO_ASIGNADO = $usuarioEncargado;
        $this->solicitud->FECHA_ASIGNACION = !is_null($usuarioEncargado) ? $this->fechaActual : null;

        $this->solicitud->VIGENTE = $vigente;
        $this->solicitud->ID_MOTIVO = null;
        $this->solicitud->OBSERVACION_RECTIFICACION = null;

        $this->solicitud->TOKEN_ACCESO = request()->header('token-clave-unica');
        $this->solicitud->FIRMA_SIMPLE = $this->validacionfirmaSimple;

        if ($this->origenRegistro == Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA) {
            $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
        }

        $this->obtenerMetricas();
    }

    public function aprobar()
    {
        if ($this->aprobacionTercero) {
            $this->usuario = new Usuario();
            $this->usuario->RUT_USUARIO = env('RUTAPROBADOR');
        } else {
            $this->requiereDocumentosAdjuntos = true;
            $this->validacionAprobarSolicitud();
        }
        try {
            DB::beginTransaction();

            $this->actualizarRegistrosAprobacion();

            $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;
            $this->solicitud->ID_USUARIO_MODIFICACION = $this->usuario->RUT_USUARIO;
            $this->solicitud->ID_ESTADO = $this->flujo[$this->solicitud->ID_ESTADO][$this->accion][self::ESTADO];
            $this->solicitud->VIGENTE = Constante::VIGENTE_1_VIGENTE;
            $this->solicitud->save();
            try {
                EnvioMail::enviaMailAprobado($this->solicitud);
            } catch (Throwable $e) {
                // fallo envio mail, pero deja continuar hasta el commit y mensaje de aprobación
                // revisar si corresponde UtilesController::enviarMail()
                Log::info('Falló mail, en Aprobar Incorporacion: ' . $this->solicitud->ID_SOLICITUD . PHP_EOL . $e);
            }

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function desistir()
    {
        $usuarioModificacion = !empty($this->usuario) ? $this->usuario->RUT_USUARIO : $this->solicitante->PER_RUN;
        DB::beginTransaction();
        if (!is_null($this->validacionporTerceros)) {
            try {
                $this->solicitud->ID_ESTADO = $this->flujo[$this->solicitud->ID_ESTADO][$this->accion][self::ESTADO];
                $this->solicitud->VIGENTE = Constante::VIGENTE_1_VIGENTE;
                $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;
                $this->solicitud->ID_USUARIO_MODIFICACION = $usuarioModificacion;
                $this->solicitud->save();
                DB::commit();
            } catch (Throwable $exception) {
                DB::rollBack();
                throw $exception;
            }
        } else {
            try {
                $this->solicitud->ID_ESTADO = $this->flujo[$this->solicitud->ID_ESTADO][$this->accion][self::ESTADO];
                $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;
                $this->solicitud->ID_USUARIO_MODIFICACION = $usuarioModificacion;
                $this->solicitud->save();
                DB::commit();
            } catch (Throwable $exception) {
                DB::rollBack();
                throw $exception;
            }
        }
    }

    public function rechazar()
    {
        if (empty($this->observacion)) {
            throw ExcepcionValidacion::new('El campo observacion es requerido.')
                ->setOrigen('observacion');
        }

        DB::beginTransaction();
        try {
            $this->solicitud->ID_ESTADO = $this->flujo[$this->solicitud->ID_ESTADO][$this->accion][self::ESTADO];
            $this->solicitud->COMENTARIO = $this->observacion;
            $this->solicitud->FECHA_MODIFICACION = $this->fechaActual;
            $this->solicitud->ID_USUARIO_MODIFICACION = $this->usuario->RUT_USUARIO;
            $this->solicitud->save();

            // ToDo: Enviar correo rechazo BandejaController@anularSolicitud

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    protected function consultarTitular()
    {
        $this->titular = $this->solicitante;
    }

    protected function definirTitular(TramiteInput $input)
    {
        if ($this->solicitante->PER_RUN == $input->runCiudadano) {
            $titular = $this->solicitante;
        } else {
            $titular = Personas::findByRun($input->runCiudadano);
        }
        return $titular;
    }

    protected function obtenerSolicitante($runSolicitante)
    {
        $ciudadano = Personas::findByRun($runSolicitante);
        if (is_null($ciudadano)) {
            throw new ExcepcionConsistencia(
                'El ciudadano no existe en la base de datos',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }
        return $ciudadano;
    }

    protected function obtenerUsuario($runUsuario)
    {
        $usuario = Usuario::where('username', $runUsuario)
            ->where('fecha_caducidad', '>=', $this->fechaActual)
            ->first();

        if ($this->esMunicipal() || $this->esChileAtiende()) {
            if (empty($usuario)) {
                throw ExcepcionValidacion::new('Usuario no es válido')
                    ->setOrigen('run-usuario')
                    ->setTitular($runUsuario);
            }
        }

        return $usuario;
    }

    protected function obtenerEncargado()
    {
        $encargado = null;
        if (!empty($this->solicitud->ID_USUARIO_ASIGNADO)) {
            $encargado = Usuario::where('username', $this->solicitud->ID_USUARIO_ASIGNADO)
                ->first();
        }
        return $encargado;
    }

    protected function obtenerAdjuntos($idSolicitud)
    {
        return SolicitudAdjuntos::recuperarAdjuntos($idSolicitud);
    }

    protected function agregarFirmante($firmante)
    {
        $this->firmantes[] = $firmante;
    }

    protected function agregarPersonasAsociadas($personasAsociadas)
    {
        $this->personasAsociadas[] = $personasAsociadas;
    }

    protected function verificarFirmaSimple()
    {
        $this->validacionfirmaSimple = $this->validadorClaveUnica->validar(
            strtolower($this->canal),
            $this->tipoSolicitud,
            $this->titular->PER_RUN,
            $this->solicitante->PER_RUN,
            $this->input
        );
    }

    protected function generarPdfFirmaSimple()
    {
        try {
            $formulario = new FormularioSolicitud($this->canal, $this);
            $formulario->guardarFormularioEnStorage();
        } catch (Throwable $ex) {
            throw new ExcepcionEjecucion('Error a generar PDF', Constante::ERR_EJECUCION_NO_CONTROLADO, $ex);
        }
    }

    protected function obtenerMetricas()
    {
        $guardarHoraDeTermino = false;      // ToDo: definir criterio para cuando guardar hora de termino

        if (!empty($this->metricas)) {
            $this->solicitud->GEO_LATITUD_CREACION =
                $this->metricas->get('latitud', $this->solicitud->GEO_LATITUD_CREACION);
            $this->solicitud->GEO_LONGITUD_CREACION =
                $this->metricas->get('longitud', $this->solicitud->GEO_LONGITUD_CREACION);
            $this->solicitud->HORA_INICIO_SOLICITUD =
                $this->metricas->get('hora-inicio', $this->solicitud->HORA_INICIO_SOLICITUD);
            if ($guardarHoraDeTermino) {
                $this->solicitud->HORA_TERMINO_SOLICITUD = date('Y-m-d H:i:s');
            }
        }
    }

    protected function cargarDataGrupoFamiliarVigente()
    {
        $this->grupoFamiliarVigente = GrupoFamiliar::findVigenteAprobadoByRunCiudadano(
            $this->solicitante->PER_RUN,
            false
        );
    }

    public function leerComunaDesdeGrupoFamiliarCiudadano($runCiudadano, $tipoSolicitud)
    {
        $comuna = null;
        if (in_array($tipoSolicitud, [
            Constante::TIPO_SOLICITUD_203_ACTUALIZACION_DATOS_TELEFONO,
            Constante::TIPO_SOLICITUD_204_ACTUALIZACION_DATOS_MAIL,
            Constante::TIPO_SOLICITUD_205_CONFIRMAR_DATOS
        ])) {
            try {
                $comuna = isset($this->grupoFamiliarVigente) ??
                    $this->grupoFamiliarVigente->GRUPFAMI_COMUNAINE;
                //(new DatosDeSolicitud())->getComunaDesdeSolicitudDomicilioVigentePorNuevoFolio($grupoFamiliar->grupfami_nuevofolio);
            } catch (Throwable $ex) {
            }
        } else {
            try {
                if (!empty($this->grupoFamiliarVigente)) {
                    $comuna = (new DatosDeSolicitud())
                        ->getComunaDesdeSolicitudDomicilioVigentePorNuevoFolio($this->grupoFamiliarVigente->GRUPFAMI_NUEVOFOLIO);
                }
                if (!$comuna) {
                    $personaExport = PersonasExport::findByRunCiudadano($runCiudadano);
                    if ($personaExport) {
                        $grupoFamiliarExport = GrupoFamiliarExport::findByIdGrupoFamiliar($personaExport->PER_FIC_GRUPOFAMILIAR_ID);
                        $comuna = isset($grupoFamiliarExport->GRUPFAMI_COMUNAINE) ?
                            $grupoFamiliarExport->GRUPFAMI_COMUNAINE : null;
                    } else {
                        throw ExcepcionConsistencia::new(
                            "No se encontro informacion asociada a residencia  RUN -> " . $runCiudadano,
                            Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
                        )
                            ->setTitular($runCiudadano);
                    }
                }
            } catch (Throwable $ex) {
                Log::error("Excepcion controlada en leerComunaDesdeGrupoFamiliarCiudadano, retornando null para (run: " . $runCiudadano . ")");
                throw $ex;
            }
        }
        return $comuna;
    }

    public function leerComunaDeUsuario()
    {
        $salida = null;
        if (!empty($this->usuario)) {
            $salida = $this->usuario->Comunas()->first()->COM_ID;
        }
        return $salida;
    }

    public function obtenerNuevoFolio(): ?int
    {
        return $this->grupoFamiliarVigente->GRUPFAMI_NUEVOFOLIO ?? $this->solicitud->NUEVOFOLIO;
    }

    public function obtenerNumeroRegistro()
    {
        $personaExport = PersonasExport::findByRunCiudadano($this->solicitante->PER_RUN);
        if ($personaExport) {
            $grupoFamiliarExport = GrupoFamiliarExport::findByIdGrupoFamiliar($personaExport->PER_FIC_GRUPOFAMILIAR_ID);
            if ($grupoFamiliarExport) {
                $nroGrupoFamiliar = $grupoFamiliarExport->GRUPFAMI_NUEVOFOLIO;
            } else {
                $nroGrupoFamiliar = '';
            }
        } else {
            $this->collectError(
                'No se puede consultar el export.',
                Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST
            );
            return false;
        }
        return $nroGrupoFamiliar;
    }

    protected function esMunicipal(): bool
    {
        return (
            in_array($this->canal, Constante::$CANAL_1_MUNICIPAL_VALIDOS) &&
            in_array($this->origenRegistro, Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS)
        );
    }

    protected function esChileAtiende(): bool
    {
        return (
            in_array($this->canal, Constante::$CANAL_4_CHILE_ATIENDE_EJECUTOR_VALIDOS) &&
            in_array($this->origenRegistro, Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS)
        );
    }

    protected function esPerfilAprobador(): bool
    {
        $esPerfilAprobador = in_array($this->usuario->ID_PERFIL, [
            Constante::PERFIL_12_ENCARGADO_COMUNAL,
            Constante::PERFIL_17_APOYO_ENCARGADO_COMUNAL,
            Constante::PERFIL_25_CHILE_ATIENDE_EJECUTOR
        ]);
        $esRunAprobacionAutomatica = $this->usuario->RUT_USUARIO == env('RUTAPROBADOR');
        return $esPerfilAprobador || $esRunAprobacionAutomatica;
    }

    protected function ingresaAMunicipal(): bool
    {
        $accionesQueLlevanAMunicipal = [
            Constante::ACCION_RESOLVER_VA_ESTADO_4
        ];
        return (
            !self::esMunicipal() &&
            !self::esChileAtiende() &&
            in_array($this->accion, $accionesQueLlevanAMunicipal)
        );
    }

    protected function validacionGuardadoSolicitud()
    {
        $this->validarComunaMunicipal();
        $this->validarEdadSolicitante();

        if (empty($this->solicitud)) {
            $this->configurarValidacionSolicitudesPendientes();
        }

        if (!empty($this->errors)) {
            throw new ExcepcionReglaNegocio($this->errors);
        }
    }

    public function validacionAprobarSolicitud()
    {
        $this->validarPerfilAprobar();
        $this->validarAdjuntosRequeridos();

        if (!empty($this->errors)) {
            throw new ExcepcionReglaNegocio($this->errors);
        }
    }

    protected function validarComunaMunicipal()
    {
        $runCiudadano = $this->solicitante->PER_RUN;

        $esMunicipal = $this->esMunicipal();
        $esAplicableAComuna = !in_array($this->tipoSolicitud, [
            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
            Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION,
            Constante::TIPO_SOLICITUD_201_CAMBIO_DATOS_DE_CONTACTO,
            Constante::TIPO_SOLICITUD_203_ACTUALIZACION_DATOS_TELEFONO,
            Constante::TIPO_SOLICITUD_204_ACTUALIZACION_DATOS_MAIL,
            Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA
        ]);

        if ($esMunicipal && $esAplicableAComuna) {

            if ($this->usuario->RUT_USUARIO != env('RUTAPROBADOR')) {

                $comunaUsuarioMunicipal = $this->leerComunaDeUsuario();
                if (is_null($comunaUsuarioMunicipal)) {
                    $this->collectError(
                        'El usuario no está asociado a ninguna comuna.',
                        Constante::ERR_NEGOCIO_USUARIO_SIN_COMUNA
                    );
                }

                if (in_array($this->tipoSolicitud, [
                    Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
                    Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE,
                    Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR
                ])) {
                    $integrantes = $this->input;
                    $runCiudadano = current($integrantes)->run;
                }

                $comunaCiudadano = $this->leerComunaDesdeGrupoFamiliarCiudadano($runCiudadano, $this->tipoSolicitud);
                if ($comunaUsuarioMunicipal != $comunaCiudadano) {
                    $this->collectError(
                        'No se puede ingresar una solicitud de un ciudadano de otra comuna.',
                        Constante::ERR_NEGOCIO_COMUNA_NO_CORRESPONDE
                    );
                    return false;
                }
            }
        }
        return true;
    }

    protected function validarEdadSolicitante()
    {
        $esMunicipal = $this->esMunicipal();
        if (!$esMunicipal) {
            if (Utiles::getEdad($this->solicitante->PER_FECHANACIMIENTO) < Constante::EDAD_18_MAYOR_DE_EDAD) {
                $this->collectError(
                    'El solicitante debe ser mayor de ' . Constante::EDAD_18_MAYOR_DE_EDAD . ' años',
                    Constante::ERR_NEGOCIO_SOLICITANTE_MENOR_EDAD
                );
                return false;
            }
        }
        return true;
    }

    protected function configurarValidacionSolicitudesPendientes()
    {
        $esActualizacionDeModulo = in_array($this->tipoSolicitud, Constante::$solicitudesActualizacionESO);
        if ($esActualizacionDeModulo) {
            if (!$this->validarSolicitudesPendientes($this->input->runTitular, $this->tipoSolicitud, false)) {
                return false;
            }
        } else {
            if (Utiles::inArrayMinuscula(
                $this->origenRegistro,
                Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS
            )) {
                if (!$this->validarSolicitudesPendientes($this->solicitante->PER_RUN, $this->tipoSolicitud, false)) {
                    return false;
                }
            } else {
                if (!$this->validarSolicitudesPendientes(
                    $this->solicitante->PER_RUN,
                    $this->tipoSolicitud,
                    false,
                    true
                )) {
                    return false;
                }
            }
            $validarIntegrantes = in_array(
                $this->tipoSolicitud,
                Constante::$solicitudesValidacionBloqueantesPorIntegrante
            );
            if ($validarIntegrantes) {
                foreach ($this->input as $integrante) {
                    if ($integrante->runTitular != $this->solicitante->PER_RUN) {
                        if (!$this->validarSolicitudesPendientes(
                            $integrante->runTitular,
                            $this->tipoSolicitud,
                            false,
                            false
                        )) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    public function validarSolicitudesPendientes(
        $runCiudadano,
        $tipoSolicitud,
        $validarEdadCiudadano = true,
        $solicitante = false
    ) {
        $puedeRealizarSolicitud = true;
        $origenRegistro = $this->origenRegistro;
        $edadCiudadano = null;

        if ($validarEdadCiudadano) {
            $edadCiudadano = Utiles::getEdad($this->solicitante->PER_FECHANACIMIENTO);
        }

        $solicitudesTramitables = DatosDeSolicitud::getTodasSolicitudes($runCiudadano, $edadCiudadano, $origenRegistro);
        if (is_numeric($solicitudesTramitables)) {
            if ($solicitudesTramitables == Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR) {
                $mensaje = Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR;
            } elseif ($solicitudesTramitables == Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL) {
                $mensaje = "El RUN ingresado no se encuentra en el registro civil.";
            } else {
                $mensaje = "El RUN ingresado no se encuentra en nuestros registros.";
            }
            throw new ExcepcionValidacion(
                'No se puede realizar esta operación: ' . $mensaje,
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }
        if (array_key_exists($tipoSolicitud, $solicitudesTramitables)) {
            $puedeRealizarSolicitud = $solicitudesTramitables[$tipoSolicitud]['puede-realizar-solicitud'];
        }

        if ($tipoSolicitud == Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE && $solicitante == false) {
            $analisisEspecialTipo3_1 = $solicitudesTramitables[Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO]['puede-realizar-solicitud'];
            $analisisEspecialTipo3_2 = $solicitudesTramitables[Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE]['puede-realizar-solicitud'];
            $analisisEspecialTipo3_3 = $solicitudesTramitables[Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA]['puede-realizar-solicitud'];
            if (($analisisEspecialTipo3_1 == true) || ($analisisEspecialTipo3_2 == true) || ($analisisEspecialTipo3_3 == true)) {
                return true;
            }
        }

        if (!$puedeRealizarSolicitud) {
            $mensaje = $solicitudesTramitables[$tipoSolicitud]['mensaje'];
            $this->collectError($mensaje, Constante::ERR_NEGOCIO_BLOQUEO_POR_SOLICITUD_PENDIENTE);
            return false;
        }
        return true;
    }


    public function validarPerfilAprobar()
    {
        if ($this->usuario->ID_PERFIL == Constante::PERFIL_8_ATENCION_DE_CASO) {
            if ($this->solicitud->ID_ESTADO > Constante::ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL) {
                throw ExcepcionReglaNegocio::new(
                    'Error: La solicitud no puede ser modificada debido a su estado. Usted no tiene permiso.',
                    Constante::ERR_NEGOCIO_PERFIL_NO_AUTORIZADO
                )
                    ->setTitular($this->usuario->RUT_USUARIO);
            }
        }
        if (!$this->esPerfilAprobador()) {
            throw new ExcepcionReglaNegocio(
                'Usuario no autorizado para aprobar.',
                Constante::ERR_NEGOCIO_PERFIL_NO_AUTORIZADO
            );
        }
    }

    public function validarAdjuntosRequeridos()
    {
        if ($this->requiereDocumentosAdjuntos) {
            if (!(new Adjuntos)->validarDocumentosRequeridos($this->solicitud)) {
                throw new ExcepcionReglaNegocio(
                    'No se han adjuntado todos los documentos requeridos.',
                    Constante::ERR_NEGOCIO_ADJUNTOS_REQUERIDOS
                );
            }
        }
    }

    public static function crearDummyParaFormulario($idSolicitud)
    {
        $ciudadano = null;
        $solicitud = Solicitud::where('ID_SOLICITUD', $idSolicitud)
            ->first();
        if ($solicitud) {
            $ciudadano = Personas::findByRun($solicitud->RUN_CIUDADANO);
        }
        $obtenerNumeroRegistro = (new TramiteExtends())->obtenerNumeroRegistro($ciudadano, false);

        return (object)[
            'tituloFormulario' => '',
            'nombreSolicitud' => $solicitud ? $solicitud->SOLICITUD : '',
            'solicitud' => (object)[
                'ID_PADRE_SOLICITUD' => $solicitud ? $solicitud->ID_PADRE_SOLICITUD : '',
                'FECHA_CREACION' => $solicitud ? $solicitud->FECHA_CREACION : '',
                'NUEVOFOLIO' => $solicitud ? $solicitud->NUEVOFOLIO : '',
            ],
            'solicitante' => (object)[
                'PER_RUN' => $ciudadano ? $ciudadano->PER_RUN : '',
                'PER_DV' => $ciudadano ? $ciudadano->PER_DV : '',
                'PER_NOMBRES' => $ciudadano ? $ciudadano->PER_NOMBRES : '',
                'PER_APELLIDOPATERNO' => $ciudadano ? $ciudadano->PER_APELLIDOPATERNO : '',
                'PER_APELLIDOMATERNO' => $ciudadano ? $ciudadano->PER_APELLIDOMATERNO : '',
                'PER_EMAIL' => $ciudadano ? $ciudadano->PER_EMAIL : '',
                'PER_TELEFONO' => $ciudadano ? $ciudadano->PER_TELEFONO : '',
            ],
            'numeroRegistro' => $obtenerNumeroRegistro,

        ];
    }

    public function setAprobacionTercero()
    {
        $this->aprobacionTercero = true;
    }

    public function setAccion($accion)
    {
        $this->accion = $accion;
    }

    public function setVigenteRechazoAprobacionTercero()
    {
    }

    // Funcion para obtener el solicitante NNA
    public function obtenerSolicitanteNNA($runSolicitante)
    {
        // Busca el ciudadano
        $ciudadano = Personas::findByRun($runSolicitante);
        // Si no se encontró el ciudadano, se lanza una excepción
        if (is_null($ciudadano)) {
            throw new ExcepcionConsistencia(
                'El ciudadano no existe en la base de datos',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }

        // Busca el beneficiario en el grupo familiar vigente y aprobado
        $bnf_nna = BeneficiarioGrupoFamiliar::join('grupos_familiares', 'grupos_familiares.grupfami_id', '=', 'beneficiarios_gruposfamiliares.bnfgrupfami_grupofamiliar')
            ->where([
                ['bnfgrupfami_persona', $ciudadano->PER_ID],
                ['bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE],
                ['bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO]
            ])
            ->first();

        // Si no se encontró el beneficiario, se lanza una excepción
        if (is_null($bnf_nna)) {
            throw new ExcepcionConsistencia(
                'No se encontró ningún beneficiario en el grupo familiar vigente y aprobado',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }

        // Busca el grupo familiar del beneficiario
        $nuevoFolio = GrupoFamiliar::where('GRUPFAMI_ID', $bnf_nna->BNFGRUPFAMI_GRUPOFAMILIAR)
            ->first();

        // Si no se encontró el grupo familiar, se lanza una excepción
        if (is_null($nuevoFolio)) {
            throw new ExcepcionConsistencia(
                'No se encontró el grupo familiar del beneficiario',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }
        // Busca el representante del beneficiario
        $representante = NNARepresentanteMigrante::where('MIGRAN_NUEVOFOLIO', $nuevoFolio->GRUPFAMI_NUEVOFOLIO)
            ->first();

        // Si el representante existe, se asignan los datos del representante al ciudadano
        if ($representante) {
            $ciudadano->PER_NOMBRES = $representante->MIGRAN_NOMBRES;
            $ciudadano->PER_APELLIDOPATERNO = $representante->MIGRAN_APELLIDOPATERNO;
            $ciudadano->PER_APELLIDOMATERNO = $representante->MIGRAN_APELLIDOMATERNO;
            $ciudadano["DNI"] = $representante->MIGRAN_NROIDENTIFICADORNAC;
            $ciudadano->PER_CELULAR = $representante->MIGRAN_CELULAR;
            $ciudadano->PER_EMAIL = $representante->MIGRAN_EMAIL;
            $ciudadano->PER_TELEFONO = $representante->MIGRAN_TELEFONO;
        }
        // Si no se encontró el representante, se lanza una excepción
        else {
            throw new ExcepcionConsistencia(
                'El Representante no existe en la base de datos',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }

        $nacionalidad = MigranteNacionalidad::where('NAC_ID', $representante->MIGRAN_NACIONALIDAD)
            ->first();

        $ciudadano->PER_NOMBRE_NACIONALIDAD = $nacionalidad->PAIS;

        return $ciudadano;
    }

    protected function obtenerSolicitanteIngresoNNA($runSolicitante)
    {
        $ciudadano = Personas::findByRun($runSolicitante);
        if (is_null($ciudadano)) {
            throw new ExcepcionConsistencia(
                'El ciudadano no existe en la base de datos',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }

        $representante = NNARepresentanteMigrante::where('MIGRAN_IDSOLICITUD', $this->solicitud->ID_SOLICITUD)
            ->first();
        if ($representante) {
            $ciudadano->PER_NOMBRES = $representante->MIGRAN_NOMBRES;
            $ciudadano->PER_APELLIDOPATERNO = $representante->MIGRAN_APELLIDOPATERNO;
            $ciudadano->PER_APELLIDOMATERNO = $representante->MIGRAN_APELLIDOMATERNO;
            $ciudadano["DNI"] = $representante->MIGRAN_NROIDENTIFICADORNAC;
            $ciudadano->PER_CELULAR = $representante->MIGRAN_CELULAR;
            $ciudadano->PER_EMAIL = $representante->MIGRAN_EMAIL;
            $ciudadano->PER_TELEFONO = $representante->MIGRAN_TELEFONO;
            $ciudadano->PER_NACIONALIDAD_MIGRANTE = $representante->MIGRAN_NACIONALIDAD;
            $ciudadano->PER_NACIONALIDAD_MIGRANTE_MANUAL = $representante->MIGRAN_NACIONALIDAD_MANUAL;
        } else {
            throw new ExcepcionConsistencia(
                'El Representante no existe en la base de datos',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }

        $nacionalidad = MigranteNacionalidad::where('NAC_ID', $representante->MIGRAN_NACIONALIDAD)
            ->first();

        $ciudadano->PER_NOMBRE_NACIONALIDAD = $nacionalidad->PAIS;

        return $ciudadano;
    }
}
