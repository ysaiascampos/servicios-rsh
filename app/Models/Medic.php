<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Medic
 *
 * @property int $ID_MEDICAMENTO
 * @property string|null $NOMBRE_MEDICAMENTO
 * @property string|null $ACTIVO
 * @method static \Illuminate\Database\Eloquent\Builder|Medic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Medic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Medic query()
 * @method static \Illuminate\Database\Eloquent\Builder|Medic whereACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medic whereIDMEDICAMENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Medic whereNOMBREMEDICAMENTO($value)
 * @mixin Eloquent
 */
class Medic extends Eloquent
{
    protected $table = 'ab_medic';
    protected $primaryKey = 'ID_MEDICAMENTO';
}
