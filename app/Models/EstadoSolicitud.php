<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\EstadoSolicitud
 *
 * @property int $ID_ESTADO
 * @property string|null $NOMBRE_ESTADO
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitud query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitud whereIDESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSolicitud whereNOMBREESTADO($value)
 * @mixin Eloquent
 */
class EstadoSolicitud extends Eloquent
{
    protected $table = 'ab_estado_solicitud';
    protected $primaryKey = 'ID_ESTADO';
    public $timestamps = false;

    public static function getNombreSolicitud($solicitud)
    {
        $nombre = self::where('id_estado', $solicitud)
            ->first();
        if ($nombre) {
            return $nombre->NOMBRE_ESTADO;
        }
        return 'No existe solicitud';
    }

}
