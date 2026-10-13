<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ComplementoIngresos
 *
 * @property int $COMPLEMENTO_ID
 * @property int|null $ID_SOLICITUD
 * @property int|null $C_TCOMPLEMENTO_INGRESO
 * @property int|null $MONTO
 * @property int|null $RUT_CAUSANTE
 * @property string|null $DV_CAUSANTE
 * @property int|null $RUT_RECEPTOR
 * @property string|null $DV_RECEPTOR
 * @property int|null $RUT_PAGADOR
 * @property string|null $DV_PAGADOR
 * @property bool|null $VIGENTE
 * @property bool|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property string|null $FECHA_CREACION
 * @property int|null $PERIODO_READ
 * @property string|null $FECHA_INICIO_PAGO
 * @property int|null $NUMERO_CUOTAS
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereCOMPLEMENTOID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereCTCOMPLEMENTOINGRESO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereDVCAUSANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereDVPAGADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereDVRECEPTOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereFECHACREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereFECHAINICIOPAGO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereMONTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereNUMEROCUOTAS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereRUTCAUSANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereRUTPAGADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereRUTRECEPTOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoIngresos whereVIGENTE($value)
 * @mixin Eloquent
 */
class ComplementoIngresos extends Eloquent
{
    protected $table = 'ab_complemento_ingresos';
    protected $primaryKey = 'COMPLEMENTO_ID';
    public $timestamps = false;
}
