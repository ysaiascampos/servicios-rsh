<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;

class ModuloSaludInput extends IntegranteInput
{

    /**
     * @inheritDoc
     */
    protected function define()
    {
        $this->campos = [
            'run-titular' => 'numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),

            's1' => 'numeric',
            's1a' => 'numeric',
            's1b' => 'numeric',
            's1c' => 'numeric',
            's1d' => 'numeric',
            's1e' => 'numeric',
            's1f' => 'numeric',
            's2a' => 'numeric',
            's2b' => 'numeric',
            's2c' => 'numeric',
            's2d' => 'numeric',
            's2e' => 'numeric',
        ];
    }
}
