<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ActualizacionIngreso
 *
 * @property int $ACTUALIZA_ID
 * @property int|null $PERIODO_RSH
 * @property int|null $RUT
 * @property int|null $MES_1
 * @property int|null $MES_2
 * @property int|null $MES_3
 * @property int|null $MES_4
 * @property int|null $MES_5
 * @property int|null $MES_6
 * @property int|null $MES_7
 * @property int|null $MES_8
 * @property int|null $MES_9
 * @property int|null $MES_10
 * @property int|null $MES_11
 * @property int|null $MES_12
 * @property int|null $DEP_O_INDEP
 * @property int|null $VIGENTE
 * @property int|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int|null $FECHAFUENTE
 * @property int|null $PERIODO_READ
 * @property int|null $N_MESES
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereACTUALIZAID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereDEPOINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereMES9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso wherePERIODORSH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereRUT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActualizacionIngreso whereVIGENTE($value)
 * @mixin Eloquent
 */
class ActualizacionIngreso extends Eloquent
{
    protected $table = 'ever_actualizacion_ing';
    protected $primaryKey = 'ACTUALIZA_ID';
    public $timestamps = false;
}
