<?php

namespace App\Clases;

use App\Models\QRScanInstituciones;
use App\Constantes\ConstanteEstado;

class QRInstitucionesService
{
    public static function consultarInstitucionesVigentes()
    {
        $listinstituciones = QRScanInstituciones::select('ID','NOMBRE_INSTITUCION')->where('ESTADO',ConstanteEstado::ESTADO_1_ACTIVO)->get();

        return $listinstituciones;
    }

    public function map($data)
    {
        $instituciones = [];
        foreach($data as $campo){
            $instituciones[] = [
                'id' => $campo->ID,
                'nombre_institucion' => $campo->NOMBRE_INSTITUCION,
            ];
        }

        return $instituciones;
    }

}


