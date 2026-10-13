<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;

class ComplementoEstablecimientoEducacionalInput extends ServiceInput
{

    protected function define()
    {
        // TODO: Implement define() method.
        $this->campos = [
            'run-titular' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'subtipo' => 'numeric|min:1|max:3',
            'monto-beca' => 'numeric|between:' .
                implode(',', Constante::$MONTO_0_Y_2147483647_POSIBLES_RESPUESTAS_NUMERIC),
            'monto-externo' => 'numeric|between:' .
                implode(',', Constante::$MONTO_0_Y_2147483647_POSIBLES_RESPUESTAS_NUMERIC),
            'monto-hogar' => 'numeric|between:' .
                implode(',', Constante::$MONTO_0_Y_2147483647_POSIBLES_RESPUESTAS_NUMERIC)
        ];
    }
}
