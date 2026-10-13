<?php

namespace App\Http\ResponseMappers;

class DatosCompaniasLuzMapper extends ResponseMapper
{
    public function __construct($datosDeCompaniasLuz)
    {
        $this->campos = self::map($datosDeCompaniasLuz);
        return $this;
    }

    public function map($datosDeCompaniasLuz){
        $arrayDatos = [];
        foreach ($datosDeCompaniasLuz as $item) {
            $arrayDatos[] = [
                'id-compania-luz' => self::entero(isset($item->ID_COMPANIA_LUZ) ? $item->ID_COMPANIA_LUZ : null),
                'nombre' => isset($item->NOMBRE_COMPANIA_LUZ) ? $item->NOMBRE_COMPANIA_LUZ : null,
                'id-region' => self::entero(isset($item->ID_REGION) ? $item->ID_REGION : null),
                'cod-compania' => self::entero(isset($item->COD_COMPANIA) ? $item->COD_COMPANIA : null),
            ];
        }
        return $arrayDatos;
    }
}
