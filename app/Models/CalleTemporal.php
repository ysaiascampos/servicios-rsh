<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\CalleTemporal
 *
 * @property int $ID_CALLE_TEMP
 * @property int|null $ID_SOLICITUD
 * @property int|null $ID_PERSONA
 * @property int|null $ID_COMUNA
 * @property int|null $ID_CIUDAD
 * @property string|null $NOMBRE_CALLE_TEMP
 * @property int|null $NUMERO_TEMP
 * @property string|null $REFERENCIA_TEMP
 * @method static \Illuminate\Database\Eloquent\Builder|CalleTemporal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalleTemporal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalleTemporal query()
 * @method static \Illuminate\Database\Eloquent\Builder|CalleTemporal whereIDCALLETEMP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalleTemporal whereIDCIUDAD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalleTemporal whereIDCOMUNA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalleTemporal whereIDPERSONA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalleTemporal whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalleTemporal whereNOMBRECALLETEMP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalleTemporal whereNUMEROTEMP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalleTemporal whereREFERENCIATEMP($value)
 * @mixin Eloquent
 */
class CalleTemporal extends Eloquent
{
    protected $table = 'ab_calle_temporal';
    protected $primaryKey = 'id_calle_temp';
    public $timestamps = false;
}
