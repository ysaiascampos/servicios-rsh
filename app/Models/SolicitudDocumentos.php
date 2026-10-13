<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Exception;

/**
 * App\Models\SolicitudDocumentos
 *
 * @property int $ID_DOCUMENTO
 * @property string|null $NOMBRE
 * @property int|null $ID_TIPO_SOLICITUD
 * @property int|null $EDAD_MINIMA
 * @property int|null $OBLIGATORIO en 1 Sí o Sí lo solicita, en 0 debe ser al menos uno del tipo solicitud
 * @property int|null $ESTADO
 * @property string|null $ABREVIACION
 * @property int|null $ID_SUB_TIPO_SOLICITUD
 * @property int|null $ORDEN
 * @property string|null $NOMBRE_CIUDADANO
 * @property int|null $CLASIFICACION_DOCUMENTO 1:Formulario Solicitud.
 * 2:Cedula Identidad.
 * 3:Institucionalizados.
 * 4:Documento Generico.
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos query()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos whereABREVIACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos whereCLASIFICACIONDOCUMENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos whereEDADMINIMA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos whereIDDOCUMENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos whereIDSUBTIPOSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos whereIDTIPOSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos whereNOMBRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos whereNOMBRECIUDADANO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos whereOBLIGATORIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudDocumentos whereORDEN($value)
 * @mixin Eloquent
 */
class SolicitudDocumentos extends Eloquent
{
    protected $table = 'ab_solicitud_documentos';
    protected $primaryKey = 'ID_DOCUMENTO';
    public $timestamps = false;

    public static function tipoDocumentosPorDocTipoDocumento($idDocumento)
    {
        $tipoDocumento = null;
        try {
            $existstipoDocumento = self::select('NOMBRE')
                ->where('id_documento', $idDocumento)
                ->first();

            if($existstipoDocumento){
                $tipoDocumento = $existstipoDocumento->NOMBRE;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $tipoDocumento;
    }

    public static function findActivosObligatorioByIdTipoSolicitud($idTipoSolicitud, $mostrarDocumentosParaIntitucionalizados = false, $idSubTipoSolicitud = null)
    {
        $documentoRequerido = null;
        try {
            $documentoRequerido = self::leftJoin('clasificacion_documentos', 'clasificacion_documento', '=', 'id_clasificacion')
//                ->select(
//                    'ID_DOCUMENTO',
//                    'ID_TIPO_SOLICITUD',
//                    'ID_SUB_TIPO_SOLICITUD',
//                    'NOMBRE',
//                    'OBLIGATORIO',
//                    'ESTADO',
//                    'ORDEN',
//                    'NOMBRE_CIUDADANO',
//                    'CLASIFICACION_DOCUMENTO',
//                    'DESCRIPCION_CLASIFICACION')
                ->where('id_tipo_solicitud', $idTipoSolicitud)
                ->where('obligatorio', Constante::DOCUMENTOS_OBLIGATORIOS_1_OBLIGATORIO)
                ->where('estado', Constante::ESTADO_1_ACTIVO);

            if (!$mostrarDocumentosParaIntitucionalizados) {
                $documentoRequerido->whereNotIn('id_documento', Constante::$DOCUMENTOSINTITUCIONALIZADOS);
            }
            if (isset($idSubTipoSolicitud)) {
                $documentoRequerido->where('id_sub_tipo_solicitud', $idSubTipoSolicitud);
            }
            $documentoRequerido = $documentoRequerido
                ->orderBy('orden', 'ASC')
                ->orderBy('id_documento', 'ASC')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $documentoRequerido;
    }

    public static function findActivosNoObligatorioByIdTipoSolicitud($idTipoSolicitud, $mostrarDocumentosParaIntitucionalizados = null, $idSubTipoSolicitud = null)
    {
        $documentoRequerido = null;
        try {
            $documentoRequerido = self::leftJoin('clasificacion_documentos', 'clasificacion_documento', '=', 'id_clasificacion')
                ->where('obligatorio', Constante::DOCUMENTOS_OBLIGATORIOS_0_NO_OBLIGATORIO)
                ->where('estado', Constante::ESTADO_1_ACTIVO);

            if (isset($idSubTipoSolicitud)) {
                $documentoRequerido->where('id_sub_tipo_solicitud', $idSubTipoSolicitud);
            }
            if($idTipoSolicitud == Constante::TIPO_SOLICITUD_78_RECTIFICACION_INGRESO_DEPENDIENTE_E_INDEPENDIENTE){
                $documentoRequerido->whereIn('id_tipo_solicitud', [Constante::TIPO_SOLICITUD_70_RECTIFICACION_INGRESO_TRABAJADOR_DEPENDIENTE,
                    Constante::TIPO_SOLICITUD_71_RECTIFICACION_INGRESO_TRABAJADOR_INDEPENDIENTE]);
            }
            else{
                $documentoRequerido->where('id_tipo_solicitud', $idTipoSolicitud);
            }
            $documentoRequerido = $documentoRequerido
                ->orderBy('orden', 'ASC')
                ->orderBy('id_documento', 'ASC')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $documentoRequerido;
    }
}
