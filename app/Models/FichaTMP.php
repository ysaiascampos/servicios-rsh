<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\FichaTMP
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FichaTMP newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaTMP newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaTMP query()
 * @mixin Eloquent
 */
class FichaTMP extends Eloquent
{
    protected $table = 'ficha_tmp';
    protected $primaryKey = 'F_RUN';
    public $timestamps = false;
}
