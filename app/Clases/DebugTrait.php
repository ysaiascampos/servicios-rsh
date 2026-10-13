<?php

namespace App\Clases;

use Illuminate\Support\Facades\Log;

trait DebugTrait
{
    protected $logDebug = false;
    protected $logChannel = 'debug';

    abstract protected function configurarDebug();

    protected function debug($mensaje, $nivel = 'debug')
    {
        if ($this->logDebug) {
            Log::channel($this->logChannel)->$nivel($mensaje);
        }
    }
}
