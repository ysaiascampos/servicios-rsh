<?php


namespace App\Http\Controllers;


use App\Clases\Locacion;
use App\Exceptions\ExcepcionValidacion;
use App\Http\ResponseMappers\JsonMapper;
use App\Http\ResponseMappers\LocacionMapper;
use App\Models\Calle;
use Illuminate\Support\Facades\Validator;

class LocacionController
{
    private $locacion;

    public function __construct()
    {
        $this->locacion = new Locacion();
    }

    public function showRegionComunaLocalidadUv($compress = 0)
    {
        $this->locacion->getRegionComunaLocalidadUVAh();
        return (new LocacionMapper)->mapperLocacion($this->locacion, $compress);
    }

    public function showRegionComunaLocalidadUvAh($compress = 0)
    {
        $this->locacion->getRegionComunaLocalidadUVAh(true);
        return (new LocacionMapper)->mapperLocacion($this->locacion, $compress);
    }

    public function showRegionComunaLocalidadUvAhPorComunaIne($comunaIne)
    {
        $this->locacion->getRegionComunaLocalidadUvAhPorComunaIne($comunaIne);
        return (new LocacionMapper)->mapperLocacion($this->locacion);
    }

    public function showCalle($codigoComuna)
    {
        $this->validarParametros([
            'codigoComuna' => $codigoComuna
        ]);

        $locacionCalle = Calle::listCallePorComunaYLocalidad($codigoComuna);
        return (new LocacionMapper)->mapperCalle($locacionCalle);
    }

    public function validarParametros(array $parametros)
    {
        $reglas = [
            'codigoComuna' => 'numeric',
            'codigoLocalidad' => 'numeric',
        ];
        $validador = Validator::make($parametros, $reglas);
        if ($validador->fails()) {
            throw new ExcepcionValidacion($validador->errors());
        }
    }

}
