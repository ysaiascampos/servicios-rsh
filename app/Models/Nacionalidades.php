<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Nacionalidades
 *
 * @property int $NAC_ID
 * @property string|null $NAC_DESCRIPCION
 * @method static \Illuminate\Database\Eloquent\Builder|Nacionalidades newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nacionalidades newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Nacionalidades query()
 * @method static \Illuminate\Database\Eloquent\Builder|Nacionalidades whereNACDESCRIPCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Nacionalidades whereNACID($value)
 * @mixin Eloquent
 */
class Nacionalidades extends Eloquent
{
    protected $table = 'nacionalidades';
    protected $primaryKey = 'NAC_ID';
    public $timestamps = false;
}
