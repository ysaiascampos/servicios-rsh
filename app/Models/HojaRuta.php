<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\HojaRuta
 *
 * @property int|null $ID_RUTA
 * @property int|null $RUN_ENCUESTADOR
 * @property string|null $FECHA_CREACION
 * @property string|null $FECHA_ASIGNACION
 * @property int|null $RUTA_CALCULOACTIVO
 * @property int|null $RUTA_VIGENTE
 * @property int|null $ID_USUARIO_CREACION
 * @property string|null $COMENTARIO
 * @property int|null $COM_ID
 * @property int|null $ID_PADRE_RUTA
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta query()
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta whereCOMENTARIO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta whereCOMID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta whereFECHAASIGNACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta whereFECHACREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta whereIDPADRERUTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta whereIDRUTA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta whereIDUSUARIOCREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta whereRUNENCUESTADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta whereRUTACALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|HojaRuta whereRUTAVIGENTE($value)
 * @mixin Eloquent
 */
class HojaRuta extends Eloquent
{
    protected $table = 'ab_hoja_ruta';
    protected $primaryKey = 'ID_RUTA';
    public $timestamps = false;

    public function solicitudes()
    {
        return $this->hasMany('RutaSolicitud', 'id_ruta');
    }

}
