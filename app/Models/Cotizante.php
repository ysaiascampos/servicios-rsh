<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Cotizante
 *
 * @property int $ISA_COT_ID
 * @property int $RUN
 * @property string|null $DV
 * @property int|null $CODIGO_ISAPRE
 * @property string|null $TIPO_PLAN
 * @property string|null $TIPO_TRABAJADOR
 * @property string|null $IDENTIFICACION_PLAN
 * @property int|null $MONTO_PLAN
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
 * @property string|null $TOTAL_ISAPRE
 * @property int|null $MESES_ISAPRE
 * @property string|null $PROMEDIO_ISAPRE_12
 * @property string|null $PROMEDIO_ISAPRE_CANT_PAGOS
 * @property int|null $MESES_ISAPRE_SII
 * @property string|null $PROMEDIO_ISAPRE_12_SII
 * @property string|null $PROMEDIO_ISAPRE_CANT_PAGOS_SII
 * @property int|null $VIGENTE
 * @property string|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int $FECHAFUENTE
 * @property int|null $PERIODO_RSH
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereCODIGOISAPRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereDV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereIDENTIFICACIONPLAN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereISACOTID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereMESESISAPRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereMESESISAPRESII($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereMONTOPLAN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante wherePERIODORSH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante wherePROMEDIOISAPRE12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante wherePROMEDIOISAPRE12SII($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante wherePROMEDIOISAPRECANTPAGOS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante wherePROMEDIOISAPRECANTPAGOSSII($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA00($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA04($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA05($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA06($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA07($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA08($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA09($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRENTA11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereRUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereTIPOPLAN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereTIPOTRABAJADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereTOTALISAPRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cotizante whereVIGENTE($value)
 * @mixin Eloquent
 */
class Cotizante extends Eloquent
{
    protected $table = 'tbl_super_salud_cotizantes';
    protected $primaryKey = 'ISA_COT_ID';
    public $timestamps = false;
}
