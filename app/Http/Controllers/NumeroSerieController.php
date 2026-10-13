<?php

namespace App\Http\Controllers;

use App\Clases\NumeroSerie;
use App\Http\Inputs\NumeroSerieInput;
use App\Http\ResponseMappers\JsonMapper;
use Exception;

class NumeroSerieController extends Controller
{
    public function validarNumeroSerie()
    {
        try {
            $input = new NumeroSerieInput(request()->all());
            $resultado = NumeroSerie::validar($input);

            return new JsonMapper($resultado);
        } catch (Exception $ex) {
            $detalle = $ex->getMessage();
            $errorJson = json_decode($detalle, true);

            $mensaje = $detalle;

            if (json_last_error() === JSON_ERROR_NONE && isset($errorJson['run-ciudadano'][0])) {
                $mensaje = $errorJson['run-ciudadano'][0];
            }
            if (json_last_error() === JSON_ERROR_NONE && isset($errorJson['numero-serie'][0])) {
                $mensaje = $errorJson['numero-serie'][0];
            }

            $mensajerespuesta = [
                'estado' => '0',
                'msg' => $mensaje,
            ];

            return new JsonMapper($mensajerespuesta);
        }
    }
}
