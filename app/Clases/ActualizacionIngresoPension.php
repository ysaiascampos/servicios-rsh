<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Models\FuenteExport;
use App\Models\Personas;
use Illuminate\Support\Facades\DB;
use Throwable;

class ActualizacionIngresoPension extends ActualizacionIngreso
{
    public $tipoSolicitud =
        Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION;
    public $nombreSolicitud = 'Solicitud de actualización de ingresos por pensión y/o jubilación';


    public function editarSolicitud()
    {
        try {
            if (is_null($this->solicitud)) {
                $fuente = $this->obtenerFuente();
                $fuenteIngreso = $fuente->id;
            } else {
                $fuenteIngreso = $this->solicitud->ID_FUENTE_ACTUAL;
            }


            $this->verificarFirmaSimple();

            if (!in_array($fuenteIngreso,
                $this->fuentes['solicitudes'][Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION]['fuentes'])) {
                throw new ExcepcionReglaNegocio('No se encontro fuente de ingreso valida para este tramite',
                    Constante::ERR_NEGOCIO_FUENTE_NO_VALIDA);
            }
            $subFuentesSolicitud = $this->fuentes['tipos-fuentes'][$fuenteIngreso]['sub-fuentes'];
            $actualizar = false;
            if (count($subFuentesSolicitud) == 1) {
                $subFuente = isset($subFuentesSolicitud['pension']) ? 'pension' : 'sii';
                // ToDo validar ultimos 4 meses para FUENTE_100_INGRESO_DE_PENSIONES_IPS y FUENTE_101_INGRESO_DE_PENSIONES_AFP
                // ToDo validar ultimos 12 meses
                $this->guardarSolicitud([
                    'fuente' => $fuenteIngreso,
                    'subTipoSolicitud' => Constante::SUB_TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION_1_ACTUALIZACION_POR_VARIACION_VALOR_PENSION,
                    'ingresos' => [
                        $this->obtenerRegistroIngreso(
                            $this->input->{$subFuente},
                            [
                                'dependiente-independiente' => Constante::DEP_O_INDEP_0_OTRO,
                                'nro-meses' => $subFuentesSolicitud[$subFuente]['nro-meses']
                            ]
                        )
                    ]
                ]);
                $actualizar = true;
            }
            if (count($subFuentesSolicitud) == 2) {
                $subFuente1 = 'pension';
                $subFuente2 = 'sii';
                // ToDo validar ultimos 4 meses IPS
                // ToDo validar ultimos 12 meses SII
                $this->guardarSolicitud([
                    'fuente' => $fuenteIngreso,
                    'subTipoSolicitud' => Constante::SUB_TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION_1_ACTUALIZACION_POR_VARIACION_VALOR_PENSION,
                    'ingresos' => [
                        $this->obtenerRegistroIngreso(
                            $this->input->$subFuente1,
                            [
                                'dependiente-independiente' => Constante::DEP_O_INDEP_0_OTRO,
                                'posicion' => 0,
                                'nro-meses' => $subFuentesSolicitud[$subFuente1]['nro-meses']
                            ]
                        ),
                        $this->obtenerRegistroIngreso(
                            $this->input->$subFuente2,
                            [
                                'dependiente-independiente' => Constante::DEP_O_INDEP_0_OTRO,
                                'posicion' => 1,
                                'nro-meses' => $subFuentesSolicitud[$subFuente2]['nro-meses']
                            ]
                        )
                    ]
                ]);
                $actualizar = true;
            }
            if ($actualizar) {
                $this->generarPdfFirmaSimple();
            }
        } catch (Throwable $ex) {
            throw $ex;
        }
    }

    protected function obtenerFuente()
    {
        $fuente = FuenteExport::join('personas', 'personas.calc_fuente_pension', '=', 'fuentes.fue_id')
            ->where('personas.per_run', $this->titular->PER_RUN)
            ->first(['fue_id', 'fue_pens_1', 'fue_pens_2']);

        return $this->formatearFuente($fuente);
    }

    protected function formatearFuente($fuente)
    {
        if (is_null($fuente)) {
            throw new ExcepcionConsistencia('La solicitud no registra la fuente.',
                Constante::ERR_CONSISTENCIA_FUENTE_NO_EXISTE);
        }
        return (object)[
            'id' => $fuente->FUE_ID,
            'pension1' => $fuente->FUE_PENS_1,
            'pension2' => $fuente->FUE_PENS_2
        ];
    }
}
