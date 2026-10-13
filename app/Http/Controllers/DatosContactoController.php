<?php


namespace App\Http\Controllers;


use App\Clases\DatosDeContacto;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Http\ResponseMappers\DatosContactoMapper;
use App\Http\ResponseMappers\ErrorMapper;
use Exception;
use Illuminate\Support\Facades\Validator;

class DatosContactoController extends Controller
{
    public function show($runCiudadano)
    {
        try {
            $this->validarInput($runCiudadano);
            $ciudadano = DatosDeContacto::obtenerDatosContacto($runCiudadano);

            return new DatosContactoMapper($ciudadano);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
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
