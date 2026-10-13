<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;


class IngresoLaboralInput extends ServiceInput
{

    protected function define()
    {
        // TODO: Implement define() method.
        $this->campos = [
            'run-titular' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'dependiente' => 'array|max:12|object:'.MesIngresoInput::class,
            'independiente' => 'array|max:12|object:'.MesIngresoInput::class,
        ];
    }
}
