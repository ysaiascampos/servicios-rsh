<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Models\Comuna;
use App\Models\Region;
use App\Utiles;
use Illuminate\Support\Facades\Cache;

class Locacion
{

    public $regionComunaLocalidades;
    public $filtroRegion;
    public $filtroComuna;
    public $comunaUVs;
    public $comunaAHs;
    public $cacheKey;
    public $verAgrupacionHabitacional;
    public $filtrarPorComuna = false;

    public function getRegionComunaLocalidadUvAhPorComunaIne($comunaIne)
    {
        $this->filtrarPorComuna = true;
        $this->filtroRegion = Utiles::getRegionDesdeCodigoComuna($comunaIne);
        $this->filtroComuna = $comunaIne;
        self::getRegionComunaLocalidadUVAh(true);

        if (empty($this->filtroRegion)) {
            throw ExcepcionConsistencia::new('No se encontró la región de la comuna: ' . $comunaIne,
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
        }
    }

    public function getRegionComunaLocalidadUVAh(bool $verAgrupacionHabitacional = false)
    {
        $this->cacheKey = ($verAgrupacionHabitacional) ? 'api-RCLUA' : 'api-RCLU';

        if (!(Cache::has($this->cacheKey))) {
            $this->verAgrupacionHabitacional = $verAgrupacionHabitacional;
            $this->regionComunaLocalidades = Region::getRegionComunaLocalidad();
            $this->comunaUVs = Comuna::getComunaUV();
            if ($verAgrupacionHabitacional) {
                $this->comunaAHs = Comuna::getComunaAH();
            }
        }
    }
}
