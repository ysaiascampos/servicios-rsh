<?php

namespace App\Http\ResponseMappers;

class GrupoFamiliarAsignacion extends GrupoFamiliarMapper
{
    public function __construct($grupoFamiliar, $integrantes, $preguntasRespuestas)
    {
        if (is_null($grupoFamiliar) || count(array_keys($grupoFamiliar->toArray())) > 3) {
            parent::__construct($grupoFamiliar, $integrantes, $preguntasRespuestas);

            if (!empty($this->campos)) {
                $this->campos = array_merge($this->campos, [
                    'folio' => self::mapFolio($grupoFamiliar),
                    'num-personas' => self::entero($grupoFamiliar->cantidadIntegrantes),
                    'num-personas-dependientes' => self::entero($grupoFamiliar->cantidadConDiscapacidad),
                    'num-personas-menores-edad' => self::entero($grupoFamiliar->cantidadMenoresDeEdad),
                    'num-personas-adulto-mayor' => self::entero($grupoFamiliar->cantidadAdultoMayor),
                    'ingreso-total' => self::mapIngresosTotales($grupoFamiliar),
                    'datos-complementarios' => self::mapDatosComplementarios($grupoFamiliar->datosComplementarios),
                    'fecha-confirmacion' => self::mapFechaConfirmacion($grupoFamiliar->confirmacion),
                    'solicitud-salud' => self::booleano(isset($grupoFamiliar->solicitudSalud) ? $grupoFamiliar->solicitudSalud : null),
                    'solicitud-cambio-domicilio' => self::booleano(isset($grupoFamiliar->solicitudCambioDomicilio) ? $grupoFamiliar->solicitudCambioDomicilio : null),
                    'fecha-ingreso' => isset($grupoFamiliar->confirmacion) ? $grupoFamiliar->confirmacion->fechaEncuesta : null
                ]);
            }
        } else {
            $this->campos = [
                'folio' => self::mapFolio($grupoFamiliar)
            ];
        }
    }

    private function mapFolio($grupoFamiliar)
    {
        return [
            'folio' => self::entero($grupoFamiliar ? $grupoFamiliar->GRUPFAMI_NUEVOFOLIO : null),
            'estado' => $grupoFamiliar ? $grupoFamiliar->estadoGrupo : null,
            'consistencia' => $grupoFamiliar ? $grupoFamiliar->consistenciaGrupo : null
        ];
    }

    private function mapFechaConfirmacion($confirmacion)
    {
        $map = [];
        if ($confirmacion) {
            $map['fecha-actualizacion'] = $confirmacion->fechaActualizacion;
            $map['run'] = self::entero($confirmacion->run);
            $map['nombre'] = $confirmacion->nombre;
        }
        return $map;
    }
}
