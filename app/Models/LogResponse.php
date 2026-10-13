<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\LogResponse
 *
 * @property int $ID
 * @property string|null $FECHA
 * @property string|null $METODO
 * @property string|null $URL
 * @property string|null $PETICION
 * @property string|null $RESPUESTA
 * @method static \Illuminate\Database\Eloquent\Builder|LogResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|LogResponse whereFECHA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogResponse whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogResponse whereMETODO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogResponse wherePETICION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogResponse whereRESPUESTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogResponse whereURL($value)
 * @mixin Eloquent
 */
class LogResponse extends Eloquent
{
    protected $table = 'LOG_RESPONSE';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
