<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\SolicitudAuditoria
 *
 * @property int $ID_SOLICITUD
 * @property int $ID_CAMPO
 * @property int $RUT_USUARIO
 * @property string $FECHA_REGISTRO
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAuditoria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAuditoria newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAuditoria query()
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAuditoria whereFECHAREGISTRO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAuditoria whereIDCAMPO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAuditoria whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SolicitudAuditoria whereRUTUSUARIO($value)
 * @mixin Eloquent
 */
class SolicitudAuditoria extends Eloquent
{
    protected $table = 'ab_solicitud_auditoria';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;
}
