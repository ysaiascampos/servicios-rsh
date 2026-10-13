<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Perfil
 *
 * @property int $ID_PERFIL
 * @property int|null $ID_NIVEL
 * @property string|null $NOMBRE_PERFIL
 * @property int|null $ORDEN_DESPLIEGUE_PERFIL
 * @property int|null $ESTADO
 * @property int|null $PERFIL_GUIA
 * @method static \Illuminate\Database\Eloquent\Builder|Perfil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Perfil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Perfil query()
 * @method static \Illuminate\Database\Eloquent\Builder|Perfil whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perfil whereIDNIVEL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perfil whereIDPERFIL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perfil whereNOMBREPERFIL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perfil whereORDENDESPLIEGUEPERFIL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perfil wherePERFILGUIA($value)
 * @mixin Eloquent
 */
class Perfil extends Eloquent
{
    protected $table = 'ab_perfil';
    protected $primaryKey = 'ID_PERFIL';
    public $timestamps = false;

    /* Lista perfiles válidos
     * queda fuera el estado = 2, que es un perfil sólo interno
     */
    public static function listarPerfilesValidos()
    {
        return Perfil::where('estado', Constante::ESTADO_1_ACTIVO)
            ->orderBy('orden_despliegue_perfil')
            ->lists('nombre_perfil', 'id_perfil');
    }

}
