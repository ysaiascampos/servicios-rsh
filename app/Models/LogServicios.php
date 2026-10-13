<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\LogServicios
 *
 * @property int $ID
 * @property string|null $CABECERA
 * @property string|null $REQUEST
 * @property string|null $SERVICIO
 * @property string|null $ACCION
 * @property string|null $FECHA_CONSUMO
 * @method static \Illuminate\Database\Eloquent\Builder|LogServicios newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogServicios newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogServicios query()
 * @method static \Illuminate\Database\Eloquent\Builder|LogServicios whereACCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogServicios whereCABECERA($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogServicios whereFECHACONSUMO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogServicios whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogServicios whereREQUEST($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogServicios whereSERVICIO($value)
 * @mixin Eloquent
 */
class LogServicios extends Eloquent
{
    protected $table = 'log_servicios';
    protected $primaryKey = 'ID';
    public $timestamps = false;
}
