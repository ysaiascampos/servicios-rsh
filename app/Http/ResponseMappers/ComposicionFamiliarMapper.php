<?php

namespace App\Http\ResponseMappers;

use App\Utiles;

class ComposicionFamiliarMapper extends ResponseMapper
{

    public static function new(): ComposicionFamiliarMapper
    {
        return new self();
    }

    public function mapperBasico($integrantes, $preguntasRespuestas): ComposicionFamiliarMapper
    {
        foreach ($integrantes as $integrante) {
            $this->campos[] = new IntegranteGrupoFamiliarMapper($integrante, $preguntasRespuestas);
        }
        return $this;
    }

    public function mapperFull($integrantes, $preguntasRespuestas): ComposicionFamiliarMapper
    {
        foreach ($integrantes as $integrante) {
            $this->campos[] = new IntegranteGrupoFamiliarFullMapper($integrante, $preguntasRespuestas);
        }
        return $this;
    }

    public function mapperPorRun($integrantes, $folio): ComposicionFamiliarMapper
    {
        $this->campos = [
            'folio-del-grupo' => self::entero($folio),
            'integrantes' => []
        ];

        //ToDo : definir un mapper para los datos del integrante incorporando parentesco, nacionalidad y pueblo originario

        if (!empty($integrantes)) {
            foreach ($integrantes as $integrante) {
                $this->campos['integrantes'][$integrante->PER_RUN] = [
                    "run" => self::entero($integrante->PER_RUN),
                    "dv" => Utiles::dv($integrante->PER_RUN),
                    "nombres" => $integrante->PER_NOMBRES,
                    "apellido-paterno" => $integrante->PER_APELLIDOPATERNO,
                    "apellido-materno" => $integrante->PER_APELLIDOMATERNO,
                    "fecha-nacimiento" => date('d-m-Y', strtotime($integrante->PER_FECHANACIMIENTO)),
                    "edad" => self::entero(Utiles::getEdad($integrante->PER_FECHANACIMIENTO)),
                    "parentesco" => self::entero($integrante->BNFGRUPFAMI_PARENTESCO),
                    "parentesco-texto" => $integrante->NOMBRE_PARENTESCO,
                    "nacionalidad" => $integrante->nacionalidad,
                    "pueblo-originario" => $integrante->puebloOriginario
                ];
            }
        }
        return $this;
    }
}
