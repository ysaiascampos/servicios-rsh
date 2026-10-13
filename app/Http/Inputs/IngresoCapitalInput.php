<?php


namespace App\Http\Inputs;
use App\Constantes\Constante;

class IngresoCapitalInput extends ServiceInput
{

    protected function define()
    {
        // TODO: Implement define() method.
        $this->campos = [
            'run-titular' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'subtipo' => 'numeric|between:' .
                implode(',', Constante::$MONTO_0_Y_2147483647_POSIBLES_RESPUESTAS_NUMERIC)
        ];
    }
}
