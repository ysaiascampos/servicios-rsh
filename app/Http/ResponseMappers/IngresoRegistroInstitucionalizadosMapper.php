<?php


namespace App\Http\ResponseMappers;


use App\Clases\Tramite;

class IngresoRegistroInstitucionalizadosMapper extends TramiteMapper
{
    public function consulta(Tramite $tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_replace($this->campos, [
            'datos-solicitud' => RegistroInstitucionalizadosMapper::new()->mapperBasico($tramite->beneficiariosSolicitudActual,
                $tramite->preguntasYRespuestas),
        ]);
        return $this;
    }
}
