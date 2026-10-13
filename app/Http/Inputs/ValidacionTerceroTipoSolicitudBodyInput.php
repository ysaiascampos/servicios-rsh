<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class ValidacionTerceroTipoSolicitudBodyInput extends ServiceInput
{
    protected function define()
    {
        //$this->removerCamposNull = true;

        $this->campos = [
            'tipo-validar-solicitud' => 'nullable|integer',
            'integrantes' => 'array|object:' . ValidacionTerceroIntegrantesInput::class,
        ];
    }

    public function getIntegrantes()
    {
        // considera solo una solicitud por consumo
        return $this->get('tipo-solicitud')->get($this->get('tipo-solicitud')->getTipo())->get('integrantes');
    }
}
