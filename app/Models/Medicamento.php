<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Medicamento
 *
 * @property int $ID_MEDICAMENTO
 * @property string|null $NOMBRE_MEDICAMENTO
 * @property string|null $ACTIVO
 * @method static \Illuminate\Database\Eloquent\Builder|Medicamento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Medicamento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Medicamento query()
 * @method static \Illuminate\Database\Eloquent\Builder|Medicamento whereACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medicamento whereIDMEDICAMENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medicamento whereNOMBREMEDICAMENTO($value)
 * @mixin Eloquent
 */
class Medicamento extends Eloquent
{
    protected $table = 'ab_medicamento';
    protected $primaryKey = 'MEDICAMENTO_ID';
}
