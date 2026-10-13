<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Throwable;

/**
 * App\Models\LocacionCalle
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionCalle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionCalle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LocacionCalle query()
 * @mixin Eloquent
 */
class LocacionCalle extends Eloquent
{
    protected $table = 'vas_calle';
    public $timestamps = false;

    public static function getCallePorComunaLocalidadYidCalle(
        $comunaCodigo,
        $idLocalidadGrupoFamiliar,
        $idCalleGrupoFamiliar,
        $nombreCalleGrupoFamiliar
    ) {
        $locacionCalle = null;
        try {
            if ($idCalleGrupoFamiliar >= Constante::ID_CALLE_11011_CORTE_GRUPO_FAMILIAR) {
                $locacionLocalidad = Ciudad::where('ID_LOCALIDAD', $idLocalidadGrupoFamiliar)
                    ->where('COMUNA_INE', $comunaCodigo)
                    ->first();
                if (!empty($locacionLocalidad)) {
                    $locacionCalle = self::where('COMUNA_INE', $comunaCodigo)
                        ->where('ID_LOCALIDAD', $idLocalidadGrupoFamiliar)
                        ->where('ID_CALLE', $idCalleGrupoFamiliar)
                        ->first();
                    if (!isset($locacionCalle)) {
                        $locacionCalle = (object)[
                            'NOMBRE' => $nombreCalleGrupoFamiliar,
                            'COMUNA_INE' => $comunaCodigo,
                            'ID_LOCALIDAD' => $idLocalidadGrupoFamiliar,
                            'ID_CALLE' => $idCalleGrupoFamiliar
                        ];
                    }
                } else {
                    $locacionCalle = self::where('COMUNA_INE', $comunaCodigo)
                        ->where('ID_CALLE', $idCalleGrupoFamiliar)
                        ->first();
                }
            } else {
                $locacionCalle = (object)[
                    'NOMBRE' => $nombreCalleGrupoFamiliar,
                    'COMUNA_INE' => $comunaCodigo,
                    'ID_LOCALIDAD' => $idLocalidadGrupoFamiliar,
                    'ID_CALLE' => $idCalleGrupoFamiliar
                ];
                $locacionLocalidad = null;
            }
        } catch (Throwable $ex) {
            throw $ex;
        }
        return $locacionCalle;
    }
}
