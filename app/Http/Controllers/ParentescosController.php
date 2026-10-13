<?php

namespace App\Http\Controllers;


use App\Clases\DatosDeParentescos;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Http\ResponseMappers\JsonMapper;
use App\Models\Parentesco;
use Exception;
use Illuminate\Support\Facades\Validator;

class ParentescosController extends Controller
{
    public function show($runCiudadano)
    {
        try {
            $this->validarInput($runCiudadano);
            $DatosDeParentescos = new DatosDeParentescos;
            $data = $DatosDeParentescos->arbolGeneologico($runCiudadano);

            return new JsonMapper($data);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function showListaParentesco()
    {
        $lista = Parentesco::obtenerParentescoEnCache();
        return new JsonMapper($lista);
    }

    public function validarInput($runCiudadano)
    {
        $runCiudadano = str_replace(['.', '-', ' '], '', $runCiudadano);
        $runCiudadano = preg_replace("/[^0-9?!]/", "", $runCiudadano);
        $entradas = ['run' => $runCiudadano];
        $reglas = [
            'run' => 'required|numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
        ];
        $validador = Validator::make($entradas, $reglas);
        if ($validador->fails()) {
            throw ExcepcionValidacion::new($validador->errors(), Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO)
                ->setTitular($runCiudadano);
        }
    }
}
