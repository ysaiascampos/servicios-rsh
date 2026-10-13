<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ComplementoCuidador
 *
 * @property int $CUIDADORES_ID
 * @property int|null $ID_SOLICITUD
 * @property int|null $C_TCOMPLEMENTO_CUIDADORES
 * @property int|null $RUT_CUIDADOR
 * @property int|null $RUT_CUIDADO
 * @property bool|null $VIGENTE
 * @property bool|null $CALCULOACTIVO
 * @property int|null $FUENTE
 * @property string|null $FECHA_CREACION
 * @property int|null $PRINCIPAL
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador whereCALCULOACTIVO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador whereCUIDADORESID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador whereCTCOMPLEMENTOCUIDADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador whereFECHACREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador whereFUENTE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador whereIDSOLICITUD($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador whereRUTCUIDADOR($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador whereRUTCUIDADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComplementoCuidador whereVIGENTE($value)
 * @mixin Eloquent
 */
class ComplementoCuidador extends Eloquent
{
    protected $table = 'ab_complemento_cuidadores';
    protected $primaryKey = 'CUIDADORES_ID';
    public $timestamps = false;
}
