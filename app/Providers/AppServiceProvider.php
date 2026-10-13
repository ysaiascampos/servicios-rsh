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

                $http = $http->withToken($accessToken);
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

                $http = $http->withToken($accessToken);
            }

            return $http;
        });
    }
}
