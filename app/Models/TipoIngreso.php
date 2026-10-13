<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\TipoIngreso
 *
 * @property int $ID_TIPO_INGRESO
 * @property string|null $TIPO_INGRESO
 * @property string|null $ESTADO
 * @method static \Illuminate\Database\Eloquent\Builder|TipoIngreso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoIngreso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoIngreso query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoIngreso whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoIngreso whereIDTIPOINGRESO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoIngreso whereTIPOINGRESO($value)
 * @mixin Eloquent
 */
class TipoIngreso extends Eloquent
{
    protected $table = 'ab_tipo_ingreso';
    protected $primaryKey = 'ID_TIPO_INGRESO';
    public $timestamps = false;
}
