<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiRunificador extends Controller
{
    /**
     * Máximo de intentos por consulta.
     */
    const MAX_INTENTOS = 3;

    /**
     * Espera base entre intentos, en milisegundos.
     */
    const ESPERA_BASE_MS = 250;

    protected $guzzle;

    public function __construct()
    {
        $this->guzzle = new Client([
            'base_uri' => env('RUNIFICADOR_REST_URL'),
            'timeout'  => 5.0,
        ]);
    }

    /**
     * Obtiene token OAuth.
     */
    public function getAccessToken(): ?string
    {
        try {

            $response = $this->guzzle->post(
                env('RUNIFICADOR_REST_TOKEN_URL'),
                [
                    'form_params' => [
                        'grant_type'    => 'client_credentials',
                        'client_id'     => env('RUNIFICADOR_REST_CLIENTID'),
                        'client_secret' => env('RUNIFICADOR_REST_CLIENTSECRET'),
                    ]
                ]
            );

            $body = $response->getBody()->getContents();

            $data = json_decode($body, true);

            if (empty($data['access_token'])) {

                Log::error('Runificador no retornó access_token', [
                    'url' => env('RUNIFICADOR_REST_URL') ,
                    'token_url' => env('RUNIFICADOR_REST_TOKEN_URL'),
                    'response' => $body
                ]);

                return null;
            }

            return $data['access_token'];

        } catch (RequestException $e) {

            Log::error('Error HTTP obteniendo token Runificador', [
                'url' => (string) $e->getRequest()->getUri(),
                'token_url' => $e->getRequest()->getMethod(),
                'status_code' => $e->hasResponse()
                    ? $e->getResponse()->getStatusCode()
                    : null,
                'response' => $e->hasResponse()
                    ? (string)$e->getResponse()->getBody()
                    : null,
                'error' => $e->getMessage()
            ]);

            return null;

        } catch (Exception $e) {

            Log::error('Excepción obteniendo token Runificador', [
                'url' => env('RUNIFICADOR_REST_URL') ,
                'token_url' => env('RUNIFICADOR_REST_TOKEN_URL'),
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine()
            ]);

            return null;
        }
    }

    /**
     * Consulta persona.
     *
     * Si la consulta (POST con el token Bearer) responde HTTP 401 se vuelve a
     * solicitar el token y se repite la consulta, hasta MAX_INTENTOS intentos.
     * El resto de los errores conserva el comportamiento original.
     */
    public function getPersona($runCiudadano, $dv)
    {
        $cacheKey = env('RUNIFICADOR_REST_CACHE_KEY_PRE', 'runificador')
            . $runCiudadano;

        $ttl = env('RUNIFICADOR_REST_CACHE_TIEMPO', 600);

        if (Cache::has($cacheKey)) {

            Log::info('Runificador cache hit', [
                'run' => $runCiudadano
            ]);

            return Cache::get($cacheKey);
        }

        $urlMetodo = env('RUNIFICADOR_REST_URL_METODO');

        $maxIntentos = max(1, (int) env('RUNIFICADOR_REST_INTENTOS', self::MAX_INTENTOS));

        $accessToken = $this->getAccessToken();

        if (!$accessToken) {

            Log::error('No fue posible obtener token', [
                'run' => $runCiudadano,
                'dv'  => $dv
            ]);

            return Constante::DATOS_DE_CONTACTO_7_ERROR_TOKEN_RUNIFICADOR;
        }

        for ($intento = 1; $intento <= $maxIntentos; $intento++) {

            try {

                $response = $this->guzzle->post(
                    $urlMetodo,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Accept'        => 'application/json',
                            'Api-Token'     => env('RUNIFICADOR_REST_API_TOKEN'),
                        ],
                        'json' => [
                            'rut' => $runCiudadano,
                            'dv'  => $dv
                        ]
                    ]
                );

                $body = $response->getBody()->getContents();

                $personaWs = json_decode($body);

                if (!$personaWs) {

                    Log::error('JSON inválido recibido desde Runificador', [
                        'url' => env('RUNIFICADOR_REST_URL') ,
                        'method' => $urlMetodo,
                        'run'  => $runCiudadano,
                        'body' => $body
                    ]);

                    return Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR;
                }

                if (
                    $personaWs->Estado === 'OK' ||
                    $personaWs->Estado === 'OK-CACHE'
                ) {

                    Cache::put($cacheKey, $personaWs, $ttl);

                    if ($intento > 1) {

                        Log::info('Runificador respondió OK tras reintento', [
                            'run'     => $runCiudadano,
                            'intento' => $intento
                        ]);
                    }

                    return $personaWs;
                }

                Log::warning('Runificador respondió con error funcional', [
                    'url' => env('RUNIFICADOR_REST_URL') ,
                    'method' => $urlMetodo,
                    'run'    => $runCiudadano,
                    'estado' => $personaWs->Estado ?? null,
                    'glosa'  => $personaWs->Glosa ?? null,
                    'body'   => $body
                ]);

                if (
                    isset($personaWs->Glosa) &&
                    $personaWs->Glosa === 'Persona no encontrada en BD'
                ) {
                    return Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL;
                }

                if (
                    isset($personaWs->Glosa) &&
                    str_contains($personaWs->Glosa, 'No responde RC')
                ) {
                    return Constante::DATOS_DE_CONTACTO_6_ERROR_REGISTRO_CIVIL;
                }

                return Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR;

            } catch (ConnectException $e) {

                Log::error('Timeout o error de conexión Runificador', [
                    'url' => env('RUNIFICADOR_REST_URL') ,
                    'method' => env('RUNIFICADOR_REST_URL_METODO'),
                    'run'   => $runCiudadano,
                    'dv'    => $dv,
                    'error' => $e->getMessage()
                ]);

                return Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR;

            } catch (RequestException $e) {
                $response = null;
                $statusCode = $e->hasResponse()
                    ? $e->getResponse()->getStatusCode()
                    : null;

                if ($e->hasResponse()) {
                    $response = (string) $e->getResponse()->getBody();

                    // Limitar tamaño
                    $response = substr($response, 0, 300);
                }

                // Token expirado o inválido: se vuelve a pedir el token y se repite la consulta
                if ($statusCode === 401) {

                    Log::warning('Token Runificador expirado o inválido', [
                        'run'          => $runCiudadano,
                        'dv'           => $dv,
                        'status_code'  => $statusCode,
                        'intento'      => $intento,
                        'max_intentos' => $maxIntentos
                    ]);

                    if ($intento < $maxIntentos) {

                        $accessToken = $this->getAccessToken();

                        if (!$accessToken) {

                            Log::error('No fue posible obtener token al reintentar', [
                                'run'     => $runCiudadano,
                                'dv'      => $dv,
                                'intento' => $intento
                            ]);

                            return Constante::DATOS_DE_CONTACTO_7_ERROR_TOKEN_RUNIFICADOR;
                        }

                        $this->esperarAntesDeReintentar($intento, $maxIntentos);

                        continue;
                    }

                    Log::error('Token Runificador expirado: se agotaron los intentos', [
                        'url'      => env('RUNIFICADOR_REST_URL') ,
                        'method'   => $urlMetodo,
                        'run'      => $runCiudadano,
                        'dv'       => $dv,
                        'intentos' => $maxIntentos
                    ]);

                    return Constante::DATOS_DE_CONTACTO_7_ERROR_TOKEN_RUNIFICADOR;
                }

                Log::error('Error HTTP Runificador', [
                    'url' => (string) $e->getRequest()->getUri(),
                    'method' => $e->getRequest()->getMethod(),
                    'run' => $runCiudadano,
                    'dv' => $dv,
                    'status_code' => $statusCode,
                    'response_preview' => $response,
                    'error' => $e->getMessage(),
                ]);

                return Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR;

            } catch (Exception $e) {

                Log::critical('Excepción inesperada Runificador', [
                    'url' => env('RUNIFICADOR_REST_URL') ,
                    'method' => env('RUNIFICADOR_REST_URL_METODO'),
                    'run'   => $runCiudadano,
                    'dv'    => $dv,
                    'error' => $e->getMessage(),
                    'file'  => $e->getFile(),
                    'line'  => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]);

                return Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR;
            }
        }

        return Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR;
    }

    /**
     * Espera un tiempo creciente entre intentos (máximo 1 segundo).
     */
    private function esperarAntesDeReintentar($intento, $maxIntentos)
    {
        if ($intento >= $maxIntentos) {
            return;
        }

        usleep(min($intento, 4) * self::ESPERA_BASE_MS * 1000);
    }
}
