<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class DetallePensionAlimentoInput extends ServiceInput
{
    protected function define()
    {
        $this->campos = [
            'id' => 'numeric',
            'pagador' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'receptor' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'beneficiario' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'monto' => 'numeric|between:' .
                implode(',', Constante::$MONTO_0_Y_2147483647_POSIBLES_RESPUESTAS_NUMERIC)
        ];
    }
}
