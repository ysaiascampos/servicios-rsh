<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\PresMedic
 *
 * @property int $ID_PRESENTACION
 * @property int $ID_MEDICAMENTO
 * @property string|null $DESC_PRESENTACION
 * @property string|null $ACTIVO
 * @method static \Illuminate\Database\Eloquent\Builder|PresMedic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PresMedic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PresMedic query()
 * @method static \Illuminate\Database\Eloquent\Builder|PresMedic whereACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PresMedic whereDESCPRESENTACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PresMedic whereIDMEDICAMENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PresMedic whereIDPRESENTACION($value)
 * @mixin Eloquent
 */
class PresMedic extends Eloquent
{
    protected $table = 'ab_pres_medic';
    protected $primaryKey = 'ID_PRESENTACION';
}
