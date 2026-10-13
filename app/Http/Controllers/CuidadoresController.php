<?php

namespace App\Http\Controllers;

use App\Servicios\DatosCuidadorDependiente;
use Exception;

class CuidadoresController extends Controller
{

    public function show()
    {
        try {
            $input = request()->all();
            $datos = new DatosCuidadorDependiente($input);

            return $datos->show();
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
