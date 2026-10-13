<?php

namespace App\Models;

use Eloquent;

/**
 * App\Models\ParametroConfig
 *
 * @property int $PA_ID Identificador de la tabla parámetro
 * @property int|null $PA_ID_PADRE Identificador padre grupo específico de datos
 * @property string|null $PA_NOMBRE Nombre del parámetro
 * @property string|null $PA_DESCRIPCION Descripción del parámetro o valor de este
 * @property bool|null $PA_VIGENTE Vigencia del parámetro padre o hijo (1:vigente - 0:No vigente)
 * @property string|null $PA_FECHACREACION Fecha de creación o carga inicial del parámetro
 * @property string|null $PA_FECHAMODIFICACION Fecha de modificación del parámetro
 * @method static \Illuminate\Database\Eloquent\Builder|ParametroConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParametroConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParametroConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|ParametroConfig wherePADESCRIPCION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametroConfig wherePAFECHACREACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametroConfig wherePAFECHAMODIFICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametroConfig wherePAID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametroConfig wherePAIDPADRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametroConfig wherePANOMBRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParametroConfig wherePAVIGENTE($value)
 * @mixin Eloquent
 */
class ParametroConfig extends Eloquent
{
    protected $table = 'ab_parametro_config';
    protected $primaryKey = 'pa_id';
    public $timestamps = false;

    /*
     * Obtiene en valor de una constante dada
     */
    public static function valorDeConstante($paNombre)
    {
        return ParametroConfig::where('pa_nombre', $paNombre)
            ->where('pa_vigente', Constante::ESTADO_1_ACTIVO)
            ->select('pa_descripcion')
            ->first()
            ->PA_DESCRIPCION;
    }

}
