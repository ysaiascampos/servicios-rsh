<?php

namespace App\Http\Controllers;

use App\Clases\NacionalidadService;
use App\Http\ResponseMappers\NacionalidadResponseMapper;
use Exception;

class NacionalidadController extends Controller
{
    private $nacionalidadService;
    private $responseMapper;

    public function __construct()
    {
        $this->nacionalidadService = new NacionalidadService();
        $this->responseMapper = new NacionalidadResponseMapper();
    }

    public function consultar()
    {
        try {
            $nacionalidades = $this->nacionalidadService->consultarNacionalidades();
            $mappedResponse = $this->responseMapper->map($nacionalidades);

            return $mappedResponse;
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
