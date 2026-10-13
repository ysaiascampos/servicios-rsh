<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Institucion
 *
 * @property int $ID_INSTITUCION
 * @property int $ID_ORGANIZACION
 * @property string|null $NOMBRE_INSTITUCION
 * @property string|null $SIGLA__INSTITUCION
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion query()
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion whereIDINSTITUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion whereIDORGANIZACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion whereNOMBREINSTITUCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Institucion whereSIGLAINSTITUCION($value)
 * @mixin Eloquent
 */
class Institucion extends Eloquent
{
    protected $table = 'ab_institucion';
    protected $primaryKey = 'ID_INSTITUCION';
    public $timestamps = false;
}
