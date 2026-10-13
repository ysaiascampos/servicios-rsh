<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Agrupacion
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Agrupacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agrupacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agrupacion query()
 * @mixin Eloquent
 */
class Agrupacion extends Eloquent
{
    protected $table = 'vas_ah'; /* vista */
    protected $primaryKey = 'ID_AH';
    public $timestamps = false;
}
