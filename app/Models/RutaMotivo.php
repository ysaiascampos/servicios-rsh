<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\RutaMotivo
 *
 * @property int $ID_MOTIVO
 * @property string $MOTIVO
 * @method static \Illuminate\Database\Eloquent\Builder|RutaMotivo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaMotivo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaMotivo query()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaMotivo whereIDMOTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaMotivo whereMOTIVO($value)
 * @mixin Eloquent
 */
class RutaMotivo extends Eloquent
{
    protected $table = 'ab_ruta_motivo';
    protected $primaryKey = 'ID_MOTIVO';
    public $timestamps = false;
}
