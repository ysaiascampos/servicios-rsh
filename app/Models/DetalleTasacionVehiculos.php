<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\DetalleTasacionVehiculos
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
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereCTACTUALIZACIONVEHICULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereCTRECTIFICACIONVEHICULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETANNIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETANNIOFABRICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETCANTAUTOSCONTAS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETCOUNTCOMERCIAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETDV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETMARCA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETMODELO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETPATENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETPATENTEOFUSCADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETPERIODO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETRUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETSUMATASACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETTASACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETTIPOSERVICIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHDETVIGENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHIDVEHICULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetalleTasacionVehiculos whereVEHVENTAPERIODO($value)
 * @mixin Eloquent
 */
class DetalleTasacionVehiculos extends Eloquent
{
    protected $table = 'ever_det_tasacion_vehiculos';
    protected $primaryKey = 'VEH_ID';
    public $timestamps = false;
}
