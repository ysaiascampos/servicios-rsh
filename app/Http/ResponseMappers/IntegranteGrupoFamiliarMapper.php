<?php

namespace App\Http\ResponseMappers;

use App\Constantes\Constante;

class IntegranteGrupoFamiliarMapper extends DatosContactoMapper
{
    public function __construct($integrante, $preguntasRespuestas)
    {
        parent::__construct($integrante);

        $this->campos = array_merge($this->campos, [
            'ficha' => new FichaMapper($integrante, $preguntasRespuestas),
            'id-solicitud' => $integrante->ID_SOLICITUD,
            'id-grupo-familiar' => $integrante->BNFGRUPFAMI_GRUPOFAMILIAR,
            'id-beneficiario' => $integrante->BNFGRUPFAMI_ID
        ]);

        if (isset($integrante->ingresoLaboral)) {
            $this->campos['ingreso-total'] = self::mapIngresosAnual($integrante);
        }

        if (isset($integrante->solicitudesEnTramite)) {
            $this->campos['solicitudes-en-tramite'] = self::mapSolicitudesTramite($integrante->solicitudesEnTramite);
        }

        if (isset($integrante->datosComplementarios)) {
            $this->campos['datos-complementarios-individuo'] = self::mapDatosComplementarios($integrante->datosComplementarios);
        }
        if (isset($integrante->detalleDatosComplementarios)) {
            $this->campos['detalle-datos-complementarios-individuo'] = self::mapDetalleDatosComplementarios($integrante->detalleDatosComplementarios);
        }

    }

    private function mapIngresosAnual($integrante)
    {
        return [
            'ingreso-trabajo-anual' => $integrante->ingresoLaboral,
            'ingreso-pensiones-anual' => $integrante->ingresoJubilacion,
            'ingreso-capital-anual' => $integrante->ingresoOtros,
            'ingreso-total-anual' => $integrante->ingresoTotal
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

    private function mapSolicitudesTramite($solicitudes)
    {
        return [
            'solicitud-en-tramite' => self::booleano($solicitudes['solicitud-en-tramite']),
            'solicitud-de-salud' => self::booleano($solicitudes['solicitud-de-salud']),
            'solicitud-de-ocupacioningreso' => self::booleano($solicitudes['solicitud-de-ocupacioningreso']),
            'solicitud-de-educacion' => self::booleano($solicitudes['solicitud-de-educacion'])
        ];
    }

    protected function mapDetalleDatosComplementarios($detalleDatosComplementarios)
    {
        $map = [
            "colegio-alto-costo"=> $detalleDatosComplementarios->colegioAltoCosto,
            "carga-isapre-alto-costo"=> $detalleDatosComplementarios->cargaIsapreAltoCosto,
            "vehiculo-alto-costo-terrestre"=> $detalleDatosComplementarios->vehiculoAltoCostoTerrestre,
            "vehiculo-alto-costo-maritimo"=> $detalleDatosComplementarios->vehiculoAltoCostoMaritimo,
            "bien-raiz-alto-costo"=> $detalleDatosComplementarios->bienRaizAltoCosto
        ];
        return $map;
    }
}
