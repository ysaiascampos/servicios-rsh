<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\LogConsultaQrCuidadoras
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogConsultaQrCuidadoras newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogConsultaQrCuidadoras newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LogConsultaQrCuidadoras query()
 * @mixin \Eloquent
 */
class LogConsultaQrCuidadoras extends Eloquent
{
    protected $table = 'log_consulta_qr_cuidadoras';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
