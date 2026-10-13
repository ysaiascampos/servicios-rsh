<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Exception;

/**
 * App\Models\ParentescoSexo
 *
 * @property int $ID
 * @property string $NOMBRE
 * @property int $PER_SEXO 1=hombre; 2=mujer
 * @property int $SEXO_JH 1=hombre; 2=mujer
 * @property int|null $ESTADO 1=activo; 0=eliminado
 * @method static \Illuminate\Database\Eloquent\Builder|ParentescoSexo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParentescoSexo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ParentescoSexo query()
 * @method static \Illuminate\Database\Eloquent\Builder|ParentescoSexo whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParentescoSexo whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParentescoSexo whereNOMBRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParentescoSexo wherePERSEXO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ParentescoSexo whereSEXOJH($value)
 * @mixin Eloquent
 */
class ParentescoSexo extends Eloquent
{
    protected $table = 'ab_parentesco_sexo';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $hidden = ['rn'];

    public static function getModel()
    {
        return new ParentescoSexo();
    }

    /*
     * Busca en ab_parentesco_sexo y arroja el nombre del parentesco de acuerdo a su sexo y al sexo del jefe de hogar
     * ej: "Hijo sólo del Jefe de hogar"
     *     "Hija sólo del Jefe de hogar"
     *     "Hijo sólo de la Jefa de hogar"
     *     "Hija sólo de la Jefa de hogar"
     */
    public function findByIdParentescoYIdSexo($idParentesco, $idSexo, $sexoJH)
    {
        $datosDeParentescoSexo = null;
        try {
            $datosDeParentescoSexo = ParentescoSexo::getModel()
                ->select('nombre')
                ->where('estado', Constante::ESTADO_1_ACTIVO)
                ->where('id', $idParentesco)
                ->where('per_sexo', $idSexo)
                ->where('sexo_jh', $sexoJH)
                ->first();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDeParentescoSexo;
    }
}
