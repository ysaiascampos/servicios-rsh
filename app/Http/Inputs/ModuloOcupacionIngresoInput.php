<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;

class ModuloOcupacionIngresoInput extends IntegranteInput
{

    /**
     * @inheritDoc
     */
    protected function define()
    {
        $this->campos = [
            'run-titular' => 'numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),

            'o1' => 'numeric',
            'o2' => 'numeric',
            'o3' => 'numeric',
            'o4' => 'numeric',
            'o5' => 'numeric',
            'o6' => 'numeric',
            'o7' => 'numeric',
            'o8' => 'numeric',
            'o9' => 'numeric',
            'y1' => 'numeric',
            'y2' => 'numeric',
            'y3' => 'numeric',

            'y1-m1' => 'numeric',
            'y1-m2' => 'numeric',
            'y1-m3' => 'numeric',
            'y1-m4' => 'numeric',
            'y1-m5' => 'numeric',
            'y1-m6' => 'numeric',
            'y1-m7' => 'numeric',
            'y1-m8' => 'numeric',
            'y1-m9' => 'numeric',
            'y1-m10' => 'numeric',
            'y1-m11' => 'numeric',
            'y1-m12' => 'numeric',

            'y2-m1' => 'numeric',
            'y2-m2' => 'numeric',
            'y2-m3' => 'numeric',
            'y2-m4' => 'numeric',
            'y2-m5' => 'numeric',
            'y2-m6' => 'numeric',
            'y2-m7' => 'numeric',
            'y2-m8' => 'numeric',
            'y2-m9' => 'numeric',
            'y2-m10' => 'numeric',
            'y2-m11' => 'numeric',
            'y2-m12' => 'numeric',
        ];
    }
}
