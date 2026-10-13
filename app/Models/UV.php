<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\UV
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UV newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UV newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UV query()
 * @mixin \Eloquent
 */
class UV extends Eloquent
{
    protected $table = 'vas_unidad_vecinal'; /* vista */
    protected $primaryKey = 'UV_CARTO';
    public $timestamps = false;
}
