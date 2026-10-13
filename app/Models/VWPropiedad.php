<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;

/**
 * App\Models\VWPropiedad
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
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad query()
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereAVALUOTOTAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereCANTPROP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereCODCOMUNA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereCTCOMPLEMENTOBIENRAIZ($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereCVERIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereDIRECCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereDIRECCIONOFUSCADA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereDVINTEGRANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereFCERTINHABIT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereFECHACARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereFEMISION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereFREMATE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereNFOJAS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereNINHABITA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereNREMATE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad wherePERIODO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad wherePORCENTAJE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereROL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereROLCOMUNA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereROLMANZANA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereROLOFUSCADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereROLPREDIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereRUT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereRUTINTEGRANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereSIIPROPDETID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereSUMADEAVALUOS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWPropiedad whereVIGENTE($value)
 * @mixin Eloquent
 */
class VWPropiedad extends Eloquent
{
    protected $table = 'vw_ever_sii_prop_det';
    protected $primaryKey = 'sii_prop_det_id';
    public $timestamps = false;
}
