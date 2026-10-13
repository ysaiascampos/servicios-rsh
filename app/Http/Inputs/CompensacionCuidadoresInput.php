<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;

class CompensacionCuidadoresInput extends ServiceInput
{

    protected function define()
    {
        // TODO: Implement define() method.
        $this->campos = [
            'subtipo' => 'string|lowercase|in:' .
                implode(',', Constante::$SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING),
            'cuidador' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'cuidado' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'principal' => 'boolean'
        ];
    }
}
