<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ErrorWrapper;
use Illuminate\Support\MessageBag;

trait ValidacionTrait
{
    /**
     * @var ErrorWrapper
     */
    public $errors;

    /**
     * @param string|MessageBag $mensaje
     * @param int $codigo
     * @param string $origen
     * @param int $titular
     * @param array $info
     */
    protected function collectError(
        $mensaje,
        int $codigo,
        $origen = null,
        $titular = null,
        array $info = null
    )
    {
        $origen = $origen ?? Constante::ERROR_DEFAULT_ORIGEN;
        $titular = $titular ?? Constante::ERROR_DEFAULT_TITULAR;

        if (empty($this->errors)) {
            $this->errors = new ErrorWrapper();
        }

        $this->errors->collectError($mensaje, $codigo, $origen, $titular, $info);
    }

    public function addErrorWrapper(ErrorWrapper $errorWrapper)
    {
        if (empty($this->errors)) {
            $this->errors = $errorWrapper;
        } else {
            $this->errors->merge($errorWrapper);
        }
    }

    public function validarArreglo($camposValidar = [], $requerido = true, $input, $titular = null)
    {
        foreach ($camposValidar as $key => $campo) {
            $valorCampo = $input->get($key);
            if (is_null($valorCampo) && $requerido
            && isset($campo['mensaje']['requerido'])
            && isset($campo['codigo']['requerido'])) {
                $this->collectError($campo['mensaje']['requerido'],
                    $campo['codigo']['requerido'],
                    $key,
                    $titular);
            }
            if (array_key_exists('tipo',$campo)) {
                switch ($campo['tipo']) {
                    case 'between':
                        if (($valorCampo < $campo['between'][0]
                            || $valorCampo > $campo['between'][1])
                            && !is_null($valorCampo)) {
                            $this->collectError(str_replace(['%min%', '%max%'], $campo['between'], $campo['mensaje']['between']),
                                $campo['codigo']['between'],
                                $key,
                                $titular);
                        }
                        break;
                    case 'in':
                        if (!in_array($valorCampo, $campo['in'])
                            && !is_null($valorCampo)) {
                            $this->collectError(str_replace('%valores%', implode(', ', $campo['in']), $campo['mensaje']['in']),
                                $campo['codigo']['in'],
                                $key,
                                $titular);
                        }
                        break;
                    case 'date_format':
                        $dateParse = date_parse_from_format($campo['date_format'], $valorCampo);
                        if ($dateParse['error_count'] !== 0 || $dateParse['warning_count'] !== 0) {
                            $this->collectError($campo['mensaje']['date_format'],
                                $campo['codigo']['date_format'],
                                $key,
                                $titular);
                        }
                        break;
                }
            }
            if (array_key_exists('dato',$campo)) {
                switch ($campo['dato']) {
                    case 'entero':
                        if (is_nan($valorCampo)) {
                            $this->collectError($campo['mensaje']['dato'] . $campo['dato'],
                                $campo['codigo']['dato'],
                                $key,
                                $titular);
                        }
                        break;
                    case 'null':
                        if (!is_null($valorCampo)) {
                            $this->collectError($campo['mensaje']['dato'] . $campo['dato'],
                                $campo['codigo']['dato'],
                                $key,
                                $titular);
                        }
                        break;
//                case 'fecha':
//                    checkdate ( $month, $day, $year )
//                    break;
                }
            }
        }
    }
}
