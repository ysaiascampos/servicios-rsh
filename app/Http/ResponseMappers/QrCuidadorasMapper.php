<?php

namespace App\Http\ResponseMappers;

use DateTime;

class QrCuidadorasMapper extends PersonaCortaMapper
{

    public function __construct($ciudadano)
    {
        parent::__construct($ciudadano);
        $sexo = [null, 'Hombre', 'Mujer'][intval($ciudadano->PER_SEXO)];
        $difunto = $ciudadano->PER_FECHADEFUNCION != 0;
        $fechaNacimiento = (strlen($ciudadano->PER_FECHANACIMIENTO) == 8) ?
            DateTime::createFromFormat('Ymd', $ciudadano->PER_FECHANACIMIENTO)->format('Y-m-d') :
            $ciudadano->PER_FECHANACIMIENTO;

        $this->campos = array_merge($this->campos,[
            'id' => self::entero($ciudadano->PER_ID),
            'fecha-nacimiento' => $fechaNacimiento,
            'sexo' => $sexo,
            'difunto' => self::booleano($difunto),
            'estado-cuidadoras' => self::booleano($ciudadano->ESTADO_CUIDADORAS),
            'rol-cuidadoras' => self::entero($ciudadano->ROL_CUIDADORAS)
        ]);
    }
}
