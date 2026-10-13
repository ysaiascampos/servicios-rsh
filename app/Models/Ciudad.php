<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Ciudad
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Ciudad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ciudad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ciudad query()
 * @mixin Eloquent
 */
class Ciudad extends Eloquent
{
    protected $table = 'vas_localidad'; /* vista */
    protected $primaryKey = 'ID_LOCALIDAD';
    public $timestamps = false;
}
