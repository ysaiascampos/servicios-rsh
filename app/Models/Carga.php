<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Carga
 *
 * @property int $ISA_CARG_ID
 * @property int $RUN_CARGA
 * @property int|null $RUN_COTIZANTE
 * @property int|null $PERIODO
 * @property int|null $FECHA_CARGA
 * @property int|null $TIPO_REGISTRO
 * @property string|null $DV_COTIZANTE
 * @property string|null $DV_CARGA
 * @property string|null $FECHA_NAC
 * @property string|null $SEXO
 * @property string|null $TIPO_CARGA
 * @property string|null $CODIGO_RELACION
 * @property int|null $VIGENTE
 * @property string|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property int $FECHAFUENTE
 * @property int|null $PERIODO_RSH
 * @property int|null $MONTO_DEMANDA
 * @property int|null $FECHA_DESAFILIACION
 * @property int|null $MONTO_PLAN
 * @property int|null $C_TACTUALIZACION_SALUD
 * @property int|null $FECHA_CAMBIO
 * @property int|null $C_TRECTIFICACION_SALUD
 * @property int|null $FECHA_RECTIFICACION
 * @property int|null $PERIODO_READ
 * @property int|null $MONTO_PLAN_UF Campo para guardar el monto del plan en UF
 * @method static \Illuminate\Database\Eloquent\Builder|Carga newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carga newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carga query()
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereCODIGORELACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereCTACTUALIZACIONSALUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereCTRECTIFICACIONSALUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereDVCARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereDVCOTIZANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereFECHACAMBIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereFECHACARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereFECHADESAFILIACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereFECHANAC($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereFECHARECTIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereISACARGID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereMONTODEMANDA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereMONTOPLAN($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereMONTOPLANUF($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga wherePERIODO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga wherePERIODORSH($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereRUNCARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereRUNCOTIZANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereSEXO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereTIPOCARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereTIPOREGISTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carga whereVIGENTE($value)
 * @mixin Eloquent
 */
class Carga extends Eloquent
{
    protected $table = 'tbl_super_salud_cargas';
    protected $primaryKey = 'ISA_CARG_ID';
    public $timestamps = false;
}
