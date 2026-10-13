<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\SiiF22
 *
 * @property int $SII_F22_ID
 * @property int $ANNIO_TRIBUTARIO
 * @property int|null $SUMA_TOTAL
 * @property int|null $VIGENTE
 * @property string|null $FECHAEXPIRACION
 * @property int|null $IDSOLICITUD
 * @property int|null $CALCULOACTIVO
 * @property int $FUENTE
 * @property int $FECHAFUENTE
 * @property int $RUT
 * @property string $DV
 * @property int|null $COD_104
 * @property int|null $COD_105
 * @property int|null $COD_106
 * @property int|null $COD_108
 * @property int|null $COD_109
 * @property int|null $COD_110
 * @property int|null $COD_152
 * @property int|null $COD_155
 * @property int|null $COD_161
 * @property int|null $COD_479
 * @property int|null $COD_547
 * @property int|null $COD_617
 * @property int|null $FECHA_REGISTRO
 * @property int|null $FECHA_INGRESO
 * @property int|null $FECHA_CARGA
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 query()
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereANNIOTRIBUTARIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD104($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD105($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD106($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD108($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD109($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD110($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD152($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD155($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD161($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD479($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD547($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereCOD617($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereDV($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereFECHACARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereFECHAEXPIRACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereFECHAFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereFECHAINGRESO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereFECHAREGISTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereRUT($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereSIIF22ID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereSUMATOTAL($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiiF22 whereVIGENTE($value)
 * @mixin Eloquent
 */
class SiiF22 extends Eloquent
{
    protected $table = 'ever_sii_f22_sumada';
    protected $primaryKey = 'SII_F22_ID';
    public $timestamps = false;
}
