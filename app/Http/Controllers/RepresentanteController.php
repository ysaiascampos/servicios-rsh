<?php

namespace App\Http\Controllers;

use App\Clases\RepresentanteNNAMigrantes;
use App\Http\ResponseMappers\RshEspecialAdultoMapper;
use Exception;

class RepresentanteController extends Controller
{
    private $representanteService;
    private $rshEspecialAdultoMapper;

    public function __construct(RepresentanteNNAMigrantes $representanteService, RshEspecialAdultoMapper $rshEspecialAdultoMapper)
    {
        $this->representanteService = $representanteService;
        $this->rshEspecialAdultoMapper = $rshEspecialAdultoMapper;
    }

    public function obtenerRepresentantes($idSolicitud)
    {
        try {
            $representante = $this->representanteService->obtenerRepresentantePorSolicitud($idSolicitud);

            if ($representante) {
                $mappedRepresentante = $this->rshEspecialAdultoMapper->map($representante);

                return $mappedRepresentante;
            } else {
                return response()->json(['error' => 'No se encontró ningún representante'], 404);
            }
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function obtenerRepresentantesRun($runSolicitante)
    {
        try {
            $representante = $this->representanteService->obtenerRepresentantePorRun($runSolicitante);

            if ($representante) {
                $mappedRepresentante = $this->rshEspecialAdultoMapper->map($representante);

                return $mappedRepresentante;
            } else {
                return response()->json(['error' => 'No se encontró ningún representante'], 404);
            }
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
