<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\SolicitudesDerivadas
 *
 * @property int $DER_ID_SOLICITUD
 * @property int|null $DER_ESTADO_ENVIO
 * @property string|null $DER_FECHA_ENVIO
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudesDerivadas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudesDerivadas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudesDerivadas query()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudesDerivadas whereDERESTADOENVIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudesDerivadas whereDERFECHAENVIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudesDerivadas whereDERIDSOLICITUD($value)
 * @mixin Eloquent
 */
class SolicitudesDerivadas extends Eloquent
{
    protected $table = 'ab_solicitud_derivadas';
    protected $primaryKey = 'DER_ID_SOLICITUD';
    public $timestamps = false;
}
