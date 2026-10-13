<?php

namespace App\Clases;

use Exception;
use App\Constantes\Constante;
use Illuminate\Support\Facades\Cache;
use App\Models\CompaniasAgua;

class DatosDeCompaniasAgua
{
    public static function getLista()
    {
        $filePath =storage_path("../app/Clases/DatosDeCompaniasAgua.php");
        if(!file_exists($filePath)){
            $cacheKey = 'servicio-companias-agua';
        }else{
            $version = filemtime($filePath);
            $cacheKey = 'servicio-companias-agua-'.$version;
        }
        $listaCompaniasAgua = [];
        try {
            if (Cache::has($cacheKey)) {
                $listaCompaniasAgua = Cache::get($cacheKey);
                if(count($listaCompaniasAgua)>0
                    && isset($listaCompaniasAgua)){
                    Cache::forget($cacheKey);
                    $listaCompaniasAgua = [];
                }
            }
            if (!Cache::has($cacheKey)) {
                $companiasAgua = CompaniasAgua::getCompaniasAgua();
                if($companiasAgua->count()){
                    $listaCompaniasAgua = $companiasAgua;
                    Cache::put($cacheKey, $listaCompaniasAgua, now()->addMinutes(Constante::CACHE_API_MINUTOS));
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $listaCompaniasAgua;
    }

}
