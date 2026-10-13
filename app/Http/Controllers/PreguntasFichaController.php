<?php

namespace App\Http\Controllers;

use App\Clases\DatosDePreguntasFicha;
use App\Http\ResponseMappers\DatosPreguntasFichaMapper;
use App\Http\ResponseMappers\ErrorMapper;
use Exception;

class PreguntasFichaController extends Controller
{

    public function show($modulo, $run = null)
    {
        try {
            $datosDePreguntasFicha = new DatosDePreguntasFicha;
            return new DatosPreguntasFichaMapper($datosDePreguntasFicha, $modulo, $run);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function showEdad($modulo, $edad)
    {
        try {
            $datosDePreguntasFicha = new DatosDePreguntasFicha;
            return new DatosPreguntasFichaMapper($datosDePreguntasFicha, $modulo, $run);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
