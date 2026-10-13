<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;

class CompensacionEconomicaInput extends ServiceInput
{

    protected function define()
    {
        // TODO: Implement define() method.
        $this->campos = [
            'subtipo' => 'string|lowercase|in:' .
                implode(',', Constante::$SUB_TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA_STRING),
            'pagador' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'representante' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'beneficiario' => 'numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'monto' => 'numeric|min:' . Constante::COMPENSACION_ECONOMICA_MONTO_MINIMO . '|max:' . Constante::MONTO_2147483647_MAXIMO_NUMERIC,
            'fecha-inicio' => 'date_format:"Y-m-d"',
            'cuotas' => 'numeric|min:1|max:' . Constante::COMPENSACION_ECONOMICA_MAXIMO_CUOTAS
        ];
    }
}
