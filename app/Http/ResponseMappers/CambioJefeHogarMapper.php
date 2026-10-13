<?php

namespace App\Http\ResponseMappers;

class CambioJefeHogarMapper extends TramiteMapper
{
    public function consulta($tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-vigente' => ComposicionFamiliarMapper::new()->mapperBasico($tramite->beneficiariosVigente),
            'datos-solicitud' => ComposicionFamiliarMapper::new()->mapperBasico($tramite->beneficiariosSolicitud),
        ]);

        return $this;
    }
}
