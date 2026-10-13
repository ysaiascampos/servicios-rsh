<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ComplementoCotizaSalud
 *
 * @property int $COMPLEMENTO_ID
 * @property int|null $ID_SOLICITUD
 * @property int|null $C_TCOMPLEMENTO_COTIZASALUD
 * @property int|null $MONTO
 * @property int|null $RUT
 * @property string|null $DV
 * @property int|null $RUT_INTEGRANTE
 * @property string|null $DV_INTEGRANTE
 * @property bool|null $VIGENTE
 * @property bool|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property string|null $FECHA_CREACION
 * @property int|null $PERIODO_READ
 * @property int|null $RUT_CAUSANTE
 * @property string|null $DV_CAUSANTE
 * @property int|null $RUT_RECEPTOR
 * @property string|null $DV_RECEPTOR
 * @property int|null $RUT_PAGADOR
 * @property string|null $DV_PAGADOR
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereCOMPLEMENTOID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereCTCOMPLEMENTOCOTIZASALUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereDV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereDVCAUSANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereDVINTEGRANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereDVPAGADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereDVRECEPTOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereFECHACREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereMONTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereRUT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereRUTCAUSANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereRUTINTEGRANTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereRUTPAGADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereRUTRECEPTOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCotizaSalud whereVIGENTE($value)
 * @mixin Eloquent
 */
class ComplementoCotizaSalud extends Eloquent
{
    protected $table = 'ab_complemento_cotizasalud'; /* vista */
    protected $primaryKey = 'COMPLEMENTO_ID';
    public $timestamps = false;
}
