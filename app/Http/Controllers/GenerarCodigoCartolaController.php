<?php

namespace App\Http\Controllers;

use App\Clases\ApiGenerarCodigoCartola;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Http\ResponseMappers\JsonMapper;
use Exception;

class GenerarCodigoCartolaController extends Controller
{
    public function show()
    {
        try {
            $input = request()->all();
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
        try {
            $apiGenerarCodigoCartola = new ApiGenerarCodigoCartola;
            $resultado = $apiGenerarCodigoCartola->generarCodigo($input);

            return new JsonMapper($resultado);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

}
