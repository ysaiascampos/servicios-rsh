<?php

namespace App\Models;

use Eloquent;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Calle
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Calle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calle query()
 * @mixin Eloquent
 */
class Calle extends Eloquent
{
    protected $table = 'vas_calle'; /* vista */
    protected $primaryKey = 'ID_CALLE';
    public $timestamps = false;


    public static function listCallePorComunaYLocalidad($comunaCodigo)
    {
        $locacionCalle = null;
        try {
            $locacionCalle = self::getModel()
                ->where('comuna_ine', $comunaCodigo)
                ->orderBy('nombre', 'asc')
                ->pluck('NOMBRE', 'ID_CALLE');
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionCalle;
    }

    public static function findByCodigoCalleIdLocalidad($codigoCalle, $Idlocalidad)
    {
        return self::where('id_localidad', $Idlocalidad)
            ->where('id_calle', $codigoCalle)
            ->first();
    }


    public static function getNombreCalle($folio)
    {
        $sql = "SELECT ASIGNACION_EXPORT.FN_NOMBRE_CALLE_EXPORT(".$folio.") AS nombre FROM DUAL";
        $row = DB::connection('oracle_export')->selectOne($sql);
        return $row->nombre ?? $row->NOMBRE ?? '';
    }
}
