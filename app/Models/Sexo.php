<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Sexo
 *
 * @property int $SEX_ID
 * @property string|null $SEX_DESCRIPCION
 * @method static \Illuminate\Database\Eloquent\Builder|Sexo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sexo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sexo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sexo whereSEXDESCRIPCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sexo whereSEXID($value)
 * @mixin Eloquent
 */
class Sexo extends Eloquent
{
    protected $table = 'sexos';
    protected $primaryKey = 'sex_id';
    public $timestamps = false;
}
