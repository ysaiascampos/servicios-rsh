<?php

namespace App\Clases;


use App\DTO\ApiResponse;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlFactory;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ApiDatosGlobales extends Controller
{
    protected $guzzle;
    protected $apiRestHost;

    public function __construct()
    {
        $this->apiRestHost = env('API_DATOS_GLOBALES_URL');
        $handler = new CurlHandler([
            'handle_factory' => new CurlFactory(2)
        ]);


        $stack = HandlerStack::create($handler);
        $logger = new Logger('guzzle_logger');
        $logger->pushHandler(new StreamHandler(storage_path('logs/guzzle.log')));
        $stack->push(Middleware::log($logger, new \GuzzleHttp\MessageFormatter('{req_headers} - {res_body}')));
        $clientConfig = [
            'handler' => $stack,
            'base_uri' => $this->apiRestHost,
            'verify' => false,
        ];

        $this->guzzle = new Client($clientConfig);
    }

    /**
     * Hace una solicitud a la API Datos Globales.
     */
    public function consumo(
        $verboRest,
        $uriRest,
        $dataBodyArray = null,
        $dataHeadersArray = null,
        $download = false,
        $timeout = 0
    ) {
        $uriCompletaRest = $this->apiRestHost . $uriRest;

        $requestOptions = [
            'headers' => [
                'Accept' => 'application/json',
                'Api-Token' => env('API_DATOS_GLOBALES_TOKEN')
            ],
            'http_errors' => false,
            'timeout' => $timeout,
        ];

        if (!is_null($dataBodyArray)) {
            if (array_key_exists('multipart', $dataBodyArray)) {
                $requestOptions['multipart'] = $dataBodyArray['multipart'];
            } else {
                $requestOptions['headers']['Content-Type'] = 'application/json; charset=utf-8';
                $requestOptions['json'] = $dataBodyArray;
            }
        }

        if (!is_null($dataHeadersArray)) {
            $requestOptions['headers'] = array_merge($requestOptions['headers'], $dataHeadersArray);
        }

        try {
            $responseConsumoRest = $this->guzzle->request($verboRest, $uriCompletaRest, $requestOptions);
        } catch (\Exception $e) {
            Log::error("Error en consumo API", ['error' => $e->getMessage()]);

            return new ApiResponse(
                500,
                ['errors' => [$e->getMessage()]]
            );
        }

        if ($download) {
            $contentType = $responseConsumoRest->getHeaderLine('Content-Type');
            $contentDisposition = $responseConsumoRest->getHeaderLine('Content-Disposition');

            $response = \Response::make(
                $responseConsumoRest->getBody()->getContents(),
                $responseConsumoRest->getStatusCode()
            );

            if ($contentType) {
                $response->header('Content-Type', $contentType);
            }

            if ($contentDisposition) {
                $response->header('Content-Disposition', str_replace('"', '', $contentDisposition));
            }

            $response->header('Pragma', 'no-cache');

            return $response;
        }

        $bodyString = $responseConsumoRest->getBody();//->getContents();

        $decoded = json_decode($bodyString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return new ApiResponse(
                500,
                0,
                'Error parseando respuesta',
                '',
                []
            );
        }
        $response = new ApiResponse(
            $responseConsumoRest->getStatusCode(),
            $decoded['code'] ?? 0,
            $decoded['message'] ?? '',
            $decoded['time'] ?? '',
            $decoded['data'] ?? []
        );
        return $response;
    }

}
