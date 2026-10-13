<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Afp
 *
 * @property int $AFP_ID
 * @property int|null $PERIODO_RSH
 * @property int|null $RUN
 * @property int|null $AFP_COD
 * @property int|null $MONTOPESOS_DEP_0
 * @property int|null $MONTOPESOS_DEP_1
 * @property int|null $MONTOPESOS_DEP_2
 * @property int|null $MONTOPESOS_DEP_3
 * @property int|null $MONTOPESOS_DEP_4
 * @property int|null $MONTOPESOS_DEP_5
 * @property int|null $MONTOPESOS_DEP_6
 * @property int|null $MONTOPESOS_DEP_7
 * @property int|null $MONTOPESOS_DEP_8
 * @property int|null $MONTOPESOS_DEP_9
 * @property int|null $MONTOPESOS_DEP_10
 * @property int|null $MONTOPESOS_DEP_11
 * @property int|null $MONTOPESOS_IND_0
 * @property int|null $MONTOPESOS_IND_1
 * @property int|null $MONTOPESOS_IND_2
 * @property int|null $MONTOPESOS_IND_3
 * @property int|null $MONTOPESOS_IND_4
 * @property int|null $MONTOPESOS_IND_5
 * @property int|null $MONTOPESOS_IND_6
 * @property int|null $MONTOPESOS_IND_7
 * @property int|null $MONTOPESOS_IND_8
 * @property int|null $MONTOPESOS_IND_9
 * @property int|null $MONTOPESOS_IND_10
 * @property int|null $MONTOPESOS_IND_11
 * @property int|null $MONTOPESOS_COMP_DEP_0
 * @property int|null $MONTOPESOS_COMP_DEP_1
 * @property int|null $MONTOPESOS_COMP_DEP_2
 * @property int|null $MONTOPESOS_COMP_DEP_3
 * @property int|null $MONTOPESOS_COMP_IND_0
 * @property int|null $MONTOPESOS_COMP_IND_1
 * @property int|null $MONTOPESOS_COMP_IND_2
 * @property int|null $MONTOPESOS_COMP_IND_3
 * @property int|null $VIGENTE
 * @property int|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int|null $FECHAFUENTE
 * @method static \Illuminate\Database\Eloquent\Builder|Afp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Afp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Afp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereAFPCOD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereAFPID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSCOMPDEP0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSCOMPDEP1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSCOMPDEP2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSCOMPDEP3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSCOMPIND0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSCOMPIND1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSCOMPIND2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSCOMPIND3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSDEP9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereMONTOPESOSIND9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp wherePERIODORSH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereRUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afp whereVIGENTE($value)
 * @mixin Eloquent
 */
class Afp extends Eloquent
{
    protected $table = 'EVER_AFP_12_MESES';
    protected $primaryKey = 'AFP_ID';
    public $timestamps = false;

}
