<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Exception;
use Illuminate\Support\ServiceProvider;
use Yajra\Oci8\Oci8ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Máximo de intentos por petición cuando la respuesta es HTTP 401.
     */
    const MAX_INTENTOS = 3;

    /**
     * Espera base entre intentos, en milisegundos.
     */
    const ESPERA_BASE_MS = 250;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(Oci8ServiceProvider::class);
        $this->app->register(ExtendedValidationServiceProvider::class);

        if ($this->app->isLocal()) {

            $this->app->register(IdeHelperServiceProvider::class);

        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Http::macro('APIPROCESSBID', function ($name) {

            $apiEnvPrefix    = "APIPROCESSBID_{$name}";
            $authType        = strtoupper(env("{$apiEnvPrefix}_AUTH_TYPE", 'NONE'));
            $responseTimeout = env("{$apiEnvPrefix}_RESPONSE_TIMEOUT", 30);
            $baseUrl         = env("{$apiEnvPrefix}_URL");

            $headers = [
                'Accept' => 'application/json',
            ];

            $apiToken = env("{$apiEnvPrefix}_HEADER_TOKEN");

            if (!empty($apiToken)) {
                $headers['Api-Token'] = $apiToken;
            }

            if (function_exists('request')) {

                try {
                    $bearerToken = request()->bearerToken();
                    if (!empty($bearerToken)) {
                        $headers['Authorization'] = 'Bearer ' . $bearerToken;
                    }
                } catch (\Exception $e) {
                    Log::debug(
                        'APIPROCESSBID: sin request activo',
                        ['error' => $e->getMessage()]
                    );
                }
            }

            $http = Http::withHeaders($headers)
                ->timeout($responseTimeout)
                ->baseUrl($baseUrl);

            $authTokenUrl = env(
                "{$apiEnvPrefix}_OAUTH_TOKEN_URL",
                env('GW_OAUTH_TOKEN_URL')
            );

            $clientId = env(
                "{$apiEnvPrefix}_OAUTH_CLIENT_ID",
                env('GW_OAUTH_CLIENT_ID')
            );

            $clientSecret = env(
                "{$apiEnvPrefix}_OAUTH_CLIENT_SECRET",
                env('GW_OAUTH_CLIENT_SECRET')
            );

            /**
             * Solicita un token nuevo según el tipo de autenticación configurado.
             */
            $solicitarToken = function () use ($authType, $authTokenUrl, $clientId, $clientSecret) {

                if ($authType === 'OAUTH') {

                    $oauthResponse = Http::asForm()->post($authTokenUrl, [
                        'client_id'     => $clientId,
                        'client_secret' => $clientSecret,
                        'grant_type'    => 'client_credentials',
                    ]);

                    if (!$oauthResponse->successful()) {
                        throw new \Exception(
                            'No fue posible obtener token OAuth'
                        );
                    }

                    $accessToken = $oauthResponse->json()['access_token'] ?? null;

                    if (!$accessToken) {
                        throw new \Exception(
                            'OAuth response sin access_token'
                        );
                    }

                    return $accessToken;
                }

                if ($authType === 'BASIC') {

                    $oauthResponse = Http::asForm()
                        ->withBasicAuth($clientId, $clientSecret)
                        ->post($authTokenUrl, [
                            'grant_type' => 'client_credentials',
                        ]);

                    if (!$oauthResponse->successful()) {
                        throw new \Exception(
                            'No fue posible obtener token BASIC'
                        );
                    }

                    $accessToken = $oauthResponse->json()['access_token'] ?? null;

                    if (!$accessToken) {
                        throw new \Exception(
                            'Basic response sin access_token'
                        );
                    }

                    return $accessToken;
                }

                return null;
            };

            /**
             * Envía la petición y, si la respuesta con el token obtenido es HTTP
             * 401, solicita un token nuevo y repite la petición hasta agotar
             * $maxIntentos.
             */
            $enviarConReintentos = function (
                $handler,
                $request,
                array $options,
                $intento,
                callable $solicitarToken,
                $apiName,
                $maxIntentos,
                $esperaBaseMs
            ) use (&$enviarConReintentos) {

                return $handler($request, $options)->then(
                    function ($response) use (
                        $handler,
                        $request,
                        $options,
                        $intento,
                        $solicitarToken,
                        $apiName,
                        $maxIntentos,
                        $esperaBaseMs,
                        &$enviarConReintentos
                    ) {

                        if ($response->getStatusCode() !== 401) {
                            return $response;
                        }

                        Log::warning('APIPROCESSBID: token inválido o expirado', [
                            'api'          => $apiName,
                            'url'          => (string) $request->getUri(),
                            'status_code'  => 401,
                            'intento'      => $intento,
                            'max_intentos' => $maxIntentos,
                        ]);

                        if ($intento >= $maxIntentos) {

                            Log::error('APIPROCESSBID: se agotaron los intentos con respuesta 401', [
                                'api'      => $apiName,
                                'url'      => (string) $request->getUri(),
                                'intentos' => $maxIntentos,
                            ]);

                            return $response;
                        }

                        $accessToken = $solicitarToken();

                        if ($esperaBaseMs > 0) {
                            usleep(min($intento, 4) * $esperaBaseMs * 1000);
                        }

                        Log::info('APIPROCESSBID: reintentando con token renovado', [
                            'api'     => $apiName,
                            'url'     => (string) $request->getUri(),
                            'intento' => $intento + 1,
                        ]);

                        return $enviarConReintentos(
                            $handler,
                            $request->withHeader('Authorization', 'Bearer ' . $accessToken),
                            $options,
                            $intento + 1,
                            $solicitarToken,
                            $apiName,
                            $maxIntentos,
                            $esperaBaseMs
                        );
                    }
                );
            };

            if ($authType === 'OAUTH' || $authType === 'BASIC') {

                $http = $http->withToken($solicitarToken());

                $maxIntentos = max(
                    1,
                    (int) env("{$apiEnvPrefix}_INTENTOS", AppServiceProvider::MAX_INTENTOS)
                );

                $esperaBaseMs = max(
                    0,
                    (int) env("{$apiEnvPrefix}_ESPERA_REINTENTO_MS", AppServiceProvider::ESPERA_BASE_MS)
                );

                $http = $http->withMiddleware(
                    function (callable $handler) use ($enviarConReintentos, $solicitarToken, $name, $maxIntentos, $esperaBaseMs) {

                        return function ($request, array $options) use ($handler, $enviarConReintentos, $solicitarToken, $name, $maxIntentos, $esperaBaseMs) {

                            return $enviarConReintentos(
                                $handler,
                                $request,
                                $options,
                                1,
                                $solicitarToken,
                                $name,
                                $maxIntentos,
                                $esperaBaseMs
                            );
                        };
                    }
                );
            }

            return $http;
        });
    }
}
