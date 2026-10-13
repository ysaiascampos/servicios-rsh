<?php

namespace App\Http\ResponseMappers;


class IntegranteGrupoFamiliarFullMapper extends IntegranteGrupoFamiliarMapper
{
    public function __construct($integrante, $preguntasRespuestas)
    {
        parent::__construct($integrante, $preguntasRespuestas);

        $this->campos = array_merge($this->campos, [
            "condicion-dependencia" => self::condicionDependencia($integrante),
            'detalle-ingreso' => self::mapDetalleIngresos($integrante->detalleIngreso),
            'ingreso-anual' => self::mapIngresosAnual($integrante),
            'datos-complementarios-individuo' => $this->mapDatosComplementarios($integrante->datosComplementarios),
            'solicitudes-en-tramite' => self::mapSolicitudes($integrante->solicitudesEnTramite),
            'medios-activos' => self::mapMediosActivos($integrante->mediosActivos),
            'fuente-ingreso-laboral' => self::entero($integrante->CALC_FUENTE_INGRESOLABORAL ? $integrante->CALC_FUENTE_INGRESOLABORAL : null)
        ]);
    }

    private function mapDetalleIngresos($detalleIngreso)
    {
        return [
            'trabajo' => [
                'monto' => intval($detalleIngreso['ingreso-trabajo']['monto']),
                'fuente-nombre' => $detalleIngreso['ingreso-trabajo']['fuente-nombre'],
                'fuente-descripcion' => $detalleIngreso['ingreso-trabajo']['fuente-descripcion'],
                'fuente-codigo' => intval($detalleIngreso['ingreso-trabajo']['fuente-codigo'])
            ],
            'pension' => [
                'monto' => intval($detalleIngreso['ingreso-pension']['monto']),
                'fuente-nombre' => $detalleIngreso['ingreso-pension']['fuente-nombre'],
                'fuente-descripcion' => $detalleIngreso['ingreso-pension']['fuente-descripcion'],
                'fuente-codigo' => intval($detalleIngreso['ingreso-pension']['fuente-codigo'])
            ],
            'capital' => [
                'monto' => intval($detalleIngreso['ingreso-capital']['monto']),
                'fuente-nombre' => $detalleIngreso['ingreso-capital']['fuente-nombre'],
                'fuente-descripcion' => $detalleIngreso['ingreso-capital']['fuente-descripcion'],
                'fuente-codigo' => intval($detalleIngreso['ingreso-capital']['fuente-codigo'])
            ]
        ];
    }

    private function mapIngresosAnual($integrante)
    {
        return [
            'trabajo' => $integrante->ingresoLaboral,
            'pensiones' => $integrante->ingresoJubilacion,
            'capital' => $integrante->ingresoOtros,
            'total' => $integrante->ingresoTotal,
        ];
    }

    private function mapSolicitudes($solicitudes)
    {
        $map = [];
        if ($solicitudes) {
            $map['solicitud-en-tramite'] = $solicitudes['solicitud-en-tramite'];
            $map['solicitud-de-salud'] = $solicitudes['solicitud-de-salud'];
            $map['solicitud-de-ocupacioningreso'] = $solicitudes['solicitud-de-ocupacioningreso'];
            $map['solicitud-de-educacion'] = $solicitudes['solicitud-de-educacion'];
        }
        return $map;
    }

    private function mapMediosActivos($mediosActivos)
    {
        $map = [];
        if ($mediosActivos) {
            $map['colegio-alto-costo'] = $mediosActivos->colegioAltoCosto;
            $map['carga-isapre-alto-costo'] = $mediosActivos->cargaIsapreAltoCosto;
            $map['vehiculo-alto-costo-terrestre'] = $mediosActivos->vehiculoAltoCostoTerrestre;
            $map['vehiculo-alto-costo-maritimo'] = $mediosActivos->vehiculoAltoCostoMaritimo;
            $map['bien-raiz-alto-costo'] = $mediosActivos->bienRaizAltoCosto;
        }
        return $map;
    }
    private function condicionDependencia($integrante)
    {
        $dependientes = 0;
        if ($integrante->CONDICION_DEPENDENCIA == 1 || !is_null($integrante->RND_CSE)) {
            $dependientes = 1;
        }
        return $dependientes;
    }
}
