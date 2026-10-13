<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\AlimentoEspecial
 *
 * @property int $ID_ALIMENTO
 * @property string|null $DESC_ALIMENTO
 * @property string|null $ACTIVO
 * @property string|null $CLASIFICACION
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentoEspecial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentoEspecial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentoEspecial query()
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentoEspecial whereACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentoEspecial whereCLASIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentoEspecial whereDESCALIMENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlimentoEspecial whereIDALIMENTO($value)
 * @mixin Eloquent
 */
class AlimentoEspecial extends Eloquent
{
    protected $table = 'ab_alimento_especial';
    protected $primaryKey = 'ID_ALIMENTO';
}
