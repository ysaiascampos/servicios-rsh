<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class DatosGlobalesInput extends ServiceInput
{

    protected function define()
    {
        $this->campos = [
            'origen-registro' => 'required|integer|exists:AB_ORIGEN_REGISTRO,ID_ORIGEN_REGISTRO',
            'run-ciudadano' => 'required|numeric|between:' . implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'run-incorporar-desvincular' => 'numeric|between:' . implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC)
        ];
    }
}
