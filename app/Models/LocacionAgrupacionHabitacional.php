<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;

/**
 * App\Models\LocacionAgrupacionHabitacional
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionAgrupacionHabitacional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionAgrupacionHabitacional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionAgrupacionHabitacional query()
 * @mixin Eloquent
 */
class LocacionAgrupacionHabitacional extends Eloquent
{
    protected $table = 'vas_ah';
    public $timestamps = false;

    public static function getAgrupacionHabitacional($comunaCodigo, $codigoAh, $idCalleGrupoFamiliar = null)
    {
        $locacionAgrupacionHabitacional = null;
        try {
            if ($idCalleGrupoFamiliar >= Constante::ID_CALLE_11011_CORTE_GRUPO_FAMILIAR || is_numeric($codigoAh)) {
                $locacionAgrupacionHabitacional = self::select('id_ah', 'nombre')
                    ->where('COMUNA_INE', $comunaCodigo)
                    ->where('ID_AH', $codigoAh)
                    ->first();
            } else {
                $locacionAgrupacionHabitacional['id_ah'] = 0;
                $locacionAgrupacionHabitacional['nombre'] = null;
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $locacionAgrupacionHabitacional;
    }
}
