<?php

namespace App\Exceptions;

use App\Constantes\Constante;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * Corresponde a errores en general.
 * @package App\Exceptions
 */
class ExcepcionEjecucion extends ExcepcionServicio
{
    public $categoria = 4;

    /**
     * Corresponde a errores en general
     * @param null $mensaje Mensaje de la excepción a lanzar.
     * @param int $codigo Codigo específico de la excepción.
     * @param Throwable|null $previa La excepción previa utilizada por la serie de excepciones.
     * @return ExcepcionEjecucion
     */
    public function __construct($mensaje = null, $codigo = 0, Throwable $previa = null)
    {
        $codigo = $this->comprobarCodigoEspecifico($codigo, $previa);
        $_mensaje = $this->analizarMensaje($mensaje, $previa);
        parent::__construct($_mensaje, $codigo, $previa);
        if ($previa) {
            if ($previa instanceof HttpException) {
                $this->statusCode = $previa->getStatusCode();
            }
            $this->file = $previa->getFile();
            $this->line = $previa->getLine();
        }
    }

    /**
     * Crea una instancia de ExcepcionEjecucion.
     * @param null $mensaje Mensaje de la excepción a lanzar.
     * @param int $codigo Codigo específico de la excepción.
     * @param Throwable|null $previa La excepción previa utilizada por la serie de excepciones.
     * @return ExcepcionEjecucion
     */
    public static function new($mensaje = null, $codigo = 0, Throwable $previa = null): ExcepcionEjecucion
    {
        $ex = new self($mensaje, $codigo, $previa);
        $ex->establecerArchivoOrigenDesdeTraza();
        return $ex;
    }

    private function comprobarCodigoEspecifico($code, Throwable $exception = null)
    {
        //ToDo: Determinar tipos de excepciones que deben tener una subcategoria asociada

        if ($exception instanceof GuzzleException) {
            $code = Constante::ERR_EJECUCION_COMUNICACION_SERVICIO_EXTERNO;
        }

        if ($exception instanceof QueryException) {
            $code = Constante::ERR_EJECUCION_ERROR_CONSULTA_SQL;
        }

        if ($exception instanceof NotFoundHttpException) {
            $code = Constante::ERR_EJECUCION_RUTA_NO_ENCONTRADA;
        }

        if (empty($code)) {
            $code = Constante::ERR_EJECUCION_NO_CONTROLADO;
        }
        return $code;
    }

    private function analizarMensaje($mensaje, Throwable $exception = null)
    {
        if ($exception instanceof NotFoundHttpException) {
            $mensaje = 'Ruta no encontrada';
        }

        if (empty($mensaje) && !is_null($exception)) {
            $mensaje = $exception->getMessage();
        }
        return $mensaje;
    }
}
