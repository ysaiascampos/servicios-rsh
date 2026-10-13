<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Ingresos
 *
 * @property int $ING_ID
 * @property int $ING_RUN
 * @property int $ID_TIPO_INGRESO
 * @property int|null $ING_MONTO_MES1
 * @property int|null $ING_MONTO_MES2
 * @property int|null $ING_MONTO_MES3
 * @property int|null $ING_MONTO_MES4
 * @property int|null $ING_MONTO_MES5
 * @property int|null $ING_MONTO_MES6
 * @property int|null $ING_MONTO_MES7
 * @property int|null $ING_MONTO_MES8
 * @property int|null $ING_MONTO_MES9
 * @property int|null $ING_MONTO_MES10
 * @property int|null $ING_MONTO_MES11
 * @property int|null $ING_MONTO_MES12
 * @property string|null $ING_FECHAACTUALIZACION
 * @property string|null $ING_FECHAEXPIRACION
 * @property int|null $ING_IDSOLICITUD
 * @property int|null $VIGENTE
 * @property int|null $ING_CALCULOACTIVO
 * @property int $ING_FUENTE
 * @property string|null $ING_FECHAFUENTE
 * @property int|null $ING_FUENTE_ORIGINAL
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereIDTIPOINGRESO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGFECHAACTUALIZACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGFUENTEORIGINAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGMONTOMES9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereINGRUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingresos whereVIGENTE($value)
 * @mixin Eloquent
 */
class Ingresos extends Eloquent
{
    protected $table = 'ab_ingresos';
    protected $primaryKey = 'ING_ID';
    public $timestamps = false;
}
