<?php


namespace App\Http\Inputs;


class PropietarioBienRaizInput extends ServiceInput
{
    protected function define()
    {
        // TODO: Implement define() method.
        $this->campos = [
            'run' => 'numeric|between:' . implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'participacion' => 'numeric|min:1|max:99'
        ];
    }
}
