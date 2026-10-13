<?php

namespace App\Models;

use Eloquent;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Fuente
 *
 * @property int $FUEN_ID
 * @property string|null $FUEN_DESCRIPCION
 * @property int|null $FUEN_MESES_RECTIFICAR
 * @property int|null $ESTADO
 * @property string|null $FUEN_DETALLE
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Fuente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Fuente newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Fuente query()
 * @mixin \Eloquent
 */
class Fuente extends Eloquent
{
    protected $table = 'fuentes';
    protected $primaryKey = 'FUEN_ID';
    public $timestamps = false;

    public static function mensajeTramo()
    {
        return DB::connection('oracle_export')
            ->table('fuentes')
            ->where('fue_id', 999)
            ->first()
            ->FUE_DESCRIPCION_LARGA;
    }

}
