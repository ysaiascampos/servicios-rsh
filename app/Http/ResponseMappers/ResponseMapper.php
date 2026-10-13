<?php

namespace App\Http\ResponseMappers;

use App\Constantes\Constante;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Contracts\Support\Responsable;
use JsonSerializable;

class ResponseMapper implements JsonSerializable, Responsable
{
    protected $statusCode = Constante::STATUS_200_SUCCESS_OK;
    protected $headers = [];
    protected $responseOptions = 0;
    public $campos = [];

    public function jsonSerialize()
    {
        return $this->campos;
    }

    public function toResponse($request)
    {
        if (substr_count($request->header('accept-encoding'), 'gzip')) {
            $this->headers['Content-Encoding'] = 'gzip';
        }

        if ($this->getHeader('Content-Encoding') == 'gzip') {
            $this->headers['Content-type'] = 'application/json';
            $response = new HttpResponse(gzencode(json_encode($this->campos)), $this->statusCode, $this->headers);
        } else {
            $response = new JsonResponse($this->campos, $this->statusCode, $this->headers, $this->responseOptions);
        }
        return $response;
    }

    protected function getHeader($header, $default = null)
    {
        $out = $default;
        if (isset($this->headers[$header])) {
            $out = $this->headers[$header];
        }
        return $out;
    }

    protected function setHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }

    protected function entero($campo)
    {
        if (is_null($campo)) {
            $out = null;
        } elseif (is_numeric($campo)) {
            $out = intval($campo);
        } else {
            $out = $campo;
        }
        return $out;
    }

    protected function flotante($campo)
    {
        if (is_null($campo)) {
            $out = null;
        } elseif (is_numeric($campo)) {
            $out = floatval($campo);
        } else {
            $out = $campo;
        }
        return $out;
    }

    protected function booleano($campo)
    {
        if (is_null($campo)) {
            $out = null;
        } elseif (is_bool($campo)) {
            $out = $campo;
        } elseif (is_numeric($campo)) {
            $out = $campo > 0;
        } elseif (in_array(strtolower($campo), ['si', 'true'])) {
            $out = true;
        } elseif (in_array(strtolower($campo), ['no', 'false'])) {
            $out = true;
        } else {
            $out = $campo;
        }
        return $out;
    }
}
