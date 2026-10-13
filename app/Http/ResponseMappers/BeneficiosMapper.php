<?php

namespace App\Http\ResponseMappers;

class BeneficiosMapper extends ResponseMapper
{
    public function __construct($integrantesExport, $integrantesAsignacion, $ciudadano)
    {
        if (empty($integrantesExport->beneficios)) {
            $this->campos = self::dummyBeneficios($integrantesAsignacion, $ciudadano);
        } else {
            $this->campos = self::formatBeneficios($integrantesExport->beneficios);
        }
    }

    private function formatBeneficios($beneficios) {
        $salida = [];
        foreach ($beneficios as $item) {
            $salida[$item['integrante']->PER_RUN] = [
                    'nombres' => $item['integrante']->PER_NOMBRES,
                    'apellido-paterno' => $item['integrante']->PER_APELLIDOPATERNO,
                    'apellido-materno' => $item['integrante']->PER_APELLIDOMATERNO,
                    'cantidad-otorgados' => count($item['otorgados']),
                    'otorgados' => $item['otorgados'],
                    'cantidad-potenciales' => count($item['potenciales']),
                    'potenciales' => $item['potenciales']
                ];
        }
        return $salida;
    }

    private function dummyBeneficios($integrantesAsignacion, $ciudadano)
    {
        $salida = [];
        if ($integrantesAsignacion) {
            foreach ($integrantesAsignacion as $integrante) {
                $salida = [
                    $integrante->PER_RUN => [
                        'nombres' => $integrante->PER_NOMBRES,
                        'apellido-paterno' => $integrante->PER_APELLIDOPATERNO,
                        'apellido-materno' => $integrante->PER_APELLIDOMATERNO,
                        'cantidad-otorgados' => 0,
                        'otorgados' => [],
                        'cantidad-potenciales' => 0,
                        'potenciales' => []
                    ]
                ];
            }
        } else {
            $salida = [$ciudadano->PER_RUN =>
                [
                    'nombres' => $ciudadano->PER_NOMBRES,
                    'apellido-paterno' => $ciudadano->PER_APELLIDOPATERNO,
                    'apellido-materno' => $ciudadano->PER_APELLIDOMATERNO,
                    'cantidad-otorgados' => 0,
                    'otorgados' => [],
                    'cantidad-potenciales' => 0,
                    'potenciales' => []
                ]
            ];
        }
        return $salida;
    }
}
