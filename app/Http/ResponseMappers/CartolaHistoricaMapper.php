<?php

namespace App\Http\ResponseMappers;
use Carbon\Carbon;
use App\Models\Personas;
use Illuminate\Support\Facades\Log;

class CartolaHistoricaMapper
{
    public static function mapIntegrantes(array $data, $run)
    {
        return collect($data)->map(function ($item) use ($run) {
            $item = array_change_key_case($item, CASE_UPPER);

            $valores = array_map(function ($valor) {
                return mb_convert_encoding($valor, 'UTF-8', 'auto');
            }, str_getcsv(trim($item['BID_RSH_COMPOSICION_HISTORICA'], '()')));

            $fechaRaw = str_replace('*', '', $valores[3]);
            $fechaCarbon = Carbon::createFromFormat('Ymd',$fechaRaw);
            $fechaFormateada = $fechaCarbon->format('m/Y');
            $periodoClave = $fechaCarbon->format('Ymd');
            $runIntegrante = trim($valores[8]);
            $dvIntegrante = trim($valores[10]);

            $persona = Personas::where('per_run',$runIntegrante)
                        ->where('per_dv',$dvIntegrante)
                        ->first();

            $nombreCompletoIntegrante =
                $persona ? trim($persona->PER_NOMBRES . ' ' . $persona->PER_APELLIDOPATERNO . ' ' . $persona->PER_APELLIDOMATERNO )
                    : 'Nombre desconocido';

            $runIntegranteFormateado = number_format((int)$runIntegrante, 0, '', '.');

            $visiblePart = substr($runIntegranteFormateado,0, -3);
            $runIntegranteFormateado = $visiblePart . 'XXX';
            $dvIntegrante = 'X';

            return [
                'folio_rsh' => $valores[0],
                'comuna' => $valores[1],
                'codigo_comuna' => $valores[2],
                'fecha' => $fechaFormateada,
                'anio' => $valores[4],
                'mes' => $valores[5],
                'cse' => $valores[6],
                'periodo' => $fechaFormateada,
                'periodo_clave' => $periodoClave,
                'nombre_completo' => $nombreCompletoIntegrante,
                'run_integrante_formateado' => $runIntegranteFormateado . '-' . $dvIntegrante,
                'parentesco' => $valores[9],
            ];
        });
    }

    public static function agruparYPaginar($datosProcesados)
    {
        // AGRUPACIÓN
        $datosAgrupados = $datosProcesados->groupBy('periodo_clave')->map(function ($itemsPorPeriodo) {
            return $itemsPorPeriodo->groupBy(function ($item) {
                return $item['folio_rsh'] . '|' . $item['comuna'] . '|' . $item['cse'];
            });
        });

        $gruposPlano = [];
        foreach ($datosAgrupados->sortKeysDesc() as $periodoClave => $gruposPorClave) {
            foreach ($gruposPorClave as $claveGrupo => $integrantes) {
                $periodoMostrar = $integrantes->first()['periodo'] ?? $periodoClave;
                $gruposPlano[]  = [
                    'periodo'     => $periodoMostrar,
                    'clave_grupo' => $claveGrupo,
                    'integrantes' => $integrantes,
                ];
            }
        }
        // PAGINACIÓN
        $maxFilasPrimeraPagina = 25;
        $maxFilasOtrasPaginas  = 25;

        $paginas       = [];
        $paginaActual  = [];
        $filasActuales = 0;
        $esPrimera     = true;

        foreach ($gruposPlano as $grupo) {
            $cantidadIntegrantes = count($grupo['integrantes']);
            $limiteActual        = $esPrimera ? $maxFilasPrimeraPagina : $maxFilasOtrasPaginas;

            if ($filasActuales + $cantidadIntegrantes > $limiteActual && !empty($paginaActual)) {
                $paginas[]     = $paginaActual;
                $paginaActual  = [];
                $filasActuales = 0;
                $esPrimera     = false;
            }

            $paginaActual[]  = $grupo;
            $filasActuales  += $cantidadIntegrantes;
        }

        if (!empty($paginaActual)) {
            $paginas[] = $paginaActual;
        }

        return $paginas;
    }
}
