<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;

class ActualizacionEstablecimientoEducacionalInput extends ServiceInput
{

    protected function define()
    {
        $this->campos = [
            'run-titular' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'subtipo' => 'numeric|min:1|max:2',
            'rbd-actual' => 'numeric|between:' .
                implode(',', Constante::$MONTO_0_Y_2147483647_POSIBLES_RESPUESTAS_NUMERIC),
            'anio' => 'digits:4',
            'mes' => 'numeric|min:1|max:12',
        ];
    }
}
