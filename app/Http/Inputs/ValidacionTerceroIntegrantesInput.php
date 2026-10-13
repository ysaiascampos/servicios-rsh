<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class ValidacionTerceroIntegrantesInput extends ServiceInput
{
    protected function define()
    {
        //$this->removerCamposNull = true;

        $this->campos = [
            'id-solicitud' => 'numeric' ,
            'run-titular' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'email' => '',
            'email-confirmar' => '',
        ];
    }
}
