<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AprobacionAutomaticaController;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('aprobacion-automatica', function () {
    $this->info("Inicio aprobacion automatica de solicitudes!");
    (new AprobacionAutomaticaController())->procesarSolicitudAprobacionAutomatica();
    $this->info("Fin aprobacion automatica de solicitudes!");
})->describe('aprobacion automatica de solicitudes');
