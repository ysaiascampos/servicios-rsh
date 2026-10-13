<?php

namespace App\Http\ResponseMappers;

class DatosCompaniasAguaMapper extends ResponseMapper
{
    public function __construct($datosDeCompaniasAgua)
    {
        $this->campos = self::map($datosDeCompaniasAgua);
        return $this;
    }

    public function map($datosDeCompaniasAgua){
        $arrayDatos = [];
        foreach ($datosDeCompaniasAgua as $item) {
            $arrayDatos[] = [
                'id-compania-agua' => self::entero(isset($item->ID_COMPANIA_AGUA) ? $item->ID_COMPANIA_AGUA : null),
                'nombre' => isset($item->NOMBRE_COMPANIA_AGUA) ? $item->NOMBRE_COMPANIA_AGUA : null,
                'id-region' => self::entero(isset($item->ID_REGION) ? $item->ID_REGION : null),
                'run-compania' => self::entero(isset($item->RUN_COMPANIA) ? $item->RUN_COMPANIA : null),
                'dv-compania' => isset($item->DV) ? $item->DV : null,
            ];
        }
        return $arrayDatos;
    }
}
