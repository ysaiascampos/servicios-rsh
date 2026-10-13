<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\VW_DIRECTEMAR
 *
 * @method static \Illuminate\Database\Eloquent\Builder|VW_DIRECTEMAR newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VW_DIRECTEMAR newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VW_DIRECTEMAR query()
 * @mixin Eloquent
 */
class VW_DIRECTEMAR extends Eloquent
{
    protected $table = 'vw_directemar';
    protected $primaryKey = 'RUN';
    public $timestamps = false;
}
