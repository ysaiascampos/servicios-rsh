<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\EstadoUsuario
 *
 * @property int $ID_ESTADO
 * @property string|null $NOMBRE_ESTADO
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoUsuario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoUsuario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoUsuario query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoUsuario whereIDESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoUsuario whereNOMBREESTADO($value)
 * @mixin Eloquent
 */
class EstadoUsuario extends Eloquent
{
    protected $table = 'ab_estado_usuario';
    protected $primaryKey = 'ID_ESTADO';
    public $timestamps = false;
}
