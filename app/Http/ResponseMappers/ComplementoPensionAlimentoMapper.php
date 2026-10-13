<?php


namespace App\Http\ResponseMappers;
use App\Clases\Tramite;

class ComplementoPensionAlimentoMapper extends TramiteMapper
{
    public function consulta(Tramite $tramite)
    {
        parent::consulta($tramite);

        $this->campos = array_merge($this->campos, [
            'datos-solicitud' => new PensionAlimentoMapper($tramite),
            'datos-vigente' => null,
        ]);
        return $this;
    }

}
