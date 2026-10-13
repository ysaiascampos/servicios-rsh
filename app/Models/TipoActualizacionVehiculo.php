<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\TipoActualizacionVehiculo
 *
 * @property int $C_TACTUALIZACION_VEHICULO
 * @property string|null $N_TACTUALIZACION_VEHICULO
 * @method static \Illuminate\Database\Eloquent\Builder|TipoActualizacionVehiculo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoActualizacionVehiculo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoActualizacionVehiculo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoActualizacionVehiculo whereCTACTUALIZACIONVEHICULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoActualizacionVehiculo whereNTACTUALIZACIONVEHICULO($value)
 * @mixin Eloquent
 */
class TipoActualizacionVehiculo extends Eloquent
{
    protected $table = 'tactualizacion_vehiculo';
    protected $primaryKey = 'C_TACTUALIZACION_VEHICULO';
    public $timestamps = false;
}
