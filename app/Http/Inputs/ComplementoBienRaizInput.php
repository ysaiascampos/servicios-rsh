<?php


namespace App\Http\Inputs;


class ComplementoBienRaizInput extends ServiceInput
{

    protected function define()
    {
        // TODO: Implement define() method.
        $this->campos = [
            'rol' => 'regex:/([0-9]+-[0-9]+-[0-9]+)/',
            'propietarios' => 'array|object:'.PropietarioBienRaizInput::class.'|min:1',
        ];
    }
}
