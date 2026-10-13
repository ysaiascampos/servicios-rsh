<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\GrupoFamiliar;
use App\Models\NNARepresentanteMigrante;
use App\Models\Personas;
use Exception;

class RepresentanteNNAMigrantes
{

    public function obtenerRepresentantePorSolicitud($idSolicitud)
    {
        try {
            $representante = NNARepresentanteMigrante::where('MIGRAN_IDSOLICITUD', $idSolicitud)->first();

            return $representante;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function obtenerRepresentantePorRun($runCiudadano)
    {
        try {
            $persona = Personas::findByRun($runCiudadano);
            $beneficiarioGrupoFamiliar = BeneficiarioGrupoFamiliar::findVigenteByPersona($persona);
            $grupoFamiliar = GrupoFamiliar::where('GRUPFAMI_ID',$beneficiarioGrupoFamiliar->BNFGRUPFAMI_GRUPOFAMILIAR)
                ->where('grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('grupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->first();
            $representante = NNARepresentanteMigrante::where('MIGRAN_NUEVOFOLIO', $grupoFamiliar->GRUPFAMI_NUEVOFOLIO)->first();

            return $representante;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
