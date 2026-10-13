<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\UsuarioComuna
 *
 * @property int|null $RUT_USUARIO
 * @property int|null $COM_ID
 * @property int $ID
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsuarioComuna newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsuarioComuna newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UsuarioComuna query()
 * @mixin \Eloquent
 */
class UsuarioComuna extends Eloquent
{
    protected $table = 'ab_usuario_comuna';
    protected $primaryKey = 'COM_ID';
    public $timestamps = false;
}
