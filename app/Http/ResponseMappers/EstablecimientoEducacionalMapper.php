<?php

namespace App\Http\ResponseMappers;


class EstablecimientoEducacionalMapper extends ResponseMapper
{
    protected $colegio;
    protected $grupoFamiliar;
    protected $persona;
    protected $ficha;
    public function __construct($tramite)
    {
        $establecimientoEducacional = $tramite->establecimientoEducacional;
        $this->colegio = $establecimientoEducacional['colegio'];
        $this->grupoFamiliar = $establecimientoEducacional['grupoFamiliar'];
        $this->persona = $establecimientoEducacional['persona'];
        $this->ficha = $establecimientoEducacional['ficha'];
        $anio = null;
        $mes = null;
        if (!is_null($this->colegio->FECHA_CAMBIO) && strlen($this->colegio->FECHA_CAMBIO) > 4) {
            $anio = substr($this->colegio->FECHA_CAMBIO, 0, 4);
            $mes = substr($this->colegio->FECHA_CAMBIO, 4, 2);
        }
        $this->campos = [
            'id-grupo-familiar' => self::mapRespuesta($this->grupoFamiliar,'GRUPFAMI_ID'),
            'id-ficha' => self::mapRespuesta($this->ficha,'FICHA_ID'),
            "id" => self::mapRespuesta($this->persona,'PER_ID'),
            "run" => self::mapRespuesta($this->persona,'PER_RUN'),
            "dv" => self::mapRespuesta($this->persona,'PER_DV'),
            "nombres" => $this->persona->PER_NOMBRES,
            "apellido-paterno" => $this->persona->PER_APELLIDOPATERNO,
            "apellido-materno" => $this->persona->PER_APELLIDOMATERNO,
            "edad" => $this->persona->PER_EDAD,
            "rbd-actual" => $this->colegio->RBD,
            "nombre-establecimiento" => $this->colegio->NOMBRE_ESTA,
            "id-solicitud" => self::mapRespuesta($this->colegio,'IDSOLICITUD'),
            "vigente" => self::mapRespuesta($this->colegio,'VIGENTE'),
            "calculoactivo" => self::mapRespuesta($this->colegio,'CALCULOACTIVO'),
            "tipo-solicitud" => self::mapRespuesta($this->colegio,'C_TACTUALIZACION_EDUCACIONAL'),
            "fecha_cambio" => $this->colegio->FECHA_CAMBIO,
            "anio" => $anio,
            "mes" => $mes
        ];
    }

    private function mapRespuesta($Data, $campo)
    {
        //esta funcion permite retornar una estructura si en necesario a futuro
        return intval($Data->$campo);
    }

}
