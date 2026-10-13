<?php


namespace App\Http\Controllers;

use App\Clases\ValidadorMail;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Http\ResponseMappers\ErrorMapper;
use Exception;
use Illuminate\Support\Facades\Validator;

use App\Http\ResponseMappers\ValidaMailMapper;
use Illuminate\Http\Request;
use App\Http\Inputs\TokenValidaMailCiudadanoInput;
use App\Http\ResponseMappers\JsonMapper;

class ValidaMailController extends Controller
{

    public function validaToken(Request $request)
    {
        try {

            $input = new TokenValidaMailCiudadanoInput(request()->all());
            $response = ValidadorMail::validaToken($input);

            return $response;

        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    //Nuevas funciones para generar el token de validacion
    public function solicitaToken(Request $request)
    {

        try {

            $runCiudadano   = $request['run-ciudadano'];

            $emailCiudadano = strtolower($request['email-ciudadano']);
            $nuevoEmailCiudadano = strtolower($request['email-nuevo']);

            $this->validarInput($runCiudadano);

            $token = ValidadorMail::generarToken();
            $guardado = ValidadorMail::guardarToken($runCiudadano,$emailCiudadano,$nuevoEmailCiudadano,$token);

            $response = [
                'statusCode' => Constante::STATUS_200_SUCCESS_OK,
                'token' => $token,
                'mensaje' => 'Token generado exitosamente para el run '. $runCiudadano,
            ];

            return $response;

        } catch (Exception $ex) {
            return $this->handleException($ex);
        }

    }

    //Actualizacion de contacto al finalizar tramite
    public function confirmaContacto(Request $request)
    {

        try {

            $runCiudadano   = $request['run-ciudadano'];
            $tipoSolicitud = strtolower($request['tipo-solicitud']);

            $nuevoTelefonoCiudadano   = $request['telefono-nuevo'];
            $nuevoEmailCiudadano = strtolower($request['email-nuevo']);

            switch ($tipoSolicitud) {
                case Constante::TIPO_SOLICITUD_203_ACTUALIZACION_DATOS_TELEFONO:
                    $telefonoResponse = ValidadorMail::actualizarTelefonoContacto($runCiudadano, $nuevoTelefonoCiudadano);
                    $sMailResponse = ValidadorMail::actualizarCorreoSolicitud($runCiudadano, $nuevoEmailCiudadano);
                    break;
                case Constante::TIPO_SOLICITUD_204_ACTUALIZACION_DATOS_MAIL:
                    $telefonoResponse = ValidadorMail::actualizarTelefonoContacto($runCiudadano, $nuevoTelefonoCiudadano);
                    $sMailResponse = ValidadorMail::actualizarCorreoSolicitud($runCiudadano, $nuevoEmailCiudadano);
                    break;
            }

            $response = $this->solicitaToken($request);
            return $response;

        } catch (Exception $ex) {
            return $this->handleException($ex);
        }

    }

    public function reenviaToken(Request $request)
    {

        try {

            $runCiudadano = $request['run-ciudadano'];
            $emailCiudadano = $request['email-ciudadano'];

            $this->validarInput($runCiudadano);

            $token = ValidadorMail::generarToken();
            $response = ValidadorMail::regeneraToken($runCiudadano,$emailCiudadano,$token);

            return $response;

        } catch (Exception $ex) {
            return $this->handleException($ex);
        }

    }

    public function validarInput($runCiudadano)
    {
        $runCiudadano = str_replace(['.', '-', ' '], '', $runCiudadano);
        $runCiudadano = preg_replace("/[^0-9?!]/", "", $runCiudadano);
        $entradas = ['run' => $runCiudadano];
        $reglas = [
            'run' => 'required|numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
        ];
        $validador = Validator::make($entradas, $reglas);
        if ($validador->fails()) {
            throw ExcepcionValidacion::new($validador->errors(), Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO)
                ->setTitular($runCiudadano);
        }
    }

}
