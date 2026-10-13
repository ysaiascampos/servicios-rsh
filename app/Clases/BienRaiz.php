<?php


namespace App\Clases;
use App\Models\Propiedad;



abstract class BienRaiz extends TramiteArc
{
    public $propiedad;

    protected function cargarDataTramite()
    {
        $this->propiedad = Propiedad::where('idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->get();
    }
}
