<?php

namespace App\Clases;

use App\Constantes\Constante;
use Illuminate\Support\Facades\Validator;

class Token
{
    public $contenido;
    public $errores;

    public function __construct()
    {
        $palabraClave = Constante::PASSWORD_TOKEN;
        $palabraClave = md5(sha1($palabraClave));
        /*
        if (!Cache::has('tokenServiceRest')) {
            $clave = Constante::CADENA_PASSWORD_TOKEN . date('YmdHis');
            $expireAt = \Carbon\Carbon::now()->addDays(1);

            Cache::put('tokenServiceRest', sha1($clave), $expireAt);
        }
        */
        $this->contenido = $palabraClave;
    }

    public function validar($request)
    {
        $input = [
            'token' => $request->header('token'),
            'clave' => $this->contenido
        ];
        $reglas = [
            'token' => 'required|same:clave'
        ];
        $mensajes = [
            'token.required' => 'El token es requerido para consumir servicio',
            'token.same' => 'El token de autenticación no corresponde'
        ];

        $validator = Validator::make($input, $reglas, $mensajes);
        if ($validator->fails()) {
            $this->errores = $validator->errors();
            return false;
        }
        return true;
    }
}
