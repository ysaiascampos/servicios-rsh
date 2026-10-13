<?php

namespace App\Http\Controllers;

use App\Exceptions\ExcepcionEjecucion;
use App\Exceptions\ExcepcionServicio;
use App\Exceptions\Logger;
use App\Http\ResponseMappers\ErrorMapper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Throwable;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function handleException(Throwable $exception)
    {
        Logger::log($exception);

        if (!($exception instanceof ExcepcionServicio)) {
            $exception = new ExcepcionEjecucion($exception->getMessage(), $exception->getCode(), $exception);
        }
        return new ErrorMapper($exception);
    }
}
