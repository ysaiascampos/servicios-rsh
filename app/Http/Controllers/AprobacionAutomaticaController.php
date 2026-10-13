<?php


namespace App\Http\Controllers;

use App\Constantes\Constante;
use App\Models\AprobadasAutomaticamente;
use App\Models\NominaAprobarSolicitud;
use App\Models\Personas;
use App\Models\Solicitud;
use App\Sequence;
use App\Utiles;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AprobacionAutomaticaController extends Controller
{
    public $campos;
    public $grupoSolicitud;
    public $grupoActual;
    public $grupoNuevoFolio;
    public $estadoSolicitud;
    public $tipoSolicitud;
    public $countSolicitudesTotal;
    public $countSolicitudesYaProcesadas;
    public $countSolicitudesAprobadas;
    public $countSolicitudesNoAprobadas;
    public function __construct()
    {
        $this->countSolicitudesTotal = 0;
        $this->countSolicitudesYaProcesadas[Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE] = 0;
        $this->countSolicitudesAprobadas = 0;
        $this->countSolicitudesNoAprobadas = 0;

    }

    public function procesarSolicitudAprobacionAutomatica()
    {
        Log::channel('aprobacion_' )->info('Inicio de Aprobacion Automatica General');
        $SolicitudesNominaYaProcesadasOtroMedio = NominaAprobarSolicitud::join('ab_solicitud',
            'ab_solicitud.id_solicitud', '=', 'nomina_aprobacion.id_solicitud')
            ->where('nomina_aprobacion.id_estado', Constante::ESTADO_NOMINA_APROBACION_1_GESTIONABLE)
            ->where('ab_solicitud.vigente', Constante::VIGENTE_1_VIGENTE)
            ->whereIn('ab_solicitud.id_estado',[Constante::ESTADO_SOLICITUD_5_APROBADO,
                Constante::ESTADO_SOLICITUD_7_RECHAZADA, Constante::ESTADO_SOLICITUD_9_DESISTIDA])
            ->select('nomina_aprobacion.ID_SOLICITUD', 'ab_solicitud.ID_TIPO_SOLICITUD', 'ab_solicitud.ID_ESTADO')
            ->get();
        try {

            foreach ($SolicitudesNominaYaProcesadasOtroMedio->groupBy('ID_TIPO_SOLICITUD') as $tipoSolicitud =>
                     $items) {
                switch ($tipoSolicitud) {
                    case Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE:
                        foreach ($items as $item) {
                            $idEstado = ($item->ID_ESTADO + 10);
                            $this->actualizarEstadoNominaAprobacion($item->id_solicitud, $idEstado);
                            $this->countSolicitudesYaProcesadas[Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE]++;
                        }
                        break;
                    default:
                        break;
                }
            }
        } catch (Exception $ex) {
            Log::channel('aprobacion_general' )->error($ex->getMessage());
            throw $ex;
        }

        $SolicitudesNominaAprobacion = NominaAprobarSolicitud::where('nomina_aprobacion.id_estado',
            Constante::ESTADO_NOMINA_APROBACION_1_GESTIONABLE)
            ->select('ID_SOLICITUD', 'ID_TIPO_SOLICITUD','RUN_CIUDADANO','ID_BLOQUE','ID_BLOQUE','fecha_carga')
            ->get();
        foreach ($SolicitudesNominaAprobacion->groupBy('ID_TIPO_SOLICITUD') as $tipoSolicitud =>
                 $SolicitudeNominaAprobacion) {
            switch ($tipoSolicitud) {
                case Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE:
                    $this->apruebarSolicitudesIncorporacion($SolicitudeNominaAprobacion);
                    break;
                default:
                    break;
            }
        }
        Log::channel('aprobacion_' )->info('Fin de Aprobacion Automatica General');
    }
    public function apruebarSolicitudesIncorporacion($SolicitudesNominaAprobacion)
    {
        $log = Log::channel('aprobacion_' . Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE);
        $log->info("Inicio del proceso de Aprobacion Automatica");
        $this->countSolicitudesTotal = $SolicitudesNominaAprobacion->count();
        $SolicitudesProcesar = Solicitud::whereIn('ab_solicitud.id_solicitud',
            $SolicitudesNominaAprobacion->pluck('ID_SOLICITUD'))
            ->where('ab_solicitud.vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('ab_solicitud.id_estado',
                [Constante::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL])
            ->join('personas','PER_RUN','=','RUN_CIUDADANO')
            ->join('beneficiarios_gruposfamiliares','beneficiarios_gruposfamiliares.BNFGRUPFAMI_PERSONA','=','personas.PER_ID')
            ->select('ab_solicitud.ID_SOLICITUD', 'ab_solicitud.ID_TIPO_SOLICITUD', 'ab_solicitud.ID_ESTADO',
                'ab_solicitud.DATO_NUEVO',  'ab_solicitud.DATO_ACTUAL',
                'beneficiarios_gruposfamiliares.BNFGRUPFAMI_PARENTESCO')
            ->get();

        if($SolicitudesProcesar->where('BNFGRUPFAMI_PARENTESCO','<>',
                Constante::PARENTESCO_1_JEFE_DE_HOGAR)->count() > 0){
            $SolicitudesParentesco = $SolicitudesProcesar
                    ->where('BNFGRUPFAMI_PARENTESCO','<>',Constante::PARENTESCO_1_JEFE_DE_HOGAR);
            foreach ($SolicitudesParentesco as $item) {
                $this->actualizarEstadoNominaAprobacion($item->id_solicitud,
                    Constante::ESTADO_NOMINA_APROBACION_7_FALLA_VALIDACION_O_SERVICIO);
                $this->countSolicitudesNoAprobadas++;
            }
        }

        if($SolicitudesProcesar->where('BNFGRUPFAMI_PARENTESCO',
                Constante::PARENTESCO_1_JEFE_DE_HOGAR)->count() > 0){
            $SolicitudesParentesco = $SolicitudesProcesar
                    ->where('BNFGRUPFAMI_PARENTESCO',Constante::PARENTESCO_1_JEFE_DE_HOGAR);
            foreach ($SolicitudesParentesco as $item) {
                $tieneRSH = Personas::tieneRSH($item->DATO_NUEVO);
                if(!$tieneRSH){
                    //Consumir Tramite
                    $tramite = $this->getTramite($item->ID_SOLICITUD, $item->ID_TIPO_SOLICITUD);

                    //Aprobar Automaticamente
                    if($tramite){
                        $this->generaBody($item->ID_TIPO_SOLICITUD, $tramite,
                            Constante::ACCION_APROBAR_VA_ESTADO_5);
                        $this->estadoSolicitud = $item->ID_ESTADO;
                        $this->tipoSolicitud = $item->ID_TIPO_SOLICITUD;
                        $this->putTramite($item->ID_SOLICITUD, $this->campos, Constante::ESTADO_NOMINA_APROBACION_5_APROBADA);
                    }
                } else {
                    $this->actualizarEstadoNominaAprobacion($item->id_solicitud,
                        Constante::ESTADO_NOMINA_APROBACION_7_FALLA_VALIDACION_O_SERVICIO);
                    $this->countSolicitudesNoAprobadas++;
                }

            }
        }

        $log->info("Fin del proceso de Aprobacion Automatica");
        $log->info("Total :" . $this->countSolicitudesTotal);
        $log->info("Solicitudes anteriormente precesadas procesadas : " .
            $this->countSolicitudesYaProcesadas[Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE]);
        $log->info("Solicitudes aprobadas :" . $this->countSolicitudesAprobadas);
        $log->info("Solicitudes No aprobadas :" . $this->countSolicitudesNoAprobadas);
    }

    public function getTramite($idSolicitud, $idTipoSolicitud)
    {
        $tramite = null;
        try {
            $metodo = 'GET';
            $url = '/tramite/' . $idSolicitud;
            $dataRest = Utiles::consumoConGuzzle($metodo, $url, null);
            $tramite = $dataRest->data;
        } catch (Exception $ex) {
            Log::channel('aprobacion_' . $idTipoSolicitud)->error($ex->getMessage());
        }
        return $tramite;
    }

    public function putTramite($idSolicitud, $body, $estadoNominaAprobacion)
    {
        $respConsumo = null;
        try {
            $metodo = 'PUT';
            $url = '/tramite/' . $idSolicitud;
            $dataRest = Utiles::consumoConGuzzle($metodo, $url, $body);
            $StatusCodeRespuesta = strval($dataRest->statusCode);
            $respConsumo = $dataRest->data;
            $JsonBody = json_encode($respConsumo);
            if($StatusCodeRespuesta == Constante::STATUS_200_SUCCESS_OK){
                $estado = $accion =
                $this->GuardarRegistrosModificados($idSolicitud, $this->estadoSolicitud, null,
                    $this->grupoSolicitud, $this->grupoActual, null, null,
                    $estadoNominaAprobacion,
                    $this->tipoSolicitud, $this->grupoNuevoFolio,
                    $JsonBody);
                $this->actualizarEstadoNominaAprobacion($idSolicitud,
                    $estadoNominaAprobacion);
                $this->countSolicitudesAprobadas++;
            } else {
                Log::channel('aprobacion_' . $this->tipoSolicitud)->info("Error al cambiar a estado aprobado, Solicitud ID " . $idSolicitud .
                    " IngresoRSH. Servicio respondio :" . $StatusCodeRespuesta . " --ERROR :" . $JsonBody);
                $this->GuardarRegistrosModificados($idSolicitud, $this->estadoSolicitud, null,
                    $this->grupoSolicitud, $this->grupoActual, null, null,
                    Constante::ESTADO_NOMINA_APROBACION_7_FALLA_VALIDACION_O_SERVICIO,
                    $this->tipoSolicitud, $this->grupoNuevoFolio,
                    $JsonBody);
                $this->actualizarEstadoNominaAprobacion($idSolicitud,
                    Constante::ESTADO_NOMINA_APROBACION_7_FALLA_VALIDACION_O_SERVICIO);
                $this->countSolicitudesNoAprobadas++;
            }

        } catch (Exception $ex) {
            Log::channel('aprobacion_' . $this->tipoSolicitud)->error("Error al Aprobar Solicitud ID " . $idSolicitud . " IngresoRSH. --ERROR: " .
                $ex->getMessage());
            $this->GuardarRegistrosModificados($idSolicitud, null, null, null,
                null, null,null,
                Constante::ESTADO_NOMINA_APROBACION_7_FALLA_VALIDACION_O_SERVICIO,
                $this->tipoSolicitud, null, substr($ex->getMessage(), 0, 1000));
            $this->actualizarEstadoNominaAprobacion($idSolicitud,
                Constante::ESTADO_NOMINA_APROBACION_7_FALLA_VALIDACION_O_SERVICIO);
            $this->countSolicitudesNoAprobadas++;
        }
        return $respConsumo;
    }
    public function actualizarEstadoNominaAprobacion($idSolicitud, $estadoNominaAprobacion)
    {
        NominaAprobarSolicitud::where('ID_SOLICITUD', $idSolicitud)
            ->update(['ID_ESTADO' => $estadoNominaAprobacion]);
    }

    public function generaBody($idTipoSolicitud, $data, $accion)
    {
        $this->campos = [
            'run-ciudadano' => $data['persona']['run'],
            'run-usuario' => env('RUTAPROBADOR'),
            'origen-registro' => Constante::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL,
            'canal' => Constante::CANAL_1_STRING_MINUS_MUNICIPAL,
            'accion' => $accion ,
            'observacion' => 'Aprobación Automatica IngresoRSH',
            'tipo-solicitud' => null
        ];
        switch ($idTipoSolicitud) {
            case Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE:
                if(isset($data['datos-solicitud'])){
                    foreach ($data['datos-solicitud'] as $integrante) {
                        $this->generaBodyIntegranteGrupoFamiliar($idTipoSolicitud, $integrante);
                    }
                }
                break;
            default:
                break;
        }
        if(!isset($this->campos['tipo-solicitud'])){
            $mensaje = 'El cuerpo del tramite solicitud: '.$data['solicitud']['id-solicitud'].' se encuentra vacio';
            Log::channel('aprobacion_' . $idTipoSolicitud)->warning($mensaje);
        }
    }

    public function generaBodyIntegranteGrupoFamiliar($idTipoSolicitud, $integrante)
    {
        $this->campos['tipo-solicitud'][$idTipoSolicitud][] = [
            'run-titular' => $integrante['persona']['run'],
            'parentesco' => $integrante['ficha']['parentesco']['valor'],
            'pareja' => null,
            'informante' => $integrante['ficha']['informante'],
            'pueblo-originario' => $integrante['ficha']['pueblo-originario'],

            'e1' => $integrante['ficha']['e1'],
//            'e2' => $integrante['ficha']['e2'],
            'e3' => $integrante['ficha']['e3'],
            'e4' => $integrante['ficha']['e4'],

            's1' => $integrante['ficha']['s1'],
            's1a' => $integrante['ficha']['s1a'],
            's1b' => $integrante['ficha']['s1b'],
            's1c' => $integrante['ficha']['s1c'],
            's1d' => $integrante['ficha']['s1d'],
            's1e' => $integrante['ficha']['s1e'],
            's1f' => $integrante['ficha']['s1f'],
            's2a' => $integrante['ficha']['s2a'],
            's2b' => $integrante['ficha']['s2b'],
            's2c' => $integrante['ficha']['s2c'],
            's2d' => $integrante['ficha']['s2d'],
            's2e' => $integrante['ficha']['s2e'],

            'o1' => $integrante['ficha']['o1'],
            'o2' => $integrante['ficha']['o2'],
            'o3' => $integrante['ficha']['o3'],
            'o4' => $integrante['ficha']['o4'],
            'o5' => $integrante['ficha']['o5'],
            'o6' => $integrante['ficha']['o6'],
            'o7' => $integrante['ficha']['o7'],
            'o8' => $integrante['ficha']['o8'],
            'o9' => $integrante['ficha']['o9'],
            'y1' => $integrante['ficha']['y1'],
            'y2' => $integrante['ficha']['y2'],
            'y3' => $integrante['ficha']['y3'],

            'y1-m1' => $integrante['ficha']['y1-m1'],
            'y1-m2' => $integrante['ficha']['y1-m2'],
            'y1-m3' => $integrante['ficha']['y1-m3'],
            'y1-m4' => $integrante['ficha']['y1-m4'],
            'y1-m5' => $integrante['ficha']['y1-m5'],
            'y1-m6' => $integrante['ficha']['y1-m6'],
            'y1-m7' => $integrante['ficha']['y1-m7'],
            'y1-m8' => $integrante['ficha']['y1-m8'],
            'y1-m9' => $integrante['ficha']['y1-m9'],
            'y1-m10' => $integrante['ficha']['y1-m10'],
            'y1-m11' => $integrante['ficha']['y1-m11'],
            'y1-m12' => $integrante['ficha']['y1-m12'],

            'y2-m1' => $integrante['ficha']['y2-m1'],
            'y2-m2' => $integrante['ficha']['y2-m2'],
            'y2-m3' => $integrante['ficha']['y2-m3'],
            'y2-m4' => $integrante['ficha']['y2-m4'],
            'y2-m5' => $integrante['ficha']['y2-m5'],
            'y2-m6' => $integrante['ficha']['y2-m6'],
            'y2-m7' => $integrante['ficha']['y2-m7'],
            'y2-m8' => $integrante['ficha']['y2-m8'],
            'y2-m9' => $integrante['ficha']['y2-m9'],
            'y2-m10' => $integrante['ficha']['y2-m10'],
            'y2-m11' => $integrante['ficha']['y2-m11'],
            'y2-m12' => $integrante['ficha']['y2-m12'],
        ];
    }
    public function GuardarRegistrosModificados(
        $idSolicitud,
        $solicitudEstado,
        $solicitudDoc,
        $fichaSolicitud,
        $fichaVigente,
        $fichaMensualSolicitud,
        $fichaMensualVigente,
        $estado,
        $tipo,
        $fichaPersona,
        $resultText
    ) {
        DB::beginTransaction();
        try {
            // fichaSolicitud y fichaVigente = En Actualizaciones lleva los ID de fichas / En Domicilio Vivienda lleva los ID de Grupos
            // fichaPersona = En Actualizaciones lleva el ID de la persona / En Domicilio Vivienda lleva el Nuevo Folio del grupo(Folio)

            $AprobadasAutomaticamente = new AprobadasAutomaticamente();
            $AprobadasAutomaticamente->APROB_ID = Sequence::nextValue('seq_ab_solicitud_aprob_auto');
            $AprobadasAutomaticamente->APROB_ID_SOLICITUD = $idSolicitud;
            $AprobadasAutomaticamente->APROB_ID_SOLICITUD_ESTADO = $solicitudEstado;
            $AprobadasAutomaticamente->APROB_SOLICITUD_DOC = $solicitudDoc;
            $AprobadasAutomaticamente->APROB_ID_FICHA_SOLICITUD = $fichaSolicitud;
            $AprobadasAutomaticamente->APROB_ID_FICHA_VIGENTE = $fichaVigente;
            $AprobadasAutomaticamente->APROB_ID_FICHA_MENSUAL_SOL = $fichaMensualSolicitud;
            $AprobadasAutomaticamente->APROB_ID_FICHA_MENSUAL_VIGENTE = $fichaMensualVigente;
            $AprobadasAutomaticamente->APROB_ESTADO_EJECUCION = $estado;
            $AprobadasAutomaticamente->APROB_FECHA_EJECUCION = date('Y-m-d H:i:s');
            $AprobadasAutomaticamente->APROB_TIPO_SOLICITUD = $tipo;
            $AprobadasAutomaticamente->APROB_FICHA_PERSONA = $fichaPersona;
            $AprobadasAutomaticamente->APROB_RESULTADO_TEXT = $resultText;
            $AprobadasAutomaticamente->save();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollback();
            Log::channel('aprobacion_' . $tipo)->error('Error al Guardar Registro Aprobacion: ' . $ex->getMessage());
            throw $ex;
        }
    }
}
