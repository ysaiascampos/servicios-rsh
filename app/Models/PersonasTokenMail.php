<?php

namespace App\Models;

use DB;
use Eloquent;
use Exception;
use Illuminate\Support\Facades\Log;
use app\Constantes\Constante;

use Illuminate\Database\Eloquent\Model;


class PersonasTokenMail extends Eloquent
{
    protected $table = 'AB_PERSONAS_TOKEN_MAIL';
    protected $primaryKey = 'ID';

    const CREATED_AT = 'FECHA_SOLICITUD';
    const UPDATED_AT = 'FECHA_ACTUALIZACION';


    protected $fillable = [
        'ID',
        'PER_RUN',
        'PER_MAIL_ANTERIOR',
        'PER_MAIL_TEMP',
        'PER_MAIL_NUEVO',
        'TOKEN',
        'ESTADO_TOKEN',
        'CANT_INTENTO',
        'FECHA_ENVIO_MAIL',
        'FECHA_VALIDACION'
    ];


    public static function findByRunCiudadano($runCiudadano)
    {
        $datosDePersona = null;
        $resultado = [
            'email-temporal' => null,
            'estado-validacion'=> null,
            'mensaje-ciudadano' => Constante::MSJ_BLOQUEO_VALIDA_EMAIL,
        ];

        try {
            $datosDePersona = self::getModel()
                ->select("PER_MAIL_TEMP","ESTADO_TOKEN")
                ->where('PER_RUN', $runCiudadano)
                ->first();

            if (!is_null($datosDePersona)){

                if ($datosDePersona->ESTADO_TOKEN != Constante::ESTADO_VALIDADO_VALIDA_EMAIL){

                    $resultado = [
                        'email-temporal'=>$datosDePersona->PER_MAIL_TEMP,
                        'estado-validacion'=>$datosDePersona->ESTADO_TOKEN,
                        'mensaje-ciudadano' => Constante::MSJ_BLOQUEO_VALIDA_EMAIL,
                    ];

                }else{

                    $resultado = [
                        'email-temporal'=>$datosDePersona->PER_MAIL_TEMP,
                        'estado-validacion'=>$datosDePersona->ESTADO_TOKEN,
                        'mensaje-ciudadano' => '',
                    ];

                }

            }

        } catch (Exception $ex) {
            throw $ex;
        }

        return $resultado;
    }

}
