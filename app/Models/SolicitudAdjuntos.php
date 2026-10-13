<?php

namespace App\Models;

use App\Clases\DatosDeContacto;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionValidacion;
use App\Sequence;
use App\Utiles;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * App\Models\SolicitudAdjuntos
 *
 * @property int $DOC_ID
 * @property string|null $DOC_ARCHIVO
 * @property int|null $DOC_IDSOLICITUD
 * @property string|null $DOC_NOMBREORIGINAL
 * @property string|null $DOC_FECHACARGA
 * @property int|null $DOC_IDUSUARIOCARGA
 * @property int|null $DOC_TIPO_DOCUMENTO
 * @property bool|null $DOC_ELIMINADO 1=ELIMINADO; 0=VIGENTE
 * @property string|null $SUBTIPO
 * @property int|null $ID_ESTADO_MOVER
 * @property string|null $RUN_TITULAR_DOCUMENTO Run del ciudadano a quien corresponde la información contenida en el documento
 * @property int|null $IDENTIFICADOR_DOC_COMPARTIDO
 * @method static Builder|SolicitudAdjuntos newModelQuery()
 * @method static Builder|SolicitudAdjuntos newQuery()
 * @method static Builder|SolicitudAdjuntos query()
 * @method static Builder|SolicitudAdjuntos whereDOCARCHIVO($value)
 * @method static Builder|SolicitudAdjuntos whereDOCELIMINADO($value)
 * @method static Builder|SolicitudAdjuntos whereDOCFECHACARGA($value)
 * @method static Builder|SolicitudAdjuntos whereDOCID($value)
 * @method static Builder|SolicitudAdjuntos whereDOCIDSOLICITUD($value)
 * @method static Builder|SolicitudAdjuntos whereDOCIDUSUARIOCARGA($value)
 * @method static Builder|SolicitudAdjuntos whereDOCNOMBREORIGINAL($value)
 * @method static Builder|SolicitudAdjuntos whereDOCTIPODOCUMENTO($value)
 * @method static Builder|SolicitudAdjuntos whereIDESTADOMOVER($value)
 * @method static Builder|SolicitudAdjuntos whereRUNTITULARDOCUMENTO($value)
 * @method static Builder|SolicitudAdjuntos whereSUBTIPO($value)
 * @mixin Eloquent
 */
class SolicitudAdjuntos extends Eloquent
{
    protected $table = 'ab_solicitud_adjuntos';
    protected $primaryKey = 'DOC_ID';
    public $timestamps = false;

    public static function recuperarAdjuntos($sid, $incluirRutaArchivo = false)
    {
        $documentos = [];
        $adjuntos = self::where('doc_idsolicitud', $sid)
            ->where('doc_eliminado', Constante::SOLICITUD_ADJUNTOS_ELIMINADO_0_DOCUMENTO_NO_ELIMINADO)
            ->get();
        foreach ($adjuntos as $key => $documento) {
            $usuarioCarga = DatosDeContacto::obtenerDatosContacto($documento->DOC_IDUSUARIOCARGA);
            $usuarioTitular = (!is_null($documento->RUN_TITULAR_DOCUMENTO) && $documento->RUN_TITULAR_DOCUMENTO > 1000000 )?
                DatosDeContacto::obtenerDatosContacto($documento->RUN_TITULAR_DOCUMENTO) :
                null;
            $adjuntos[$key]['usuario_carga'] = $usuarioCarga;
            $adjuntos[$key]['propietario_dato'] = $usuarioTitular;
            $adjuntos[$key]['archivo-carga'] = $documento->DOC_NOMBREORIGINAL;
            $adjuntos[$key]['firma_simple'] = (strpos($documento->DOC_ARCHIVO,
                    Constante::STRING_IDENTIFICADOR_FIRMA_SIMPLE) !== false);
            $adjuntos[$key]['tipo_documento'] = SolicitudDocumentos::tipoDocumentosPorDocTipoDocumento(
                $documento->DOC_TIPO_DOCUMENTO);

            if ($incluirRutaArchivo) {
                $adjuntos[$key]['ruta-archivo'] = storage_path() . trim($documento->DOC_ARCHIVO);
            }

            if (array_key_exists($documento->DOC_TIPO_DOCUMENTO, $documentos)) {
                if (array_key_exists('fecha-carga', $documentos[$documento->DOC_TIPO_DOCUMENTO])) {
                    $documentos[$documento->DOC_TIPO_DOCUMENTO] = [
                        $documentos[$documento->DOC_TIPO_DOCUMENTO],
                        $adjuntos[$key]
                    ];
                } else {
                    $documentos[$documento->DOC_TIPO_DOCUMENTO][] = $adjuntos[$key];
                }
            } else {
                $documentos[$documento->DOC_TIPO_DOCUMENTO][] = $adjuntos[$key];
            }
        }
        return $documentos;
    }

    /**
     * @throws ExcepcionConsistencia
     * @throws ExcepcionValidacion
     */
    public static function recuperarDocumento($idSolicitud, $idTipoDocumento, $idDocumento = null)
    {
        $documentos = self::queryDocumentos($idSolicitud, $idTipoDocumento, $idDocumento)->get();
        self::validarListaVacia($documentos, $idDocumento);

        $salida = [];
        foreach ($documentos as $documento) {
            self::validarDocumentoCorrespondeASolicitud($idSolicitud, $idTipoDocumento, $idDocumento, $documento);
            $documento->DOC_NOMBREORIGINAL = self::limpiarNombreArchivo($documento->DOC_NOMBREORIGINAL);
            $documento->save();
            $path = storage_path() . trim($documento->DOC_ARCHIVO);
            if (!file_exists($path)) {
                throw new ExcepcionConsistencia(
                    'El archivo registrado en la plataforma no está disponible en el servidor para descarga.',
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
                );
            }
            $salida[] = [
                'ruta-archivo' => $path,
                'archivo-carga' => trim($documento->DOC_NOMBREORIGINAL)
            ];
        }
        return $salida;
    }

    private static function validarListaVacia(Collection $documentos, $idDocumento)
    {
        if ($documentos->isEmpty()) {
            if (!is_null($idDocumento)) {
                throw new ExcepcionConsistencia('El archivo no existe en la base de datos.',
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            } else {
                throw new ExcepcionValidacion('No se ha adjuntado ningun documento de este tipo para esta solicitud.');
            }
        }
    }

    private static function validarDocumentoCorrespondeASolicitud(
        $idSolicitud,
        $idTipoDocumento,
        $idDocumento,
        SolicitudAdjuntos $documento
    ) {
        $solictudes = Solicitud::where('ID_PADRE_SOLICITUD', function ($query) use ($idSolicitud) {
            $query->select('ID_PADRE_SOLICITUD')
                ->from('ab_solicitud')
                ->where('ID_SOLICITUD', $idSolicitud);
        })->pluck('ID_SOLICITUD')->toArray();

        if (!is_null($idDocumento)) {
            if (!in_array($documento->DOC_IDSOLICITUD,$solictudes)) {
                throw new ExcepcionValidacion('El documento no corresponde a la solicitud');
            }
            if ($idTipoDocumento != $documento->DOC_TIPO_DOCUMENTO) {
                throw new ExcepcionValidacion('El documento no corresponde al tipo de documento especificado');
            }
        }
    }

    public static function actualizarRegistroDocumento(
        $idSolicitud,
        $idTipoDocumento,
        $runUsuario,
        $nombreOriginal,
        $rutaLocal,
        $idDocumento = null,
        $runTitular = null,
        $esDocumentoCompartido = false
    ) {
        try {
            DB::beginTransaction();

            $documento = null;
            $registroYaExiste = false;
            $correspondeFirmaSimple = strpos($nombreOriginal, Constante::STRING_IDENTIFICADOR_FIRMA_SIMPLE);

            if (!is_null($idDocumento) || $correspondeFirmaSimple !== false) {

                $doc = self::queryDocumentos($idSolicitud, $idTipoDocumento, $idDocumento, $esDocumentoCompartido)
                    ->get();

                if ($doc->count() > 0) {
                    foreach ($doc as $documento) {
                        //self::validarDocumentoCorrespondeASolicitud($idSolicitud, $idTipoDocumento, $idDocumento,
                        //    $documento);
                        $nombreArchivo = $documento->DOC_ARCHIVO;
                        $correspondeFirmaSimple = strpos($nombreArchivo, Constante::STRING_IDENTIFICADOR_FIRMA_SIMPLE);
                        if ($correspondeFirmaSimple !== false) {
                            self::actualizarRegistroDocumentoFirmaSimple($documento, $nombreOriginal, $nombreArchivo,
                                $rutaLocal);
                            $registroYaExiste = true;
                        } else {
                            $documento->DOC_ELIMINADO = Constante::SOLICITUD_ADJUNTOS_ELIMINADO_1_DOCUMENTO_ELIMINADO;
                            $documento->save();
                        }
                    }
                    if (!is_null($idDocumento)) {
                        $runTitular = $runTitular ?: $doc[0]->RUN_TITULAR_DOCUMENTO;
                    }
                }
            }
            if (!$registroYaExiste) {
                $documento = new SolicitudAdjuntos();
                $documento->DOC_ID = Sequence::nextValue('SEQ_AB_ADJUNTO');
                $documento->DOC_NOMBREORIGINAL = self::limpiarNombreArchivo($nombreOriginal);
                $documento->DOC_ARCHIVO = $rutaLocal;
                $documento->DOC_IDSOLICITUD = $idSolicitud;
                $documento->DOC_FECHACARGA = date('Y-m-d H:i:s');
                $documento->DOC_IDUSUARIOCARGA = $runUsuario;
                $documento->DOC_TIPO_DOCUMENTO = $idTipoDocumento;
                $documento->RUN_TITULAR_DOCUMENTO = $runTitular;
                $documento->ID_ESTADO_MOVER = Constante::SOLICITUD_ADJUNTOS_ESTADO_MOVER_1;
                $documento->save();
            }
            DB::commit();
            return $documento;
        } catch (Throwable $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public static function eliminarRegistroDocumento(
        $idSolicitud,
        $idTipoDocumento,
        $idDocumento = null,
        $esDocumentoCompartido = null
    ) {
        try {
            DB::beginTransaction();
            $result = self::queryDocumentos($idSolicitud, $idTipoDocumento, $idDocumento, $esDocumentoCompartido)
                ->update([
                        'doc_eliminado' => Constante::SOLICITUD_ADJUNTOS_ELIMINADO_1_DOCUMENTO_ELIMINADO
                    ]
                );
            DB::commit();
            if ($result == 0) {
                throw ExcepcionConsistencia::new('No se encontró el documento.',
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE)
                    ->setContexto([
                        'idSolicitud' => $idSolicitud,
                        'idTipoDocumento' => $idTipoDocumento,
                        'idDocumento' => $idDocumento,
                    ]);
            }
            return $result;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    private static function limpiarNombreArchivo($nombreOriginal)
    {
        if (preg_match('/[\'^£$%&*´()}{@#~?ñÑ><,|=_+¬-]/', $nombreOriginal)) {
            $nombreOriginal = Utiles::getCleanField($nombreOriginal);
            $nombreOriginal = Utiles::cambiaTildeASuLetra($nombreOriginal);
            $nombreOriginal = strtolower(Utiles::cleanNombreVariables2($nombreOriginal));
        }
        return $nombreOriginal;
    }

    private static function actualizarRegistroDocumentoFirmaSimple(
        SolicitudAdjuntos $documento,
        $nombreOriginal,
        $nombreArchivo,
        $rutaLocal
    ) {
        $documento->DOC_ELIMINADO = Constante::SOLICITUD_ADJUNTOS_ELIMINADO_0_DOCUMENTO_NO_ELIMINADO;
        $documento->DOC_NOMBREORIGINAL = self::limpiarNombreArchivo($nombreOriginal);
        $documento->DOC_ARCHIVO = $rutaLocal;
        $documento->DOC_FECHACARGA = date('Y-m-d H:i:s');
        $documento->ID_ESTADO_MOVER = Constante::SOLICITUD_ADJUNTOS_ESTADO_MOVER_1;
        $documento->save();

        $doc_archivo = storage_path() . $nombreArchivo;
        if (file_exists($doc_archivo)) {
            if (@unlink($doc_archivo) !== true) {
                throw new Exception('No se pudo eliminar el archivo: ' . $doc_archivo . ' Cierre todas las aplicaciones que lo están usando.');
            }
        }
    }

    private static function queryDocumentos(
        $idSolicitud,
        $idTipoDocumento,
        $idDocumento = null,
        $esDocumentoCompartido = null
    ) {
        $doc = self::where('doc_eliminado', Constante::SOLICITUD_ADJUNTOS_ELIMINADO_0_DOCUMENTO_NO_ELIMINADO);
        if (!empty($idDocumento)) {
            if ($esDocumentoCompartido) {
                $doc->where('doc_archivo', function ($query) use ($idDocumento) {
                    $query->select('doc_archivo')
                        ->from('ab_solicitud_Adjuntos')
                        ->where('doc_id', $idDocumento);
                });
            } else {
                $doc->where('doc_id', $idDocumento);
            }
        } else {
            $doc->where('doc_idsolicitud', $idSolicitud)
                ->where('doc_tipo_documento', $idTipoDocumento);
        }
        return $doc;
    }
}
