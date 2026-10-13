<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Calendario
 *
 * @property int $ID_FECHA
 * @property string|null $FECHA_INICIO
 * @property string|null $FECHA_FIN
 * @property string|null $SEMANA
 * @method static \Illuminate\Database\Eloquent\Builder|Calendario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendario query()
 * @method static \Illuminate\Database\Eloquent\Builder|Calendario whereFECHAFIN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendario whereFECHAINICIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendario whereIDFECHA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calendario whereSEMANA($value)
 * @mixin Eloquent
 */
class Calendario extends Eloquent
{
    protected $table = 'ab_calendario';
    protected $primaryKey = 'ID_FECHA';
}
