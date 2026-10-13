<?php

namespace App\Exceptions;

use App\Constantes\Constante;
use Throwable;

/**
 * Corresponde a inconsistencias en la data que impiden continuar con la ejecución.
 * @package App\Exceptions
 */
class ExcepcionConsistencia extends ExcepcionServicio
{
    public $categoria = Constante::ERROR_CATEGORIA_CONSISTENCIA;
    public $nivelLog = Constante::LOG_LEVEL_NOTICE;

    /**
     * Corresponde a inconsistencias en la data que impiden continuar con la ejecución.
     * @param null $mensaje Mensaje de la excepción a lanzar.
     * @param int $codigo Codigo específico de la excepción.
     * @param Throwable|null $previa La excepción previa utilizada por la serie de excepciones.
     */
    public function __construct($mensaje = null, $codigo = 0, Throwable $previa = null)
    {
        parent::__construct($mensaje, $codigo, $previa);
    }

    /**
     * Crea una instancia de ExcepcionConsistencia.
     * @param null $mensaje Mensaje de la excepción a lanzar.
     * @param int $codigo Codigo específico de la excepción.
     * @param Throwable|null $previa La excepción previa utilizada por la serie de excepciones.
     * @return ExcepcionConsistencia
     */
    public static function new($mensaje = null, $codigo = 0, Throwable $previa = null): ExcepcionConsistencia
    {
        $ex = new self($mensaje, $codigo, $previa);
        $ex->establecerArchivoOrigenDesdeTraza();
        return $ex;
    }
}
