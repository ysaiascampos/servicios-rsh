<?php


namespace App\Exceptions;


class DetalleError
{
    public $codigo;
    public $mensaje;
    public $info;

    public function __construct($codigo, $mensaje, array $info = null)
    {
        $this->codigo = $codigo;
        $this->mensaje = $mensaje;
        $this->info = $info;
    }
}
