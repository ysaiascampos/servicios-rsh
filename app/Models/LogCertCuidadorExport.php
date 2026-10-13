<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Exception;
use Illuminate\Support\Facades\Log;
use DB;


/**
 * App\Models\LogCertCuidadorExport
 *
 * @property int $ID_LOG
 * @property int|null $CANAL
 * @property int|null $RUN_USUARIO
 * @property int|null $RUN_CUIDADOR
 * @property string|null $IP
 * @property string|null $FECHA
 * @property string|null $NOTA
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogCertCuidadorExport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogCertCuidadorExport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogCertCuidadorExport query()
 * @mixin \Eloquent
 */
class LogCertCuidadorExport extends Eloquent
{
    protected $connection = 'oracle_export';
    protected $table = 'ab_log_certificado_cuidador';
    protected $primaryKey = 'ID_LOG';
    public $timestamps = false;
    protected $hidden = ['rn'];
    protected $appends = [];
    protected $filled = [];

    public static function nextValue($name)
    {
        return DB::connection('oracle_export')
                ->select("SELECT $name.NEXTVAL as ID FROM DUAL")[0]->ID;
    }
}
