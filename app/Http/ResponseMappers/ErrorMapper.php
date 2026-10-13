<?php

namespace App\Http\ResponseMappers;

use App\Constantes\Constante;
use App\Exceptions\DetalleError;
use App\Exceptions\ExcepcionServicio;

class ErrorMapper extends ResponseMapper
{
    private $codigoCategoria;

    public function __construct(ExcepcionServicio $excepcion)
    {
        if (empty($statusCode = $excepcion->getStatusCode())) {
            $statusCode = Constante::$ERROR_STATUS_CODE[$excepcion->getCategoria()];
        }
        $this->statusCode = $statusCode;
        $this->codigoCategoria = $excepcion->getCategoria();

        $this->campos = [
            'errors' => self::mapError($excepcion)
        ];
    }

    private function mapError(ExcepcionServicio $excepcion)
    {
        $map = [];
        $errorWrapper = $excepcion->getErrorWrapper();

        $cambiarMensaje = self::analizarSiCambiarMensaje($excepcion);

        foreach ($errorWrapper as $titular => $mensajesError) {
            if (!array_key_exists($titular, $map)) {
                $map[$titular] = [];
            }
            foreach ($mensajesError as $origen => $listadoErrores) {
                if (!array_key_exists($origen, $map)) {
                    $map[$titular][$origen] = [];
                }
                foreach ($listadoErrores as $detalle) {
                    $map[$titular][$origen][] = self::mapDetalle($detalle, $cambiarMensaje);
                }
            }
        }
        return $map;
    }

    private function mapDetalle(DetalleError $error, $cambiarMensaje = true)
    {
        return [
            'codigo' => $this->formatearCodigo($error->codigo),
            'mensaje' => $cambiarMensaje ? Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST : $error->mensaje
        ];
    }

    private function formatearCodigo(int $codigo)
    {
        return sprintf('%02d', $this->codigoCategoria) . sprintf('%05d', $codigo);
    }

    private function analizarSiCambiarMensaje(ExcepcionServicio $excepcion)
    {

        $cambiarMensaje = $excepcion->getCategoria() === Constante::ERROR_CATEGORIA_EJECUCION &&
            $excepcion->getCode() !== Constante::ERR_EJECUCION_RUTA_NO_ENCONTRADA;

        return $cambiarMensaje;
    }
}
