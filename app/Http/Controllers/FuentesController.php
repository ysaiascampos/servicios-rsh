<?php

namespace App\Http\Controllers;

use App\Clases\DatosDeFuentes;
use App\Http\ResponseMappers\JsonMapper;
use Exception;

class FuentesController extends Controller
{

    public function show()
    {
        try {
            $datosDeFuentes = DatosDeFuentes::getListaFuentes();
            return new JsonMapper($datosDeFuentes);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
