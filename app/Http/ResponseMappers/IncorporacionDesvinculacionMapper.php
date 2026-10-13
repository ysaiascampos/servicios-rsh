<?php

namespace App\Http\ResponseMappers;

use App\Clases\Tramite;

class IncorporacionDesvinculacionMapper extends TramiteMapper
{
    public function consulta(Tramite $tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-solicitud' => ComposicionFamiliarMapper::new()->mapperBasico($tramite->beneficiariosSolicitudActual,
                $tramite->preguntasYRespuestas),
            'datos-grupo-familiar' => ComposicionFamiliarMapper::new()->mapperPorRun($tramite->beneficiariosVigente,
                $tramite->folio)
        ]);
        return $this;
    }
}
