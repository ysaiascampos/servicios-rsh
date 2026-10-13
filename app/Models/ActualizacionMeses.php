<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ActualizacionMeses
 *
 * @property int|null $PERIODO_RSH
 * @property int|null $MES_1_ACTUALIZA
 * @property int|null $MES_2_ACTUALIZA
 * @property int|null $MES_3_ACTUALIZA
 * @property int|null $MES_4_ACTUALIZA
 * @property int|null $MES_5_ACTUALIZA
 * @property int|null $MES_6_ACTUALIZA
 * @property int|null $MES_7_ACTUALIZA
 * @property int|null $MES_8_ACTUALIZA
 * @property int|null $MES_9_ACTUALIZA
 * @property int|null $MES_10_ACTUALIZA
 * @property int|null $MES_11_ACTUALIZA
 * @property int|null $MES_12_ACTUALIZA
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES10ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES11ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES12ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES1ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES2ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES3ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES4ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES5ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES6ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES7ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES8ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses whereMES9ACTUALIZA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionMeses wherePERIODORSH($value)
 * @mixin Eloquent
 */
class ActualizacionMeses extends Eloquent
{
    protected $table = 'tbl_actualizacion_meses';
    protected $primaryKey = 'PERIODO_RSH';
}
