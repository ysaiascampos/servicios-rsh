<?php


namespace App\Http\ResponseMappers;


class BienRaizCompMapper extends ResponseMapper
{
    protected $propiedad;

    public function __construct($tramite)
    {
        if (count($tramite->propietarios) > 0) {
            $this->propiedad = $tramite->propietarios->first();
            $this->campos = [
                'rol' => self::mapRespuesta($this->propiedad,"ROL"),
                'ro-ofuscado' => $this->propiedad->ROL_OFUSCADO,
                'direccion' => $this->propiedad->DIRECCION,
                'direccion-ofuscada' => $this->propiedad->DIRECCION_OFUCADA,
                'propietarios' => self::mapDatosComplementarios($tramite->propietarios),
            ];
        }
    }

    private function mapRespuesta($Data, $campo)
    {
        //esta funcion permite retornar una estructura si en necesario a futuro
        return intval($Data->$campo);
    }

    private function mapDatosComplementarios($datosComplementarios)
    {
        $map = [];
        if ($datosComplementarios) {
            foreach ($datosComplementarios as $dato) {
                $map[] = [
                    'rut' => $dato->RUT,
                    'dv' => $dato->DV,
                    'nombre' => $dato->NOMBRES,
                    'apellido-paterno' => $dato->APELLIDO_PATERNO,
                    'apellido-materno' => $dato->APELLIDO_MATERNO,
                    'parentesco' => $dato->PARENTESCO,
                    'participacion' => $dato->PORCENTAJE
                ];
            }
        }
        return $map;
    }

}
