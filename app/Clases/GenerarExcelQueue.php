<?php


namespace App\Clases;

use App\Constantes\Constante;
use App\Exports\ExcelBandejaExport;
use App\Exports\GabineteExport;
use App\Models\Comuna;
use App\Models\EstadoSolicitud;
use App\Models\FolioGabineteRespuestas;
use App\Models\FolioPlan;
use App\Models\PreguntaPlan;
use App\Models\SubEstadoSolicitud;
use App\Models\TipoSolicitud;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class GenerarExcelQueue
{
    public static function GenerarExcel($com)
    {
        Log::info("Id_comuna a procesar :" . $com );
        $lstEstadoSolicitud = EstadoSolicitud::pluck('NOMBRE_ESTADO', 'ID_ESTADO');
        //Log::info("Finaliza proceso de lstEstadoSolicitud");
        $lstSubEstadoSolicitud = SubEstadoSolicitud::pluck('NOMBRE_SUB_ESTADO', 'ID_SUB_ESTADO');
        //Log::info("Finaliza proceso de lstSubEstadoSolicitud");
        $lstComuna = Comuna::pluck('COM_DESCRIPCION', 'COM_ID');
        //Log::info("Finaliza proceso de lstComuna");
        $lstTipoSolicitud = TipoSolicitud::where('VIGENTE', Constante::VIGENTE_1_VIGENTE)
            ->whereNotIn('ID_TIPO_SOLICITUD', [
                Constante::TIPO_SOLICITUD_9_RECTIFICACION_VEHICULO,
                Constante::TIPO_SOLICITUD_10_RECTIFICACION_ISAPRE,
                Constante::TIPO_SOLICITUD_11_RECTIFICACION_VIVIENDA_PROPIEDADES,
                Constante::TIPO_SOLICITUD_12_RECTIFICACION_ESTABLECIMIENTO_EDUCACIONAL,
                Constante::TIPO_SOLICITUD_15_FALLECIDO,
                Constante::TIPO_SOLICITUD_20_COMPLEMENTO_DE_SALUD,
                Constante::TIPO_SOLICITUD_99_SOLICITUD_TEMPORAL_PARA_MODULO_DE_SALUD
            ])
            ->pluck('TIPO_SOLICITUD', 'ID_TIPO_SOLICITUD');
        //Log::info("Finaliza proceso de lstTipoSolicitud");
        //Construye la data de esa comuna
        $sqlSolicitudes = self::solicitudes($com);
        //Genera la consulta de la data
        $lstSolicitud = DB::select($sqlSolicitudes);
        //Genera en el Directorio segun la data solicitada
        self::generaExcelListadoSolicitudes($lstSolicitud, $lstTipoSolicitud, $lstEstadoSolicitud,
                                            $lstSubEstadoSolicitud, $lstComuna, $com);
    }
    static function solicitudes($id_comuna){

        $listadoSolicitudes = "
            SELECT DISTINCT
                s.run_ciudadano,
                s.identidad_acreditada,
                s.id_padre_solicitud,
                s.id_solicitud,
                s.id_estado,
                s.comentario,
                s.id_sub_estado,
                s.id_sub_tipo_solicitud,
                s.id_tipo_solicitud,
                s.MD5_EXTRANJERO,
                s.dato_nuevo,
                CASE
                    WHEN s.canal = 'Municipal' OR s.canal = '4' OR s.canal = '5' Then 'Presencial'
                    WHEN s.canal = 'Clave Unica' OR s.canal = 'Clave Única' OR s.canal = 'Run Ciudadano' Then 'Via Web'
                END AS origen,
                CASE
                    WHEN s.canal = '4' Then '" . Constante::CANAL_4_STRING_MAYUS_CHILE_ATIENDE_EJECUTOR . "'
                    WHEN s.canal = '5' Then 'Municipal'
                    ELSE s.canal
                END AS canal,
                p.PER_RUN RUN,
                p.PER_DV DV,
                p.PER_MD5,
                p.PER_NOMBRES NOMBRES,
                p.PER_APELLIDOPATERNO APAT,
                p.PER_APELLIDOMATERNO AMAT,
                s.COM_ID,
                fn_Obtener_Id_Region (s.COM_ID) REG_ID,
                s.FECHA_CREACION,
                s.FECHA_MODIFICACION,
                U.NOMBRES_USUARIO,
                U.APAT_USUARIO,
                U.RUT_USUARIO,
                U.ID_PERFIL AS RUT_USUARIO_PERFIL,
                nvl(s.alerta_titular_modulo,-1) alerta_titular_modulo,
                p2.per_run run_asociado,
                p2.PER_NOMBRES NOMBRES_ASOCIADO,
                p2.PER_APELLIDOPATERNO APAT_ASOCIADO,
                p2.PER_APELLIDOMATERNO AMAT_ASOCIADO,
                p.PER_TELEFONO tel,
                p.PER_EMAIL mail,
                s.dato_actual,";

        $listadoSolicitudes .= "
                s.ID_USUARIO_ASIGNADO
            FROM ab_solicitud s
                    LEFT JOIN AB_USUARIO U on (U.RUT_USUARIO = s.ID_USUARIO_ASIGNADO)
                    LEFT JOIN personas p2 ON (s.ID_TIPO_SOLICITUD IN (5,6,14) AND s.DATO_ACTUAL = p2.PER_ID),
                personas p
             WHERE (s.MD5_EXTRANJERO = p.PER_MD5 OR s.RUN_CIUDADANO = p.PER_RUN) ";

        $listadoSolicitudes .= " AND s.vigente = 1 ";

        $listadoSolicitudes .= " AND s.COM_ID = '" . $id_comuna . "' ";

        $cantSolicitudes = (isset(env('CANTIDAD_LISTADO_SOLICITUDES'))) ?
            env('CANTIDAD_LISTADO_SOLICITUDES') : '-1';

        if( $cantSolicitudes != '-1'){
            $listadoSolicitudes .= " AND rownum <= ";
            $listadoSolicitudes .= $cantSolicitudes . ' ';
        }

        $listadoSolicitudes .= " ORDER BY s.id_padre_solicitud DESC ";

        return $listadoSolicitudes;
    }
    private static function generaExcelListadoSolicitudes( $lstSolicitud, $lstTipoSolicitud, $lstEstadoSolicitud,
                                                           $lstSubEstadoSolicitud, $lstComuna, $com)
    {
        ini_set('max_execution_time', 1000000000);
        set_time_limit(1000000000);
        try {
                $data['listado'] = $lstSolicitud;
                $data['lstTipoSolicitudes'] = $lstTipoSolicitud;
                $data['lstEsados'] = $lstEstadoSolicitud;
                $data['lstSubEstado'] = $lstSubEstadoSolicitud;
                $data['lstComuna'] = $lstComuna;
                $ExcelExport = new ExcelBandejaExport($data);
                //Log::info("Sale del ExcelBandejaExporta");
                if(!File::isDirectory( Storage::disk('QueueDisk')->path(''))){
                    File::makeDirectory( Storage::disk('QueueDisk')->path(''), 0777, true, true);
                }
                $nombre_File = 'Excel_' . $com . '.xlsx';
                Excel::store( $ExcelExport, $nombre_File, 'QueueDisk');
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
                return Response::json(['errors' => ['Error inesperado']], Constante::STATUS_500_ERROR_INTERNAL_ERROR);
            }
    }
}
