<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class MetricaInput extends ServiceInput
{
    protected function define()
    {
        /* latitud desde Visviri a Antartica
         * longitud desde la punta de Hanga-Roa hasta la Antartica de Chile.
         */
        $this->campos = [
            'hora-inicio' => 'date_format:"Y-m-d H:i:s"',
            'latitud' => 'numeric|between:' . implode(',', Constante::$GEO_LATITUD_73_Y_17_POSIBLES_RESPUESTAS_NUMERIC),
            'longitud' => 'numeric|between:' . implode(',',
                    Constante::$GEO_LONGITUD_109_Y_55_POSIBLES_RESPUESTAS_NUMERIC)
        ];
    }
}
