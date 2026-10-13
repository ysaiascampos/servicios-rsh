<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ComplementoColegio
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoColegio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoColegio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoColegio query()
 * @mixin Eloquent
 */
class ComplementoColegio extends Eloquent
{
    protected $table = 'ab_complemento_colegio';
    protected $primaryKey = 'complemento_id';
    public $timestamps = false;
}
