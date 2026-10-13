<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\LocacionUnidadVecinal
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionUnidadVecinal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionUnidadVecinal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionUnidadVecinal query()
 * @mixin Eloquent
 */
class LocacionUnidadVecinal extends Eloquent
{
    protected $table = 'vas_unidad_vecinal';
    public $timestamps = false;
}
