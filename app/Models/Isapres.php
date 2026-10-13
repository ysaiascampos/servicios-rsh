<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Isapres
 *
 * @property int $ID_ISAPRE
 * @property string|null $NOMBRE
 * @property int|null $VIGENCIA
 * @method static \Illuminate\Database\Eloquent\Builder|Isapres newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Isapres newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Isapres query()
 * @method static \Illuminate\Database\Eloquent\Builder|Isapres whereIDISAPRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapres whereNOMBRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Isapres whereVIGENCIA($value)
 * @mixin Eloquent
 */
class Isapres extends Eloquent
{
    protected $table = 'ab_isapre';
    protected $primaryKey = 'ID_ISAPRE';
    public $timestamps = false;

    public static function obtenerNombreIsapre($isa_id = null)
    {
        if ($isa_id > 0) {
            $oIsapre = Isapres::where('id_isapre', $isa_id)
                ->first();
            if ($oIsapre) {
                return $oIsapre->NOMBRE;
            } else {
                return 'ERROR - No corresponde';
            }
        } else {
            return 'No hay isapre asociada';
        }
    }

}
