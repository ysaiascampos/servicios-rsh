<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SolicitudUrl
 *
 * @property int $ID_SOLICITUD_URL
 * @property int $ID_TIPO_SOLICITUD
 * @property int $ID_SUB_TIPO_SOLICITUD
 * @property string $TIPO_SOLICITUD
 * @property string $URL
 * @property int|null $ESTADO
 * @property string|null $FECHA_CREACION
 * @property string|null $FECHA_MODIFICACION
 * @mixin Eloquent
 */

class SolicitudUrl extends Model
{
    protected $table = 'ab_solicitud_url';
    protected $primaryKey = 'ID_SOLICITUD_URL';
    public $timestamps = false;
}
