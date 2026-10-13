<?php
//ToDo : Normalizar mapeo de objetos básicos
/*
 * Mapper genérico basico para objetos que no requieren un procesamiento especial de mapeo.
 * Implementa funcionalidad de compresion gzip si el request lo solicita mediante
 * header 'accept-encoding: gzip'
 */

namespace App\Http\ResponseMappers;


class JsonMapper extends ResponseMapper
{
    public function __construct($objetoSerializable)
    {
        $this->campos = $objetoSerializable;
    }
}
