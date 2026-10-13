<?php

namespace App\Models;
use App\Constantes\Constante;

use Eloquent;
use Exception;

/**
 * App\Models\Preguntas
 *
 * @property int $ID
 * @property string|null $CODIGO
 * @property string|null $NOMBRE
 * @property int|null $ESTADO estado del registro 1=activo; 0=inactivo
 * @property int|null $TIPO_INPUT 1=input text; 2=combobox; 3=checkbox
 * @property string|null $ID_PREG_PADRE V=Vivienda; E=Educación, S=Salud, O=Ocupación, Y=Ingreso
 * @property string|null $NOMBRE_CIUDADANO
 * @property string|null $INDICACION
 * @method static \Illuminate\Database\Eloquent\Builder|Preguntas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Preguntas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Preguntas query()
 * @method static \Illuminate\Database\Eloquent\Builder|Preguntas whereCODIGO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preguntas whereESTADO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preguntas whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preguntas whereIDPREGPADRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preguntas whereINDICACION($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preguntas whereNOMBRE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preguntas whereNOMBRECIUDADANO($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preguntas whereTIPOINPUT($value)
 * @mixin Eloquent
 */
class Preguntas extends Eloquent
{
    protected $table = 'ab_respuesta_pregunta';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $hidden = ['rn'];

    public static function findPreguntasByCodigo($codigoPregunta)
    {
        $datosDePreguntas = null;
        try {
            $datosDePreguntas = Preguntas::where('codigo', $codigoPregunta)
                ->where('estado', Constante::ESTADO_1_ACTIVO)
                ->first();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDePreguntas;
    }

    public static function findPreguntasByCodigoLista($listaCodigoPregunta)
    {
        $datosDePreguntas = null;
        try {
            $datosDePreguntas = Preguntas::select('codigo', 'nombre', 'tipo_input')
                ->whereIn('codigo', $listaCodigoPregunta)
                ->where('estado', Constante::ESTADO_1_ACTIVO)
                ->orderBy('codigo', 'ASC')
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDePreguntas;
    }

    public static function findPreguntasByPregPadre($listaCodigoPadre)
    {
        $datosDePreguntas = null;
        try {
            $datosDePreguntas = Preguntas::where('estado', Constante::ESTADO_1_ACTIVO)
                ->whereIn('id_preg_padre', $listaCodigoPadre)
                ->orderBy('codigo', 'ASC')
                ->get(['codigo', 'nombre', 'nombre_ciudadano', 'tipo_input', 'indicacion']);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDePreguntas;
    }

    public static function findPreguntasByPregPadreconID($listaCodigoPadre)
    {
        $datosDePreguntas = null;
        try {
            $datosDePreguntas = Preguntas::where('estado', Constante::ESTADO_1_ACTIVO)
                ->whereIn('id_preg_padre', $listaCodigoPadre)
                ->orderBy('codigo', 'ASC')
                ->get(['id', 'codigo', 'nombre', 'nombre_ciudadano', 'tipo_input', 'indicacion']);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $datosDePreguntas;
    }
}
