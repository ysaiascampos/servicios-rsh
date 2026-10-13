<?php

namespace App\Http\ResponseMappers;

class GrupoFamiliarExport extends GrupoFamiliarMapper
{
    public function __construct($grupoFamiliar, $integrantes, $preguntasRespuestas)
    {

        if (empty($grupoFamiliar)) {
            $this->campos = [];
        } else {
            $this->campos = [
                'direccion' => new DomicilioMapper($grupoFamiliar),
                'vivienda' => new ViviendaMapper($grupoFamiliar)
            ];
            if (!empty($integrantes)) {
                $this->campos['composicion'] = ComposicionFamiliarMapper::new()->mapperFull($integrantes, $preguntasRespuestas);
            }

            $this->campos = array_merge($this->campos, [
                'grupo-familiar-fecha-calificacion' => ($grupoFamiliar) ? $grupoFamiliar->FECHA_CALIFICACION : null,
                'num-personas' => self::entero($grupoFamiliar->cantidadIntegrantes),
                'num-personas-dependientes' => self::entero($grupoFamiliar->cantidadConDiscapacidad),
                'num-personas-menores-edad' => self::entero($grupoFamiliar->cantidadMenoresDeEdad),
                'num-personas-adulto-mayor' => self::entero($grupoFamiliar->cantidadAdultoMayor),
                'n-tipo-in-dep' => isset($grupoFamiliar->N_TIPO_IN_DEP)?$grupoFamiliar->N_TIPO_IN_DEP:0,
                'n-tipo-in-dicap' => isset($grupoFamiliar->N_TIPO_IN_DICAP)?$grupoFamiliar->N_TIPO_IN_DICAP:0,
                'n-tipo-in-inval' => isset($grupoFamiliar->N_TIPO_IN_INVAL)?$grupoFamiliar->N_TIPO_IN_INVAL:0,
                'n-tipo-in-ed-esp' => isset($grupoFamiliar->N_TIPO_IN_ED_ESP)?$grupoFamiliar->N_TIPO_IN_ED_ESP:0,
                'tramo' => $this->mapTramo($grupoFamiliar->tramo),
                'ingreso-total' => $this->mapIngresosTotales($grupoFamiliar),
                'mensaje-ingreso-pension-alimentaria' => $grupoFamiliar->detallePensionAlimentoExport,
                'datos-complementarios' => $this->mapDatosComplementarios($grupoFamiliar->datosComplementarios)
            ]);
        }
    }

    private function mapTramo($tramo)
    {
        $map = [];
        if ($tramo) {
            $map = [
                'numero' => self::entero($tramo->numero),
                'nombre' => $tramo->nombre,
                'mensaje-tramo' => $tramo->mensaje
            ];
        }
        return $map;
    }
}
