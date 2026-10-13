<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\EstadoNivelCentral
 *
 * @property int $ID_ESTADO_NC
 * @property string|null $NOMBRE_ESTADO
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoNivelCentral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoNivelCentral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoNivelCentral query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoNivelCentral whereIDESTADONC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoNivelCentral whereNOMBREESTADO($value)
 * @mixin Eloquent
 */
class EstadoNivelCentral extends Eloquent
{
    protected $table = 'ab_estado_nc';
    protected $primaryKey = 'ID_ESTADO_NC';
    public $timestamps = false;

    public static function getNombreEstado($id_estado_nc)
    {
        $nombre = self::where('id_estado_nc', $id_estado_nc)
            ->first();
        if ($nombre) {
            return $nombre->nombre_estado;
        }
        return 'No existe estado nivel central';
    }

}
