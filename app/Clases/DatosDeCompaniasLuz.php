<?php

namespace App\Clases;

use Exception;
use App\Constantes\Constante;
use Illuminate\Support\Facades\Cache;
use App\Models\CompaniasLuz;

class DatosDeCompaniasLuz
{
    public static function getLista()
    {
        $filePath =storage_path("../app/Clases/DatosDeCompaniasLuz.php");
        if(!file_exists($filePath)){
            $cacheKey = 'servicio-companias-luz';
        }else{
            $version = filemtime($filePath);
            $cacheKey = 'servicio-companias-luz-'.$version;
        }
        $listaCompaniasLuz = [];
        try {
            if (Cache::has($cacheKey)) {
                $listaCompaniasLuz = Cache::get($cacheKey);
                if(count($listaCompaniasLuz)>0
                    && isset($listaCompaniasLuz)){
                    Cache::forget($cacheKey);
                    $listaCompaniasLuz = [];
                }
            }
            if (!Cache::has($cacheKey)) {
                $companiasLuz = CompaniasLuz::getCompaniasLuz();
                if($companiasLuz->count()){
                    $listaCompaniasLuz = $companiasLuz;
                    Cache::put($cacheKey, $listaCompaniasLuz, now()->addMinutes(Constante::CACHE_API_MINUTOS));
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $listaCompaniasLuz;
    }

}
