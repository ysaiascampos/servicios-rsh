<?php


namespace App\Http\ResponseMappers;


class ViviendaMapper extends ResponseMapper
{
    public function __construct($grupoFamiliar)
    {
        $this->campos = [
            'v1' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_TIPOVIVIENDA) : null,
            'v2' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_SITUACIONSITIO) : null,
            'v3' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_SITUACIONVIVIENDA) : null,
            'v4' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_FUENTEAGUA) : null,
            'v5' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_DISTRIBUCIONAGUA) : null,
            'v6' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_SISTEMABANO) : null,
            'v7' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_DORMITORIOSOCUPADOS) : null,
            'v8' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_PRINCIPALOCUPANTE) : null,
            'v9' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_MATERIALMURO) : null,
            'v9ec' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_MATERIALMUROESTADO) : null,
            'v10' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_MATERIALPISO) : null,
            'v10ec' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_MATERIALPISOESTADO) : null,
            'v11' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_MATERIALTECHO) : null,
            'v11ec' => $grupoFamiliar ? self::entero($grupoFamiliar->GRUPFAMI_MATERIALTECHOESTADO) : null,
            'compania-luz' => $grupoFamiliar ? $grupoFamiliar->GRUPFAMI_IDCOMPLUZ : null,
            'numero-cliente-luz' => $grupoFamiliar ? $grupoFamiliar->GRUPFAMI_NUMCLIENTELUZ : null,
            'compania-agua' => $grupoFamiliar ? $grupoFamiliar->GRUPFAMI_IDCOMPAGUA : null,
            'numero-cliente-agua' => $grupoFamiliar ? $grupoFamiliar->GRUPFAMI_NUMCLIENTEAGUA : null
        ];
    }
}
