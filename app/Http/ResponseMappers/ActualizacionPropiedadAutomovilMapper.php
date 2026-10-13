<?php

namespace App\Http\ResponseMappers;

use App\Clases\Tramite;

class ActualizacionPropiedadAutomovilMapper extends TramiteMapper
{
    public function consulta(Tramite $tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-solicitud' => new PropiedadAutomovilMapper($tramite),
            'datos-vigente' => null
        ]);
        return $this;
    }
}
