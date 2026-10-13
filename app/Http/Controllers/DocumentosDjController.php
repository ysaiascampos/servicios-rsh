<?php

namespace App\Http\Controllers;

use App\Servicios\DatosDucumentosDj;
use Exception;

class DocumentosDjController extends Controller
{

    public function show($documentosDj)
    {
        try {
            $datos = new DatosDucumentosDj($documentosDj);

            return $datos->show();
        } catch (Exception $ex) {
             $this->handleException($ex);
            return response()->json([
                'status' => 'error',
                'errores' => 'El archivo no existe en el servidor',
            ], 200);
        }
    }
}
