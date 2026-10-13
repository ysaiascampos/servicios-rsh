<?php

namespace App\Http\ResponseMappers;

use DateTime;
use App\Models\Personas;
use App\Models\QrCuidadoras;

class PersonaMapper extends PersonaCortaMapper
{

    public function __construct($ciudadano)
    {
        parent::__construct($ciudadano);
        $sexo = [null, 'Hombre', 'Mujer'][intval($ciudadano->PER_SEXO)];
        $difunto = $ciudadano->PER_FECHADEFUNCION != 0;
        $fechaNacimiento = (strlen($ciudadano->PER_FECHANACIMIENTO) == 8) ?
            DateTime::createFromFormat('Ymd', $ciudadano->PER_FECHANACIMIENTO)->format('Y-m-d') :
            $ciudadano->PER_FECHANACIMIENTO;
        $nacionalidad = ($ciudadano->PER_NACIONALIDAD == 1) ? "CHILENA" :"EXTRANJERO";
        $fuenteDependencia = Personas::fuenteDependencia($ciudadano->PER_RUN);
        $esCuidador = QrCuidadoras::validaEsCuidador($ciudadano->PER_RUN);

        $this->campos = array_merge($this->campos,[
            'id' => self::entero($ciudadano->PER_ID),
            'fecha-nacimiento' => $fechaNacimiento,
            'estado-civil' => $ciudadano->PER_ESTADOCIVIL,
            'telefono' => self::entero($ciudadano->PER_TELEFONO),
            'telefono-movil' => self::entero($ciudadano->PER_CELULAR),
            'email' => $ciudadano->PER_EMAIL,
            'sexo' => $sexo,
            'fecha-actualizacion' => $ciudadano->PER_FECHAACTUALIZACION,
            'difunto' => self::booleano($difunto),
            'tiene-rsh' => self::booleano($ciudadano->TIENE_RSH),
            'nacionalidad' => $nacionalidad,
            'validacion-email' => $ciudadano->validacion_email,
            'situacion-unico-per-id' => $ciudadano->situacion_unico_per_id,
            'persona-juridica' => self::booleano($ciudadano->P_JURIDICA),
            'cuidador' => self::booleano($esCuidador),
            'fuente-dependencia' => $fuenteDependencia
        ]);
    }
}
