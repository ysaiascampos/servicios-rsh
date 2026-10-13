<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Http\Inputs\DatosGlobalesInput;
use App\Models\ActualizacionMeses;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\Colegio;
use App\Models\Fuente;
use App\Models\GrupoFamiliar;
use App\Models\GrupoFamiliarExport;
use App\Models\Isapre;
use App\Models\Personas;
use App\Models\PersonasExport;
use App\Models\Solicitud;
use App\Models\SupervisionMensajesExport;
use App\Models\TramoQuintil;
use App\Models\ModalAviso;
use App\Models\ValidaSolicitudTercero;
use App\Models\NNARepresentanteMigrante;
use App\Http\ResponseMappers\RshEspecialAdultoMapper;
use App\Utiles;
use Illuminate\Support\Arr;
use stdClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DatosGlobales
{
    use DebugTrait;
    use PreguntasYRespuestasESOVTrait;

    public $ciudadano;
    public $datosContactoAdultoResponsable;
    public $grupoFamiliarAsignacion;
    public $integrantesAsignacion;
    public $ciudadanoExport;
    public $grupoFamiliarExport;
    public $integrantesExport;
    public $tramo;
    public $solicitudes;
    public $solicitudesIncorporarDesvincular;
    public $solicitudesTramitables;
    public $mensajesSupervision;
    public $modalAviso;
    public $solicitudesValidacionTercero;

    public $cargarDatosContacto;
    public $cargarGrupoFamiliarAsignacion;
    public $cargarGrupoFamiliarExport;
    public $cargarSolicitudes;
    public $cargarSolicitudesIncorporarDesvincular;
    public $cargarBeneficios;
    public $cargarSupervision;
    public $cargarModalAviso;
    public $cargarSolicitudesValidacionTercero;

    private $runCiudadano;
    private $runIncorporarDesvincular;
    private $origenRegistro;
    private $tramosQuintil;

    private $datosDeSolicitud;

    public function __construct(DatosGlobalesInput $input)
    {
        $this->datosDeSolicitud = new DatosDeSolicitud();
        $this->tramosQuintil = TramoQuintil::findAll();

        $this->origenRegistro = $input->{'origen-registro'};
        $this->runCiudadano = $input->{'run-ciudadano'};
        $this->runIncorporarDesvincular = $input->{'run-incorporar-desvincular'};

        $this->configurarDebug();
        $this->configurarAccesos();
        $this->main();
    }

    private function main()
    {
        self::debug("****Antes de Obtener datos asignacion: " . $this->runCiudadano . "****");

        $this->ciudadano = $this->obtenerCiudadanoAsignacion($this->runCiudadano);
         // Accede a los datos de contacto del adulto responsable del NNA Migrante
        $this->datosContactoAdultoResponsable = $this->obtenerDatosContactoAdultoResponsable($this->ciudadano->PER_ID);

        $this->grupoFamiliarAsignacion = $this->obtenerGrupoFamiliarAsignacion();
        $this->integrantesAsignacion = $this->obtenerIntegrantesGrupoFamiliarAsignacion();

        $this->ciudadanoExport = $this->obtenerCiudadanoExport($this->runCiudadano);
        $this->grupoFamiliarExport = $this->obtenerGrupoFamiliarExport();
        $this->integrantesExport = $this->obtenerIntegrantesGrupoFamiliarExport();

        $this->mensajesSupervision = $this->obtenerMensajesSupervision();

        $this->modalAviso = $this->obtenerModalAviso();

        $this->solicitudes = $this->obtenerSolicitudes();
        $this->solicitudesTramitables = $this->obtenerSolicitudesTramitables();
        $this->solicitudesIncorporarDesvincular = $this->obtenerSolicitudesIncorporarDesvincular($this->runIncorporarDesvincular);
        $this->solicitudesValidacionTercero = $this->obtenerSolicitudesValidacionTercero($this->runCiudadano);

        if ($this->integrantesExport) {
            //$this->integrantesExport->beneficios = $this->obtenerBeneficios();
            $this->integrantesExport->beneficios = null;
        }

        $this->obtenerPreguntasYRespuestasESOV($this->origenRegistro);
    }

    /**
     * Obtiene los datos de contacto del adulto responsable de un beneficiario.
     *
     * @param int $id_nna El ID del beneficiario.
     * @return mixed Los datos del contacto del adulto responsable o null si no se encuentra.
     */
    private function obtenerDatosContactoAdultoResponsable($id_nna)
    {
        // Busca el beneficiario en el grupo familiar vigente y aprobado
        $bnf_nna = BeneficiarioGrupoFamiliar::join('grupos_familiares', 'grupos_familiares.grupfami_id', '=', 'beneficiarios_gruposfamiliares.bnfgrupfami_grupofamiliar')
            ->where([
                ['bnfgrupfami_persona', $id_nna],
                ['bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE],
                ['bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO]
            ])
            ->first();

        if ($bnf_nna) {
            // Busca el representante migrante asociado al grupo familiar
            $migrante = NNARepresentanteMigrante::where([
                ['MIGRAN_GRUPOFAMILIAR', $bnf_nna->GRUPFAMI_ID],
                ['MIGRAN_NUEVOFOLIO', $bnf_nna->GRUPFAMI_NUEVOFOLIO],
                ['MIGRAN_VIGENTE', Constante::VIGENTE_1_VIGENTE],
                ['MIGRAN_CALCULOACTIVO', Constante::CALCULO_ACTIVO_1_APROBADO]
            ])->first();

            // Mapea los datos del representante migrante
            $mapp_representante = $migrante ? (new RshEspecialAdultoMapper)->map($migrante) : null;

            return $mapp_representante;
        }

        return null;
    }


    private function obtenerCiudadanoAsignacion($runCiudadano)
    {
        if (!$this->cargarDatosContacto) {
            return null;
        }
        return DatosDeContacto::obtenerDatosContacto($runCiudadano);
    }

    private function obtenerGrupoFamiliarAsignacion()
    {
        if (!$this->cargarGrupoFamiliarAsignacion) {
            return null;
        }

        $grupoFamiliar = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->ciudadano->PER_RUN, false);
        if ($grupoFamiliar) {

            $grupoFamiliar->estadoGrupo = $grupoFamiliar->obtenerEstadoGrupoFamiliar();
            $grupoFamiliar->consistenciaGrupo = $grupoFamiliar->obtenerConsistenciaGrupoFamiliar($this->ciudadano);
            $grupoFamiliar->confirmacion = $this->obtenerInfoConfirmacionGrupoFamiliar($grupoFamiliar);

            $grupoFamiliar->rangoIngresos = $grupoFamiliar->findTramosdeIngreso();
            $grupoFamiliar->ingresoLaboralGrupoFamiliar = 0;
            $grupoFamiliar->ingresoJubilacionGrupoFamiliar = 0;
            $grupoFamiliar->ingresoOtrosGrupoFamiliar = 0;
            $grupoFamiliar->ingresoTotalGrupoFamiliar = 0;

            $grupoFamiliar->cantidadIntegrantes = 0;
            $grupoFamiliar->cantidadConDiscapacidad = 0;
            $grupoFamiliar->cantidadAdultoMayor = 0;
            $grupoFamiliar->cantidadMenoresDeEdad = 0;

            //inicializacion banderas de solicitud, se asignan mas adelante
            $grupoFamiliar->solicitudSalud = false;
            $grupoFamiliar->solicitudCambioDomicilio = false;
        } else {
            $gf = GrupoFamiliar::findVigenteAprobadoByRunCiudadanoInconsistente($this->ciudadano->PER_RUN, false);
            if ($gf) {
                $grupoFamiliar = new GrupoFamiliar();
                $grupoFamiliar->GRUPFAMI_NUEVOFOLIO = $gf->GRUPFAMI_NUEVOFOLIO;
                $grupoFamiliar->estadoGrupo = $gf->obtenerEstadoGrupoFamiliar();
                $grupoFamiliar->consistenciaGrupo = $grupoFamiliar->obtenerConsistenciaGrupoFamiliar($this->ciudadano);
            }
        }
        return $grupoFamiliar;
    }

    private function obtenerIntegrantesGrupoFamiliarAsignacion()
    {
        if (!$this->cargarGrupoFamiliarAsignacion) {
            return null;
        }

        $integrantes = null;
        if (!is_null($this->grupoFamiliarAsignacion) && count(array_keys($this->grupoFamiliarAsignacion->toArray())) > 3) {

            $ingresoLaboralGrupoFamiliar = 0;
            $ingresoJubilacionGrupoFamiliar = 0;
            $ingresoOtrosGrupoFamiliar = 0;
            $ingresoTotalGrupoFamiliar = 0;

            $integrantes = BeneficiarioGrupoFamiliar::findBeneficiariosFichaPersonaByGrupoFamiliar($this->grupoFamiliarAsignacion);

            foreach ($integrantes as $integrante) {

                $integrante->situacion_unico_per_id = Personas::situacionUnicoPerID($integrante->PER_RUN);
                $integrante->situacion_unico_folio = Personas::situacionUnicoFolio($integrante->PER_RUN);
                $integrante->P_JURIDICA = Personas::personaJuridica($integrante->PER_RUN);
                $integrante->FUENTE_DEPENDENCIA = Personas::fuenteDependencia($integrante->PER_RUN);
                $puedeSerIncorporado = Utiles::getPuedeSerIncorporado($integrante);
                $integrante->puedeSerIncorporado = $puedeSerIncorporado['puedeserincorporado'];

                $ingresoTotal = $integrante->FICHA_INGRESOANUAL +
                    $integrante->FICHA_INGRESOJUBILACION +
                    $integrante->FICHA_INGRESOOTROS;

                $integrante->ingresoLaboral = Utiles::traeTramoIngresoAsignacion($integrante->FICHA_INGRESOANUAL);
                $integrante->ingresoJubilacion = Utiles::traeTramoIngresoAsignacion($integrante->FICHA_INGRESOJUBILACION);
                $integrante->ingresoOtros = Utiles::traeTramoIngresoAsignacion($integrante->FICHA_INGRESOOTROS);
                $integrante->ingresoTotal = Utiles::traeTramoIngresoAsignacion($ingresoTotal);

                $ingresoLaboralGrupoFamiliar += $integrante->FICHA_INGRESOANUAL;
                $ingresoJubilacionGrupoFamiliar += $integrante->FICHA_INGRESOJUBILACION;
                $ingresoOtrosGrupoFamiliar += $integrante->FICHA_INGRESOOTROS;
                $ingresoTotalGrupoFamiliar += $ingresoTotal;

                $integrante->solicitudesEnTramite = $this->datosDeSolicitud->getSolicitudDatosGlobales($integrante->PER_RUN);

                $this->grupoFamiliarAsignacion->cantidadIntegrantes++;
                if ($integrante->PER_EDAD >= Constante::EDAD_60_ADULTO_MAYOR) {
                    $this->grupoFamiliarAsignacion->cantidadAdultoMayor++;
                }
                if ($integrante->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD) {
                    $this->grupoFamiliarAsignacion->cantidadMenoresDeEdad++;
                }
                if ($integrante->solicitudesEnTramite['solicitud-de-salud'] == true) {
                    $this->grupoFamiliarAsignacion->solicitudSalud = $this->grupoFamiliarAsignacion->solicitudSalud || true;
                }
            }

            $this->grupoFamiliarAsignacion->ingresoLaboralGrupoFamiliar = Utiles::traeTramoIngresoAsignacion($ingresoLaboralGrupoFamiliar);
            $this->grupoFamiliarAsignacion->ingresoJubilacionGrupoFamiliar = Utiles::traeTramoIngresoAsignacion($ingresoJubilacionGrupoFamiliar);
            $this->grupoFamiliarAsignacion->ingresoOtrosGrupoFamiliar = Utiles::traeTramoIngresoAsignacion($ingresoOtrosGrupoFamiliar);
            $this->grupoFamiliarAsignacion->ingresoTotalGrupoFamiliar = Utiles::traeTramoIngresoAsignacion($ingresoTotalGrupoFamiliar);
        }
        return $integrantes;
    }

    private function obtenerCiudadanoExport($runCiudadano)
    {
        if (!$this->cargarGrupoFamiliarExport) {
            return null;
        }
        return PersonasExport::findByRunCiudadano($runCiudadano);
    }

    private function obtenerGrupoFamiliarExport()
    {
        if (!$this->cargarGrupoFamiliarExport) {
            return null;
        }

        $grupoFamiliar = null;
        if ($this->ciudadanoExport) {
            $grupoFamiliar = GrupoFamiliarExport::findByIdGrupoFamiliar($this->ciudadanoExport->PER_FIC_GRUPOFAMILIAR_ID);
            if ($grupoFamiliar) {

                $grupoFamiliar->rangoIngresos = null;
                $grupoFamiliar->ingresoLaboralGrupoFamiliar = Utiles::traeTramoIngreso(
                    $grupoFamiliar->CALC_TRAMO_LABORAL_MIN,
                    $grupoFamiliar->CALC_TRAMO_LABORAL_MAX
                );
                $grupoFamiliar->ingresoJubilacionGrupoFamiliar = Utiles::traeTramoIngreso(
                    $grupoFamiliar->CALC_TRAMO_PENSION_MIN,
                    $grupoFamiliar->CALC_TRAMO_PENSION_MAX
                );
                $grupoFamiliar->ingresoOtrosGrupoFamiliar = Utiles::traeTramoIngreso(
                    $grupoFamiliar->CALC_TRAMO_CAPITAL_MIN,
                    $grupoFamiliar->CALC_TRAMO_CAPITAL_MAX
                );
                $grupoFamiliar->ingresoTotalGrupoFamiliar = Utiles::traeTramoIngreso(
                    $grupoFamiliar->CALC_TRAMO_DEF_MIN,
                    $grupoFamiliar->CALC_TRAMO_DEFL_MAX
                );
                $grupoFamiliar->detallePensionAlimentoExport = self::detallePensionAlimentoExport($grupoFamiliar);

                $grupoFamiliar->datosComplementarios = self::obtenerDatosComplementarios($grupoFamiliar);

                $grupoFamiliar->tramo = null;
                if (!empty($this->tramosQuintil) && !empty($grupoFamiliar)) {
                    $msjTramo = Fuente::mensajeTramo();
                    $grupoFamiliar->tramo = new stdClass();
                    $grupoFamiliar->tramo->numero = $grupoFamiliar->CALC_QUINTIL_VALIDACION2;
                    $grupoFamiliar->tramo->nombre = $this->tramosQuintil[$grupoFamiliar->CALC_QUINTIL_VALIDACION2];
                    $grupoFamiliar->tramo->mensaje = $msjTramo;
                }

                $grupoFamiliar->cantidadIntegrantes = 0;
                $grupoFamiliar->cantidadConDiscapacidad = 0;
                $grupoFamiliar->cantidadAdultoMayor = 0;
                $grupoFamiliar->cantidadMenoresDeEdad = 0;
            }
        }
        return $grupoFamiliar;
    }

    private function obtenerIntegrantesGrupoFamiliarExport()
    {
        if (!$this->cargarGrupoFamiliarExport) {
            return null;
        }

        $integrantes = null;
        if (!empty($this->grupoFamiliarExport)) {

            $integrantes = PersonasExport::findByIdGrupoFamiliarExport($this->grupoFamiliarExport->GRUPFAMI_ID);

            foreach ($integrantes as $integrante) {

                $integrante->situacion_unico_per_id = PersonasExport::situacionUnicoPerID($integrante->PER_RUN);
                $integrante->situacion_unico_folio = PersonasExport::situacionUnicoFolio($integrante->PER_RUN);
                //esta logica corresponde a datos de asignación,
                //así que no tiene sentido colocar en export
//                $puedeSerIncorporado = Utiles::getPuedeSerIncorporado($integrante);
//                $integrante->puedeSerIncorporado = $puedeSerIncorporado['puedeserincorporado'];

                $integrante->ingresoLaboral = Utiles::traeTramoIngreso($integrante->CALC_TRAMO_LABORAL_MIN,
                    $integrante->CALC_TRAMO_LABORAL_MAX);
                $integrante->ingresoJubilacion = Utiles::traeTramoIngreso($integrante->CALC_TRAMO_PENSION_MIN,
                    $integrante->CALC_TRAMO_PENSION_MAX);
                $integrante->ingresoOtros = Utiles::traeTramoIngreso($integrante->CALC_TRAMO_CAPITAL_MIN,
                    $integrante->CALC_TRAMO_CAPITAL_MAX);
                $integrante->ingresoTotal = Utiles::traeTramoIngreso($integrante->CALC_TRAMO_DEF_MIN,
                    $integrante->CALC_TRAMO_DEFL_MAX);

                $this->grupoFamiliarExport->cantidadIntegrantes++;

                if ($integrante->PER_EDAD >= Constante::EDAD_60_ADULTO_MAYOR) {
                    $this->grupoFamiliarExport->cantidadAdultoMayor++;
                }
                if ($integrante->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD) {
                    $this->grupoFamiliarExport->cantidadMenoresDeEdad++;
                }
                if ($integrante->CONDICION_DEPENDENCIA == 1 || !is_null($integrante->RND_CSE)) {
                    $this->grupoFamiliarExport->cantidadConDiscapacidad++;
                }

                $integrante->detalleIngreso = PersonasExport::detalleIngresoExport($integrante);
                $integrante->datosComplementarios = self::obtenerDatosComplementariosIntegrantes($integrante);
                $integrante->detalleDatosComplementarios = self::obtenerDetalleDatosComplementariosIntegrantes($integrante);

                //ToDo: revisar si corresponde solicitudes en tramite para export
                $solicitudesEnTramite = null;
                if (is_null($this->integrantesAsignacion)) {
                    $this->integrantesAsignacion = [];
                }
                foreach ($this->integrantesAsignacion as $integranteAsignacion) {
                    if ($integranteAsignacion->PER_RUN === $integrante->PER_RUN) {
                        $solicitudesEnTramite = $integranteAsignacion->solicitudesEnTramite;
                        $integrante->FICHA_CONDICIONDISCAPACIDAD = $integranteAsignacion->FICHA_CONDICIONDISCAPACIDAD;
                        break;
                    }
                }
                $integrante->solicitudesEnTramite = $solicitudesEnTramite ?? $this->datosDeSolicitud->getSolicitudDatosGlobales($integrante->PER_RUN);

                $integrante->mediosActivos = new stdClass();
                $integrante->mediosActivos->colegioAltoCosto = (int)$integrante->CALC_COLEGIO_COSTOSO == 1;
                $integrante->mediosActivos->cargaIsapreAltoCosto = (int)$integrante->CALC_ISAPRECARGA_COSTOSO == 1;
                $integrante->mediosActivos->vehiculoAltoCostoTerrestre = (int)$integrante->CALC_VEHICULOS_COSTOSOS == 1;
                $integrante->mediosActivos->vehiculoAltoCostoMaritimo = (int)$integrante->DNAVES_IND_MUY_ALTO_VALOR == 1;
                $integrante->mediosActivos->bienRaizAltoCosto = (int)$integrante->CALC_PROPIEDADES_COSTOSAS == 1;
            }
        }
        return $integrantes;
    }

    private function obtenerSolicitudes()
    {
        if (!$this->cargarSolicitudes) {
            return null;
        }

        $solicitudes = null;
        if (Utiles::inArrayMinuscula($this->origenRegistro,
            Constante::$ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA_VALIDOS)) {
            $solicitudes = $this->datosDeSolicitud->getSolicitudesTramitablesPorRunExcluyendoOrigenRegistroServicio(
                $this->runCiudadano,
                Constante::ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA
            );
        } else {
            $runsPorIdGrupoFamiliar = [];
            if ($this->integrantesAsignacion) {
                $runsPorIdGrupoFamiliar = array_column($this->integrantesAsignacion->toArray(), 'PER_RUN');
            }
            $solicitudes = $this->datosDeSolicitud->getSolicitudDatosGlobales2($this->ciudadano,
                $runsPorIdGrupoFamiliar);
        }

        foreach ($solicitudes as $checkSolicitud) {
            if (in_array($checkSolicitud['id-tipo-solicitud'], [
                    Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
                    Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA
                ]) &&
                in_array($checkSolicitud['id-estado'], [
                    Constante::ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS,
                    Constante::ESTADO_SOLICITUD_2_AGENDADO,
                    Constante::ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL,
                    Constante::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL,
                    Constante::ESTADO_SOLICITUD_6_EN_ESPERA_CIUDADANO,
                    Constante::ESTADO_SOLICITUD_8_POR_APROBAR_SIT,
                    Constante::ESTADO_SOLICITUD_10_EN_NIVEL_CENTRAL,
                    Constante::ESTADO_SOLICITUD_12_POR_APROBAR_UGIT,
                    Constante::ESTADO_SOLICITUD_15_EN_RUTA,
                    Constante::ESTADO_SOLICITUD_16_SUPERVISION
                ])
            ) {
                $this->grupoFamiliarAsignacion->solicitudCambioDomicilio = true;
            }
        }
        return $solicitudes;
    }

    private function obtenerSolicitudesTramitables()
    {
        if (!$this->cargarSolicitudes) {
            return null;
        }

        self::debug("Antes linea getTodasSolicitudes: " . $this->runCiudadano);

        $parametrosSolicitudesTramitables = [
            'persona' => $this->ciudadano,
            'personaExport' => $this->ciudadanoExport,
            'gAsignacion' => $this->grupoFamiliarAsignacion,
            'gExport' => $this->grupoFamiliarExport
        ];
        $solicitudesTramitables = DatosDeSolicitud::getTodasSolicitudes(
            $this->runCiudadano,
            $this->ciudadano->PER_EDAD,
            $this->origenRegistro,
            $parametrosSolicitudesTramitables
        );
        if(isset($this->solicitudes) && $this->ciudadano->TIENE_RSH == true){
            $grupoFamiliarSolicitud = GrupoFamiliar::findVigenteAprobadoByRunCiudadano($this->runCiudadano);
            if(isset($grupoFamiliarSolicitud->GRUPFAMI_IDSOLICITUD)) {
                $solicitud = Solicitud::where('ID_SOLICITUD', $grupoFamiliarSolicitud->GRUPFAMI_IDSOLICITUD)
                    ->whereIn('ID_TIPO_SOLICITUD', [
                        Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
                        Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA])
                    ->where('ID_ESTADO', Constante::ESTADO_SOLICITUD_5_APROBADO)
                    ->where('FECHA_MODIFICACION', '>=', DB::RAW('ADD_MONTHS(sysdate,-6)'))
                    ->first();

                if (isset($solicitud->ID_TIPO_SOLICITUD) && $solicitud->ID_TIPO_SOLICITUD ==
                    Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA) {
                    if (!isset($grupoFamiliarSolicitud) || !isset($this->grupoFamiliarExport) || (
                            $grupoFamiliarSolicitud->GRUPFAMI_COMUNAINE !== $this->grupoFamiliarExport->GRUPFAMI_COMUNAINE
                            || $grupoFamiliarSolicitud->GRUPFAMI_C_CALLE !== $this->grupoFamiliarExport->GRUPFAMI_C_CALLE)) {

                        $solicitudNoTramitable = ['puede-realizar-solicitud' => false,
                            'mensaje' => 'Solicitud de Cambio de Domicilio y Vivienda en espera de tramo',
                            'mensaje-ciudadano' => 'Solicitud de Cambio de Domicilio y Vivienda en espera de tramo'];
                        foreach ($solicitudesTramitables as $key => $solicitudesTramitable) {
                            if (!in_array($key, array_merge(Constante::$solicitudesActualizacionARC, Constante::$solicitudesComplementoARC, Constante::$solicitudesRectificacionARC))) {
                                $solicitudesTramitables[($key)] = $solicitudNoTramitable;
                            }
                        }
                    }
                }
            }
        }
        if (is_numeric($solicitudesTramitables)) {
            if ($solicitudesTramitables == Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR) {
                $mensaje = Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR;
            } elseif ($solicitudesTramitables == Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL) {
                $mensaje = "El RUN ingresado no se encuentra en el registro civil.";
            } else {
                $mensaje = "El RUN ingresado no se encuentra en nuestros registros.";
            }
            self::debug($mensaje . ": " . $this->runCiudadano);
        } else {
            $solicitudesTramitables = $this->bloqueosSupervisionMensajes($solicitudesTramitables);
        }
        self::debug("Fin getTodasSolicitudes: " . $this->runCiudadano);
        if($this->ciudadano->institucionalizado->ES_INSTITUCIONALIZADO == true && $this->ciudadano->PER_EDAD >= Constante::EDAD_18_MAYOR_DE_EDAD){
            foreach ($solicitudesTramitables as $key => $solicitudesTramitable) {
                if($key != Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO){
                    $solicitudesTramitables[$key] = ['puede-realizar-solicitud' => false,
                        'mensaje' => 'Usuario cuenta con registro como persona institucionalizada, debe realizar un nuevo ingreso al registro',
                        'mensaje-ciudadano' => 'Usuario cuenta con registro como persona institucionalizada, debe realizar un nuevo ingreso al registro'];
                }
            }
        }
        return $solicitudesTramitables;
    }

    private function obtenerSolicitudesIncorporarDesvincular($runCiudadano)
    {
        if (!$this->cargarSolicitudesIncorporarDesvincular) {
            return null;
        }
        return DatosDeSolicitud::getSolicitudesExcluyentesLogicaNegocioPorRunAsociadoMensajes($runCiudadano);
    }

    private function obtenerBeneficios()
    {
        if (!$this->cargarBeneficios) {
            return null;
        }

        return DatosDeBeneficios::obtenerBeneficiosIntegrantesExport(
            $this->integrantesExport,
            $this->origenRegistro
        );
    }

    private function obtenerInfoConfirmacionGrupoFamiliar($grupoFamiliar)
    {
        $validacion = $grupoFamiliar->getFechaValidacion($this->ciudadano);
        if ($validacion->runValidacion != $this->ciudadano->PER_RUN) {
            $temp = Personas::findByRun($validacion->runValidacion);
            $nombre = $temp->PER_NOMBRES . " " . $temp->PER_APELLIDOPATERNO . " " . $temp->PER_APELLIDOMATERNO;
        } else {
            $nombre = $this->ciudadano->PER_NOMBRES . " " . $this->ciudadano->PER_APELLIDOPATERNO . " " . $this->ciudadano->PER_APELLIDOMATERNO;
        }


        $data = new stdClass();
        $data->fechaActualizacion = $validacion->fechaValidacion;
        $data->run = $validacion->runValidacion;
        $data->nombre = $nombre;
        $data->fechaEncuesta = $validacion->fechaEncuesta;
        return $data;
    }

    private function obtenerDatosComplementarios($grupoFamiliar)
    {
        $datosComplementarios = [];
        if (is_null($grupoFamiliar)) {
            return [];
        }
        $medio_padre_no_presente = false;
        $medio_padre = null;
        if (isset($grupoFamiliar->MEDIO_PADRE_NO_PRESENT)) {
            $medio_padre = $grupoFamiliar->MEDIO_PADRE_NO_PRESENT;
            if ($grupoFamiliar->MEDIO_PADRE_NO_PRESENT != 1) {
                $medio_padre_no_presente = true;
            }
        } elseif (isset($grupoFamiliar->MEDIO_PADRE_NO_PRESEN_AMB)) {
            $medio_padre = $grupoFamiliar->MEDIO_PADRE_NO_PRESEN_AMB;
            if ($grupoFamiliar->MEDIO_PADRE_NO_PRESEN_AMB != 1) {
                $medio_padre_no_presente = true;
            }
        }

        if (($grupoFamiliar->DNAVES_MUYALTO != 1 &&
                $grupoFamiliar->CALC_VEHICULOS_COSTOSOS != 1 &&
                $grupoFamiliar->CALC_ISAPRECARGA_COSTOSO != 1 &&
                $grupoFamiliar->CALC_PROPIEDADES_COSTOSAS != 1 &&
                $grupoFamiliar->CALC_COLEGIO_COSTOSO != 1
            ) && ($medio_padre_no_presente == true)
        ) {
            $datosComplementarios[] = (object)[
                'item' => 'No hay datos complementarios que influyan en la calificación del hogar.',
                'info' => null
            ];
        } else {
            if ($grupoFamiliar->CALC_VEHICULOS_COSTOSOS == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor de la suma de los avalúos fiscales de los vehículos terrestre del hogar.',
                    'info' => 'La Información de tenencia y propiedad del o los vehículos es obtenida del Registro Civil, y la información de avalúo fiscal del vehículo terrestre es obtenida del Servicio de Impuestos Internos(SII).'
                ];
            }
            if ($grupoFamiliar->DNAVES_MUYALTO == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor de la suma de los avalúos fiscales de los vehículos marítimo  del hogar.',
                    'info' => 'La Información de tenencia y propiedad del o los vehículos es obtenida del Registro Civil, y la información de avalúo fiscal del vehículo marítimo es obtenida del Servicio de Impuestos Internos(SII).'
                ];
            }

            if ($grupoFamiliar->CALC_ISAPRECARGA_COSTOSO == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor del Plan de Salud de uno o más integrantes del hogar .',
                    'info' => 'Información obtenida de la base de datos respectiva proporcionada por la Superintendencia de Salud.'
                ];
            }

            if ($grupoFamiliar->CALC_PROPIEDADES_COSTOSAS == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor de la suma de los avalúos fiscales de las propiedades(bienes raíces) a nombre de los integrantes del hogar.',
                    'info' => 'Información extraída de la base de datos de propiedades del Servicio de Impuestos Internos(SII).'
                ];
            }

            if ($grupoFamiliar->CALC_COLEGIO_COSTOSO == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Presencia en el hogar de alumnos matriculados en colegios con mensualidad de alto costo.',
                    'info' => 'Información obtenida desde la base de datos de matrículas, proporcionada por el Ministerio de Educación.'
                ];
            }

            if ($medio_padre == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Ingresos del padre o madre no presente en el hogar.',
                    'info' => 'Información obtenida de la base de datos del Servicio de Registro Civil e Identificación (SRCeI) e ingresos totales obtenidos de alguna(s) de las siguientes fuentes de ingresos: Administradora de Fondos de Cesantía (AFC), Superintendencia de Pensiones (SP), Superintendencia de Salud (SS), Servicio de Impuestos Internos (SII) y/o Registro Social de Hogares (RSH).'
                ];
            }
        }
        return $datosComplementarios;
    }

    private function obtenerMensajesSupervision()
    {
        if (!$this->cargarSupervision) {
            return null;
        }

        if (empty($this->integrantesExport)) {
            return null;
        }

        $supervisionMensajes = SupervisionMensajesExport::getMensajes(
            //Arr::pluck($this->integrantesExport, 'PER_RUN'),
            $this->runCiudadano,
            $this->grupoFamiliarExport,
            $this->integrantesExport
        );

        if((!empty($supervisionMensajes)) && !empty($this->integrantesAsignacion)){
            $runesAsignacion = [];
            foreach ($this->integrantesAsignacion as $item) {
                $runesAsignacion[] = $item->PER_RUN;
            }
            foreach ($supervisionMensajes as $key => $supervisionMensaje) {
                if($supervisionMensaje->SV_RUN != 0
                    && !in_array($supervisionMensaje->SV_RUN,$runesAsignacion)){
                    unset($supervisionMensajes[$key]);
                }
            }

        }
        return $supervisionMensajes;
    }


    private function configurarAccesos()
    {
        $this->cargarDatosContacto = Utiles::inArrayMinuscula($this->origenRegistro,
            Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS,
            Constante::$ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS
        );

        $this->cargarGrupoFamiliarAsignacion = Utiles::inArrayMinuscula($this->origenRegistro,
            Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS,
            Constante::$ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS
        );

        $this->cargarGrupoFamiliarExport = Utiles::inArrayMinuscula($this->origenRegistro,
            Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS,
            Constante::$ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS
        );

        $this->cargarSolicitudes = Utiles::inArrayMinuscula($this->origenRegistro,
            Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS,
            Constante::$ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS,
            Constante::$ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA_VALIDOS
        );

        $this->cargarSolicitudesIncorporarDesvincular = Utiles::inArrayMinuscula($this->origenRegistro,
            Constante::$ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA_VALIDOS
        );

        $this->cargarBeneficios = Utiles::inArrayMinuscula($this->origenRegistro,
            Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS,
            Constante::$ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS
        );

        $this->cargarSupervision = Utiles::inArrayMinuscula($this->origenRegistro,
            Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS,
            Constante::$ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS
        );

        $this->cargarSolicitudesValidacionTercero = Utiles::inArrayMinuscula($this->origenRegistro,
            Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS,
            Constante::$ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS
        );
        $this->cargarModalAviso = Utiles::inArrayMinuscula($this->origenRegistro,
            Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS,
            Constante::$ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS
        );
    }

    private function configurarDebug()
    {
        $activaLogDG = env('ACTIVAR_DEPURACION_DATOS_GLOBALES', false);
        $runesDebug = explode(',', env('RUNES_DEBUG'));

        if (in_array($this->runCiudadano, $runesDebug) && $activaLogDG) {
            $this->logDebug = true;
            $this->debug("****************************************************************");
            $this->debug("Depuración para: " . $this->runCiudadano);
        }
    }

    public static function detallePensionAlimentoExport($grupoFamiliar)
    {
        $detalleIngreso = [
            'fuente-nombre' => null,
            'fuente-descripcion' => null,
            'fuente-codigo' => null
        ];
        try {
            if (is_object($grupoFamiliar)) {
                try {
                    $listadoFuentes = DatosDeFuentes::getListaFuentes();
                } catch (Exception $exception) {
                    log::error('Problema al obtener las fuentes para los tramites ARC de ingreso');
                    $listadoFuentes = null;
                }

                $fuentePensionAlimentoNombre = !(is_null($grupoFamiliar->PENSION_ALIMENTOS)) ?
                    $listadoFuentes['tipos-fuentes'][$grupoFamiliar->PENSION_ALIMENTOS]['descripcion'] : null;
                $fuentePensionAlimentoDescripcion = !(is_null($fuentePensionAlimentoNombre)) ?
                    $listadoFuentes['tipos-fuentes'][$grupoFamiliar->PENSION_ALIMENTOS]['descripcion-larga'] : null;
                $fuentePensionAlimentoCodigo = !(is_null($fuentePensionAlimentoNombre)) ?
                    $grupoFamiliar->PENSION_ALIMENTOS : null;


                $detalleIngreso = [
                    'fuente-nombre' => $fuentePensionAlimentoNombre,
                    'fuente-descripcion' => $fuentePensionAlimentoDescripcion,
                    'fuente-codigo' => $fuentePensionAlimentoCodigo
                ];
            }
        } catch (Exception $e) {
            Log::error("Problema al armar el detalle de ingreso pension alimento: " . $e);
        }
        return ($detalleIngreso);
    }

    private function obtenerDatosComplementariosIntegrantes($integrante)
    {
        $datosComplementarios = [];
        if (is_null($integrante)) {
            return [];
        }
        $medio_padre_no_presente = false;
        $medio_padre = null;
        if (isset($integrante->MEDIO_PADRE_NO_PRESENT)) {
            $medio_padre = $integrante->MEDIO_PADRE_NO_PRESENT;
            if ($integrante->MEDIO_PADRE_NO_PRESENT != 1) {
                $medio_padre_no_presente = true;
            }
        } elseif (isset($integrante->MEDIO_PADRE_NO_PRESEN_AMB)) {
            $medio_padre = $integrante->MEDIO_PADRE_NO_PRESEN_AMB;
            if (isset($integrante->MEDIO_PADRE_NO_PRESEN_AMB)) {
                $medio_padre_no_presente = true;
            }
        }

        if (($integrante->DNAVES_IND_MUY_ALTO_VALOR != 1 &&
                $integrante->CALC_VEHICULOS_COSTOSOS != 1 &&
                $integrante->CALC_ISAPRECARGA_COSTOSO != 1 &&
                $integrante->CALC_PROPIEDADES_COSTOSAS != 1 &&
                $integrante->CALC_COLEGIO_COSTOSO != 1
            ) && ($medio_padre_no_presente == true)
        ) {
            $datosComplementarios[] = (object)[
                'item' => 'No hay datos complementarios que influyan en la calificación del hogar.',
                'info' => null
            ];
        } else {
            if ($integrante->DNAVES_IND_MUY_ALTO_VALOR == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Tenencia de embarcaciones marítimas de muy alto valor en el hogar.',
                    'info' => 'La información de tenencia y propiedad de la o las embarcaciones marítimas es obtenida de
                    los registros de la Dirección General de Territorio Marítimo y de Marina Mercante (DIRECTEMAR).'
                ];
            }
            if ($integrante->CALC_VEHICULOS_COSTOSOS == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor de la suma de los avalúos fiscales de los vehículos terrestre del hogar.',
                    'info' => 'La Información de tenencia y propiedad del o los vehículos es obtenida del Registro Civil,
                    y la información de avalúo fiscal del vehículo terrestre es obtenida del Servicio de Impuestos Internos(SII).'
                ];
            }

            if ($integrante->CALC_ISAPRECARGA_COSTOSO == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor del Plan de Salud de uno o más integrantes del hogar .',
                    'info' => 'Información obtenida de la base de datos respectiva proporcionada por la Superintendencia de Salud.'
                ];
            }

            if ($integrante->CALC_PROPIEDADES_COSTOSAS == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor de la suma de los avalúos fiscales de las propiedades(bienes raíces) a nombre de los integrantes del hogar.',
                    'info' => 'Información extraída de la base de datos de propiedades del Servicio de Impuestos Internos(SII).'
                ];
            }

            if ($integrante->CALC_COLEGIO_COSTOSO == 1) {
                $colegiosIntegrante = Colegio::where('run_alu', $integrante->PER_RUN)
//                ->where('vigente', 1)
//                ->where('calculoactivo', 1)
//                ->orderBy('agno', 'desc')
                    ->get();
                $colegios = [];
                $colegios['educacion-alto-costo'] = false;
                foreach ($colegiosIntegrante as $colegioIntegrante) {
                    $colegio = [];
                    $colegio['anio-fuente'] = $colegioIntegrante->AGNO;
                    $colegio['rbd'] = $colegioIntegrante->RBD;
                    $colegio['colegio-actual'] = $colegioIntegrante->NOMBRE_ESTA;
                    if ($colegioIntegrante->VIGENTE == Constante::VIGENTE_1_VIGENTE &&
                        $colegioIntegrante->CALCULOACTIVO == Constante::CALCULO_ACTIVO_1_APROBADO
                    ) {
                        $colegio['educacion-alto-costo'] = $integrante->CALC_COLEGIO_COSTOSO == 1;
                        $colegios = $colegio;
                    } else {
                        $colegios['historico'][] = $colegio;
                    }

                }
                $datosComplementarios[] = (object)[
                    'item' => 'Presencia en el hogar de alumnos matriculados en colegios con mensualidad de alto costo.',
                    'info' => 'Información obtenida desde la base de datos de matrículas, proporcionada por el Ministerio de Educación.'
                ];
            }

            if ($medio_padre == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Ingresos del padre o madre no presente en el hogar.',
                    'info' => 'Información obtenida de la base de datos del Servicio de Registro Civil e Identificación (SRCeI) e ingresos totales obtenidos de alguna(s) de las siguientes fuentes de ingresos: Administradora de Fondos de Cesantía (AFC), Superintendencia de Pensiones (SP), Superintendencia de Salud (SS), Servicio de Impuestos Internos (SII) y/o Registro Social de Hogares (RSH).'
                ];
            }
        }
        return $datosComplementarios;
    }
    private function obtenerDetalleDatosComplementariosIntegrantes($integrante)
    {
        $datosComplementarios = (object)[
                "colegioAltoCosto"=> null,
                "cargaIsapreAltoCosto"=> null,
                "vehiculoAltoCostoTerrestre"=> null,
                "vehiculoAltoCostoMaritimo"=> null,
                "bienRaizAltoCosto"=> null
            ];
        if (is_null($integrante)) {
            return $datosComplementarios;
        }

        if (!($integrante->DNAVES_IND_MUY_ALTO_VALOR != 1 &&
            $integrante->CALC_VEHICULOS_COSTOSOS != 1 &&
                $integrante->CALC_ISAPRECARGA_COSTOSO != 1 &&
                $integrante->CALC_PROPIEDADES_COSTOSAS != 1 &&
                $integrante->CALC_COLEGIO_COSTOSO != 1
            )
        ) {
            if ($integrante->DNAVES_IND_MUY_ALTO_VALOR == 1) {
                //$datosComplementarios->vehiculoAltoCosto = (object)[
                //    'patente' => null,
                //];
            }

            if ($integrante->CALC_VEHICULOS_COSTOSOS == 1) {
                //$datosComplementarios->vehiculoAltoCosto = (object)[
                //    'patente' => null,
                //];
            }

            if ($integrante->CALC_ISAPRECARGA_COSTOSO == 1) {
                $periodoRSHActual = ActualizacionMeses::max("periodo_rsh");
                $query1 = DB::table('ever_super_salud_cotizantes')
                    ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                    ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                    ->where('run', $integrante->PER_RUN)
                    ->where('periodo_rsh', $periodoRSHActual)
                    ->select(DB::raw('1 as marca'));
                $query2 = DB::table('tbl_super_salud_cargas')
                    ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                    ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                    ->where('run_carga', $integrante->PER_RUN)
                    ->where('periodo_rsh', $periodoRSHActual)
                    ->select(DB::raw('2 as marca'));
                $cargaOCotizante = $query1->union($query2)->first();

                if (isset($cargaOCotizante->MARCA)) {
                    switch ($cargaOCotizante->MARCA) {
                        case 1:
                            $dataNombreIsapre = Isapre::select('isapre_nom', 'periodo_rsh')
                                ->where('run', $integrante->PER_RUN)
                                ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                                ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                                ->orderBy('periodo_rsh', 'desc')
                                ->first();
                            break;
                        case 2:
                            $dataCiudadanoCargasEloq = DB::table('tbl_super_salud_cargas tssc')
                                ->where('run_carga', $integrante->PER_RUN)
                                ->orderBy('periodo_rsh', 'desc')
                                ->first();
                            $ultimoPeriodoCargadoDelCiudadano = isset($periodoRSHActual) ? $periodoRSHActual : null;
                            $dataNombreIsapre = Isapre::select('isapre_nom', 'periodo_rsh')
                                ->where('run', $dataCiudadanoCargasEloq->RUN_COTIZANTE)
                                ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                                ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                                ->where('periodo_rsh', $ultimoPeriodoCargadoDelCiudadano)
                                ->orderBy('periodo_rsh', 'desc')
                                ->first();
                            break;
                    }

                    if(isset($dataNombreIsapre)){
                        $nombre = $dataNombreIsapre->ISAPRE_NOM;
                        $datosComplementarios->cargaIsapreAltoCosto = (object)[
                            'nombre-isapre' => $nombre
                        ];
                    }
                }
            }

            if ($integrante->CALC_PROPIEDADES_COSTOSAS == 1) {
                //$datosComplementarios->bienRaizAltoCosto = (object)[
                //    'info' => null
                //];
            }

            if ($integrante->CALC_COLEGIO_COSTOSO == 1) {
                $colegiosIntegrante = Colegio::where('run_alu', $integrante->PER_RUN)
                    ->get();
                $colegios = [];
                $colegios['educacion-alto-costo'] = false;
                $colegio['colegio-actual'] = null;
                foreach ($colegiosIntegrante as $colegioIntegrante) {
                    $colegio = [];
                    $colegio['anio-fuente'] = $colegioIntegrante->AGNO;
                    $colegio['rbd'] = $colegioIntegrante->RBD;
                    $colegio['colegio-actual'] = $colegioIntegrante->NOMBRE_ESTA;
                    if ($colegioIntegrante->VIGENTE == Constante::VIGENTE_1_VIGENTE &&
                        $colegioIntegrante->CALCULOACTIVO == Constante::CALCULO_ACTIVO_1_APROBADO
                    ) {
                        $colegio['educacion-alto-costo'] = $integrante->CALC_COLEGIO_COSTOSO == 1;
                        $colegios = $colegio;
                    } else {
                        $colegios['historico'][] = $colegio;
                    }

                }
                $datosComplementarios->colegioAltoCosto = (object)[
                    'nombre-colegio' => $colegio['colegio-actual'],
                ];
            }
        }
        return $datosComplementarios;
    }

    private function bloqueosSupervisionMensajes($solicitudesTramitables)
    {
        if(env('MENSAJE_BLOQUEO')
            && isset($this->mensajesSupervision)
            && $this->mensajesSupervision->whereIn('SV_GRAVEDAD',[2,3])->count()>0){
            $mensajesSupervisionID = Array();
            foreach ($this->mensajesSupervision as $item) {
                if($item->SV_MENOR == Constante::VIGENTE_1_VIGENTE){
                    if($item->SV_RUN_MENOR == $this->ciudadano->PER_RUN){
                        $mensajesSupervisionID[] = $item->SV_ID;
                    }
                } else {
                    $mensajesSupervisionID[] = $item->SV_ID;
                }
            }
            $mensajesSupervision = $this->mensajesSupervision->whereIn('SV_ID',$mensajesSupervisionID);
            if(isset($mensajesSupervision)
            && $mensajesSupervision->whereIn('SV_GRAVEDAD',[2,3])->count()>0) {
                $bloqueos = array();
                foreach ($solicitudesTramitables as $key => $solicitudTramitable) {
                    if ($key != Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO
                        && $solicitudTramitable['puede-realizar-solicitud'] == true) {
                        $base = [
                            'puede-realizar-solicitud' => false,
                            'mensaje' => '',
                            'mensaje-ciudadano' => ''
                        ];
                        $solicitudesTramitables[$key] = $base;
                    } else {
                        $bloqueos[] = $key;
                    }
                }
                ksort($solicitudesTramitables);
                $desbloqueos = array();
                foreach ($mensajesSupervision->where('SV_GRAVEDAD', 3) as $mensaje) {
                    $solicitudUno = (int)$mensaje->SV_TIPO_SOL_RELEVANTE_1;
                    $solicitudDos = (int)$mensaje->SV_TIPO_SOL_RELEVANTE_2;
                    $solicitudTres = (int)$mensaje->SV_TIPO_SOL_RELEVANTE_3;
                    if ($solicitudUno > 0
                        && !in_array($solicitudUno, $desbloqueos)) {
                        $desbloqueos[] = $solicitudUno;
                    } elseif ($solicitudDos > 0
                        && !in_array($solicitudDos, $desbloqueos)) {
                        $desbloqueos[] = $solicitudDos;
                    } elseif ($solicitudTres > 0
                        && !in_array($solicitudTres, $desbloqueos)) {
                        $desbloqueos[] = $solicitudTres;
                    }
                }
                if (count($desbloqueos) > 0) {
                    foreach ($solicitudesTramitables as $key => $solicitudTramitable) {
                        if (in_array($key, $desbloqueos)
                            && !in_array($key, $bloqueos)) {
                            $base = [
                                'puede-realizar-solicitud' => true,
                                'mensaje' => '',
                                'mensaje-ciudadano' => ''
                            ];
                            unset($solicitudesTramitables[$key]);
                            $solicitudesTramitables[$key] = $base;
                        }
                    }
                } else {
                    foreach ($mensajesSupervision->where('SV_GRAVEDAD', 2) as $mensaje) {
                        $solicitudUno = (int)$mensaje->SV_TIPO_SOL_RELEVANTE_1;
                        $solicitudDos = (int)$mensaje->SV_TIPO_SOL_RELEVANTE_2;
                        $solicitudTres = (int)$mensaje->SV_TIPO_SOL_RELEVANTE_3;
                        if ($solicitudUno > 0
                            && !in_array($solicitudUno, $desbloqueos)) {
                            $desbloqueos[] = $solicitudUno;
                        }
                        if ($solicitudDos > 0
                            && !in_array($solicitudDos, $desbloqueos)) {
                            $desbloqueos[] = $solicitudDos;
                        }
                        if ($solicitudTres > 0
                            && !in_array($solicitudTres, $desbloqueos)) {
                            $desbloqueos[] = $solicitudTres;
                        }
                    }
                    if (count($desbloqueos) > 0) {
                        foreach ($solicitudesTramitables as $key => $solicitudTramitable) {
                            if (in_array($key, $desbloqueos)
                                && !in_array($key, $bloqueos)) {
                                $base = [
                                    'puede-realizar-solicitud' => true,
                                    'mensaje' => '',
                                    'mensaje-ciudadano' => ''
                                ];
                                unset($solicitudesTramitables[$key]);
                                $solicitudesTramitables[$key] = $base;
                            }
                        }
                    }

                }
            }

        }
        return $solicitudesTramitables;
    }

    private function obtenerModalAviso()
    {
        if (!$this->cargarModalAviso) {
            return null;
        }

        if (env('ACTIVAR_MODAL_AVISO')){
            $filePath =storage_path("../app/Clases/DatosGlobales.php");
            if(!file_exists($filePath)){
                $cacheKey = 'modal-aviso';
            }else{
                $version = filemtime($filePath);
                $cacheKey = 'modal-aviso-'.$version;
            }
            $mensajesModalAviso = array();
            if (Cache::has($cacheKey)) {
                $mensajesModalAviso = Cache::get($cacheKey);
                if(count($mensajesModalAviso) == 0){
                    Cache::forget($cacheKey);
                }
            }
            if(!Cache::has($cacheKey)){
                $mensajesModalAviso = ModalAviso::findAllModalAviso();
                Cache::put($cacheKey, $mensajesModalAviso, now()->addMinutes(Constante::CACHE_API_MINUTOS));
            }
            return $mensajesModalAviso;
        }
        return null;

    }

    private function obtenerSolicitudesValidacionTercero($runCiudadano)
    {
        if (!$this->cargarSolicitudesValidacionTercero) {
            return null;
        }

        try {
            $data = ValidaSolicitudTercero::listSolicitudes($runCiudadano);
        } catch (\Exception $ex) {
            throw $ex;
        }
        return $data;
    }
}
