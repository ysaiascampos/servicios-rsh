<?php


namespace App\Http\ResponseMappers;


use App\Clases\Locacion;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LocacionMapper extends ResponseMapper
{
    public function mapperLocacion(Locacion $locacion, $comprimir = 0)
    {
        if ($comprimir) {
            $this->setHeader('Content-Encoding', 'gzip');
        }

        if (Cache::has($locacion->cacheKey)) {
            $map = Cache::get($locacion->cacheKey);
        } else {
            $map = self::mapLocalidad($locacion);
            Cache::put($locacion->cacheKey, $map, now()->addMinutes(Constante::CACHE_API_MINUTOS));
        }

        if (!empty($locacion->filtroComuna)) {

            try {
                $this->campos = $map['regiones'][$locacion->filtroRegion]['comunas'][$locacion->filtroComuna];
            } catch (Exception $ex) {
                throw ExcepcionConsistencia::new('No se encontró la comuna: ' . $locacion->filtroComuna,
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }

        } else {

            $this->campos = $map;

        }
        return $this;

    }

    public function mapperCalle($locacionCalle)
    {
        $calles = array();
        foreach ($locacionCalle as $codigo => $calle) {
            try {
                $calles[] = [
                    'id-calle' => (string)$codigo,
                    'nombre' => $calle
                ];
            } catch (Exception $ex) {
                Log::info("No se pudo traer la calle :" . $locacionCalle);
            }
        }
        $this->campos = $calles;
        return $this;
    }

    public function mapLocalidad(Locacion $locacion)
    {
        $comunaUVArray = null;
        $comunaAHArray = null;

        foreach ($locacion->comunaUVs as $comunaUV) {
            try {
                $comunaUVArray[$comunaUV->COM_ID][] = [
                    'id-uv' => $comunaUV->UV_CARTO,
                    'nombre' => $comunaUV->NOMBRE
                ];
            } catch (Exception $ex) {
                Log::info("No se pudo traer la UV :" . $comunaUV);
            }
        }

        if ($locacion->verAgrupacionHabitacional) {
            foreach ($locacion->comunaAHs as $comunaAH) {
                try {
                    $comunaAHArray[$comunaAH->COM_ID][] = [
                        'id-ah' => $comunaAH->ID_AH,
                        'nombre' => $comunaAH->NOMBRE
                    ];
                } catch (Exception $ex) {
                    Log::info("No se pudo traer la AH :" . $comunaUV);
                }
            }
        }

        $map = null;
        $cacheRegion = null;
        $cacheComuna = null;
        foreach ($locacion->regionComunaLocalidades as $regionComunaLocalidad) {
            try {
                if ($cacheRegion != $regionComunaLocalidad->REG_ID) {
                    $map['regiones'][$regionComunaLocalidad->REG_ID]['id-region'] = $regionComunaLocalidad->REG_ID;
                    $map['regiones'][$regionComunaLocalidad->REG_ID]['nombre'] = $regionComunaLocalidad->REG_DESCRIPCION;
                }
                $cacheRegion = $regionComunaLocalidad->REG_ID;

                if ($cacheComuna != $regionComunaLocalidad->COM_ID) {
                    $map['regiones'][$regionComunaLocalidad->REG_ID]['comunas'][$regionComunaLocalidad->COM_ID]['comuna-ine'] = $regionComunaLocalidad->COM_ID;
                    $map['regiones'][$regionComunaLocalidad->REG_ID]['comunas'][$regionComunaLocalidad->COM_ID]['nombre'] = $regionComunaLocalidad->COM_DESCRIPCION;
                    try {
                        if (isset($comunaUVArray[$regionComunaLocalidad->COM_ID])) {
                            $map['regiones'][$regionComunaLocalidad->REG_ID]['comunas'][$regionComunaLocalidad->COM_ID]['uv'] = $comunaUVArray[$regionComunaLocalidad->COM_ID];
                        } else {
                            $map['regiones'][$regionComunaLocalidad->REG_ID]['comunas'][$regionComunaLocalidad->COM_ID]['uv'] = [];
                        }
                    } catch (Exception $ex) {
                        Log::info("No se pudo traer UV en comuna: " . $regionComunaLocalidad->COM_ID);
                    }
                    if ($locacion->verAgrupacionHabitacional) {
                        try {
                            if (isset($comunaAHArray[$regionComunaLocalidad->COM_ID])) {
                                $map['regiones'][$regionComunaLocalidad->REG_ID]['comunas'][$regionComunaLocalidad->COM_ID]['ah'] = $comunaAHArray[$regionComunaLocalidad->COM_ID];
                            } else {
                                $map['regiones'][$regionComunaLocalidad->REG_ID]['comunas'][$regionComunaLocalidad->COM_ID]['ah'] = [];
                            }
                        } catch (Exception $ex) {
                            Log::info("No se pudo traer AH en comuna: " . $regionComunaLocalidad->COM_ID);
                        }
                    }
                }
                $cacheComuna = $regionComunaLocalidad->COM_ID;

                $map['regiones'][$regionComunaLocalidad->REG_ID]['comunas'][$regionComunaLocalidad->COM_ID]['localidades'][] = [
                    'id-localidad' => $regionComunaLocalidad->ID_LOCALIDAD,
                    'nombre' => $regionComunaLocalidad->NOMBRE,
                    'tipo-zona' => $regionComunaLocalidad->ZONA
                ];

            } catch (Exception $ex) {
                Log::info("No se pudo traer Reg-Com-Loc-UV en comuna: " . $regionComunaLocalidad->COM_ID);
            }
        }

        return $map;

    }
}
