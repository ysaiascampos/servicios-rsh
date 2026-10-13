<?php

namespace App\Models;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * App\Models\Solicitud
 *
 * @property int $ID_SOLICITUD
 * @property int|null $ID_TIPO_SOLICITUD
 * @property string|null $SOLICITUD
 * @property int|null $ID_USUARIO_CREACION
 * @property string|null $FECHA_CREACION
 * @property int|null $ID_USUARIO_MODIFICACION
 * @property string|null $FECHA_MODIFICACION
 * @property string|null $FECHA_RESOLUCION
 * @property int|null $ID_ESTADO
 * @property int|null $DATO_ACTUAL
 * @property int|null $ID_FUENTE_ACTUAL
 * @property int|null $DATO_NUEVO
 * @property int|null $ID_FUENTE_NUEVA
 * @property int|null $VIGENCIA_DATO
 * @property int|null $ID_PROCESO
 * @property int|null $ID_PADRE_SOLICITUD
 * @property string|null $EMAIL_CIUDADANO
 * @property string|null $FONO_CIUDADANO
 * @property int|null $RUN_CIUDADANO
 * @property int|null $COM_ID
 * @property int|null $ID_USUARIO_RESOLUCION
 * @property string|null $FONO_CIUDADANO2
 * @property int|null $ID_WARNING
 * @property int|null $ID_SUB_ESTADO
 * @property string|null $OBS_SOLICITUD
 * @property int|null $ID_USUARIO_ASIGNADO
 * @property string|null $FECHA_ASIGNACION
 * @property string|null $FECHA_SUB_ESTADO
 * @property int|null $ID_MOTIVO
 * @property int|null $ID_GRUPO_ANTIGUO
 * @property string|null $MD5_EXTRANJERO
 * @property int|null $ID_SUPERVISION
 * @property string|null $COMENTARIO
 * @property string|null $CANAL
 * @property string|null $RESPUESTA
 * @property int|null $NUEVOFOLIO
 * @property string|null $OBSERVACION_RECTIFICACION
 * @property int|null $AVISO_CORREO
 * @property int|null $RUN_INTEGRANTE
 * @property string|null $OBSERVACION_NIVEL_CENTRAL
 * @property int|null $ID_SUB_TIPO_SOLICITUD
 * @property int|null $ID_ESTADO_NC
 * @property int|null $VIGENTE
 * @property int|null $IDENTIDAD_ACREDITADA
 * @property int|null $FECHA_IDENT_ACREDITADA
 * @property int|null $ID_DOC_COMPLEMENTO
 * @property int|null $MIGRACION_COMUNA
 * @property string|null $FECHA_MIGRACION_COMUNA
 * @property int|null $ORIGEN_REGISTRO
 * @property int|null $FIRMA_SIMPLE
 * @property string|null $TOKEN_ACCESO
 * @property float|null $GEO_LATITUD_CREACION Metrica. geolocalización del lugar donde fue creada la solicitud
 * @property float|null $GEO_LONGITUD_CREACION Metrica. geolocalización del lugar donde fue creada la solicitud
 * @property string|null $HORA_INICIO_SOLICITUD Metrica. Hora en la que el usuario inicia la solicitud en el front-end para medición del tiempo que le toma realizar el proceso
 * @property string|null $HORA_TERMINO_SOLICITUD Metrica. Hora en la que se guardó el ultimo cambio en la solicitud
 * @property string|null $FECHA_CREACION_CIUDADANO
 * @method static Builder|Solicitud newModelQuery()
 * @method static Builder|Solicitud newQuery()
 * @method static Builder|Solicitud query()
 * @method static Builder|Solicitud whereAVISOCORREO($value)
 * @method static Builder|Solicitud whereCANAL($value)
 * @method static Builder|Solicitud whereCOMENTARIO($value)
 * @method static Builder|Solicitud whereCOMID($value)
 * @method static Builder|Solicitud whereDATOACTUAL($value)
 * @method static Builder|Solicitud whereDATONUEVO($value)
 * @method static Builder|Solicitud whereEMAILCIUDADANO($value)
 * @method static Builder|Solicitud whereFECHAASIGNACION($value)
 * @method static Builder|Solicitud whereFECHACREACION($value)
 * @method static Builder|Solicitud whereFECHACREACIONCIUDADANO($value)
 * @method static Builder|Solicitud whereFECHAIDENTACREDITADA($value)
 * @method static Builder|Solicitud whereFECHAMIGRACIONCOMUNA($value)
 * @method static Builder|Solicitud whereFECHAMODIFICACION($value)
 * @method static Builder|Solicitud whereFECHARESOLUCION($value)
 * @method static Builder|Solicitud whereFECHASUBESTADO($value)
 * @method static Builder|Solicitud whereFIRMASIMPLE($value)
 * @method static Builder|Solicitud whereFONOCIUDADANO($value)
 * @method static Builder|Solicitud whereFONOCIUDADANO2($value)
 * @method static Builder|Solicitud whereGEOLATITUDCREACION($value)
 * @method static Builder|Solicitud whereGEOLONGITUDCREACION($value)
 * @method static Builder|Solicitud whereHORAINICIOSOLICITUD($value)
 * @method static Builder|Solicitud whereHORATERMINOSOLICITUD($value)
 * @method static Builder|Solicitud whereIDDOCCOMPLEMENTO($value)
 * @method static Builder|Solicitud whereIDENTIDADACREDITADA($value)
 * @method static Builder|Solicitud whereIDESTADO($value)
 * @method static Builder|Solicitud whereIDESTADONC($value)
 * @method static Builder|Solicitud whereIDFUENTEACTUAL($value)
 * @method static Builder|Solicitud whereIDFUENTENUEVA($value)
 * @method static Builder|Solicitud whereIDGRUPOANTIGUO($value)
 * @method static Builder|Solicitud whereIDMOTIVO($value)
 * @method static Builder|Solicitud whereIDPADRESOLICITUD($value)
 * @method static Builder|Solicitud whereIDPROCESO($value)
 * @method static Builder|Solicitud whereIDSOLICITUD($value)
 * @method static Builder|Solicitud whereIDSUBESTADO($value)
 * @method static Builder|Solicitud whereIDSUBTIPOSOLICITUD($value)
 * @method static Builder|Solicitud whereIDSUPERVISION($value)
 * @method static Builder|Solicitud whereIDTIPOSOLICITUD($value)
 * @method static Builder|Solicitud whereIDUSUARIOASIGNADO($value)
 * @method static Builder|Solicitud whereIDUSUARIOCREACION($value)
 * @method static Builder|Solicitud whereIDUSUARIOMODIFICACION($value)
 * @method static Builder|Solicitud whereIDUSUARIORESOLUCION($value)
 * @method static Builder|Solicitud whereIDWARNING($value)
 * @method static Builder|Solicitud whereMD5EXTRANJERO($value)
 * @method static Builder|Solicitud whereMIGRACIONCOMUNA($value)
 * @method static Builder|Solicitud whereNUEVOFOLIO($value)
 * @method static Builder|Solicitud whereOBSERVACIONNIVELCENTRAL($value)
 * @method static Builder|Solicitud whereOBSERVACIONRECTIFICACION($value)
 * @method static Builder|Solicitud whereOBSSOLICITUD($value)
 * @method static Builder|Solicitud whereORIGENREGISTRO($value)
 * @method static Builder|Solicitud whereRESPUESTA($value)
 * @method static Builder|Solicitud whereRUNCIUDADANO($value)
 * @method static Builder|Solicitud whereRUNINTEGRANTE($value)
 * @method static Builder|Solicitud whereSOLICITUD($value)
 * @method static Builder|Solicitud whereTOKENACCESO($value)
 * @method static Builder|Solicitud whereVIGENCIADATO($value)
 * @method static Builder|Solicitud whereVIGENTE($value)
 * @mixin Eloquent
 */
class Solicitud extends Eloquent
{
    protected $table = 'ab_solicitud';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;

    public function TipoSolicitud()
    {
        return $this->hasOne(TipoSolicitud::class, 'ID_TIPO_SOLICITUD', 'ID_TIPO_SOLICITUD');
    }

    public function Ciudadano()
    {
        return $this->hasOne(Personas::class, 'PER_RUN', 'RUN_CIUDADANO');
    }

    public function GrupoFamiliar()
    {
        return $this->hasOne(GrupoFamiliar::class, 'GRUPFAMI_ID', 'DATO_ACTUAL');
    }

    public function Integrante()
    {
        return $this->hasOne(BeneficiarioGrupoFamiliar::class, 'BNFGRUPFAMI_IDSOLICITUD', 'ID_SOLICITUD');
    }

    public function MotivoRectificacion()
    {
        return $this->hasOne(MotivoRectificacion::class, 'ID', 'ID_MOTIVO');
    }

    public function DetalleTasacionVehiculos()
    {
        return $this->hasOne(DetalleTasacionVehiculos::class, 'VEH_ID', 'DATO_NUEVO');
    }

    public function EverSii()
    {
        return $this->hasOne(Sii::class, 'IDSOLICITUD', 'ID_SOLICITUD');
    }

    public function setCanalAttribute($value)
    {
        $this->attributes['CANAL'] = Constante::$canalSaveSegunCanalMinuscula[strtolower($value)];
    }

    public function getRunTitularAttribute()
    {
        //ToDo: Normalizar esta función
        try {
            if (!isset($this->attributes['RUN_TITULAR'])) {
                if($this->attributes['ID_TIPO_SOLICITUD'] == Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE) {
                    $titular = Personas::find($this->attributes['DATO_NUEVO']);
                    $this->attributes['RUN_TITULAR'] = isset($titular->PER_RUN) ? $titular->PER_RUN : null;
                }else{
                    $this->attributes['RUN_TITULAR'] = null;
                }
            }
            return $this->attributes['RUN_TITULAR'];
        } catch (Throwable $ex) {
            Log::error($ex);
            $this->attributes['RUN_TITULAR'] = null;
            return null;
        }
    }

    /**
     * @return Solicitud
     * @throws ExcepcionConsistencia
     */
    public static function findByIdSolicitud($idSolicitud, $tipoSolicitud = null)
    {
        $datosDeSolicitud = self::where('id_solicitud', $idSolicitud)
            ->whereIn('vigente', [
                Constante::VIGENTE_SOLICITUD_1_VIGENTE,
                Constante::VIGENTE_SOLICITUD_3_SOLICITUD_BORRADOR
            ]);
        if (!is_null($tipoSolicitud)) {
            $datosDeSolicitud->where('id_tipo_solicitud', $tipoSolicitud);
        }
        $datosDeSolicitud = $datosDeSolicitud->first();

        if (empty($datosDeSolicitud)) {
            throw ExcepcionConsistencia::new(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE)
                ->setContexto(['idSolicitud' => $idSolicitud, 'tipoSolicitud' => $tipoSolicitud]);
        }
        return $datosDeSolicitud;
    }

    /**
     * @return Solicitud[]|Collection
     * @throws ExcepcionConsistencia
     */
    public static function findByIdPadreSolicitud($idPadreSolicitud, $tipoSolicitud = null)
    {
        $datosDeSolicitud = self::where('id_padre_solicitud', $idPadreSolicitud)
            ->whereIn('vigente', [
                Constante::VIGENTE_SOLICITUD_1_VIGENTE,
                Constante::VIGENTE_SOLICITUD_3_SOLICITUD_BORRADOR
            ]);
        if (!is_null($tipoSolicitud)) {
            if (is_array($tipoSolicitud)) {
                $datosDeSolicitud->whereIn('id_tipo_solicitud', $tipoSolicitud);
            } else {
                $datosDeSolicitud->where('id_tipo_solicitud', $tipoSolicitud);
            }
        }
        $datosDeSolicitud = $datosDeSolicitud->get();

        if (is_null($datosDeSolicitud)) {
            throw ExcepcionConsistencia::new(Constante::MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA,
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE)
                ->setContexto(['idPadreSolicitud' => $idPadreSolicitud, 'tipoSolicitud' => $tipoSolicitud]);
        }
        return $datosDeSolicitud;
    }
}
