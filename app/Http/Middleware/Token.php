<?php

namespace App\Http\Middleware;

use App\Exceptions\ExcepcionValidacion;
use Closure;

class Token
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = new \App\Clases\Token();
        if (!$token->validar($request)) {
            throw new ExcepcionValidacion($token->errores);
        }

        return $next($request);
    }
}
