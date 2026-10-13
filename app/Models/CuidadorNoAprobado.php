<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\CuidadorNoAprobado
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CuidadorNoAprobado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CuidadorNoAprobado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CuidadorNoAprobado query()
 * @mixin \Eloquent
 */
class CuidadorNoAprobado extends Eloquent
{
    protected $table = 'CUIDADOR_NO_APROBADO';
    protected $primaryKey = 'ID';
    public $timestamps = false;

}
