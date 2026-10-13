<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\VWDetalleTasacionVehiculos
 *
 * @property int|null $VEH_DET_VIGENTE
 * @property int|null $VEH_DET_IDSOLICITUD
 * @property int|null $VEH_DET_CALCULOACTIVO
 * @property string|null $VEH_DET_FECHAEXPIRACION
 * @property int|null $FUENTE
 * @property int|null $FECHAFUENTE
 * @property int|null $VEH_DET_COUNT_COMERCIAL
 * @property int|null $VEH_DET_ANNIO
 * @property int|null $VEH_DET_PERIODO
 * @property int|null $VEH_ID
 * @property string|null $VEH_DET_DV
 * @property int|null $VEH_DET_RUN
 * @property string|null $VEH_DET_MARCA
 * @property string|null $VEH_DET_MODELO
 * @property int|null $VEH_DET_ANNIO_FABRICACION
 * @property int|null $VEH_DET_TASACION
 * @property string|null $VEH_DET_TIPO_SERVICIO
 * @property int|null $VEH_DET_SUMA_TASACION
 * @property int|null $VEH_DET_CANT_AUTOS_CON_TAS
 * @property string|null $VEH_DET_PATENTE
 * @property int|null $VEH_ID_VEHICULO
 * @property int|null $C_TRECTIFICACION_VEHICULO
 * @property int|null $C_TACTUALIZACION_VEHICULO
 * @property int|null $VEH_VENTA_PERIODO
 * @property int|null $PERIODO_READ
 * @property string|null $VEH_DET_PATENTE_OFUSCADO
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos query()
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereCTACTUALIZACIONVEHICULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereCTRECTIFICACIONVEHICULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETANNIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETANNIOFABRICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETCANTAUTOSCONTAS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETCOUNTCOMERCIAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETDV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETMARCA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETMODELO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETPATENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETPATENTEOFUSCADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETPERIODO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETRUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETSUMATASACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETTASACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETTIPOSERVICIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHDETVIGENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHIDVEHICULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VWDetalleTasacionVehiculos whereVEHVENTAPERIODO($value)
 * @mixin Eloquent
 */
class VWDetalleTasacionVehiculos extends Eloquent
{
    protected $table = 'vw_det_tasacion_veh';
    protected $primaryKey = 'VEH_ID';
    public $timestamps = false;
}
