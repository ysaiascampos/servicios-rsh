<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ComplementoSalud
 *
 * @property int $COMPLEMENTO_ID
 * @property int|null $ID_SOLICITUD
 * @property string|null $RUT_MEDICO
 * @property string|null $NOMBRE_MEDICO
 * @property string|null $FECHA
 * @property string|null $DATO_COMPLEMENTO
 * @property string|null $PERIODO
 * @property string|null $PERIODO_DIARIO
 * @property string|null $PERIODO_HORAS
 * @property int|null $PASO
 * @property int|null $GASTO_SALUD
 * @property int|null $CALCULO_ACTIVO
 * @property string|null $DV_MEDICO
 * @property int|null $ACTIVO
 * @property int|null $MONTO_GASTO
 * @property string|null $FECHA_GASTO
 * @property string|null $PERIODICIDAD_GASTO
 * @property int|null $ID_PACIENTE
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereCOMPLEMENTOID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereDATOCOMPLEMENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereDVMEDICO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereFECHA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereFECHAGASTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereGASTOSALUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereIDPACIENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereMONTOGASTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereNOMBREMEDICO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud wherePASO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud wherePERIODICIDADGASTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud wherePERIODO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud wherePERIODODIARIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud wherePERIODOHORAS($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoSalud whereRUTMEDICO($value)
 * @mixin Eloquent
 */
class ComplementoSalud extends Eloquent
{
    protected $table = 'ab_complemento_salud';
    protected $primaryKey = 'COMPLEMENTO_ID';
    public $timestamps = false;
}
