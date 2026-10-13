<?php

namespace App\Http\Controllers;

use App\Clases\DatosDeCartola2024;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionEjecucion;
use App\Exceptions\ExcepcionValidacion;
use ErrorException;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use PDF;
use App\Utiles;
use Throwable;

class Cartola2024Controller extends Controller
{
    public function show($canal, $runCiudadano, $formato = 'download')
    {

        $datosDeCartola = new DatosDeCartola2024;
        $error = false;
        try {
            $this->validarInput($runCiudadano);
            $restGetRealIP = request()->header('getRealIP');
            $usuario = request()->header('usuario');
            $result = $datosDeCartola->crearCartola($canal, $runCiudadano, $entendiendo = true, $restGetRealIP, $usuario);
        } catch (ExcepcionValidacion $ex) {
            $error = true;
        } catch (ErrorException $ex) {
            $error = true;
        } catch (Throwable $ex) {
            $error = true;
        } catch (Exception $ex) {
            $error = true;
        }
        if($error){
            Log::error("Ha ocurrido un problema al obtener cartola del Ciudadano RUN ". $runCiudadano.
                " Detalles del error  -> ". $ex);
            return $this->handleException($ex);

            //$result = $datosDeCartola->errorCartola();
        }
        return $result;
    }

    public function showSinEntendiendo($canal, $runCiudadano, $formato = 'download')
    {

        $datosDeCartola = new DatosDeCartola2024;
        $error = false;
        try {
            $this->validarInput($runCiudadano);
            $restGetRealIP = request()->header('getRealIP');
            $usuario = request()->header('usuario');
            $result = $datosDeCartola->crearCartola($canal, $runCiudadano, $entendiendo = false, $restGetRealIP, $usuario,$validacionCartola=false,$formato);
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
            return $this->handleException($ex);
            //$result = $datosDeCartola->errorCartola();
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

    private function getCartolabyRun($run)
    {
        $metodo = 'GET';
        $url = '/cartola-rsh-api/ciudadano/' . $run;
        $dataRest = Utiles::consumoConGuzzleMunicipal($metodo, $url, null);
        return $dataRest->data;
    }

    public function errorCartola()
    {
        $this->vista = 'formularios.cartola-rsh';
        if(isset($this->data)){
            $this->data = ['titulo' => 'Cartola Hogar',
                'error' => true,
                'mensaje' => "Cartola no válida"
            ];
        }else{
            $this->data = array_merge($this->data,['titulo' => 'Cartola Hogar',
                'error' => true]);
        }
        return $this->generarPdf();
    }

}
