<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class AdjuntosInput extends ServiceInput
{
    public static $campoArchivo = 'documento';

    protected function define()
    {
        $this->campos = [
            'run-usuario' => 'numeric',
            'run-titular' => 'numeric',
            'documento' => 'file|mimes:' . Constante::ADJUNTOS_MIME . '|min:1|max:' . Constante::ADJUNTOS_MAX_SIZE_KB
        ];
    }
}
