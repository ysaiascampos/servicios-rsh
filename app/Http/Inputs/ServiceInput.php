<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;
use App\Utiles;
use Exception;
use Iterator;
use JsonSerializable;
use App\Exceptions\ExcepcionValidacion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

abstract class ServiceInput implements JsonSerializable, Iterator
{
    /**
     * @var array Definicion de las propiedades del objeto y sus valores permitidos.
     */
    protected $default;
    protected $campos;
    protected $attributes;
    protected $messages = [];

    protected $campoTitular = ['run-titular', 'run'];
    protected $removerCamposNull = false;

    /**
     * Define los campos de entrada
     * @return void
     */
    abstract protected function define();

    /**
     * @throws ExcepcionValidacion
     */
    public function __construct(array $arrayInput, $defaultValue = null)
    {
        $this->default = $defaultValue;
        $this->define();
        $arrayInput = array_change_key_case($arrayInput, CASE_LOWER);
        foreach ($this->campos as $campo => $definicion) {
            $campo = strtolower($campo);
            $this->attributes[$campo] = $this->default;
            if (array_key_exists($campo, $arrayInput)) {
                if (strpos($definicion, 'object') !== false) {
                    $this->attributes[$campo] = $this->createSubObject($definicion, $arrayInput[$campo]);
                } else {
                    $this->attributes[$campo] = $this->setFormat($definicion, $arrayInput[$campo]);
                }
            }
        }
        if ($this->removerCamposNull) {
            $this->attributes = array_filter($this->attributes);
        }
        $this->validate();
        $this->log(self::CASO_LOG_INPUT);
    }

    /**
     * @throws ExcepcionValidacion
     */
    public function validate(array $fields = null, array $rules = null, array $messages = null)
    {
        $entradas = is_null($fields) ? array_filter($this->attributes) : $fields;
        $reglas = is_null($rules) ? $this->campos : $rules;
        $mensajes = is_null($messages) ? $this->messages : $messages;
        $validador = Validator::make($entradas, $reglas, $mensajes);
        if ($validador->fails()) {
            throw ExcepcionValidacion::new($validador->errors(), Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO)
                ->setTitular($this->obtenerTitular());
        }
        return true;
    }

    protected function setFormat(string $definition, $value)
    {
        $def = explode('|', $definition);
        if (is_string($value)) {
            $value = trim($value);
            if (in_array('lowercase', $def)) {
                $value = strtolower($value);
            }
            if (in_array('uppercase', $def)) {
                $value = strtoupper($value);
            }
        }
        return $value;
    }

    /**
     * @throws ExcepcionValidacion
     * @throws Exception
     */
    protected function createSubObject(string $definition, $data)
    {
        $out = null;
        try {
            $definitionsList = explode('|', trim($definition));
            foreach ($definitionsList as $currentDefinition) {
                $def = explode(':', trim($currentDefinition));
                $type = trim($def[0]);
                if ($type != 'object') {
                    continue;
                }
                $class = trim($def[1]);
                if (in_array('array', $definitionsList)) {
                    $out = [];
                    if (!is_array($data)) {
                        $out[] = false;
                    } else {
                        foreach ($data as $elemento) {
                            if (!is_array($elemento)) {
                                $out[] = false;
                            } else {
                                $out[] = new $class($elemento);     //puede disparar ExcepcionValidacion
                            }
                        }
                    }
                } else {
                    /**
                     * @throw ExcepcionValidacion
                     */
                    $out = new $class($data);
                }
            }
        } catch (ExcepcionValidacion $ex) {
            throw $ex;
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            $out = false;
        }
        return $out;
    }

    private function obtenerTitular()
    {
        $titular = null;
        if(is_array($this->campoTitular)){
            foreach ($this->campoTitular as $campoTitular) {
                if (isset($this->attributes[$campoTitular])) {
                    $titular = $this->attributes[$campoTitular];
                    break;
                }
            }
        }
        return $titular;
    }

    /**
     * Aplica explícitamente la validacion de campo requerido a los campos especificados en el argumento.
     * @param array $camposObligatorios Array que contiene el nombre de los campos que deben ser obligatorios.
     * @return bool true en caso de éxito.
     * @throws ExcepcionValidacion En caso de error de alguna validacion.
     */
    public function validarCamposObligatorios(array $camposObligatorios, $mensajes = null)
    {
        $reglas = array_fill_keys($camposObligatorios, 'required');
        return $this->validate($this->attributes, $reglas, $mensajes);
    }

    /**
     * Devuelve el valor de una propiedad o un valor arbitrario si dicha propiedad no está definida.
     * @param string $name Nombre de la propiedad a recuperar.
     * @param mixed|null $defaultValue Valor por defecto devuelto en caso de que la propiedad no
     * esté definida en el objeto.
     * @return mixed|null
     */
    public function get(string $name, $defaultValue = null)
    {
        $name = strtolower($name);
        if (!is_null($this->$name)) {
            $value = $this->$name;
        } else {
            $value = (func_num_args() == 2) ? $defaultValue : $this->default;
        }
        return $value;
    }

    public function set(string $name, $valor)
    {
        $name = strtolower($name);
        $this->$name = $valor;
    }

    public function __get($name)
    {
        $value = $this->default;
        $attribute = $name;
        if (!array_key_exists($attribute, $this->attributes)) {
            $attribute = Utiles::camelToKebab($name);
            if (!array_key_exists($attribute, $this->attributes)) {
                $this->log(self::CASO_ERROR_GET, $name);
                return $value;
            }
        }
        if (!is_null($this->attributes[$attribute])) {
            $value = $this->setFormat($this->campos[$attribute], $this->attributes[$attribute]);
        }
        return $value;
    }

    /**
     * @throws ExcepcionValidacion
     */
    public function __set($name, $value)
    {
        $attribute = $name;
        if (!array_key_exists($attribute, $this->attributes)) {
            $attribute = Utiles::camelToKebab($name);
            if (!array_key_exists($attribute, $this->attributes)) {
                $this->log(self::CASO_ERROR_SET, $name);
                return;
            }
        }
        $this->validate([$attribute => $value]);
        $this->attributes[$attribute] = $value;
    }

    protected function log($case, $arg = null)
    {
        if (env('LOG_SERVICE_INPUT', false)) {
            switch ($case) {
                case self::CASO_ERROR_GET:
                    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
                    $info = $trace[1]['file'] . ':' . $trace[1]['line'];
                    Log::error("[" . get_class($this) . "] Lectura: Campo '" . $arg . "' no definido, en " . $info);
                    break;
                case self::CASO_ERROR_SET:
                    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
                    $info = $trace[1]['file'] . ':' . $trace[1]['line'];
                    Log::error("[" . get_class($this) . "] Escritura: Campo '" . $arg . "' no definido, en " . $info);
                    break;
                case self::CASO_LOG_INPUT:
                    Log::info("<" . get_class($this) . "> " . json_encode($this->all()));
                    break;
            }
        }
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function all()
    {
        //alias de toArray() para compatibilidad
        return $this->toArray();
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function rewind()
    {
        reset($this->attributes);
    }

    public function current()
    {
        return current($this->attributes);
    }

    public function key()
    {
        return key($this->attributes);
    }

    public function next()
    {
        next($this->attributes);
    }

    public function valid()
    {
        return false !== $this->current();
    }

    const CASO_ERROR_GET = 1;
    const CASO_ERROR_SET = 2;
    const CASO_LOG_INPUT = 3;
}
