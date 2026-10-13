<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\TasacionVehiculos
 *
 * @property int $VEH_ID
 * @property int $VEH_ANNIO
 * @property int $VEH_PERIODO
 * @property int $VEH_RUN
 * @property string $VEH_DV
 * @property string|null $VEH_TIPO_SERVICIO
 * @property int|null $VEH_SUMA_TASACION
 * @property int|null $VEH_SUMA_TAS_SIN_MAX_COM
 * @property int|null $VEH_CANT_AUTOS_CON_TAS
 * @property int|null $VEH_COUNT_COMERCIAL
 * @property int|null $VEH_MAX_VALOR_COM
 * @property int|null $VEH_MAX_VALOR_PART
 * @property int|null $VEH_VIGENTE
 * @property int|null $VEH_IDSOLICITUD
 * @property string|null $VEH_FECHAEXPIRACION
 * @property int|null $VEH_CALCULOACTIVO
 * @property int $FUENTE
 * @property int $FECHAFUENTE
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos query()
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHANNIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHCANTAUTOSCONTAS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHCOUNTCOMERCIAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHDV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHMAXVALORCOM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHMAXVALORPART($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHPERIODO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHRUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHSUMATASACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHSUMATASSINMAXCOM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHTIPOSERVICIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TasacionVehiculos whereVEHVIGENTE($value)
 * @mixin Eloquent
 */
class TasacionVehiculos extends Eloquent
{
    protected $table = 'tasacion_vehiculos';
    protected $primaryKey = 'VEH_ID';
    public $timestamps = false;

    public function Detalle()
    {
        return $this->hasMany('DetalleTasacionVehiculos', 'veh_id', 'veh_id');
    }

}

