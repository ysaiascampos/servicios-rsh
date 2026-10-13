<?php

namespace App\Http\ResponseMappers;


class BienRaizMapper extends ResponseMapper
{
    protected $propiedad;
    public function __construct($tramite)
    {
        if (count($tramite->propiedad) > 0) {
            $this->propiedad = $tramite->propiedad->first();
            $this->campos = ['rol' => $this->propiedad->ROL,
                'ro-ofuscado' => $this->propiedad->ROL_OFUSCADO,
                'direccion' => $this->propiedad->DIRECCION,
                'direccion-ofuscada' => $this->propiedad->DIRECCION_OFUCADA
            ];
        }

    }
    private function mapRespuesta($Data, $campo)
    {
        //esta funcion permite retornar una estructura si en necesario a futuro
        return intval($Data->$campo);
    }

}
