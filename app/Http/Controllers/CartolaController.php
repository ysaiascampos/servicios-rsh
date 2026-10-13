<?php


namespace App\Http\Controllers;


use App\Clases\DatosDeCartola;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CartolaController extends Controller
{

    public function show($portal,$runCiudadano)
    {
        $datosDeCartola = new DatosDeCartola;
        $error = false;
        try {
            $this->validarInput($runCiudadano);
            $restGetRealIP = request()->header('getRealIP');
            $usuario = request()->header('usuario');
            $result = $datosDeCartola->crearCartola($portal, $runCiudadano, $restGetRealIP, $usuario);
        } catch (ExcepcionValidacion $ex) {
            $error = true;
        } catch (\ErrorException $ex) {
            $error = true;
        } catch (\Throwable $ex) {
            $error = true;
        } catch (\Exception $ex) {
            $error = true;
        }
        if($error){
            Log::error("Ha ocurrido un problema al obtener cartola del Ciudadano RUN ". $runCiudadano.
                " Detalles del error  -> ". $ex);
            $result = $datosDeCartola->errorCartola();
        }
        return $result;

    }
    public function validarInput($runCiudadano)
    {
        $runCiudadano = str_replace(['.', '-', ' '], '', $runCiudadano);
        $runCiudadano = preg_replace("/[^0-9?!]/", "", $runCiudadano);
        $entradas = ['run-ciudadano' => $runCiudadano];
        $reglas = [
            'run-ciudadano' => 'required|numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
        ];
        $validador = Validator::make($entradas, $reglas);
        if ($validador->fails()) {
            throw ExcepcionValidacion::new($validador->errors(), Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO)
                ->setTitular($runCiudadano);
        }
    }

    public function validar($hashMd5)
    {
        $datosDeCartola = new DatosDeCartola;
        $error = false;
        try {
            $result = $datosDeCartola->validacionCartolaQr($hashMd5);
        } catch (\Throwable $ex) {
            $error = true;
        }
        if($error){
            Log::error("Ha ocurrido un problema al obtener cartola del Ciudadano Detalles del error  -> ". $ex);
            $result = $datosDeCartola->errorCartola();
        }
        return $result;

    }


}
