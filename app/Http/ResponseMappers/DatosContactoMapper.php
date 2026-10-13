<?php


namespace App\Http\ResponseMappers;


use App\Constantes\Constante;

class DatosContactoMapper extends ResponseMapper
{
    public function __construct($ciudadano)
    {
        $this->campos['persona'] = new PersonaMapper($ciudadano);

        if (!is_null($ciudadano->puedeSerIncorporado)) {
            $this->campos['puede-ser-incorporado'] = $ciudadano->puedeSerIncorporado;
        }
        if (!empty($ciudadano->situacion_unico_folio)) {
            $this->campos['situacion-unico-folio'] = $ciudadano->situacion_unico_folio;
        }

        if (isset($ciudadano->institucionalizado)) {
            $this->campos['institucionalizado'] = self::mapInstitucionalizado($ciudadano->institucionalizado);
        }
    }

    private function mapInstitucionalizado($institucionalizado)
    {
        return [
            'institucionalizado' => self::booleano($institucionalizado->ES_INSTITUCIONALIZADO),
            'menor-de-edad' => self::booleano($institucionalizado->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD),
            'id-grupo-familiar' => self::entero(isset($institucionalizado->BNFGRUPFAMI_GRUPOFAMILIAR) ? $institucionalizado->BNFGRUPFAMI_GRUPOFAMILIAR : null),
            'nombre-institucion' => isset($institucionalizado->NOMBRE) ? $institucionalizado->NOMBRE : null,
            'id-institucion' => self::entero(isset($institucionalizado->ID_INSTITUCION) ? $institucionalizado->ID_INSTITUCION : null),
            'fecha-ingreso' => isset($institucionalizado->BNFGRUPFAMI_FECHA_INGRESO) ? $institucionalizado->BNFGRUPFAMI_FECHA_INGRESO : null,
            'id-solicitud' => self::entero(isset($institucionalizado->ID_SOLICITUD) ? $institucionalizado->ID_SOLICITUD : null),
        ];
    }
}
