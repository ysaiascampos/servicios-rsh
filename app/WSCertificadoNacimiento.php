<?php
namespace App;

use App\Constantes\Constante;
use DomainException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Validator;
use LogicException;
use OutOfRangeException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property mixed rutpadre
 * @property mixed dvpadre
 * @property mixed rutmadre
 * @property mixed dvmadre
 */
class WSCertificadoNacimiento
{
    public $estado;
    public $mensaje;
    public $detalle = [];

    protected $_exception;

    /**
     * @param $rutCiudadano
     * @param $rutUsuario
     * @param $validacion
     * @return WSCertificadoNacimiento
     * @throws Exception
     * @throws GuzzleException
     */
    public static function get($rutCiudadano, $rutUsuario, $validacion)
    {
        $_instancia = new self;
        $_instancia->validateInputs(func_get_args());
        $_instancia->map($_instancia->guzzleConsumption([
            'parameters' => implode('/', [$rutCiudadano, $rutUsuario, $validacion])
        ]));
        return $_instancia;
    }

    private function map($response)
    {
        $this->estado = $response['estado'];
        $this->mensaje = $response['descripcion'];
        if ($this->estado != Response::HTTP_OK) {
            $this->wsErrorHandler($this->mensaje, $this->estado);
        }
        if (isset($response['datos']) && !empty($response['datos'])) {
            foreach ($response['datos'] as $key => $value) {
                if ($value == null) {
                    $value = Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE;
                }
                if (strpos($key, 'rut') !== false) {
                    if (!is_numeric($value) || (int)$value < 1) {
                        $value = Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE;
                    }
                }
                $this->detalle[$key] = $value;
            }
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws GuzzleException
     */
    private function guzzleConsumption($data)
    {
        if (!env('CERTIFICADO_NACIMIENTO_WS_URL',false)) {
            throw new LogicException("La variable de entorno 'CERTIFICADO_NACIMIENTO_WS_URL' no se encuentra habilitada");
        }
        $uri = parse_url(env('CERTIFICADO_NACIMIENTO_WS_URL'));
        if ($uri == false) {
            throw new LogicException('La URI del servicio de certificado de nacimiento no está correctamente formada.');
        }
        $timeout = Constante::ENV_DEFECTO_TIMEOUT_WS_CERTIFICADO_NACIMIENTO;
        if (is_numeric(env('CERTIFICADO_NACIMIENTO_WS_TIMEOUT'))) {
            $timeout = env('CERTIFICADO_NACIMIENTO_WS_TIMEOUT');
        }

        $baseUri = $uri['scheme'] . '://' . $uri['host'];
        $path = $uri['path'] . '/' . $data['parameters'];
        $config = [
            'connect_timeout' => $timeout,
            'timeout' => $timeout
        ];
        $client = new Client(['base_uri' => $baseUri]);
        $responseObject = $client->request('GET', $path, $config);
        $statusCode = $responseObject->getStatusCode();
        if ($statusCode != Response::HTTP_OK) {
            $this->wsErrorHandler('Falló el consumo del certificado de nacimiento', $statusCode);
        }

        return json_decode($responseObject->getBody(), true);
    }

    private function validateInputs($args)
    {
        $input = array_combine(['rutCiudadano', 'rutUsuario', 'validacion'], $args);
        $rules = [
            'rutCiudadano' => 'run',
            'rutUsuario' => 'run',
            'validacion' => 'integer'
        ];
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            throw new DomainException($validator->messages());
        }
    }

    private function wsErrorHandler($message, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $this->estado = $statusCode;
        $this->mensaje = $message;
        $this->_exception = new Exception($message, null, $this->_exception);
    }

    // establece si lanza una excepcion cuando el codigo de estado devuelto no es 200
    public function withWSException($value)
    {
        if ($value && !is_null($this->_exception)) {
            throw $this->_exception;
        }
        return $this;
    }

    public function __get($name)
    {
        if (!isset($this->detalle[$name])) {
            throw new OutOfRangeException('No existe la propiedad \''.$name.'\'');
        }
        return $this->detalle[$name];
    }

    public function __toString()
    {
        return json_encode($this);
    }

}
