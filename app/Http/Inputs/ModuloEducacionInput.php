<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;

class ModuloEducacionInput extends IntegranteInput
{

    /**
     * @inheritDoc
     */
    protected function define()
    {
        $this->campos = [
            'run-titular' => 'numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'e1' => 'integer|in:' . implode(',', Constante::$RESPUESTA_E1_1_A_2_POSIBLES_RESPUESTAS),
//            'e2' => 'integer|in:' . implode(',', Constante::$RESPUESTA_E2_1_A_12_POSIBLES_RESPUESTAS),
            'e3' => 'integer|in:' . implode(',', Constante::$RESPUESTA_E3_0_A_41_POSIBLES_RESPUESTAS),
            'e4' => 'integer|in:' . implode(',', Constante::$RESPUESTA_E4_0_A_15_POSIBLES_RESPUESTAS)
        ];
    }
}
