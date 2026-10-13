<?php

namespace App\Http\Controllers;

use App\Clases\DatosGlobales;
use App\Http\Inputs\DatosGlobalesInput;
use App\Http\ResponseMappers\DatosGlobalesMapper;
use Exception;

class DatosGlobalesController extends Controller
{

    public function show()
    {
        try {

            $input = new DatosGlobalesInput(request()->all());
            $datos = new DatosGlobales($input);

            return new DatosGlobalesMapper($datos);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
