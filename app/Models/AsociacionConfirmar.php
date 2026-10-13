<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\AsociacionConfirmar
 *
 * @property int $ID_ASOCIACION
 * @property int $ID_SOLICITUD_MAESTRA
 * @property int $ID_SOLICITUD_ESCLAVA
 * @property int $RUN_USUARIO_CREACION
 * @property string $FECHA_CREACION
 * @property int|null $RUN_USUARIO_MODIFICACION
 * @property string|null $FECHA_MODIFICACION
 * @property int $ESTADO
 * @property int $RUN_CIUDADANO
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar query()
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar whereFECHACREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar whereFECHAMODIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar whereIDASOCIACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar whereIDSOLICITUDESCLAVA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar whereIDSOLICITUDMAESTRA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar whereRUNCIUDADANO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar whereRUNUSUARIOCREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AsociacionConfirmar whereRUNUSUARIOMODIFICACION($value)
 * @mixin Eloquent
 */
class AsociacionConfirmar extends Eloquent
{
    protected $table = 'ASOCIACION_CONFIRMAR';
    protected $primaryKey = 'ID_ASOCIACION';
    public $timestamps = false;

    public function SolicitudMaestra()
    {
        return $this->hasOne('Solicitud', 'id_solicitud', 'ID_SOLICITUD_MAESTRA');
    }

    public function SolicitudEsclava()
    {
        return $this->hasOne('Solicitud', 'id_solicitud', 'ID_SOLICITUD_ESCLAVA');
    }
}
