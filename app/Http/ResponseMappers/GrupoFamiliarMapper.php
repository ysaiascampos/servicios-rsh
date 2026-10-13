<?php

namespace App\Http\ResponseMappers;

class GrupoFamiliarMapper extends ResponseMapper
{
    public function __construct($grupoFamiliar, $integrantes, $preguntasRespuestas = null)
    {
        if (empty($grupoFamiliar)) {
            $this->campos = [];
        } else {
            $this->campos = [
                'direccion' => new DomicilioMapper($grupoFamiliar),
                'vivienda' => new ViviendaMapper($grupoFamiliar),
                'datos-grupo-familiar' => null
            ];
            $this->campos['datos-grupo-familiar']['id-grupo-familiar'] = isset($grupoFamiliar->GRUPFAMI_ID)?$grupoFamiliar->GRUPFAMI_ID:null;
            $this->campos['datos-grupo-familiar']['nuevofolio'] = isset($grupoFamiliar->GRUPFAMI_NUEVOFOLIO)?$grupoFamiliar->GRUPFAMI_NUEVOFOLIO:null;
            if (!empty($integrantes)) {
                $this->campos['composicion'] = ComposicionFamiliarMapper::new()->mapperBasico($integrantes, $preguntasRespuestas);
            }
        }
    }

    protected function mapIngresosTotales($grupoFamiliar)
    {
        return [
            'rango-ingreso' => $grupoFamiliar->rangoIngresos,
            'ingreso-trabajo-anual' => $grupoFamiliar->ingresoLaboralGrupoFamiliar,
            'ingreso-pensiones-anual' => $grupoFamiliar->ingresoJubilacionGrupoFamiliar,
            'ingreso-capital-anual' => $grupoFamiliar->ingresoOtrosGrupoFamiliar,
            'ingreso-total-anual' => $grupoFamiliar->ingresoTotalGrupoFamiliar
        ];
    }

    protected function mapDatosComplementarios($datosComplementarios)
    {
        $map = [];
        if ($datosComplementarios) {
            foreach ($datosComplementarios as $dato) {
                $map[] = [
                    'item' => $dato->item,
                    'info' => $dato->info,
                ];
            }
        }
        return $map;
    }
}
