<?php

namespace App\Clases;

use App\Http\ResponseMappers\CartolaHistoricaMapper;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DatosCartolaHistorica
{
    public function obtenerPaginas($run, $dv, $usuario, $inicio, $termino)
    {
        $json = $this->consultarComposicionHistorica($run, $dv, $usuario, $inicio, $termino);

        if (empty($json['data'])) {
            return [];
        }

        $datosProcesados = CartolaHistoricaMapper::mapIntegrantes($json['data'], $run);

        return CartolaHistoricaMapper::agruparYPaginar($datosProcesados);
    }

    private function consultarComposicionHistorica($run, $dv, $usuario, $inicio, $termino)
    {
        try {
            $response = Http::APIPROCESSBID('RSH')
                ->get('/ris/process/v1/rsh/composicion-historica',
                    [
                        'run_consultado'   => $run,
                        'dv_consultado'    => $dv,
                        'usuario_consulta' => $usuario,
                        'inicio'           => $inicio,
                        'termino'          => $termino,
                    ]
                );

            if (!$response->successful()) {
                throw new Exception('Error consumiendo API composición histórica');
            }

            $json = $response->json();

            if (!isset($json['code']) || $json['code'] != 1000) {
                throw new Exception('Respuesta inválida de API');
            }

            return $json;

        } catch (Exception $e) {
            Log::error('ERROR API RSH', [
                'mensaje' => $e->getMessage(),
                'linea'   => $e->getLine(),
                'archivo' => $e->getFile(),
            ]);
            throw $e;
        }
    }
}
