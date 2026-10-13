<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;

/**
 * App\Models\PuebloIndigena
 *
 * @property int $ID
 * @property string $NOMBRE
 * @property int|null $ESTADO 1 = activo ; 0 = eliminado
 * @method static \Illuminate\Database\Eloquent\Builder|PuebloIndigena newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PuebloIndigena newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PuebloIndigena query()
 * @method static \Illuminate\Database\Eloquent\Builder|PuebloIndigena whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PuebloIndigena whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PuebloIndigena whereNOMBRE($value)
 * @mixin Eloquent
 */
class PuebloIndigena extends Eloquent
{
    protected $table = 'ab_pueblo_indigena';
    public $timestamps = false;

    public static function getPuebloIndigenaByIdPersona($idPersona)
    {
        $ficha = Ficha::select('ficha_puebloindigena')
            ->where('FICHA_PERSONA', $idPersona)
            ->where('FICHA_CALCULOACTIVO', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('FICHA_VIGENTE', Constante::VIGENTE_1_VIGENTE)
            ->orderByDesc('FICHA_ID')
            ->get()
            ->last();
        return self::select('nombre')
            ->where('ID', '=', $ficha->FICHA_PUEBLOINDIGENA)
            ->first();
    }

    public static function listarPueblosOriginarios()
    {
        return self::where('estado', Constante::VIGENTE_1_VIGENTE)
            ->get();
    }
}
