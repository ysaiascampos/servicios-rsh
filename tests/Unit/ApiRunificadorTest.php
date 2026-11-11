<?php

namespace Tests\Unit;

use App\Clases\ApiRunificador;
use App\Constantes\Constante;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use ReflectionProperty;
use Tests\TestCase;

/**
 * Pruebas del mecanismo de reintentos de ApiRunificador.
 *
 * Se reemplaza el cliente Guzzle por un MockHandler para simular las respuestas
 * del servicio Runificador (token y consulta de persona) sin hacer peticiones
 * reales. Cada escenario define la secuencia exacta de respuestas y luego se
 * inspecciona el historial de peticiones para validar cuántos intentos se
 * realizaron y con qué token.
 */
class ApiRunificadorTest extends TestCase
{
    const TOKEN_URL = 'https://runificador.test/oauth/token';

    const URL_METODO = 'https://runificador.test/persona';

    /**
     * Variables de entorno originales, para restaurarlas al finalizar cada prueba.
     *
     * @var array
     */
    private $variablesEntornoOriginales = [];

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();

        $this->establecerVariablesEntorno([
            'RUNIFICADOR_REST_URL'            => 'https://runificador.test',
            'RUNIFICADOR_REST_TOKEN_URL'      => self::TOKEN_URL,
            'RUNIFICADOR_REST_URL_METODO'     => self::URL_METODO,
            'RUNIFICADOR_REST_CLIENTID'       => 'cliente-prueba',
            'RUNIFICADOR_REST_CLIENTSECRET'   => 'secreto-prueba',
            'RUNIFICADOR_REST_API_TOKEN'      => 'api-token-prueba',
            'RUNIFICADOR_REST_CACHE_KEY_PRE'  => 'runificador_prueba_',
            'RUNIFICADOR_REST_CACHE_TIEMPO'   => '600',
            'RUNIFICADOR_REST_INTENTOS'       => (string) ApiRunificador::MAX_INTENTOS,
        ]);
    }

    protected function tearDown(): void
    {
        $this->restaurarVariablesEntorno();

        Cache::flush();

        parent::tearDown();
    }

    /**
     * Camino feliz: el primer intento responde OK y no se reintenta.
     */
    public function testObtienePersonaEnElPrimerIntento()
    {
        $historial = [];

        $api = $this->crearApi([
            $this->respuestaToken('token-1'),
            $this->respuestaPersonaOk(),
        ], $historial);

        $persona = $api->getPersona('12345678', '9');

        $this->assertSame('OK', $persona->Estado);
        $this->assertCount(2, $historial);
        $this->assertCount(1, $this->solicitudesToken($historial));
        $this->assertCount(1, $this->solicitudesPersona($historial));
        $this->assertSame(
            'Bearer token-1',
            $this->tokenDeSolicitudPersona($historial, 0)
        );
    }

    /**
     * Primer intento responde 401: se solicita un token nuevo y el segundo
     * intento responde OK.
     */
    public function testReintentaYObtienePersonaEnElSegundoIntento()
    {
        $historial = [];

        $api = $this->crearApi([
            $this->respuestaToken('token-1'),
            $this->respuestaNoAutorizado(),
            $this->respuestaToken('token-2'),
            $this->respuestaPersonaOk(),
        ], $historial);

        $persona = $api->getPersona('12345678', '9');

        $this->assertSame('OK', $persona->Estado);

        // token + persona + token nuevo + persona
        $this->assertCount(4, $historial);
        $this->assertCount(2, $this->solicitudesToken($historial));

        $solicitudesPersona = $this->solicitudesPersona($historial);
        $this->assertCount(2, $solicitudesPersona);

        // La segunda consulta debe usar el token renovado.
        $this->assertSame('Bearer token-1', $this->tokenDeSolicitudPersona($historial, 0));
        $this->assertSame('Bearer token-2', $this->tokenDeSolicitudPersona($historial, 1));
    }

    /**
     * Dos respuestas 401 consecutivas: el tercer intento responde OK.
     */
    public function testReintentaYObtienePersonaEnElTercerIntento()
    {
        $historial = [];

        $api = $this->crearApi([
            $this->respuestaToken('token-1'),
            $this->respuestaNoAutorizado(),
            $this->respuestaToken('token-2'),
            $this->respuestaNoAutorizado(),
            $this->respuestaToken('token-3'),
            $this->respuestaPersonaOk(),
        ], $historial);

        $persona = $api->getPersona('12345678', '9');

        $this->assertSame('OK', $persona->Estado);

        // token + persona + token + persona + token + persona
        $this->assertCount(6, $historial);
        $this->assertCount(3, $this->solicitudesToken($historial));

        $solicitudesPersona = $this->solicitudesPersona($historial);
        $this->assertCount(3, $solicitudesPersona);

        $this->assertSame('Bearer token-1', $this->tokenDeSolicitudPersona($historial, 0));
        $this->assertSame('Bearer token-2', $this->tokenDeSolicitudPersona($historial, 1));
        $this->assertSame('Bearer token-3', $this->tokenDeSolicitudPersona($historial, 2));
    }

    /**
     * Los tres intentos responden 401: se detiene y retorna error de token.
     */
    public function testAgotaLosTresIntentosYRetornaErrorDeToken()
    {
        $historial = [];

        $api = $this->crearApi([
            $this->respuestaToken('token-1'),
            $this->respuestaNoAutorizado(),
            $this->respuestaToken('token-2'),
            $this->respuestaNoAutorizado(),
            $this->respuestaToken('token-3'),
            $this->respuestaNoAutorizado(),
        ], $historial);

        $resultado = $api->getPersona('12345678', '9');

        $this->assertSame(
            Constante::DATOS_DE_CONTACTO_7_ERROR_TOKEN_RUNIFICADOR,
            $resultado
        );

        // Exactamente 3 consultas de persona (ni una más) y 3 solicitudes de token.
        $this->assertCount(3, $this->solicitudesPersona($historial));
        $this->assertCount(3, $this->solicitudesToken($historial));
        $this->assertCount(6, $historial);
    }

    /**
     * Si al renovar el token no se obtiene access_token, no se realiza un nuevo
     * intento de consulta y se retorna error de token.
     */
    public function testNoReintentaConsultaSiFallaLaRenovacionDeToken()
    {
        $historial = [];

        $api = $this->crearApi([
            $this->respuestaToken('token-1'),
            $this->respuestaNoAutorizado(),
            $this->respuestaTokenSinAccessToken(),
        ], $historial);

        $resultado = $api->getPersona('12345678', '9');

        $this->assertSame(
            Constante::DATOS_DE_CONTACTO_7_ERROR_TOKEN_RUNIFICADOR,
            $resultado
        );

        // token + persona(401) + intento de token
        $this->assertCount(3, $historial);
        $this->assertCount(2, $this->solicitudesToken($historial));
        $this->assertCount(1, $this->solicitudesPersona($historial));
    }

    /**
     * Un error HTTP distinto de 401 conserva el comportamiento original: no se
     * reintenta y se retorna el error inesperado del Runificador.
     */
    public function testNoReintentaAnteUnErrorHttpDistintoDe401()
    {
        $historial = [];

        $api = $this->crearApi([
            $this->respuestaToken('token-1'),
            $this->respuestaErrorServidor(),
        ], $historial);

        $resultado = $api->getPersona('12345678', '9');

        $this->assertSame(
            Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR,
            $resultado
        );

        $this->assertCount(2, $historial);
        $this->assertCount(1, $this->solicitudesPersona($historial));
    }

    /**
     * Construye una instancia de ApiRunificador con un cliente Guzzle simulado.
     *
     * @param array $respuestas Respuestas que entregará el MockHandler en orden.
     * @param array $historial  Se completa con las transacciones realizadas.
     *
     * @return ApiRunificador
     */
    private function crearApi(array $respuestas, array &$historial)
    {
        $mock = new MockHandler($respuestas);

        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($historial));

        $cliente = new Client([
            'handler'   => $stack,
            'base_uri'  => 'https://runificador.test',
        ]);

        $api = new ApiRunificador();

        $propiedad = new ReflectionProperty(ApiRunificador::class, 'guzzle');
        $propiedad->setAccessible(true);
        $propiedad->setValue($api, $cliente);

        return $api;
    }

    /**
     * @param string $token
     *
     * @return Response
     */
    private function respuestaToken($token)
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['access_token' => $token])
        );
    }

    /**
     * Respuesta 200 sin access_token.
     *
     * @return Response
     */
    private function respuestaTokenSinAccessToken()
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['token_type' => 'Bearer'])
        );
    }

    /**
     * @return Response
     */
    private function respuestaPersonaOk()
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'Estado' => 'OK',
                'Nombre' => 'PERSONA DE PRUEBA',
            ])
        );
    }

    /**
     * @return Response
     */
    private function respuestaNoAutorizado()
    {
        return new Response(
            401,
            ['Content-Type' => 'application/json'],
            json_encode(['error' => 'invalid_token'])
        );
    }

    /**
     * @return Response
     */
    private function respuestaErrorServidor()
    {
        return new Response(
            500,
            ['Content-Type' => 'application/json'],
            json_encode(['error' => 'error_interno'])
        );
    }

    /**
     * Peticiones de consulta de persona (llevan header Authorization).
     *
     * @param array $historial
     *
     * @return array
     */
    private function solicitudesPersona(array $historial)
    {
        return array_values(array_filter($historial, function ($transaccion) {
            return $transaccion['request']->hasHeader('Authorization');
        }));
    }

    /**
     * Peticiones de obtención de token (no llevan header Authorization).
     *
     * @param array $historial
     *
     * @return array
     */
    private function solicitudesToken(array $historial)
    {
        return array_values(array_filter($historial, function ($transaccion) {
            return !$transaccion['request']->hasHeader('Authorization');
        }));
    }

    /**
     * Token usado en la enésima consulta de persona.
     *
     * @param array $historial
     * @param int   $indice
     *
     * @return string
     */
    private function tokenDeSolicitudPersona(array $historial, $indice)
    {
        $solicitudes = $this->solicitudesPersona($historial);

        return $solicitudes[$indice]['request']->getHeaderLine('Authorization');
    }

    /**
     * Define variables de entorno guardando su valor previo.
     *
     * @param array $variables
     *
     * @return void
     */
    private function establecerVariablesEntorno(array $variables)
    {
        foreach ($variables as $nombre => $valor) {

            $this->variablesEntornoOriginales[$nombre] = [
                'env'    => array_key_exists($nombre, $_ENV) ? $_ENV[$nombre] : null,
                'server' => array_key_exists($nombre, $_SERVER) ? $_SERVER[$nombre] : null,
                'putenv' => getenv($nombre) === false ? null : getenv($nombre),
            ];

            $_ENV[$nombre] = $valor;
            $_SERVER[$nombre] = $valor;
            putenv($nombre . '=' . $valor);
        }
    }

    /**
     * Restaura las variables de entorno modificadas por la prueba.
     *
     * @return void
     */
    private function restaurarVariablesEntorno()
    {
        foreach ($this->variablesEntornoOriginales as $nombre => $original) {

            if ($original['env'] === null) {
                unset($_ENV[$nombre]);
            } else {
                $_ENV[$nombre] = $original['env'];
            }

            if ($original['server'] === null) {
                unset($_SERVER[$nombre]);
            } else {
                $_SERVER[$nombre] = $original['server'];
            }

            if ($original['putenv'] === null) {
                putenv($nombre);
            } else {
                putenv($nombre . '=' . $original['putenv']);
            }
        }

        $this->variablesEntornoOriginales = [];
    }
}
