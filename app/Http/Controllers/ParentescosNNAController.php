<?php

namespace App\Http\Controllers;

use App\Clases\ParentescoService;
use App\Http\ResponseMappers\ParentescoResponseMapper;
use Exception;

class ParentescosNNAController extends Controller
{
    private $parentescoService;
    private $responseMapper;

    public function __construct(ParentescoService $parentescoService, ParentescoResponseMapper $responseMapper)
    {
        $this->parentescoService = $parentescoService;
        $this->responseMapper = $responseMapper;
    }

    public function obtenerParentescos()
    {
        try {
            $parentesco = $this->parentescoService->listParentescos();
            $mappedResponse = $this->responseMapper->map($parentesco);

            return $mappedResponse;
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
