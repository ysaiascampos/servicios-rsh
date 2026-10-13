<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\OrigenRegistro
 *
 * @property int $ID_ORIGEN_REGISTRO
 * @property string|null $NOMBRE_ORIGEN_REGISTRO
 * @method static \Illuminate\Database\Eloquent\Builder|OrigenRegistro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrigenRegistro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrigenRegistro query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrigenRegistro whereIDORIGENREGISTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrigenRegistro whereNOMBREORIGENREGISTRO($value)
 * @mixin Eloquent
 */
class OrigenRegistro extends Eloquent
{
    protected $table = 'AB_ORIGEN_REGISTRO';
    protected $primaryKey = 'ID_ORIGEN_REGISTRO';
    public $timestamps = false;

}
