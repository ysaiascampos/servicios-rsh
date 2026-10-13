<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\SolicitudLocalizacion
 *
 * @property int $ID_SEGUIMIENTO
 * @property int|null $ID_SOLICITUD
 * @property int|null $ID_TIPO_LOC
 * @property string|null $FEC_INGRESO
 * @property int|null $TIPO_SOLICITUD
 * @property string|null $TIPO_LOC
 * @property int|null $COD_LOC_VIGENTE
 * @property string|null $NOM_LOC_VIGENTE
 * @property int|null $COD_COMUNA
 * @property string|null $NOM_COMUNA
 * @property int|null $COD_REGION
 * @property string|null $OBS_EC
 * @property int|null $COD_LOC_NO_VIGENTE
 * @property string|null $NOM_LOC_NO_VIGENTE
 * @property string|null $OBS_UGIT
 * @property string|null $RESPUESTA
 * @property int|null $COD_FINAL
 * @property string|null $NOM_LOC_VIGENTE_FINAL
 * @property string|null $FEC_MODIFICACION
 * @property string|null $ORIGEN_SOLICITUD
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereCODCOMUNA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereCODFINAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereCODLOCNOVIGENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereCODLOCVIGENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereCODREGION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereFECINGRESO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereFECMODIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereIDSEGUIMIENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereIDTIPOLOC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereNOMCOMUNA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereNOMLOCNOVIGENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereNOMLOCVIGENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereNOMLOCVIGENTEFINAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereOBSEC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereOBSUGIT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereORIGENSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereRESPUESTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereTIPOLOC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudLocalizacion whereTIPOSOLICITUD($value)
 * @mixin Eloquent
 */
class SolicitudLocalizacion extends Eloquent
{
    protected $table = 'ab_solicitud_localizacion'; /* Solicitud VIA-AH-CP */
    protected $primaryKey = 'ID_SEGUIMIENTO';
    public $timestamps = false;
}
