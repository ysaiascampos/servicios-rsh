<?php

namespace App\Clases;

use App\Exceptions\ExcepcionConsistencia;
use App\Models\Personas;
use App\Models\Solicitud;
use Exception;
use App\Constantes\Constante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\PersonasTokenMail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ValidadorMail
{

    public static function validaToken($input){

        //Obtiene el token almacenado en la base de datos y lo valida con el ingresado por el ciudadano

        $response = [];

        $email  = strtolower($input->email_ciudadano);
        $run    = $input->run_ciudadano;
        $token  = $input->token_ciudadano;


        if (!empty($run)&&!empty($email)&&!empty($token)) {

            $solicitud = PersonasTokenMail::where([
                ['PER_RUN', $run]
            ])->latest()->first();
            if (empty($solicitud)) {
                throw new ExcepcionConsistencia('No se encuentra la solicitud de actualización de correo',
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
        }

        //Valida la cantidad de intentos de validacion (Hasta 3). O si no, vuelve a generar un nuevo código.
        if ($solicitud['CANT_INTENTO'] < Constante::CANT_INTENTOS_INVALIDOS_VALIDA_EMAIL) {

            //Compara el token con el registro almacenado
            if (ValidadorMail::desencriptaToken($solicitud['TOKEN']) == $token) {

                //pass - Añade la marca de validación a la bd
                $resultado = ValidadorMail::guardarValidacion($solicitud['ID']);

                $response = [
                    'mensaje' => Constante::MSJ_SUCESS_VALIDA_EMAIL,
                    'statusCode' => Constante::TOKEN_VALIDO_VALIDA_EMAIL,
                    'response' => $resultado
                ];

            }else{

                //No coincide. Aumenta el contador de intentos y envia msj de error.
                $resultado = ValidadorMail::aumentaIntentosFallidos($solicitud['ID'], $solicitud['CANT_INTENTO']);

                $response = [
                    'mensaje' => Constante::MSJ_ERROR_VALIDA_EMAIL.$resultado['CANT_INTENTO'],
                    'statusCode' => Constante::TOKEN_INVALIDO_VALIDA_EMAIL
                ];

            }

        }else{

            //Genera un nuevo token por maximo de intentos fallidos.
            $token = ValidadorMail::generarToken();
            $resultado = ValidadorMail::reemplazarToken($solicitud['ID'], $token);

            $response = [
                'mensaje' => Constante::MSJ_LIMITE_VALIDA_EMAIL.$resultado['PER_MAIL_TEMP'],
                'email-nuevo' => $resultado['PER_MAIL_TEMP'],
                'token' => $token,
                'statusCode' => Constante::EXCEDE_LIMITE_VALIDA_EMAIL
            ];

        }

        return $response;

    }

    public static function aumentaIntentosFallidos($id, $cant_intentos){

        //Aumenta el contador de intentos fallidos
        $resultado = PersonasTokenMail::where('ID',$id)->first();

        if ($cant_intentos >= 3) {
            $resultado->CANT_INTENTO = 0;
        }else{
            $resultado->CANT_INTENTO = $resultado->CANT_INTENTO + 1;
        }

        try {
            $resultado->save();
       } catch (\Throwable $th) {
            throw (new ExcepcionConsistencia("REGISTRO NO EN BD",
            Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));
       }

        return $resultado;

    }

    public static function generarToken(){

        //Genera un nuevo token aleatorio

        $caracteres = Constante::CARACTERES_GENERADOR_TOKEN_VALIDA_EMAIL;

        // generate a pin based on 2 * 7 digits + a random character
        $pin = mt_rand(10000000, 99999999);
        mt_rand(10000000, 99999999);
        $caracteres[rand(0, strlen($caracteres) - 1)];

        // shuffle el resultado
        $token = str_shuffle($pin);

        return $token;

    }

    public static function guardarToken($runCiudadano,$emailCiudadano,$nuevoEmailCiudadano,$token){

        //Guarda el token en la base de datos

        //Encripta el token generado
        $eToken = Crypt::encryptString($token);

        //Almacena el token en la bd
        try {

            //Busca a la persona
            $registro =     PersonasTokenMail::where([['PER_RUN', $runCiudadano]])->latest()->first();

            if (!(is_null($registro))) {

                $registro->PER_MAIL_ANTERIOR = $emailCiudadano;
                $registro->PER_MAIL_TEMP     = $nuevoEmailCiudadano;
                $registro->PER_MAIL_NUEVO    = null;
                $registro->TOKEN             = $eToken;
                $registro->ESTADO_TOKEN      = 1;
                $registro->CANT_INTENTO      = 0;
                $registro->FECHA_SOLICITUD   = \Carbon\Carbon::now();
                $registro->FECHA_ENVIO_MAIL  = null;
                $registro->FECHA_VALIDACION  = null;

                $registro->save();

            } else {

                $registro = PersonasTokenMail::create([

                    'PER_RUN'           => $runCiudadano,
                    'PER_MAIL_ANTERIOR' => $emailCiudadano,
                    'PER_MAIL_TEMP'     => $nuevoEmailCiudadano,
                    'TOKEN'             => $eToken,
                    'ESTADO_TOKEN'      => 1,
                    'CANT_INTENTO'      => 0,

                ]);

                $registro->save();
            }

        } catch (\Throwable $th) {
                throw (new ExcepcionConsistencia("REGISTRO NO EN BD",
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));
        }

        return $registro;
    }

    //NUEVO
    public static function actualizarCorreoSolicitud($runCiudadano,$nuevoEmailCiudadano){

        //Guarda el email ingresado en la solicitud actual

        try {

            Solicitud::where('run_ciudadano', $runCiudadano)
                ->whereIn('id_estado', [
                    Constante::ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS,
                    Constante::ESTADO_SOLICITUD_11_EN_DIGITACION
                ])
                ->update(array('email_ciudadano' => $nuevoEmailCiudadano));

            return Constante::STATUS_200_SUCCESS_OK;

        } catch (\Throwable $th) {
            throw (new ExcepcionConsistencia("REGISTRO NO EN BD",
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));
        }

    }

    public static function actualizarTelefonoContacto($runCiudadano,$nuevoTelefonoCiudadano){

        //Guarda el telefono de contacto
        $test = null;

        try {

            Solicitud::where('run_ciudadano', $runCiudadano)
                ->whereIn('id_estado', [
                    Constante::ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS,
                    Constante::ESTADO_SOLICITUD_11_EN_DIGITACION
                ])
                ->update(array('fono_ciudadano' => $nuevoTelefonoCiudadano, 'fono_ciudadano2' => $nuevoTelefonoCiudadano));

            $persona = Personas::where('PER_RUN', $runCiudadano)
                ->update(array('PER_TELEFONO' => $nuevoTelefonoCiudadano));


            return Constante::STATUS_200_SUCCESS_OK;

        } catch (\Throwable $th) {
            throw (new ExcepcionConsistencia($test,
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));
        }

    }

    //NUEVO

    public static function regeneraToken($runCiudadano,$emailCiudadano,$token){

        //regenera el token para luego guardarlo en el registro del ciudadano

        $run   = $runCiudadano;
        $email = strtolower($emailCiudadano);
        $token = $token;

        try {

            if (!empty($run)&&!empty($email)&&!empty($token)) {

                $solicitud = PersonasTokenMail::where([
                    ['PER_RUN', $run]
                ])->latest()->first();

                if (empty($solicitud)) {
                    throw new ExcepcionConsistencia('No se encuentra la solicitud de actualización de correo',
                        Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
                }

            }

            $resultado = ValidadorMail::reemplazarToken($solicitud['ID'], $token);

            $response = [
                'statusCode' => Constante::STATUS_200_SUCCESS_OK,
                'token' => $token,
                'email-nuevo' => $solicitud['PER_MAIL_TEMP'],
                'mensaje' => 'Token regenerado exitosamente para el run '. $runCiudadano,
            ];

            return $response;

        } catch (\Throwable $th) {
            throw (new ExcepcionConsistencia("REGISTRO NO EN BD",
            Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));

            return Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE;
        }

    }

    public static function guardarValidacion($idRegistro){

        //Marca la validación en la base de datos
        try {

            $registro = PersonasTokenMail::where('ID', $idRegistro )->first();

            $registro->PER_MAIL_NUEVO     = $registro->PER_MAIL_TEMP;
            $registro->CANT_INTENTO       = 0;
            $registro->ESTADO_TOKEN       = 3;
            $registro->FECHA_VALIDACION   = \Carbon\Carbon::now();

            $registro->save();

            return $registro;

        } catch (\Throwable $th) {
            throw (new ExcepcionConsistencia("REGISTRO NO EN BD",
            Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));

            return Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE;
        }
    }

    public static function reemplazarToken($idRegistro, $token){

        //Reemplaza un token generado anteriormente por uno nuevo
        $etoken = ValidadorMail::encriptaToken($token);

        try {

            $registro = PersonasTokenMail::where('ID', $idRegistro )->first();

            $registro->TOKEN            = $etoken;
            $registro->PER_MAIL_NUEVO   = null;
            $registro->ESTADO_TOKEN     = 1;
            $registro->CANT_INTENTO     = 0;
            $registro->FECHA_ENVIO_MAIL = null;

            $registro->save();

            return $registro;

        } catch (\Throwable $th) {
            throw (new ExcepcionConsistencia("REGISTRO NO EN BD",
            Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE));

            return Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE;
        }

    }

    public static function encriptaToken($token){

        $token = Crypt::encryptString($token);
        return $token;

    }

    public static function desencriptaToken($token){

        $token = Crypt::decryptString($token);
        return $token;

    }


}
