<?php

namespace App\Http\ResponseMappers;


class EducacionSuperiorMapper extends ResponseMapper
{
    public function __construct($tramite)
    {
        $educacionSuperior = $tramite->educacionSuperior;
        $this->campos = [
            "run-titular" => intval($educacionSuperior['run-titular']),
            "mes" => intval($educacionSuperior['mes']),
            "motivo" => $educacionSuperior['motivo'],
            "matricula" => $educacionSuperior['matricula']
        ];
    }

    private function mapRespuesta($Data, $campo)
    {
        //esta funcion permite retornar una estructura si en necesario a futuro
        return intval($Data->$campo);
    }

}
