<?php

namespace App\Http\ResponseMappers;

class DomicilioMapper extends ResponseMapper
{
    public function __construct($grupoFamiliar)
    {
        $this->campos = [
            'region' => ($grupoFamiliar->REGION) ? self::mapRegion($grupoFamiliar->REGION) : null,
            'comuna' => ($grupoFamiliar->COMUNA) ? self::mapComuna($grupoFamiliar->COMUNA) : null,
            'localidad' => self::mapLocalidad($grupoFamiliar->LOCALIDAD),
            'calle' => ($grupoFamiliar->CALLE) ? $grupoFamiliar->CALLE : null,
            'numero' => ($grupoFamiliar) ? $grupoFamiliar->GRUPFAMI_NUMDOMICILIO : null,
            'departamento' => ($grupoFamiliar) ? $grupoFamiliar->GRUPFAMI_DPTO : null,
            'casa' => ($grupoFamiliar) ? $grupoFamiliar->GRUPFAMI_CASA : null,
            'sitio' => ($grupoFamiliar) ? $grupoFamiliar->GRUPFAMI_SITIO : null,
            'block' => ($grupoFamiliar) ? $grupoFamiliar->GRUPFAMI_BLOCK : null,
            'unidad-vecinal' => self::mapUnidadVecinal($grupoFamiliar),
            'agrupacion-habitacional' => self::mapAgrupacionAbitacional($grupoFamiliar),
            'referencia' => ($grupoFamiliar) ? $grupoFamiliar->GRUPFAMI_REFERENCIA : null,
            'latitud' => ($grupoFamiliar) ? self::flotante($grupoFamiliar->COORDENADA_Y) : null,
            'longitud' => ($grupoFamiliar) ? self::flotante($grupoFamiliar->COORDENADA_X) : null,
            'cod_direccion_gis' => ($grupoFamiliar) ? self::entero($grupoFamiliar->COD_DIRECCION_GIS) : null,
            'compania-luz' => ($grupoFamiliar) ? $grupoFamiliar->GRUPFAMI_IDCOMPLUZ : null,
            'numero-cliente-luz' => ($grupoFamiliar) ? $grupoFamiliar->GRUPFAMI_NUMCLIENTELUZ : null,
            'compania-agua' => ($grupoFamiliar) ? $grupoFamiliar->GRUPFAMI_IDCOMPAGUA : null,
            'numero-cliente-agua' => ($grupoFamiliar) ? $grupoFamiliar->GRUPFAMI_NUMCLIENTEAGUA : null
        ];
    }

    public function mapRegion($region)
    {
        return [
            'codigo' => ($region) ? self::entero($region->REG_ID) : null,
            'nombre' => ($region) ? $region->REG_DESCRIPCION : null
        ];
    }

    public function mapComuna($comuna)
    {
        return [
            'codigo' => ($comuna) ? self::entero($comuna->CODIGO) : null,
            'nombre' => ($comuna) ? $comuna->DESCRIPCION : null
        ];
    }

    public function mapLocalidad($localidad)
    {
        return [
            'codigo' => ($localidad) ? self::entero($localidad->OBJETO_CODIGO) : null,
            'nombre' => ($localidad) ? $localidad->NOMBRE : null,
            'zona' => ($localidad) ? $localidad->ZONA : null
        ];
    }

    private function mapUnidadVecinal($grupoFamiliar)
    {
        return [
            'codigo' => ($grupoFamiliar) ? self::entero($grupoFamiliar->GRUPFAMI_UNIDADVECINAL) : null,
            'nombre' => ($grupoFamiliar) ? $grupoFamiliar->NOMBRE_UNIDAD_VECINAL : null
        ];
    }

    private function mapAgrupacionAbitacional($grupoFamiliar)
    {
        return [
            'codigo' => ($grupoFamiliar) ? self::entero($grupoFamiliar->GRUPFAMI_CODIGOAH) : null,
            'nombre' => ($grupoFamiliar) ? $grupoFamiliar->NOMBRE_AGRUPACION_HABITACIONAL : null
        ];
    }
}
