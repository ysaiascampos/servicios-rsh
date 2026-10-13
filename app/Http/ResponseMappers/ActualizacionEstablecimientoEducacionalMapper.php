<?php

namespace App\Http\ResponseMappers;

use App\Clases\Tramite;

class ActualizacionEstablecimientoEducacionalMapper extends TramiteMapper
{
    public function consulta(Tramite $tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-solicitud' => new EstablecimientoEducacionalMapper($tramite),
            'datos-vigente' => null
        ]);
        return $this;
    }
}
