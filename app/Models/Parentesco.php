<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Exception;
use Illuminate\Support\Facades\Cache;

/**
 * App\Models\Parentesco
 *
 * @property int $ID
 * @property string $NOMBRE
 * @property int|null $ESTADO 1= activo ; 0 = eliminado
 * @method static \Illuminate\Database\Eloquent\Builder|Parentesco newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Parentesco newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Parentesco query()
 * @method static \Illuminate\Database\Eloquent\Builder|Parentesco whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parentesco whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Parentesco whereNOMBRE($value)
 * @mixin Eloquent
 */
class Parentesco extends Eloquent
{
    protected $table = 'ab_parentesco';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $hidden = ['rn'];

    public static function getModel()
    {
        return new Parentesco();
    }

    public static function listParentescos()
    {
        $datosDeParentesco = null;
        try {
            $datosDeParentesco = Parentesco::getModel()
                ->where('estado', Constante::VIGENTE_1_VIGENTE)
                ->pluck('NOMBRE', 'ID');
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeParentesco;
    }

    public static function listParentescosRest()
    {
        $datosDeParentesco = null;
        try {
            $datosDeParentesco = Parentesco::getModel()
                ->where('estado', Constante::VIGENTE_1_VIGENTE)
                ->where('id', '<>', Constante::PARENTESCO_99_POR_DEFINIR)
                ->pluck('NOMBRE', 'ID');
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeParentesco;
    }

    public static function getParentescos($idParentesco)
    {
        $datosDeParentesco = null;
        try {
            $datosDeParentesco = Parentesco::getModel()
                //->where('estado', Constante::VIGENTE_1_VIGENTE)
                ->where('id', $idParentesco)
                ->select('nombre')
                ->first();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeParentesco;
    }

    public static function obtenerParentescoEnCache()
    {
        $cacheKey = 'Parentescos';
        if (Cache::has($cacheKey)) {
            $parentesco = Cache::get($cacheKey);
        } else {
            $parentesco = Parentesco::where('estado', 1)
                ->orderBy('id')
                ->pluck('NOMBRE', 'ID');
            Cache::put($cacheKey, $parentesco, Constante::CACHE_API_MINUTOS);
        }
        return $parentesco;
    }
}
