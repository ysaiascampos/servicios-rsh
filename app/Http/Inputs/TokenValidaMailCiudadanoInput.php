<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class TokenValidaMailCiudadanoInput extends ServiceInput
{
    protected $campoTitular = 'run_ciudadano';

    protected function define()
    {
        $this->campos = [
            'run_ciudadano' => 'required|numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'email_ciudadano' => 'required',
            'token_ciudadano' => 'required'
        ];
    }
}
