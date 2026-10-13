<?php

namespace App\Models;

use DB;
use Eloquent;
use Exception;
use Illuminate\Support\Facades\Log;


/**
 * App\Models\AgrupacionHabitacional
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AgrupacionHabitacional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgrupacionHabitacional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgrupacionHabitacional query()
 * @mixin Eloquent
 */
class AgrupacionHabitacional extends Eloquent
{
    protected $table = 'vas_ah';
    //protected $primaryKey = 'CODIGO_INST_AH';
    public $timestamps = false;

    public static function getNombreAH($folio)
    {
        $sql = "SELECT ASIGNACION_EXPORT.FN_NOMBRE_AGRUP_HAB_EXPORT(".$folio.") AS nombre FROM DUAL";
        $row = \Illuminate\Support\Facades\DB::connection('oracle_export')->selectOne($sql);

        $nombre = $row->nombre ?? $row->NOMBRE ?? '';
        $nombre = trim((string)$nombre);

        if ($nombre === '' || stripos($nombre, 'ERROR') === 0) {
            return null;
        }

        return $nombre;
    }
}
