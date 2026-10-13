<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\RunesInspeccionFunfa
 *
 * @property int|null $RUN
 * @property string|null $DV
 * @property string|null $COMUNA
 * @property string|null $INTEGRANTE_GRUPO
 * @method static \Illuminate\Database\Eloquent\Builder|RunesInspeccionFunfa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RunesInspeccionFunfa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RunesInspeccionFunfa query()
 * @method static \Illuminate\Database\Eloquent\Builder|RunesInspeccionFunfa whereCOMUNA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RunesInspeccionFunfa whereDV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RunesInspeccionFunfa whereINTEGRANTEGRUPO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RunesInspeccionFunfa whereRUN($value)
 * @mixin Eloquent
 */
class RunesInspeccionFunfa extends Eloquent
{
    protected $table = 'runes_inspeccion_funfa';
    public $timestamps = false;

}
