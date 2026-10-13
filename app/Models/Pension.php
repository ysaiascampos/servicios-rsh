<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Pension
 *
 * @property int $PENSION_IPS_ID
 * @property int|null $PERIODO_RSH
 * @property int|null $RUT
 * @property int|null $C_INSTIT
 * @property string|null $N_INSTIT
 * @property int|null $C_BENEF
 * @property string|null $N_BENEF
 * @property int|null $C_ENTIDAD
 * @property string|null $N_ENTIDAD
 * @property int|null $C_TIPOBE
 * @property string|null $N_TIPOBE
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
 * @property int|null $MES_1_COMPL_IPS
 * @property int|null $MES_2_COMPL_IPS
 * @property int|null $MES_3_COMPL_IPS
 * @property int|null $MES_4_COMPL_IPS
 * @property int|null $VIGENTE
 * @property int|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int|null $FECHAFUENTE
 * @method static \Illuminate\Database\Eloquent\Builder|Pension newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pension newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pension query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereCBENEF($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereCENTIDAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereCINSTIT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereCTIPOBE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES1COMPLIPS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES2COMPLIPS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES3COMPLIPS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES4COMPLIPS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereMES9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereNBENEF($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereNENTIDAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereNINSTIT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereNTIPOBE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension wherePENSIONIPSID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension wherePERIODORSH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereRUT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pension whereVIGENTE($value)
 * @mixin Eloquent
 */
class Pension extends Eloquent
{
    protected $table = 'ever_ips_pension_12_meses';
    protected $primaryKey = 'PENSION_IPS_ID';
    public $timestamps = false;
}
