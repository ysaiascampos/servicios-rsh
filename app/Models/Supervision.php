<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Supervision
 *
 * @property int $ID_SUPERVISION
 * @property string $MOTIVO
 * @property string|null $TIPO
 * @property int|null $RUT_USUARIO
 * @property string|null $OBSERVACIONES
 * @property string|null $RESPUESTA
 * @property int|null $RUN_SOLICITANTE
 * @property int|null $PERFIL_SOLICITANTE
 * @property int|null $COD_REGION_SOLICITANTE
 * @property int|null $COD_COMUNA_SOLICITANTE
 * @property int|null $ESTADO_SUP
 * @property int|null $FOLIO
 * @property int|null $RUN_A_SUP
 * @property int|null $COD_REGION_A_SUP
 * @property int|null $COD_COMUNA_A_SUP
 * @property string|null $FEC_CREACION
 * @property string|null $FEC_MODIFICACION
 * @property string|null $FEC_EVALUACION_ADMISIBILIDAD
 * @property string|null $FEC_ENVIO_NOTIFICACION
 * @property int|null $ID_SUPERVISION_RESPONSABLE
 * @property int|null $DOC_IDSUPERVISION
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereCODCOMUNAASUP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereCODCOMUNASOLICITANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereCODREGIONASUP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereCODREGIONSOLICITANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereDOCIDSUPERVISION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereESTADOSUP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereFECCREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereFECENVIONOTIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereFECEVALUACIONADMISIBILIDAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereFECMODIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereFOLIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereIDSUPERVISION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereIDSUPERVISIONRESPONSABLE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereMOTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereOBSERVACIONES($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision wherePERFILSOLICITANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereRESPUESTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereRUNASUP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereRUNSOLICITANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereRUTUSUARIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supervision whereTIPO($value)
 * @mixin Eloquent
 */
class Supervision extends Eloquent
{
    protected $table = 'ab_supervision'; /* vista */
    protected $primaryKey = 'id_supervision';
    public $timestamps = false;

    public function SupervisionCategoria()
    {
        return $this->hasOne('SupervisionCategoria', 'id_supervision', 'id_supervision');
    }

    public function SupervisionIntegrante()
    {
        return $this->hasMany('SupervisionIntegrante', 'id_supervision', 'id_supervision');
    }

    public function SupervisionAdjunto()
    {
        return $this->hasOne('SupervisionAdjuntos', 'doc_idsupervision', 'id_supervision');
    }

    public function SupervisionResponsable()
    {
        return $this->hasOne('SupervisionResponsable', 'id_supervision_responsable', 'id_supervision_responsable');
    }

}
