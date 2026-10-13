<?php

namespace App\Http\ResponseMappers;

use App\Clases\Tramite;

class ActualizacionIngresoMapper extends TramiteMapper
{
    public function consulta(Tramite $tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-solicitud' => new IngresoMapper($tramite),
            'datos-vigente' => null
        ]);
        return $this;
    }
}
