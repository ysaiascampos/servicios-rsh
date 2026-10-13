<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ComplementoEducacion
 *
 * @property int $COMPLEMENTO_ID
 * @property int|null $ID_SOLICITUD
 * @property string|null $FECHA_CREACION
 * @property int|null $RUN_DATO
 * @property string|null $DV_DATO
 * @property string|null $DATO_COMPLEMENTO
 * @property int|null $PORCENTAJE
 * @property int|null $CALCULO_ACTIVO
 * @property int|null $ACTIVO
 *  * @property int|null $VIGENTE
 * @property int|null $C_TCOMPLEMENTO_EDUCACION CAMPO PARA SUBTIPO, 1 - HOGAR, BECA Y EXTERNO, 2 - HOGAR Y BECA, 3 - HOGAR Y EXTERNO
 * @property int|null $MONTO MONTO PAGADO POR RL HOGAR
 * @property int|null $FUENTE
 * @property int|null $PERIODO_READ
 * @property int|null $MONTO_PENSION MONTO PAGADO POR EXTERNO
 * @property int|null $MONTO_BECA MONTO PAGADO POR BECA
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereACTIVO($value)
 *  * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereVIGENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereCOMPLEMENTOID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereCTCOMPLEMENTOEDUCACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereDATOCOMPLEMENTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereDVDATO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereFECHACREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereMONTO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereMONTOBECA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereMONTOPENSION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion wherePERIODOREAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion wherePORCENTAJE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoEducacion whereRUNDATO($value)
 * @mixin Eloquent
 * @property int|null $MONTO_EXTERNO MONTO_EXTERNO
 */
class ComplementoEducacion extends Eloquent
{
    protected $table = 'ab_complemento_educacion';
    protected $primaryKey = 'COMPLEMENTO_ID';
    public $timestamps = false;
}
