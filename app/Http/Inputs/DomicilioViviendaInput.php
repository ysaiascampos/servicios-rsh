<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;

class DomicilioViviendaInput extends ModuloVivientaInput
{

    protected function define()
    {
        parent::define();
        $this->campos = array_merge($this->campos, [
            'comuna' => 'numeric|min:1',
            'localidad' => 'integer|min:' . Constante::CODIGO_LOCALIDAD_1_MINIMO_NUMERIC,
            'nombre-calle' => 'string|max:100',
            'codigo-calle' => 'numeric|min:' . Constante::CODIGO_CALLE_1_MINIMO_NUMERIC . '|max:' . Constante::CODIGO_CALLE_99999999999999999999_MAXIMO_NUMERIC,
            'numero-domicilio' => 'string|min:1|max:6',
            'sitio' => 'string|max:10',
            'block' => 'string|max:10',
            'departamento' => 'string|max:10',
            'zona' => 'string|max:10',
            'agrupacion-habitacional' => 'integer|max:999999999',
            'unidad-vecinal' => 'integer|min:0|max:9999999999',
            'casa' => 'string|max:10',
            'referencia' => 'string|min:15|max:400',
            'numero-cliente-luz' => 'alpha_num|max:20',
            'numero-cliente-agua' => 'alpha_num|max:30',
            'latitud' => 'numeric|between:' . implode(',', Constante::$GEO_LATITUD_73_Y_17_POSIBLES_RESPUESTAS_NUMERIC),
            'longitud' => 'numeric|between:' . implode(',', Constante::$GEO_LONGITUD_109_Y_55_POSIBLES_RESPUESTAS_NUMERIC),
            'referencia' => 'string|min:15|max:400',
            'compania-luz' => 'integer|min:0|max:9999999999',
            'compania-agua' => 'integer|min:0|max:9999999999',
        ]);
    }
}
