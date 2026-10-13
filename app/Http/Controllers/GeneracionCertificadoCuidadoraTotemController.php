<?php


namespace App\Http\Controllers;


use App\Clases\DatosDeCertificadoCuidadoraTotem;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Models\LogConsultasQRTotem;
use App\Models\QrCuidadoras;
use App\Models\QrScanCodigosInstituciones;
use App\Models\QRScanInstituciones;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeneracionCertificadoCuidadoraTotemController extends Controller
{

    public function show($portal,$runCiudadano)
    {

        $datosDeCertificado = new DatosDeCertificadoCuidadoraTotem();
        $error = false;
        try {
            $this->validarInput($runCiudadano);
            $restGetRealIP = request()->header('ip');
            $usuario = request()->header('run-usuario');
            $comunaUsuario = request()->header('comuna-usuario');
            $result = $datosDeCertificado->crearCertificado($portal, $runCiudadano, $restGetRealIP, $usuario, $comunaUsuario);
        } catch (ExcepcionValidacion $ex) {
            $error = true;
        } catch (\ErrorException $ex) {
            $error = true;
        } catch (\Throwable $ex) {
            $error = true;
        } catch (\Exception $ex) {
            $error = true;
        }
        if($error){
            Log::error("Ha ocurrido un problema al obtener el certificado del Ciudadano RUN ". $runCiudadano.
                " Detalles del error  -> ". $ex);
            $result = $datosDeCertificado->errorCertificado();
        }
        return $result;

    }

    public function showBase64($portal,$runCiudadano)
    {

        $datosDeCertificado = new DatosDeCertificadoCuidadoraTotem();
        $error = false;
        try {
            $this->validarInput($runCiudadano);
            $restGetRealIP = request()->header('ip');
            $usuario = request()->header('run-usuario');
            $comunaUsuario = request()->header('comuna-usuario');
            $result = $datosDeCertificado->crearCertificadoBase64($portal, $runCiudadano, $restGetRealIP, $usuario, $comunaUsuario);
        } catch (ExcepcionValidacion $ex) {
            $error = true;
        } catch (\ErrorException $ex) {
            $error = true;
        } catch (\Throwable $ex) {
            $error = true;
        } catch (\Exception $ex) {
            $error = true;
        }
        if($error){
            Log::error("Ha ocurrido un problema al obtener el certificado del Ciudadano RUN ". $runCiudadano.
                " Detalles del error  -> ". $ex);
            $result = $datosDeCertificado->errorCertificadoBase64($ex->getMessage());
        }
        return $result;

    }

    public function statuscuidadora($codigoinstitucion,$runCiudadano)
    {
        try {
            $this->validarInput($runCiudadano);

            //validar codigo de la institucion
            $getcodigoinstitucion = QrScanCodigosInstituciones::where('codigo',$codigoinstitucion)->first();

            if(!$getcodigoinstitucion){
                throw ExcepcionValidacion::new("Error Código no registrado")->setTitular($runCiudadano);
            }

            $esCuidador = QrCuidadoras::validaEsCuidador($runCiudadano);

            if(!$esCuidador){
                throw ExcepcionValidacion::new("Cuidadano no presenta registro de cuidador")->setTitular($runCiudadano);
            }

            //Guardar LOG de consultas
            $idinstitucion = $getcodigoinstitucion->ID_INSTITUCION;
            $getinstitucion = QRScanInstituciones::where('id',$idinstitucion)->first();

            if($getinstitucion){
                $log = new LogConsultasQRTotem();
                $log->RUN_CONSULTADO = $runCiudadano;
                $log->ID_INSTITUCION = $idinstitucion;
                $log->FECHA_HORA = date('Y-m-d H:i:s');
                $log->IP = \Request::ip();
                $log->save();

                return true;
            }else{
                throw ExcepcionValidacion::new("Error Institución no registrado")->setTitular($runCiudadano);
            }

            return true;

        } catch (ExcepcionValidacion $ex) {
            return false;
        } catch (\ErrorException $ex) {
            return false;
        } catch (\Throwable $ex) {
            return false;
        } catch (\Exception $ex) {
            return false;
        }

        return false;

    }

    public function validarInput($runCiudadano)
    {
        $runCiudadano = str_replace(['.', '-', ' '], '', $runCiudadano);
        $runCiudadano = preg_replace("/[^0-9?!]/", "", $runCiudadano);
        $entradas = ['run-ciudadano' => $runCiudadano];
        $reglas = [
            'run-ciudadano' => 'required|numeric|between:' .
                implode(',', Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
        ];
        $validador = Validator::make($entradas, $reglas);
        if ($validador->fails()) {
            throw ExcepcionValidacion::new($validador->errors(), Constante::ERR_VALIDACION_VALOR_NO_PERMITIDO)
                ->setTitular($runCiudadano);
        }
    }

}
