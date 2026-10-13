<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Propiedad
 *
 * @property int $SII_PROP_DET_ID
 * @property int|null $VIGENTE
 * @property string|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int $FECHAFUENTE
 * @property int $RUT
 * @property int|null $AVALUO_TOTAL
 * @property int|null $FECHA_CARGA
 * @property string $ROL
 * @property int $PERIODO
 * @property int|null $SUMA_DE_AVALUOS
 * @property int|null $CANT_PROP
 * @property int|null $N_FOJAS
 * @property string|null $C_VERIFICACION
 * @property int|null $N_INHABITA
 * @property string|null $F_EMISION
 * @property string|null $F_CERT_INHABIT
 * @property string|null $N_REMATE
 * @property string|null $F_REMATE
 * @property string|null $ROL_COMUNA
 * @property string|null $ROL_MANZANA
 * @property string|null $ROL_PREDIO
 * @property string|null $DIRECCION
 * @property int|null $COD_COMUNA
 * @property int|null $C_TCOMPLEMENTO_BIENRAIZ
 * @property int|null $RUT_INTEGRANTE
 * @property string|null $DV_INTEGRANTE
 * @property string|null $PORCENTAJE
 * @property string|null $DIRECCION_OFUSCADA
 * @property string|null $ROL_OFUSCADO
 * @property int|null $PERIODO_READ
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad query()
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereAVALUOTOTAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereCANTPROP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereCODCOMUNA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereCTCOMPLEMENTOBIENRAIZ($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereCVERIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereDIRECCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereDIRECCIONOFUSCADA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereDVINTEGRANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereFCERTINHABIT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereFECHACARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereFEMISION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereFREMATE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereNFOJAS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereNINHABITA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereNREMATE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad wherePERIODO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad wherePORCENTAJE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereROL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereROLCOMUNA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereROLMANZANA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereROLOFUSCADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereROLPREDIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereRUT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereRUTINTEGRANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereSIIPROPDETID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereSUMADEAVALUOS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Propiedad whereVIGENTE($value)
 * @mixin Eloquent
 */
class Propiedad extends Eloquent
{
    protected $table = 'ever_sii_propiedades_det';
    protected $primaryKey = 'sii_prop_det_id';
    public $timestamps = false;
}
