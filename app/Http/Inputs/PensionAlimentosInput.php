<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class PensionAlimentosInput extends ServiceInput
{
    protected function define()
    {
        $this->campos = [
            'subtipo' => 'string|lowercase|in:' .
                implode(',', Constante::$SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_STRING),
            'detalle' => 'object:'.DetallePensionAlimentoInput::class,
        ];
    }
}
