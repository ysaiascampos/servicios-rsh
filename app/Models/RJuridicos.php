<?php

namespace App\Models;

use Eloquent;
/**
 * App\Models\RJuridicos
 *
 * @method static \Illuminate\Database\Eloquent\Builder|RJuridicos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RJuridicos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RJuridicos query()
 * @mixin Eloquent
 */
class RJuridicos extends Eloquent
{
    protected $table = 'r_juridicos';
    protected $primaryKey = 'R_JUR_ID';
    public $timestamps = false;
}
