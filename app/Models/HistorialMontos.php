<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\HistorialMontos
 *
 * @property int|null $ID_HISTORIAL_MONTO
 * @property int|null $MONTO
 * @property string|null $MES
 * @property int|null $USUARIO
 * @property int|null $IDSOLICITUD
 * @property string|null $FECHA_MODIFICACION
 * @property string|null $TIPO
 * @property int|null $MONTO_ANTIGUO
 * @method static \Illuminate\Database\Eloquent\Builder|HistorialMontos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HistorialMontos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HistorialMontos query()
 * @method static \Illuminate\Database\Eloquent\Builder|HistorialMontos whereFECHAMODIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistorialMontos whereIDHISTORIALMONTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistorialMontos whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistorialMontos whereMES($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistorialMontos whereMONTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistorialMontos whereMONTOANTIGUO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistorialMontos whereTIPO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HistorialMontos whereUSUARIO($value)
 * @mixin Eloquent
 */
class HistorialMontos extends Eloquent
{
    protected $table = 'ab_historial_montos';
    protected $primaryKey = 'ID_HISTORIAL_MONTO';
    public $timestamps = false;
}
