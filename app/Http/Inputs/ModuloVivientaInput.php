<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;

class ModuloVivientaInput extends ServiceInput
{

    protected function define()
    {
        $this->campos = [
            'v1' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V1_1_A_11_POSIBLES_RESPUESTAS),
            'v2' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V2_1_A_6_POSIBLES_RESPUESTAS),
            'v3' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V3_1_A_5_POSIBLES_RESPUESTAS),
            'v4' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V4_1_A_6_POSIBLES_RESPUESTAS),
            'v5' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V5_0_A_2_POSIBLES_RESPUESTAS),
            'v6' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V6_0_A_3_POSIBLES_RESPUESTAS),
            'v7' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V7_0_Y_99_POSIBLES_RESPUESTAS),
            'v8' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V8_1_A_2_POSIBLES_RESPUESTAS),
            'v9' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V9_1_A_4_DEPARTAMENTO),
            'v9-estado' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V9EC_1_A_3_POSIBLES_RESPUESTAS),
            'v10' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V10_1_A_7_POSIBLES_RESPUESTAS),
            'v10-estado' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V10EC_1_A_3_POSIBLES_RESPUESTAS),
            'v11' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V11_1_A_7_POSIBLES_RESPUESTAS),
            'v11-estado' => 'integer|in:' . implode(',', Constante::$RESPUESTA_V11EC_1_A_3_POSIBLES_RESPUESTAS),
            'compania-luz' => 'integer',
            'compania-agua' => 'integer',
            'numero-cliente-luz' => 'alpha_num|max:20',
            'numero-cliente-agua' => 'alpha_num|max:30'
        ];
    }
}
