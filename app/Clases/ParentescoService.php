<?php

namespace App\Clases;

use App\Models\MigranteParentesco;
use App\Constantes\Constante;

class ParentescoService
{
    public function listParentescos()
    {
        $parentescos = MigranteParentesco::where('ACTIVO', Constante::VIGENTE_1_VIGENTE)->select('PAREN_ID', 'PAREN_DESCN')->get();
        // Obtener todos los parentescos

        return $parentescos;
    }
}
