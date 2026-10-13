<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Rol
 *
 * @property int $ID_ROL
 * @property string|null $NOMBRE_ROL
 * @property int|null $ORDEN_DESPLIEGUE_ROL
 * @property int|null $ESTADO
 * @property string|null $ACCION
 * @method static \Illuminate\Database\Eloquent\Builder|Rol newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rol newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rol query()
 * @method static \Illuminate\Database\Eloquent\Builder|Rol whereACCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rol whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rol whereIDROL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rol whereNOMBREROL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rol whereORDENDESPLIEGUEROL($value)
 * @mixin Eloquent
 */
class Rol extends Eloquent
{
    protected $table = 'ab_rol';
    protected $primaryKey = 'ID_ROL';
    public $timestamps = false;
}
