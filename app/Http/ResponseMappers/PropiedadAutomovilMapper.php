<?php

namespace App\Http\ResponseMappers;


class PropiedadAutomovilMapper extends ResponseMapper
{
    public function __construct($tramite)
    {
        if ($tramite->vehiculo) {
            $this->campos = ['motivo' => $tramite->vehiculo->MOTIVO,
                'anio' => $tramite->vehiculo->PERIODO_VENTA_ANIO,
                'mes' => $tramite->vehiculo->PERIODO_VENTA_MES,
                'vehiculo' => [
                    'id-vehiculo' => self::mapRespuesta($tramite->vehiculo,'VEH_ID'),
                    'marca' => $tramite->vehiculo->VEH_DET_MARCA,
                    'modelo' => $tramite->vehiculo->VEH_DET_MODELO,
                    'anio' => $tramite->vehiculo->VEH_DET_ANNIO_FABRICACION,
                    'patente' => $tramite->vehiculo->VEH_DET_PATENTE
                ]

            ];
        }

    }
    private function mapRespuesta($Data, $campo)
    {
        //esta funcion permite retornar una estructura si en necesario a futuro
        return intval($Data->$campo);
    }

}
