<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\EstadoSupervision
 *
 * @property int $ID_ESTADO_SUPERVISION
 * @property string|null $NOMBRE_ESTADO_SUPERVISION
 * @property int|null $VIGENTE
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSupervision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSupervision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSupervision query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSupervision whereIDESTADOSUPERVISION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSupervision whereNOMBREESTADOSUPERVISION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoSupervision whereVIGENTE($value)
 * @mixin Eloquent
 */
class EstadoSupervision extends Eloquent
{
    protected $table = 'ab_estado_supervision';
    protected $primaryKey = 'ID_ESTADO_SUPERVISION';
    public $timestamps = false;
}
