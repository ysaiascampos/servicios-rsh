<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\CondicionSalud
 *
 * @property int $ID
 * @property string $DESCRIPCION
 * @property int|null $ACTIVO
 * @method static \Illuminate\Database\Eloquent\Builder|CondicionSalud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CondicionSalud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CondicionSalud query()
 * @method static \Illuminate\Database\Eloquent\Builder|CondicionSalud whereACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CondicionSalud whereDESCRIPCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CondicionSalud whereID($value)
 * @mixin Eloquent
 */
class CondicionSalud extends Eloquent
{
    protected $table = 'ab_condicion_salud';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
