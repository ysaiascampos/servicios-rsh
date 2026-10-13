<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\PerfilNC
 *
 * @property int $ID_PERFIL_NC
 * @property string $NOMBRE_PERFIL_NC
 * @property string|null $DETALLE_PERFIL_NC
 * @property int $RUT_USUARIO
 * @method static \Illuminate\Database\Eloquent\Builder|PerfilNC newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerfilNC newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerfilNC query()
 * @method static \Illuminate\Database\Eloquent\Builder|PerfilNC whereDETALLEPERFILNC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerfilNC whereIDPERFILNC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerfilNC whereNOMBREPERFILNC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PerfilNC whereRUTUSUARIO($value)
 * @mixin Eloquent
 */
class PerfilNC extends Eloquent
{
    protected $table = 'ab_usuario_perfiles_nc';
    protected $primaryKey = 'RUT_USUARIO';
    public $timestamps = false;
}
