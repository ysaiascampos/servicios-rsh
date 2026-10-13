<?php


namespace App\Http\ResponseMappers;

use App\Clases\Tramite;

class ComplementoCotizacionSaludMapper extends TramiteMapper
{
    public function consulta(Tramite $tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-solicitud' => new CotizacionSaludCompMapper($tramite),
            'datos-vigente' => null,
        ]);
        return $this;
    }
}
