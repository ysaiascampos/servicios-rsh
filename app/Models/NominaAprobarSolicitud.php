<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\NominaAprobarSolicitud
 *
 * @property int $ID_SOLICITUD
 * @property int $ID_TIPO_SOLICITUD
 * @property int $RUN_CIUDADANO
 * @property int $ID_ESTADO
 * @property int|null $ID_BLOQUE
 * @property string|null $FECHA_CARGA
 * @method static \Illuminate\Database\Eloquent\Builder|NominaAprobarSolicitud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NominaAprobarSolicitud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NominaAprobarSolicitud query()
 * @method static \Illuminate\Database\Eloquent\Builder|NominaAprobarSolicitud whereFECHACARGA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NominaAprobarSolicitud whereIDBLOQUE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NominaAprobarSolicitud whereIDESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NominaAprobarSolicitud whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NominaAprobarSolicitud whereIDTIPOSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NominaAprobarSolicitud whereRUNCIUDADANO($value)
 * @mixin Eloquent
 */
class NominaAprobarSolicitud extends Eloquent
{
    protected $table = 'nomina_aprobacion';
    protected $primaryKey = 'ID_SOLICITUD';
    public $timestamps = false;
}
