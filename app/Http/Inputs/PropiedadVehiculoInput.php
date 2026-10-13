<?php


namespace App\Http\Inputs;

use App\Constantes\Constante;

class PropiedadVehiculoInput extends ServiceInput
{

    protected function define()
    {
        $this->campos = [
        'id-vehiculo' => 'numeric',
        'motivo' => 'string|lowercase|in:' .
            implode(',', Constante::$SUB_TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL_STRING),
        'anio' => 'numeric|min:'.Constante::PROPIEDAD_VEHICULO_ANIO_MINIMO,
        'mes' => 'numeric|min:1|max:12'
    ];
    }
}
