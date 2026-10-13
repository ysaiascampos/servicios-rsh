<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\AfpVejInv
 *
 * @property int $PENSION_AFP_VI_ID
 * @property int|null $PERIODO_RSH
 * @property int|null $RUT
 * @property int|null $COD_TIPOBE
 * @property string|null $NOM_TIPOBE
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
 * @property int|null $MES_1_COMPL_AFP_VI
 * @property int|null $MES_2_COMPL_AFP_VI
 * @property int|null $MES_3_COMPL_AFP_VI
 * @property int|null $MES_4_COMPL_AFP_VI
 * @property int|null $VIGENTE
 * @property int|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int|null $FECHAFUENTE
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv query()
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereCODTIPOBE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES1COMPLAFPVI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES2COMPLAFPVI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES3COMPLAFPVI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES4COMPLAFPVI($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereMES9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereNOMTIPOBE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv wherePENSIONAFPVIID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv wherePERIODORSH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereRUT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AfpVejInv whereVIGENTE($value)
 * @mixin Eloquent
 */
class AfpVejInv extends Eloquent
{
    protected $table = 'ever_afp_vej_inv_pens_12_mes';
    protected $primaryKey = 'PENSION_AFP_VI_ID';
    public $timestamps = false;
}
