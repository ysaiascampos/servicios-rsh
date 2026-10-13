<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Afc
 *
 * @property int $AFC_ID
 * @property int|null $PERIODO_RSH
 * @property int|null $RUN
 * @property int|null $NUM_CUENTA
 * @property int|null $RENTA_11
 * @property int|null $RENTA_10
 * @property int|null $RENTA_09
 * @property int|null $RENTA_08
 * @property int|null $RENTA_07
 * @property int|null $RENTA_06
 * @property int|null $RENTA_05
 * @property int|null $RENTA_04
 * @property int|null $RENTA_03
 * @property int|null $RENTA_02
 * @property int|null $RENTA_01
 * @property int|null $RENTA_00
 * @property int|null $MONTO_AFC_COMP_DEP_3
 * @property int|null $MONTO_AFC_COMP_DEP_2
 * @property int|null $MONTO_AFC_COMP_DEP_1
 * @property int|null $MONTO_AFC_COMP_DEP_0
 * @property int|null $VIGENTE
 * @property int|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int|null $FECHAFUENTE
 * @property int|null $SUB_INCAP_LABORAL
 * @method static \Illuminate\Database\Eloquent\Builder|Afc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Afc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Afc query()
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereAFCID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereMONTOAFCCOMPDEP0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereMONTOAFCCOMPDEP1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereMONTOAFCCOMPDEP2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereMONTOAFCCOMPDEP3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereNUMCUENTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc wherePERIODORSH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA00($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA04($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA05($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA06($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA07($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA08($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA09($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRENTA11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereRUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereSUBINCAPLABORAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Afc whereVIGENTE($value)
 * @mixin Eloquent
 */
class Afc extends Eloquent
{
    protected $table = 'ever_afc_ingreso';
    protected $primaryKey = 'AFC_ID';
    public $timestamps = false;
}
