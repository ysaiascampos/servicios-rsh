<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\AgendaSolicitud
 *
 * @property int $ID_AGENDAR_VISITA
 * @property int|null $ID_SOLICITUD
 * @property string|null $FECHA_DESDE
 * @property string|null $FECHA_HASTA
 * @property string|null $OBSERVACIONES
 * @property int|null $USUARIO_ASIGNADO
 * @method static \Illuminate\Database\Eloquent\Builder|AgendaSolicitud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgendaSolicitud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AgendaSolicitud query()
 * @method static \Illuminate\Database\Eloquent\Builder|AgendaSolicitud whereFECHADESDE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgendaSolicitud whereFECHAHASTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgendaSolicitud whereIDAGENDARVISITA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgendaSolicitud whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgendaSolicitud whereOBSERVACIONES($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AgendaSolicitud whereUSUARIOASIGNADO($value)
 * @mixin Eloquent
 */
class AgendaSolicitud extends Eloquent
{
    protected $table = 'ab_agendar_visita';
    protected $primaryKey = 'ID_AGENDAR_VISITA';
    public $timestamps = false;
}
