<?php

namespace App\Http\ResponseMappers;

use App\Clases\ActualizacionIngreso;
use App\Constantes\Constante;
use App\Models\Sii;

class IngresoMapper extends ResponseMapper
{
    public function __construct(ActualizacionIngreso $tramite)
    {
        if ($tramite->tipoSolicitud === Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL) {
            $this->campos = $this->mapIngresoCapital($tramite->ingresos);
        } else {
            $this->campos = $this->mapIngresosLaboralPension($tramite);
        }
    }

    private function mapIngresoCapital(Sii $ingresos): array
    {
        return [
            'capital' => $ingresos->KAPITAL_SUM_COMPL
        ];
    }

    private function mapIngresosLaboralPension(ActualizacionIngreso $tramite): array
    {
        $map = [
            'periodo' => null,
            'meses-requeridos' => []
        ];

        if (count($tramite->ingresos) > 0) {

            $map['periodo'] = self::entero($tramite->ingresos[0]->PERIODO_RSH);

            foreach ($tramite->ingresos as $ingreso) {

                $tipoFuente = $ingreso->nombreTipoFuente;

                $mesesRequeridos = $ingreso->N_MESES ?? $tramite->fuentes['tipos-fuentes']
                    [$tramite->solicitud->ID_FUENTE_ACTUAL]['sub-fuentes'][$tipoFuente]['nro-meses'];

                $map['meses-requeridos'][$tipoFuente] = self::entero($mesesRequeridos);
                $map[$tipoFuente] = [];

                for ($nMes = (13 - $mesesRequeridos); $nMes <= 12; $nMes++) {
                    $map[$tipoFuente][] = [
                        'anio' => self::entero($tramite->periodoSolicitud->meses[$nMes]->anio),
                        'mes' => self::entero($tramite->periodoSolicitud->meses[$nMes]->nMes),
                        'valor' => self::entero($ingreso->{'MES_' . $nMes}),
                    ];
                }
            }
        }
        return $map;
    }
}
