<?php

namespace App\Http\Controllers;

use App\Servicios\BuscadorDomicilio;
use Exception;

class DuplicidadDomicilioController extends Controller
{

    public function show()
    {
        try {
            $input = request()->all();
            $datos = new BuscadorDomicilio($input);
            return $datos->show();

        } catch (Exception $ex) {
            return response()->json([
                'status' => "error",
                'message' => $ex->getMessage(),
            ], 404);
        }
    }
}
