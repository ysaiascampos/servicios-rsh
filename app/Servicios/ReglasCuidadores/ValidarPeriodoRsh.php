<?php


namespace App\Servicios\ReglasCuidadores;

use Closure;
use DB;
use Exception;

class ValidarPeriodoRsh
{
    public function handle($request, Closure $next)
    {
        $rsh = DB::table('CUIDADOR_DEPENDIENTE')
            ->where('ESTADO', 1)
            ->max('PERIODO_RSH');

        if (empty($rsh)) {
            throw new Exception("No existe periodo en el RSH");
        }

        $request['periodo_rsh'] = $rsh;


        return $next($request);
    }
}