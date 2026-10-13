<?php

namespace App\Exceptions;

use App\Constantes\Constante;
use Exception;
use Throwable;

abstract class ExcepcionServicio extends Exception
{
    /**
     * @var ErrorWrapper
     */
    protected $errorWrapper;
    protected $categoria;
    protected $nivelLog = Constante::LOG_LEVEL_ERROR;
    protected $contexto = [];
    protected $statusCode;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if (is_null($this->errorWrapper)) {
            $this->errorWrapper = new ErrorWrapper();
            $this->errorWrapper->collectError($message, $code);
        }
    }

    public function getErrorWrapper()
    {
        return $this->errorWrapper;
    }

    public function setTitular($titular)
    {
        if (!empty($titular)) {
            $this->errorWrapper->setTitular($titular);
            $this->contexto['titular'] = $titular;
        }
        return $this;
    }

    public function setOrigen($origen)
    {
        if (!empty($origen)) {
            $this->errorWrapper->setOrigen($origen);
            $this->contexto['origen'] = $origen;
        }
        return $this;
    }

    public function setContexto(array $informacionContexto)
    {
        if (!empty($informacionContexto)) {
            $this->contexto = array_merge($this->contexto, $informacionContexto);
        }
        return $this;
    }

    public function getContexto()
    {
        return $this->contexto;
    }

    public function setNivelLog($nivelLog)
    {
        if (!empty($nivelLog)) {
            $this->nivelLog = $nivelLog;
        }
        return $this;
    }

    public function getNivelLog()
    {
        return $this->nivelLog;
    }

    public function setStatusCode($statusCode)
    {
        if (!empty($statusCode)) {
            $this->statusCode = $statusCode;
        }
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function establecerArchivoOrigenDesdeTraza()
    {
        $trace = $this->getTrace();
        $this->file = $trace[0]['file'];
        $this->line = $trace[0]['line'];
    }
}
