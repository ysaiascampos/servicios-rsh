<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionEjecucion;
use App\Exceptions\ExcepcionValidacion;
use App\Http\Controllers\Controller;
use App\Http\Inputs\NumeroSerieInput;
use App\Http\Inputs\RunificadorInput;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiGenerarCodigoCartola extends Controller
{
    protected $guzzle;

    public function __construct()
    {
    }

    /**
     * Hace una solicitud a la API utilizando el token de acceso.
     */
    public function generarCodigo($input)
    {
        $runCiudadano = $input['run-emisor'];
        $nombresEmisor = $input['nombres-emisor'];
        $comuna = $input['comuna'];
        $tramo = $input['tramo'];
        try {

            $metodo = 'POST';
            $url = '/codigo-cartola-emitida';
            $dataBody = [
                'run-emisor' => $runCiudadano,
                'nombres-emisor' => $nombresEmisor,
                'comuna' => $comuna,
                'tramo' => $tramo
            ];
            $apiDatosGlobales = new ApiDatosGlobales;

            $dataRest = $apiDatosGlobales->consumo($metodo, $url, $dataBody);

            if (!$dataRest->isOk()) {
                return response()->json([
                    'error' => $dataRest->message
                ], $dataRest->statusCode);
            }

            $codigo['codigo'] = $dataRest->data['codigo'];

            return $codigo;

        } catch (Exception $ex) {
            return Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR;
        }
    }

}
