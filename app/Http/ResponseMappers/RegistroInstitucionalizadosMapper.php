<?php


namespace App\Http\ResponseMappers;


use App\Constantes\Constante;

class RegistroInstitucionalizadosMapper extends ResponseMapper
{
    public static function new(): RegistroInstitucionalizadosMapper
    {
        return new self();
    }

    public function mapperBasico($integrantes, $preguntasRespuestas): RegistroInstitucionalizadosMapper
    {
        foreach ($integrantes as $integrante) {
            $this->campos['persona'] = new PersonaCortaMapper($integrante);
            $this->campos['ficha'] = new FichaMapper($integrante, $preguntasRespuestas);
            $this->campos['institucionalizado'] = self::mapInstitucionalizado($integrante->institucionalizado);
            $this->campos['director'] = self::mapCentro($integrante->institucionalizado);
        }
        return $this;
    }

    private function mapInstitucionalizado($institucionalizado)
    {
        return [
            'institucionalizado' => $institucionalizado->ES_INSTITUCIONALIZADO,
            'menor-de-edad' => $institucionalizado->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD,
            'id-grupo-familiar' => isset($institucionalizado->BNFGRUPFAMI_GRUPOFAMILIAR) ? $institucionalizado->BNFGRUPFAMI_GRUPOFAMILIAR : null,
            'nombre-institucion' => isset($institucionalizado->NOMBRE) ? $institucionalizado->NOMBRE : null,
            'id-institucion' => isset($institucionalizado->ID_INSTITUCION) ? $institucionalizado->ID_INSTITUCION : null,
            'fecha-ingreso' => isset($institucionalizado->BNFGRUPFAMI_FECHA_INGRESO) ? $institucionalizado->ID_INSTITUCION : null,
            'id-solicitud' => isset($institucionalizado->ID_SOLICITUD) ? $institucionalizado->ID_SOLICITUD : null,
        ];
    }
    private function mapCentro($institucionalizado)
    {
        return [
            'run-director-centro' => isset($institucionalizado->RUN_DIRECTOR_CENTRO) ? $institucionalizado->RUN_DIRECTOR_CENTRO : null,
            'dv-director-centro' => isset($institucionalizado->DV_DIRECTOR_CENTRO) ? $institucionalizado->DV_DIRECTOR_CENTRO : null,
            'nombre-director-centro' => isset($institucionalizado->NOMBRE_DIRECTOR_CENTRO) ? $institucionalizado->NOMBRE_DIRECTOR_CENTRO : null,
            'apellido-paterno-director-centro' => isset($institucionalizado->PATERNO_DIRECTOR_CENTRO) ? $institucionalizado->PATERNO_DIRECTOR_CENTRO : null,
            'apellido-materno-director-centro' => isset($institucionalizado->MATERNO_DIRECTOR_CENTRO) ? $institucionalizado->MATERNO_DIRECTOR_CENTRO : null,
            'email-director-centro' => isset($institucionalizado->EMAIL_DIRECTOR_CENTRO) ? $institucionalizado->EMAIL_DIRECTOR_CENTRO : null,
            'fecha-resolucion' => isset($institucionalizado->FECHA_RESOLUCION) ? $institucionalizado->FECHA_RESOLUCION : null,
        ];
    }
}
