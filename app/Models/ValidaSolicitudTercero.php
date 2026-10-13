<?php

namespace App\Models;

use App\Clases\ValidacionSolicitudTercero;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Http\ResponseMappers\ValidaTerceroMapper;
use App\Utiles;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Doctrine\Common\Cache\Psr6\get;

/**
 * App\Models\ValidaSolicitudTercero
 *
 * @property int $ID
 * @property int|null $PER_RUN
 * @property int $IDSOLICITUD
 * @property string|null $EMAIL
 * @property int|0 $ENVIO_MAIL_AVISO_SOLICITUD
 * @property int|0 $ESTADO_VALIDACION
 * @property int|null $FECHA_VALIDACION
 * @property int|0 $ENVIO_MAIL_VALIDACION
 *
 * @method static Builder|ValidaSolicitudTercero newModelQuery()
 * @method static Builder|ValidaSolicitudTercero newQuery()
 * @method static Builder|ValidaSolicitudTercero query()
 *
 * @method static Builder|ValidaSolicitudTercero whereID($value)
 * @method static Builder|ValidaSolicitudTercero wherePERRUN($value)
 * @method static Builder|ValidaSolicitudTercero whereIDSOLICITUD($value)
 * @method static Builder|ValidaSolicitudTercero whereEMAIL($value)
 * @method static Builder|ValidaSolicitudTercero whereENVIOMAILAVISOSOLICITUD($value)
 * @method static Builder|ValidaSolicitudTercero whereESTADOVALIDACION($value)
 * @method static Builder|ValidaSolicitudTercero whereFECHAVALIDACION($value)
 * @method static Builder|ValidaSolicitudTercero whereENVIOMAILVALIDACION($value)
 *
 * @mixin \Eloquent
 */
class ValidaSolicitudTercero extends Eloquent
{
    protected $table = 'VALIDA_SOLICITUD_TERCEROS';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $appends = [
        'NOMBRE_ASOCIADO_SOLICITUD',
        'RUN_CIUDADANO',
        'NOMBRE_TITULAR_SOLICITUD',
        'ID_PADRE_SOLICITUD',
        'ID_ESTADO_SOLICITUD',
        'ESTADO_SOLICITUD',
        'ID_SUB_ESTADO_SOLICITUD',
        'SUB_ESTADO_SOLICITUD',
        'ID_TIPO_SOLICITUD',
        'TIPO_SOLICITUD',
        'ID_SUB_TIPO_SOLICITUD',
        'SUB_TIPO_SOLICITUD',
        'VIGENTE',
        'CODIGO_COMUNA',
        'COMUNA_DESCRIPCION',
        'CODIGO_REGION',
        'REGION_DESCRIPCION',
        'FECHA_CREACION',
        'FECHA_MODIFICACION',
        'FECHA_CREACION_CIUDADANO',
        'COMENTARIO'
    ];

    // NOMBRE ASOCIADO SOLICITUD

    public function getnombreAsociadoSolicitudAttribute()
    {
        if (!isset($this->attributes['NOMBRE_ASOCIADO_SOLICITUD'])) {
            $this->attributes['NOMBRE_ASOCIADO_SOLICITUD'] = $this->nombreAsociadoSolicitud($this->attributes['PER_RUN']);
        }
        return $this->attributes['NOMBRE_ASOCIADO_SOLICITUD'];

    }

    public static function nombreAsociadoSolicitud($runCiudadano)
    {
        try {
            $data = Personas::where('PER_RUN', $runCiudadano)
                ->select('PER_NOMBRES', 'PER_APELLIDOPATERNO', 'PER_APELLIDOMATERNO')
                ->first();
            $nombre = ucwords(strtolower(
                    Utiles::cambiaTildeASuLetra($data['PER_NOMBRES']) . ' ' .
                    Utiles::cambiaTildeASuLetra($data['PER_APELLIDOPATERNO']) . ' ' .
                    Utiles::cambiaTildeASuLetra($data['PER_APELLIDOMATERNO']))
            );

        } catch (Exception $e) {
            Log::error("No es posible obtener el NOMBRE_ASOCIADO_SOLICITUD del rut: " . $runCiudadano);
        }
        return ($nombre);
    }

    //RUN_CIUDADANO
    public function getrunCiudadanoAttribute()
    {
        if (!isset($this->attributes['RUN_CIUDADANO'])) {
            $this->attributes['RUN_CIUDADANO'] = $this->runCiudadano($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['RUN_CIUDADANO'];

    }

    public static function runCiudadano($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('RUN_CIUDADANO')
                ->first();
            $runCiudadano = ($data) ? $data->RUN_CIUDADANO : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el RUN_CIUDADANO de la solicitud: " . $idSolicitud);
        }
        return ($runCiudadano);
    }

    // NOMBRE TITULAR SOLICITUD

    public function getnombreTitularSolicitudAttribute()
    {
        if (!isset($this->attributes['NOMBRE_TITULAR_SOLICITUD'])) {
            $this->attributes['NOMBRE_TITULAR_SOLICITUD'] = $this->nombreTitularSolicitud($this->attributes['RUN_CIUDADANO']);
        }
        return $this->attributes['NOMBRE_TITULAR_SOLICITUD'];

    }

    public static function nombreTitularSolicitud($runCiudadano)
    {
        try {
            $data = Personas::where('PER_RUN', $runCiudadano)
                ->select('PER_NOMBRES', 'PER_APELLIDOPATERNO', 'PER_APELLIDOMATERNO')
                ->first();
            $nombre = ucwords(strtolower(
                    Utiles::cambiaTildeASuLetra($data['PER_NOMBRES']) . ' ' .
                    Utiles::cambiaTildeASuLetra($data['PER_APELLIDOPATERNO']) . ' ' .
                    Utiles::cambiaTildeASuLetra($data['PER_APELLIDOMATERNO']))
            );

        } catch (Exception $e) {
            Log::error("No es posible obtener el NOMBRE_TITULAR_SOLICITUD del rut: " . $runCiudadano);
        }
        return ($nombre);
    }

    //ID PADRE SOLICITUD
    public function getIdPadreSolicitudAttribute()
    {
        if (!isset($this->attributes['ID_PADRE_SOLICITUD'])) {
            $this->attributes['ID_PADRE_SOLICITUD'] = $this->idPadreSolicitud($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['ID_PADRE_SOLICITUD'];
    }

    public static function idPadreSolicitud($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('ID_PADRE_SOLICITUD')
                ->first();
            $idPadreSolicitud = ($data) ? $data->ID_PADRE_SOLICITUD : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el ID_PADRE_SOLICITUD de la solicitud: " . $idSolicitud);
        }
        return ($idPadreSolicitud);
    }

    //ID ESTADO SOLICITUD
    public function getidEstadoSolicitudAttribute()
    {
        if (!isset($this->attributes['ID_ESTADO_SOLICITUD'])) {
            $this->attributes['ID_ESTADO_SOLICITUD'] = $this->idEstadoSolicitud($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['ID_ESTADO_SOLICITUD'];
    }

    public static function idEstadoSolicitud($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('ID_ESTADO')
                ->first();
            $idEstadoSolicitud = ($data) ? $data->ID_ESTADO : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el ID_ESTADO de la solicitud: " . $idSolicitud);
        }
        return ($idEstadoSolicitud);
    }

    //STRING ESTADO SOLICITUD
    public function getestadoSolicitudAttribute()
    {
        if (!isset($this->attributes['ESTADO_SOLICITUD'])) {
            $this->attributes['ESTADO_SOLICITUD'] = $this->EstadoSolicitud($this->attributes['ID_ESTADO_SOLICITUD']);
        }
        return $this->attributes['ESTADO_SOLICITUD'];
    }

    public static function estadoSolicitud($idEstadoSolicitud)
    {
        try {
            $data = EstadoSolicitud::where('ID_ESTADO', $idEstadoSolicitud)
                ->select('NOMBRE_ESTADO')
                ->first();
            $EstadoSolicitud = ($data) ? $data->NOMBRE_ESTADO : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el NOMBRE_ESTADO para el ID: " . $idEstadoSolicitud);
        }
        return ($EstadoSolicitud);
    }

    //ID SUB ESTADO SOLICITUD
    public function getidSubEstadoSolicitudAttribute()
    {
        if (!isset($this->attributes['ID_SUB_ESTADO_SOLICITUD'])) {
            $this->attributes['ID_SUB_ESTADO_SOLICITUD'] = $this->idSubEstadoSolicitud($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['ID_SUB_ESTADO_SOLICITUD'];
    }

    public static function idSubEstadoSolicitud($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('ID_SUB_ESTADO')
                ->first();
            $idSubEstadoSolicitud = ($data) ? $data->ID_SUB_ESTADO : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el ID_SUB_ESTADO de la solicitud: " . $idSolicitud);
        }
        return ($idSubEstadoSolicitud);
    }

    //STRING SUB ESTADO SOLICITUD
    public function getsubEstadoSolicitudAttribute()
    {
        if (!isset($this->attributes['SUB_ESTADO_SOLICITUD'])) {
            $this->attributes['SUB_ESTADO_SOLICITUD'] = $this->subEstadoSolicitud($this->attributes['ID_SUB_ESTADO_SOLICITUD']);
        }
        return $this->attributes['SUB_ESTADO_SOLICITUD'];
    }

    public static function subEstadoSolicitud($idSubEstadoSolicitud)
    {
        try {
            $data = SubEstadoSolicitud::where('ID_SUB_ESTADO', $idSubEstadoSolicitud)
                ->select('NOMBRE_SUB_ESTADO')
                ->first();
            $SubEstadoSolicitud = ($data) ? $data->NOMBRE_SUB_ESTADO : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el NOMBRE_SUB_ESTADO par el ID: " . $idSubEstadoSolicitud);
        }
        return ($SubEstadoSolicitud);
    }

    //ID TIPO SOLICITUD
    public function getidTipoSolicitudAttribute()
    {
        if (!isset($this->attributes['ID_TIPO_SOLICITUD'])) {
            $this->attributes['ID_TIPO_SOLICITUD'] = $this->idTipoSolicitud($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['ID_TIPO_SOLICITUD'];
    }

    public static function idTipoSolicitud($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('ID_TIPO_SOLICITUD')
                ->first();
            $idTipoSolicitud = ($data) ? $data->ID_TIPO_SOLICITUD : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el ID_TIPO_SOLICITUD de la solicitud: " . $idSolicitud);
        }
        return ($idTipoSolicitud);
    }

    //STRING TIPO DE SOLICITUD
    public function getTipoSolicitudAttribute()
    {
        if (!isset($this->attributes['TIPO_SOLICITUD'])) {
            $this->attributes['TIPO_SOLICITUD'] = $this->TipoSolicitud($this->attributes['ID_TIPO_SOLICITUD']);
        }
        return $this->attributes['TIPO_SOLICITUD'];
    }

    public static function TipoSolicitud($idTipoSolicitud)
    {
        try {
            $data = TipoSolicitud::where('ID_TIPO_SOLICITUD', $idTipoSolicitud)
                ->select('TIPO_SOLICITUD')
                ->first();
            $TipoSolicitud = ($data) ? $data->TIPO_SOLICITUD : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el TIPO_SOLICITUD para el ID: " . $idTipoSolicitud);
        }
        return ($TipoSolicitud);
    }

    //ID SUB TIPO SOLICITUD
    public function getidSubTipoSolicitudAttribute()
    {
        if (!isset($this->attributes['ID_SUB_TIPO_SOLICITUD'])) {
            $this->attributes['ID_SUB_TIPO_SOLICITUD'] = $this->IdSubTipoSolicitud($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['ID_SUB_TIPO_SOLICITUD'];
    }

    public static function idSubTipoSolicitud($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('ID_SUB_TIPO_SOLICITUD')
                ->first();
            $idSubTipoSolicitud = ($data) ? $data->ID_SUB_TIPO_SOLICITUD : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el ID_SUB_TIPO_SOLICITUD de la solicitud: " . $idSolicitud);
        }
        return ($idSubTipoSolicitud);
    }

    //STRING SUB TIPO SOLICITUD
    public function getsubTipoSolicitudAttribute()
    {
        if (!isset($this->attributes['SUB_TIPO_SOLICITUD'])) {
            $this->attributes['SUB_TIPO_SOLICITUD'] = $this->subTipoSolicitud($this->attributes['ID_TIPO_SOLICITUD'],
                $this->attributes['ID_SUB_TIPO_SOLICITUD']);
        }
        return $this->attributes['SUB_TIPO_SOLICITUD'];
    }

    public static function subTipoSolicitud($idTipoSolicitud, $idSubTipoSolicitud)
    {
        try {
            $data = SubTipoSolicitud::where('ID_TIPO_SOLICITUD', $idTipoSolicitud)
                ->where('ID_SUB_TIPO_SOLICITUD', $idSubTipoSolicitud)
                ->select('NOMBRE_SUB_TIPO_SOLICITUD')
                ->first();
            $subTipoSolicitud = ($data) ? $data->NOMBRE_SUB_TIPO_SOLICITUD : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el NOMBRE_SUB_TIPO_SOLICITUD para el tipo de solicitud: " . $idTipoSolicitud . ' y subtipo : ' . $idSubTipoSolicitud);
        }
        return ($subTipoSolicitud);
    }

    //VIGENTE
    public function getvigenteAttribute()
    {
        if (!isset($this->attributes['VIGENTE'])) {
            $this->attributes['VIGENTE'] = $this->obtenerVigente($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['VIGENTE'];

    }

    public static function obtenerVigente($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('VIGENTE')
                ->first();
            $fechaCreacion = ($data) ? $data->VIGENTE : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el VIGENTEN de la solicitud: " . $idSolicitud);
        }
        return ($fechaCreacion);
    }

    //ID CODIGO COMUNA
    public function getcodigoComunaAttribute()
    {
        if (!isset($this->attributes['CODIGO_COMUNA'])) {
            $this->attributes['CODIGO_COMUNA'] = $this->codigoComuna($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['CODIGO_COMUNA'];

    }

    public static function codigoComuna($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('COM_ID')
                ->first();
            $codigoComuna = ($data) ? $data->COM_ID : null;

        } catch (Exception $e) {
            Log::error("No es posible obtener el COM_ID de la solicitud: " . $idSolicitud);
        }
        return ($codigoComuna);
    }

    //STRING COMUNA
    public function getcomunaDescripcionAttribute()
    {
        if (!isset($this->attributes['COMUNA_DESCRIPCION'])) {
            $this->attributes['COMUNA_DESCRIPCION'] = $this->comunaDescripcion($this->attributes['CODIGO_COMUNA']);
        }
        return $this->attributes['COMUNA_DESCRIPCION'];
    }

    public static function comunaDescripcion($idComuna)
    {
        try {
            $data = Comuna::obtenerNombreComuna($idComuna);
            $comuna = ($data) ? ucwords(strtolower($data)) : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener la COMUNA  para la solicitud: " . $idComuna);
        }
        return ($comuna);
    }

    //ID CODIGO REGION
    public function getcodigoRegionAttribute()
    {
        if (!isset($this->attributes['CODIGO_REGION'])) {
            $this->attributes['CODIGO_REGION'] = $this->codigoRegion($this->attributes['CODIGO_COMUNA']);
        }
        return $this->attributes['CODIGO_REGION'];

    }

    public static function codigoRegion($comunaIne)
    {
        try {
            $data = Utiles::getRegionDesdeCodigoComuna($comunaIne);
            $codigoComuna = ($data) ? $data : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el CODIGO_REGION de la solicitud: " . $comunaIne);
        }
        return ($codigoComuna);
    }

    //STRING REGION
    public function getRegionDescripcionAttribute()
    {
        if (!isset($this->attributes['REGION_DESCRIPCION'])) {
            $this->attributes['REGION_DESCRIPCION'] = $this->regionDescripcion($this->attributes['CODIGO_REGION']);
        }
        return $this->attributes['REGION_DESCRIPCION'];
    }

    public static function regionDescripcion($idRegion)
    {
        try {
            $data = LocacionRegion::where('reg_id', (int)$idRegion)
                ->select('REG_DESCRIPCION')
                ->first();
            $region = ($data) ? ucwords(strtolower($data->REG_DESCRIPCION)) : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener la region  para la comuna id: " . $idRegion);
        }
        return ($region);

    }

    //FECHA CREACION
    public function getfechaCreacionAttribute()
    {
        if (!isset($this->attributes['FECHA_CREACION'])) {
            $this->attributes['FECHA_CREACION'] = $this->fechaCreacion($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['FECHA_CREACION'];

    }

    public static function fechaCreacion($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('FECHA_CREACION')
                ->first();
            $fechaCreacion = ($data) ? $data->FECHA_CREACION : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener la FECHA_CREACION de la solicitud: " . $idSolicitud);
        }
        return ($fechaCreacion);
    }

    //FECHA MODIFICACION
    public function getfechaModificacionAttribute()
    {
        if (!isset($this->attributes['FECHA_MODIFICACION'])) {
            $this->attributes['FECHA_MODIFICACION'] = $this->fechaModificacion($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['FECHA_MODIFICACION'];

    }

    public static function fechaModificacion($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('FECHA_MODIFICACION')
                ->first();
            $fechaModificacion = ($data) ? $data->FECHA_MODIFICACION : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener la FECHA_MODIFICACION de la solicitud: " . $idSolicitud);
        }
        return ($fechaModificacion);
    }

    //FECHA CREACION CIUDADANO
    public function getfechaCreacionCiudadanoAttribute()
    {
        if (!isset($this->attributes['FECHA_CREACION_CIUDADANO'])) {
            $this->attributes['FECHA_CREACION_CIUDADANO'] = $this->fechaCreacionCiudadano($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['FECHA_CREACION_CIUDADANO'];

    }

    public static function fechaCreacionCiudadano($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('FECHA_CREACION_CIUDADANO')
                ->first();
            $fechaCreacionCiudadano = ($data) ? $data->FECHA_CREACION_CIUDADANO : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener la FECHA_CREACION_CIUDADANO de la solicitud: " . $idSolicitud);
        }
        return ($fechaCreacionCiudadano);
    }

    //FECHA CREACION CIUDADANO
    public function getcomentarioAttribute()
    {
        if (!isset($this->attributes['COMENTARIO'])) {
            $this->attributes['COMENTARIO'] = $this->obtenerComentario($this->attributes['IDSOLICITUD']);
        }
        return $this->attributes['COMENTARIO'];

    }

    public static function obtenerComentario($idSolicitud)
    {
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                ->select('COMENTARIO')
                ->first();
            $comentario = ($data) ? $data->COMENTARIO : null;
        } catch (Exception $e) {
            Log::error("No es posible obtener el COMENTARIO de la solicitud: " . $idSolicitud);
        }
        return ($comentario);
    }


    public static function getModel()
    {
        return new ValidaSolicitudTercero();
    }

    public static function getSolicitud($runCiudadano, $idSolicitud)
    {
        $solicitud = null;
        try {
            $solicitud = ValidaSolicitudTercero::getModel()
                ->where('PER_RUN', $runCiudadano)
                ->where('IDSOLICITUD', $idSolicitud)
                ->first();
        } catch (Exception $ex) {
            throw $ex;
        }
        return new ValidaTerceroMapper($solicitud);
    }

    public static function listSolicitudes($runCiudadano)
    {
        $datosDeSolicitud = null;
        $listaSolicitud = array();
        try {
            $datosDeSolicitud = ValidaSolicitudTercero::join('ab_solicitud', 'IDSOLICITUD', 'ab_solicitud.id_solicitud')
                ->whereNotIn('ab_solicitud.id_estado', [
                    Constante::ESTADO_SOLICITUD_7_RECHAZADA,
                    Constante::ESTADO_SOLICITUD_11_EN_DIGITACION
                ])
                ->where(function ($query) use ($runCiudadano) {
                    $query->where(function ($q) use ($runCiudadano) {
                        // Caso normal: ID_TIPO_SOLICITUD != 4
                        $q->where('ab_solicitud.ID_TIPO_SOLICITUD', '!=', 4)
                        ->where('VALIDA_SOLICITUD_TERCEROS.PER_RUN', $runCiudadano);
                    })
                    ->orWhere(function ($q) use ($runCiudadano) {
                        // Caso especial: ID_TIPO_SOLICITUD = 4
                        $q->where('ab_solicitud.ID_TIPO_SOLICITUD', 4)
                        ->where('ab_solicitud.ID_USUARIO_MODIFICACION', $runCiudadano);
                    });
                })
                ->orderBy('ESTADO_VALIDACION','asc')
                ->orderBy('VALIDA_SOLICITUD_TERCEROS.ID', 'desc')
                ->get();

            foreach ($datosDeSolicitud as $solicitud) {
                $solicitud->ID_ESTADO = $solicitud->ID_ESTADO_SOLICITUD;
                $listaSolicitud[] = $solicitud;
            }

        } catch (Exception $ex) {
            throw $ex;
        }

        if (empty($listaSolicitud)) {
            return null;
        }

        return $listaSolicitud;
    }

    /**
     * @return ValidaSolicitudTercero
     * @throws ExcepcionConsistencia
     */
    public static function findByIdValidacion($idValidacion)
    {
        $datosDeSolicitud = self::where('id', $idValidacion)
                            ->first();
        if (empty($datosDeSolicitud)) {
            throw ExcepcionConsistencia::new(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE)
                ->setContexto(['id' => $idValidacion]);
        }
        return $datosDeSolicitud;
    }

    /**
     * @return ValidaSolicitudTercero[]|Collection
     * @throws ExcepcionConsistencia
     */
    public static function findByIdSolicitud($idValidacion,$iSolicitud)
    {
        $datosDeSolicitud = self::where('idsolicitud', $iSolicitud)
            ->where('id','!=',$idValidacion)
            ->where('estado_validacion', 0)
            ->get();

        if (is_null($datosDeSolicitud)) {
            throw ExcepcionConsistencia::new(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE)
                ->setContexto(['idsolicitud' => $iSolicitud]);
        }
        return $datosDeSolicitud;
    }

    public static function listSolicitudesCiudadano($idSolicitud)
    {
        $datosDeSolicitud = null;
        $listaSolicitud = array();
        try {
            $data = Solicitud::where('ID_SOLICITUD', $idSolicitud)
                    ->select('ID_PADRE_SOLICITUD','TIPO_VALIDACION_TERCEROS')->first();

            $dataSolicitudes = Solicitud::where('ID_PADRE_SOLICITUD', $data->ID_PADRE_SOLICITUD)
                ->select('ID_SOLICITUD')->get();

            foreach ($dataSolicitudes as $dataSolicitud) {
                $datosDeSolicitud = ValidaSolicitudTercero::getModel()
                    ->where('IDSOLICITUD', $dataSolicitud->ID_SOLICITUD)
                    ->orderBy('id')
                    ->get();
                foreach ($datosDeSolicitud as $solicitud) {
                    $listaSolicitud[$dataSolicitud->ID_SOLICITUD][] = $solicitud;
                }
            }

        } catch (Exception $ex) {
            throw $ex;
        }

        if (empty($listaSolicitud)) {
            return null;
        }

        return $listaSolicitud;
    }

}
