<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ComplementoBienRaiz
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoBienRaiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoBienRaiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoBienRaiz query()
 * @mixin Eloquent
 */
class ComplementoBienRaiz extends Eloquent
{
    protected $table = 'complemento_bienraiz'; /* vista */
    protected $primaryKey = 'COMPLEMENTO_ID';
    public $timestamps = false;
}
