<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Rechazo
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Rechazo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rechazo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rechazo query()
 * @mixin Eloquent
 */
class Rechazo extends Eloquent
{
    protected $table = 'ab_categoria_rechazo';
    protected $primaryKey = 'ID_CATEGORIA_TIPO_RECHAZO';
    public $timestamps = false;
}
