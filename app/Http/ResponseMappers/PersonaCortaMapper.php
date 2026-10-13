<?php

namespace App\Http\ResponseMappers;

class PersonaCortaMapper extends ResponseMapper
{

    public function __construct($ciudadano)
    {
        $this->campos = [
            'run' => self::entero($ciudadano->PER_RUN),
            'dv' => $ciudadano->PER_DV,
            'nombres' => $ciudadano->PER_NOMBRES,
            'apellido-paterno' => $ciudadano->PER_APELLIDOPATERNO,
            'apellido-materno' => $ciudadano->PER_APELLIDOMATERNO,
            'edad' => self::entero($ciudadano->PER_EDAD),
        ];
        return $this;
    }
}
