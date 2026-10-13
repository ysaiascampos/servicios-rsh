<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Utiles;

trait PreguntasYRespuestasESOVTrait
{
    public $preguntasYRespuestas;

    protected function obtenerPreguntasYRespuestasESOV($origenRegistro)
    {
        $origen = null;
        if(empty($origenRegistro)){
            $origen = 'municipal';
        }else {
            if (Utiles::inArrayMinuscula($origenRegistro,
                Constante::$ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS)) {
                $origen = 'ciudadano';
            }
            if (Utiles::inArrayMinuscula($origenRegistro,
                Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS)) {
                $origen = 'municipal';
            }
        }
        $this->preguntasYRespuestas =  Respuestas::findAllActivePorCategoriaESOV($origen);
    }
}
