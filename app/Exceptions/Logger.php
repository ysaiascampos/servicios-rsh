<?php

namespace App\Exceptions;

use App\Constantes\Constante;
use Illuminate\Support\Facades\Log;
use Throwable;

class Logger
{
    public static function log(Throwable $exception)
    {
        $nivelLog = Constante::LOG_LEVEL_ERROR;
        $contexto = [];
        $prev = null;

        if ($exception instanceof ExcepcionServicio) {
            $nivelLog = $exception->getNivelLog();
            $contexto = $exception->getContexto();
        }

        if ($prev = $exception->getPrevious()) {
            if ($prev->getCode() != $exception->getCode()) {
                self::log($prev);
            }
        }

        if ($exception instanceof ExcepcionValidacion) {
            $errorText = "Validación: " . $exception->getMessage();
        } else {

            $errorText = self::formatErrorLine($exception);

            if (!$prev) {
                $trace = $exception->getTraceAsString();
                $errorText .= PHP_EOL . 'Pila de llamadas:' . PHP_EOL . $trace;
            } else {
                $errorText .= PHP_EOL . 'Origen: ' . self::formatErrorLine($prev);
            }
        }

        Log::$nivelLog($errorText, $contexto);
    }

    public static function formatErrorLine($exception)
    {
        $datosOSCliente = request()->header('client-info');
        if (isset($datosOSCliente)) {
            $datosOSCliente = PHP_EOL . "Datos enviados del Cliente: [" . $datosOSCliente . "]";
        }
        return "'" . get_class($exception) . "' con mensaje '" . $exception->getMessage() . "' en " . $exception->getFile() . ":" . $exception->getLine() . $datosOSCliente;
    }
}
