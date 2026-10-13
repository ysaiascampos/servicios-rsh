<?php

namespace App\Http\Controllers;

use App\Clases\DatosDeCompaniasLuz;
use App\Http\ResponseMappers\DatosCompaniasLuzMapper;
use Exception;

class CompaniasLuzController extends Controller
{

    public function show()
    {
        try {
            $DatosDeCompaniasLuz = DatosDeCompaniasLuz::getLista();
            return new DatosCompaniasLuzMapper($DatosDeCompaniasLuz);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
