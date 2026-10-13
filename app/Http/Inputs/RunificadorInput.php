<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class RunificadorInput extends ServiceInput
{
    protected $campoTitular = 'run-ciudadano';

    protected function define()
    {
        $this->campos = [
            'run-ciudadano' => 'required|numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'dv' => 'string|min:1|max:1',
        ];
        $this->messages = [
            'run.required' => 'El RUN es un parámetro obligatorio',
            'run.numeric' => 'El RUN debe ser de tipo númerico',
            'run.between' => 'Verifique la longitud del RUN ingresado',
            'dv.required' => 'El DV es un parámetro obligatorio',
            'dv.between' => 'Verifique la longitud del DV ingresado',
        ];
    }
}
