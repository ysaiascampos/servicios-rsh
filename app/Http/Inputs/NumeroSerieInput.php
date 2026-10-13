<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class NumeroSerieInput extends ServiceInput
{
    protected $campoTitular = 'run-ciudadano';

    protected function define()
    {
        $this->campos = [
            'run-ciudadano' => 'required|numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'numero-serie' => 'required|between:8,10'
        ];
    }
}
