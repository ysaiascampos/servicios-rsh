<?php


namespace App\Http\Inputs;


class MesIngresoInput extends ServiceInput
{
    protected function define()
    {
        $this->campos = [
            'anio' => 'digits:4',
            'mes' => 'numeric|min:1|max:12',
            'valor' => 'numeric|min:0|max:9999999999'
        ];
    }
}
