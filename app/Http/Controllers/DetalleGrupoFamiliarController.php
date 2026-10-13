<?php


namespace App\Http\Controllers;


use App\Clases\DetalleGrupoFamiliar;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Http\ResponseMappers\DetalleGrupoFamiliarMapper;
use Exception;
use Illuminate\Support\Facades\Validator;

class DetalleGrupoFamiliarController extends Controller
{
    public function show($runCiudadano)
    {
        try {
            $this->validarParametros(['run' => $runCiudadano]);

            $detalle = new DetalleGrupoFamiliar($runCiudadano);
            return new DetalleGrupoFamiliarMapper($detalle);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function validarParametros($parametros)
    {
        $reglas = [
            'run' => 'required|numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC)
        ];
        $mensajes = [
            'run.required' => 'El RUN es un parámetro obligatorio',
            'run.numeric' => 'El RUN debe ser de tipo númerico',
            'run.between' => 'Verifique la longitud del RUN ingresado',
        ];
        $validador = Validator::make($parametros, $reglas, $mensajes);
        if ($validador->fails()) {
            throw new ExcepcionValidacion($validador->errors());
        }
    }
}
