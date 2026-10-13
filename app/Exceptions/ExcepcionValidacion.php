<?php

namespace App\Exceptions;

use App\Constantes\Constante;
use Illuminate\Support\MessageBag;
use Throwable;

/**
 * Corresponde a validaciones fallidas.
 * @package App\Exceptions
 */
class ExcepcionValidacion extends ExcepcionServicio
{
    public $categoria = 1;
    public $nivelLog = Constante::LOG_LEVEL_INFO;

    /**
     * Corresponde a validaciones fallidas.
     * @param string|ErrorWrapper|MessageBag $mensaje Mensaje de la excepción a lanzar.
     * @param int $codigo Codigo específico de la excepción.
     * @param Throwable|null $previa La excepción previa utilizada por la serie de excepciones.
     * @return ExcepcionValidacion
     */
    public function __construct(
        $mensaje,
        $codigo = Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO,
        Throwable $previa = null
    ) {
        if ($mensaje instanceof ErrorWrapper) {
            $this->errorWrapper = $mensaje;
        }
        parent::__construct($mensaje, $codigo, $previa);
    }

    /**
     * Crea una instancia de ExcepcionValidacion.
     * @param string|array|MessageBag $mensaje Mensaje de la excepción a lanzar.
     * @param int $codigo Codigo específico de la excepción.
     * @param Throwable|null $previa La excepción previa utilizada por la serie de excepciones.
     * @return ExcepcionValidacion
     */
    public static function new(
        $mensaje,
        $codigo = Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO,
        Throwable $previa = null
    ): ExcepcionValidacion {
        $ex = new self($mensaje, $codigo, $previa);
        $ex->establecerArchivoOrigenDesdeTraza();
        return $ex;
    }
}
