<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\SolicitudHistorial
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
 * @property int|null $IDENTIDAD_ACREDITADA
 * @property int|null $FECHA_IDENT_ACREDITADA
 * @property int|null $RUN_INTEGRANTE
 * @property string|null $OBSERVACION_NIVEL_CENTRAL
 * @property int|null $ID_SUB_TIPO_SOLICITUD
 * @property int|null $ID_ESTADO_NC
 * @property int|null $VIGENTE
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
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial query()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereAVISOCORREO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereCANAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereCOMENTARIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereCOMID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereDATOACTUAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereDATONUEVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereEMAILCIUDADANO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereFECHAASIGNACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereFECHACREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereFECHAIDENTACREDITADA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereFECHAMIGRACIONCOMUNA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereFECHAMODIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereFECHARESOLUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereFECHASUBESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereFIRMASIMPLE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereFONOCIUDADANO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereFONOCIUDADANO2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereGEOLATITUDCREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereGEOLONGITUDCREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereHORAINICIOSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereHORATERMINOSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDDOCCOMPLEMENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDENTIDADACREDITADA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDESTADONC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDFUENTEACTUAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDFUENTENUEVA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDGRUPOANTIGUO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDMOTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDPADRESOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDPROCESO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDSUBESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDSUBTIPOSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDSUPERVISION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDTIPOSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDUSUARIOASIGNADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDUSUARIOCREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDUSUARIOMODIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDUSUARIORESOLUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereIDWARNING($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereMD5EXTRANJERO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereMIGRACIONCOMUNA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereNUEVOFOLIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereOBSERVACIONNIVELCENTRAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereOBSERVACIONRECTIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereOBSSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereORIGENREGISTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereRESPUESTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereRUNCIUDADANO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereRUNINTEGRANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereTOKENACCESO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereVIGENCIADATO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudHistorial whereVIGENTE($value)
 * @mixin Eloquent
 */
class SolicitudHistorial extends Eloquent
{
    protected $table = 'ab_solicitud_historial';
    public $timestamps = false;

    public function TipoSolicitud()
    {
        return $this->hasOne('TipoSolicitud', 'id_tipo_solicitud', 'id_tipo_solicitud');
    }

    public function Ciudadano()
    {
        return $this->hasOne('Personas', 'per_run', 'run_ciudadano');
    }

    public function GrupoFamiliar()
    {
        return $this->hasOne('GrupoFamiliar', 'grupfam_id', 'dato_actual');
    }

    public function Integrante()
    {
        return $this->hasOne('BeneficiarioGrupoFamiliar', 'bnfgrupfami_idsolicitud', 'id_solicitud');
    }

}
