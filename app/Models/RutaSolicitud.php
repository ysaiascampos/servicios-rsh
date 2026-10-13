<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\RutaSolicitud
 *
 * @property int|null $ID_RUTA_SOLICITUD
 * @property int|null $ID_RUTA
 * @property int|null $ID_SOLICITUD
 * @property int|null $RUN_ENCUESTADOR
 * @property string|null $FECHA_ENCUESTA
 * @property string|null $OBSERVACION
 * @property int|null $RUTA_SOLI_CALCULOACTIVO
 * @property int|null $RUTA_SOLI_VIGENTE
 * @property string|null $FECHA_VISITA
 * @property string|null $FECHA_VISITA_2
 * @property string|null $FECHA_VISITA_3
 * @property int|null $ID_MOTIVO
 * @property int|null $ID_ESTADO
 * @property int|null $RUN_USU_CREACION_RUTA
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud query()
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereFECHAENCUESTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereFECHAVISITA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereFECHAVISITA2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereFECHAVISITA3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereIDESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereIDMOTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereIDRUTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereIDRUTASOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereOBSERVACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereRUNENCUESTADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereRUNUSUCREACIONRUTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereRUTASOLICALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RutaSolicitud whereRUTASOLIVIGENTE($value)
 * @mixin Eloquent
 */
class RutaSolicitud extends Eloquent
{
    protected $table = 'ab_ruta_solicitud';
    protected $primaryKey = 'ID_RUTA_SOLICITUD';
    public $timestamps = false;
}
