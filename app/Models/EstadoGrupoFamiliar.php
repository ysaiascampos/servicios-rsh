<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\EstadoGrupoFamiliar
 *
 * @property int $ID
 * @property string|null $NOMBRE
 * @property int|null $ESTADO indica si fue eliminado 1=activo 0=eliminado
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoGrupoFamiliar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoGrupoFamiliar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoGrupoFamiliar query()
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoGrupoFamiliar whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoGrupoFamiliar whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EstadoGrupoFamiliar whereNOMBRE($value)
 * @mixin Eloquent
 */
class EstadoGrupoFamiliar extends Eloquent
{
    protected $table = 'ab_estado_grupo_familiar';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
