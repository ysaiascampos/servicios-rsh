<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\TipoRectificacionVehiculo
 *
 * @property int $C_TRECTIFICACION_VEHICULO
 * @property string|null $N_TRECTIFICACION_VEHICULO
 * @method static \Illuminate\Database\Eloquent\Builder|TipoRectificacionVehiculo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoRectificacionVehiculo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoRectificacionVehiculo query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoRectificacionVehiculo whereCTRECTIFICACIONVEHICULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoRectificacionVehiculo whereNTRECTIFICACIONVEHICULO($value)
 * @mixin Eloquent
 */
class TipoRectificacionVehiculo extends Eloquent
{
    protected $table = 'trectificacion_vehiculo';
    protected $primaryKey = 'c_trectificacion_vehiculo';
    public $timestamps = false;
}
