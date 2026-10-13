<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Vehiculo
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehiculo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehiculo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Vehiculo query()
 * @mixin \Eloquent
 */
class Vehiculo extends Eloquent
{
    protected $table = 'vehiculos';
    protected $primaryKey = 'veh_id';
    public $timestamps = false;
}
