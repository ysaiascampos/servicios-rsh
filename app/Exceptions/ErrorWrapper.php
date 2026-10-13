<?php

namespace App\Exceptions;

use App\Constantes\Constante;
use Countable;
use Illuminate\Support\MessageBag;
use Iterator;
use Throwable;

class ErrorWrapper implements Iterator, Countable
{
    /**
     * @var array[][]
     */
    public $errors;

    /**
     * @param string|MessageBag $mensaje
     * @param int $codigo
     * @param string $origen
     * @param int $titular
     * @param array $info
     */

    public function collectError(
        $mensaje,
        int $codigo,
        $origen = null,
        $titular = null,
        array $info = null
    ) {
        $origen = $origen ?? Constante::ERROR_DEFAULT_ORIGEN;
        $titular = $titular ?? Constante::ERROR_DEFAULT_TITULAR;

        if (empty($this->errors)) {
            $this->errors = [
                $titular => []
            ];
        }

        if (is_string($mensaje)) {
            $this->errors[$titular][$origen][] = new DetalleError($codigo, $mensaje, $info);
        } elseif ($mensaje instanceof MessageBag) {
            $bag = $mensaje->getMessages();
            $codigoGenerico = Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO;
            foreach ($bag as $campo => $listadoErrores) {
                foreach ($listadoErrores as $detalle) {
                    $this->errors[$titular][$campo][] = new DetalleError($codigoGenerico, $detalle, $info);
                }
            }
        }
    }

    public function merge(ErrorWrapper $errorWrapper)
    {
        $errors = $errorWrapper->errors;
        foreach($errors as $titular => $campos){
            if(array_key_exists($titular,$this->errors)){
                foreach($campos as $campo => $detalleErrores){
                    $this->errors[$titular][$campo] = $detalleErrores;
                }
            }else{
                $this->errors[$titular] = $campos;
            }
        }
    }

    public function setOrigen(string $origen): ErrorWrapper
    {
        foreach ($this->errors as $titular => $errores) {
            if (array_key_exists(Constante::ERROR_DEFAULT_ORIGEN, $errores)) {
                $this->errors[$titular][$origen] = $errores[Constante::ERROR_DEFAULT_ORIGEN];
                unset($this->errors[$titular][Constante::ERROR_DEFAULT_ORIGEN]);
                break;
            }
        }
        return $this;
    }

    public function setTitular(int $titular): ErrorWrapper
    {
        if (array_key_exists(Constante::ERROR_DEFAULT_TITULAR, $this->errors)) {
            $this->errors[$titular] = $this->errors[Constante::ERROR_DEFAULT_TITULAR];
            unset($this->errors[Constante::ERROR_DEFAULT_TITULAR]);
        }
        return $this;
    }

    public function rewind()
    {
        reset($this->errors);
    }

    public function current()
    {
        return current($this->errors);
    }

    public function key()
    {
        return key($this->errors);
    }

    public function next()
    {
        next($this->errors);
    }

    public function valid(): bool
    {
        return false !== $this->current();
    }

    public function count(): int
    {
        return count($this->errors);
    }

    public function __toString()
    {
        try {
            $out = '';
            foreach ($this->errors as $titular => $mensajesError) {
                $out .= $titular != Constante::ERROR_DEFAULT_TITULAR ? '<' . $titular . '> ' : '';
                foreach ($mensajesError as $origen => $detalles) {
                    $out .= $origen != Constante::ERROR_DEFAULT_ORIGEN ? '(' . $origen . ') ' : '';
                    foreach ($detalles as $detalle) {
                        $out .= $detalle->mensaje . '; ';
                    }
                }
                $out .= ' ';
            }
        } catch (Throwable $ex) {
            $out = 'Error: ' . get_class() . ' no pudo ser convertido a string';
        }
        return trim($out);
    }
}
