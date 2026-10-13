<?php

namespace App\Clases;

use App\Models\MigranteNacionalidad;

class NacionalidadService
{
    public function consultarNacionalidades()
    {
        $nacionalidades = MigranteNacionalidad::orderBy('PRIORIDAD_VISUAL', 'asc')
            ->select('NAC_ID', 'PAIS')
            ->get();

        return $nacionalidades;
    }
}


