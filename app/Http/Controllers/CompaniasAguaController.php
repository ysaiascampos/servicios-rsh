<?php

namespace App\Http\Controllers;

use App\Clases\DatosDeCompaniasAgua;
use App\Http\ResponseMappers\DatosCompaniasAguaMapper;
use Exception;

class CompaniasAguaController extends Controller
{

    public function show()
    {
        try {
            $DatosDeCompaniasAgua = DatosDeCompaniasAgua::getLista();
            return new DatosCompaniasAguaMapper($DatosDeCompaniasAgua);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
