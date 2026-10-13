<?php

namespace App\Http\Controllers;

use App\Clases\ApiRunificador;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Http\Inputs\RunificadorInput;
use App\Http\ResponseMappers\JsonMapper;
use Exception;

class ApiRunificadorController extends Controller
{
    public function show()
    {
        try {
            $input = new RunificadorInput(request()->all());
            $runCiudadano = $input->{'run-ciudadano'};
            $dv = $input->{'dv'};
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
        try {
            $apiRunificador = new ApiRunificador;
            $resultado = $apiRunificador->getPersona($runCiudadano,$dv);

            return new JsonMapper($resultado);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

}
