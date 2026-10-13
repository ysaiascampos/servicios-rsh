<?php

namespace App\Exceptions;

use App\Constantes\Constante;
use Exception;
use Illuminate\Support\MessageBag;
use Throwable;

/**
 * Corresponde a reglas de negocio que impiden continuar con la ejecución.
 * @package App\Exceptions
 */
class ExcepcionReglaNegocio extends ExcepcionServicio
{
    public $categoria = 3;
    public $nivelLog = Constante::LOG_LEVEL_NOTICE;

    /**
     * Corresponde al no cumplimiento de validaciones de reglas de negocio.
     * @param string|ErrorWrapper|MessageBag $mensaje Mensaje de la excepción a lanzar.
     * @param int $codigo Codigo específico de la excepción.
     * @param Throwable|null $previa La excepción previa utilizada por la serie de excepciones.
     * @return ExcepcionReglaNegocio
     */
    public function __construct($mensaje, $codigo = null, Throwable $previa = null)
    {
        if ($mensaje instanceof ErrorWrapper) {
            $this->errorWrapper = $mensaje;
        } else {
            if (is_null($codigo)) {
                throw new Exception("El parametro 'codigo' no puede ser null si el parámetro 'mensaje' no es de tipo 'ErrorWrapper'");
            }
        }

        parent::__construct($mensaje, $codigo, $previa);
    }

    /**
     * Crea una instancia de ExcepcionReglaNegocio.
     * @param string|ErrorWrapper|MessageBag $mensaje Mensaje de la excepción a lanzar.
     * @param int $codigo Codigo específico de la excepción.
     * @param Throwable|null $previa La excepción previa utilizada por la serie de excepciones.
     * @return ExcepcionReglaNegocio
     */
    public static function new($mensaje = null, $codigo = 0, Throwable $previa = null): ExcepcionReglaNegocio
    {
        $ex = new self($mensaje, $codigo, $previa);
        $ex->establecerArchivoOrigenDesdeTraza();
        return $ex;
    }
}
