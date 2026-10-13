<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\FuenteExport
 *
 * @property int $FUE_ID
 * @property string|null $FUE_DESCRIPCION
 * @property string|null $FUE_DESCRIPCION_LARGA
 * @property int|null $FUE_INDEPENDIENTE
 * @property int|null $FUE_DEPENDIENTE
 * @property int|null $FUE_PENS_1
 * @property int|null $FUE_PENS_2
 * @property string|null $TABLA
 * @property int|null $N_MESES_INDEPENDIENTE
 * @property int|null $N_MESES_DEPENDIENTE
 * @property bool|null $TIPO_SOLICITUD_60
 * @property bool|null $TIPO_SOLICITUD_61
 * @property bool|null $TIPO_SOLICITUD_62
 * @property bool|null $TIPO_SOLICITUD_63
 * @property bool|null $TIPO_SOLICITUD_64
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport query()
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereFUEDEPENDIENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereFUEDESCRIPCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereFUEDESCRIPCIONLARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereFUEID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereFUEINDEPENDIENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereFUEPENS1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereFUEPENS2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereNMESESDEPENDIENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereNMESESINDEPENDIENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereTABLA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereTIPOSOLICITUD60($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereTIPOSOLICITUD61($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereTIPOSOLICITUD62($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereTIPOSOLICITUD63($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FuenteExport whereTIPOSOLICITUD64($value)
 * @mixin Eloquent
 */
class FuenteExport extends Eloquent
{
    protected $connection = 'oracle_export';
    protected $table = 'fuentes';
    protected $primaryKey = 'FUE_ID';
    public $timestamps = false;

    public static function getFuentes()
    {
        $FuenteExport = FuenteExport::orderBy('fue_id')
            ->get();
        return $FuenteExport;
    }

    public static function findByFuenteDependencia($idFuenteDependencia)
    {
        $fuente = null;
        $resultado = null;
        try {
            $fuente = self::getModel()
                ->select("FUE_ID","FUE_DESCRIPCION","FUE_DESCRIPCION_LARGA")
                ->where('FUE_ID', $idFuenteDependencia)
                ->first();

            if (!is_null($fuente) && $fuente->FUE_ID != 0 && $fuente->FUE_ID != 430) {
                $resultado = [
                    'fuente'=>$fuente->FUE_DESCRIPCION
//                    'descripcion'=>$fuente->FUE_DESCRIPCION_LARGA
                ];
            }else{
                $resultado = false;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $resultado;
    }
}
