<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;

/**
 * App\Models\FichaMontoMensual
 *
 * @property int $MENSUAL_ID
 * @property int $MENSUAL_ID_FICHA
 * @property int $MENSUAL_ID_PERSONA
 * @property int|null $MENSUAL_Y1_M1
 * @property int|null $MENSUAL_Y1_M2
 * @property int|null $MENSUAL_Y1_M3
 * @property int|null $MENSUAL_Y1_M4
 * @property int|null $MENSUAL_Y1_M5
 * @property int|null $MENSUAL_Y1_M6
 * @property int|null $MENSUAL_Y1_M7
 * @property int|null $MENSUAL_Y1_M8
 * @property int|null $MENSUAL_Y1_M9
 * @property int|null $MENSUAL_Y1_M10
 * @property int|null $MENSUAL_Y1_M11
 * @property int|null $MENSUAL_Y1_M12
 * @property int|null $MENSUAL_Y2_M1
 * @property int|null $MENSUAL_Y2_M2
 * @property int|null $MENSUAL_Y2_M3
 * @property int|null $MENSUAL_Y2_M4
 * @property int|null $MENSUAL_Y2_M5
 * @property int|null $MENSUAL_Y2_M6
 * @property int|null $MENSUAL_Y2_M7
 * @property int|null $MENSUAL_Y2_M8
 * @property int|null $MENSUAL_Y2_M9
 * @property int|null $MENSUAL_Y2_M10
 * @property int|null $MENSUAL_Y2_M11
 * @property int|null $MENSUAL_Y2_M12
 * @property int|null $MENSUAL_FECHA_INICIO
 * @property int|null $MENSUAL_VIGENTE
 * @property int|null $MENSUAL_CALCULOACTIVO
 * @property int|null $MENSUAL_IDSOLICITUD
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual query()
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALFECHAINICIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALIDFICHA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALIDPERSONA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALVIGENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY1M9($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M10($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M11($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M12($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M8($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FichaMontoMensual whereMENSUALY2M9($value)
 * @mixin Eloquent
 */
class FichaMontoMensual extends Eloquent
{
    protected $table = 'ficha_monto_mensual';
    protected $primaryKey = 'MENSUAL_ID';
    public $timestamps = false;
    public $incrementing = false;

    public static function findVigenteByIdPersona($idPersona)
    {
        return self::where('mensual_id_persona', $idPersona)
            ->where('mensual_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('mensual_vigente', Constante::VIGENTE_1_VIGENTE)
            ->first();
    }

    public static function findByIdSolicitud($idSolicitud)
    {
        return self::where('mensual_idsolicitud', $idSolicitud)
            ->first();
    }

    public static function findByIdFicha($IdFicha)
    {
        return self::where('mensual_idsolicitud', $IdFicha)
            ->first();
    }
}
