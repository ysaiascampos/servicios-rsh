<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\RutaEstado
 *
 * @property int $ID_ESTADO
 * @property string $ESTADO
 * @method static \Illuminate\Database\Eloquent\Builder|RutaEstado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaEstado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaEstado query()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaEstado whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaEstado whereIDESTADO($value)
 * @mixin Eloquent
 */
class RutaEstado extends Eloquent
{
    protected $table = 'ab_ruta_estado';
    protected $primaryKey = 'ID_ESTADO';
    public $timestamps = false;
}
