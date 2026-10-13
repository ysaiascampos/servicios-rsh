<?php


namespace App\Http\Inputs;


class ActualizacionBienRaizInput extends ServiceInput
{

    protected function define()
    {
        $this->campos = [
            'rol' => 'regex:/([0-9]+-[0-9]+-[0-9]+)/'
        ];
    }
}
