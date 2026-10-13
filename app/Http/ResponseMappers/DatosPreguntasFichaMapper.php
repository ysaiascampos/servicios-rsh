<?php

namespace App\Http\ResponseMappers;

class DatosPreguntasFichaMapper extends ResponseMapper
{
    private $datosDePreguntasFicha;
    private $modulo;
    private $run;
    public function __construct($DatosDePreguntasFicha, $modulo, $run = null)
    {
        $this->datosDePreguntasFicha = $DatosDePreguntasFicha;
        $this->modulo = $modulo;
        $this->run = $run;
        $this->campos = $this->show();
    }
    private function show() {
        if((int)$this->run>0){
            $datos = $this->datosDePreguntasFicha->showModulo($this->modulo, $this->run);
            $respuesta = $this->formatModuloConRun($datos);
        } else {
            $datos = $this->datosDePreguntasFicha->showModuloSinRun($this->modulo);
            $respuesta = $this->formatModuloSinRun($datos);
        }

        return $respuesta;
    }


    private function formatModuloConRun($datos) {

        return $datos;
    }
    private function formatModuloSinRun($datos) {

        return $datos;
    }
}
