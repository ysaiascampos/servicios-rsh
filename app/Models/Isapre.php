<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Isapre
 *
 * @property int $ISA_COT_ID
 * @property int|null $PERIODO_RSH
 * @property int|null $RUN
 * @property int|null $ID_TIPO_TRABAJADOR
 * @property string|null $TIPO_TRABAJADOR
 * @property string|null $ISAPRE_NOM
 * @property int|null $ISAPRE_COD
 * @property string|null $IDENTIFICACION_PLAN
 * @property int|null $MONTO_PLAN
 * @property int|null $RENTA_DEP_11
 * @property int|null $RENTA_DEP_10
 * @property int|null $RENTA_DEP_09
 * @property int|null $RENTA_DEP_08
 * @property int|null $RENTA_DEP_07
 * @property int|null $RENTA_DEP_06
 * @property int|null $RENTA_DEP_05
 * @property int|null $RENTA_DEP_04
 * @property int|null $RENTA_DEP_03
 * @property int|null $RENTA_DEP_02
 * @property int|null $RENTA_DEP_01
 * @property int|null $RENTA_DEP_00
 * @property int|null $RENTA_IND_11
 * @property int|null $RENTA_IND_10
 * @property int|null $RENTA_IND_09
 * @property int|null $RENTA_IND_08
 * @property int|null $RENTA_IND_07
 * @property int|null $RENTA_IND_06
 * @property int|null $RENTA_IND_05
 * @property int|null $RENTA_IND_04
 * @property int|null $RENTA_IND_03
 * @property int|null $RENTA_IND_02
 * @property int|null $RENTA_IND_01
 * @property int|null $RENTA_IND_00
 * @property int|null $MONTO_COT_COMP_DEP_03
 * @property int|null $MONTO_COT_COMP_DEP_02
 * @property int|null $MONTO_COT_COMP_DEP_01
 * @property int|null $MONTO_COT_COMP_DEP_00
 * @property int|null $MONTO_COT_COMP_IND_03
 * @property int|null $MONTO_COT_COMP_IND_02
 * @property int|null $MONTO_COT_COMP_IND_01
 * @property int|null $MONTO_COT_COMP_IND_00
 * @property int|null $VIGENTE
 * @property int|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int|null $FECHAFUENTE
 * @property int|null $C_TACTUALIZACION_SALUD
 * @property int|null $FECHA_CAMBIO
 * @property int|null $FECHA_DESAFILIACION
 * @property int|null $C_TRECTIFICACION_SALUD
 * @property int|null $FECHA_RECTIFICACION
 * @property int|null $PERIODO_READ
 * @property int|null $DEP_O_INDEP Campo que diferencia valores de si es ingreso dependiente o independiente.  Dependiente = 1, Independiente = 2
 * @property int|null $MONTO_PLAN_UF Campo para guardar el monto del plan en UF
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre query()
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereCTACTUALIZACIONSALUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereCTRECTIFICACIONSALUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereDEPOINDEP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereFECHACAMBIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereFECHADESAFILIACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereFECHARECTIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereIDENTIFICACIONPLAN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereIDTIPOTRABAJADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereISACOTID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereISAPRECOD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereISAPRENOM($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereMONTOCOTCOMPDEP00($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereMONTOCOTCOMPDEP01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereMONTOCOTCOMPDEP02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereMONTOCOTCOMPDEP03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereMONTOCOTCOMPIND00($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereMONTOCOTCOMPIND01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereMONTOCOTCOMPIND02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereMONTOCOTCOMPIND03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereMONTOPLAN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereMONTOPLANUF($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre wherePERIODORSH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP00($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP04($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP05($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP06($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP07($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP08($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP09($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTADEP11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND00($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND01($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND02($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND03($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND04($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND05($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND06($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND07($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND08($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND09($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRENTAIND11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereRUN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereTIPOTRABAJADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapre whereVIGENTE($value)
 * @mixin Eloquent
 */
class Isapre extends Eloquent
{
    protected $table = 'ever_super_salud_cotizantes';
    protected $primaryKey = 'ISA_COT_ID';
    public $timestamps = false;
}
