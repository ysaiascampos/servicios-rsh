<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\Modulo
 *
 * @property int $ID_MODULO
 * @property int $ID_MODULO_PADRE
 * @property string|null $NOMBRE_MODULO
 * @property string|null $DESCRIPCION_MODULO
 * @property int|null $NIVEL_MODULO
 * @property int|null $ORDEN_MODULO
 * @property string|null $URL
 * @property int|null $ESTADO
 * @method static \Illuminate\Database\Eloquent\Builder|Modulo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Modulo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Modulo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Modulo whereDESCRIPCIONMODULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modulo whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modulo whereIDMODULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modulo whereIDMODULOPADRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modulo whereNIVELMODULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modulo whereNOMBREMODULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modulo whereORDENMODULO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Modulo whereURL($value)
 * @mixin Eloquent
 */
class Modulo extends Eloquent
{
    protected $table = 'MODULO';
    protected $primaryKey = 'ID_MODULO';
    public $timestamps = false;

    public static function listarModulosActivos()
    {

        return Modulo::where('estado', 1)
            ->get();

    }
}

