<?php

namespace App;

use App\Clases\ApiRunificador;
use App\Clases\DatosDeSolicitud;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\Calle;
use App\Models\LocacionComuna;
use App\Models\LocacionLocalidad;
use App\Models\AgrupacionHabitacional;
use App\Models\ComplementoIngresos;
use App\Models\GrupoFamiliar;
use App\Models\LogServicios;
use App\Models\Personas;
use App\Models\PersonasExport;
use App\Models\VWPropiedad;
use App\Models\Solicitud;
use App\Models\SubTipoSolicitud;
use App\Models\TipoSolicitud;
use App\Models\Usuario;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlFactory;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use SoapClient;
use stdClass;
use Validator;

use GuzzleHttp\Middleware;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Utiles
{
    const EXTERNO = 'EXTERNO';
    const INTERNO = 'INTERNO';

    public static function getFormateaFecha($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }

    public static function getFormateaFechaHora($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i');
    }

    public static function getEdad($fechaNacimiento)
    {
        if ($fechaNacimiento == 0) {
            $fechaNacimiento = Constante::DEFAULT_FECHA_NACIMIENTO;
        }
        $interval = date_diff(date_create(), date_create($fechaNacimiento));
        return (int)$interval->format('%y');
    }

    public static function getEdad_old($fecha)
    {
        $fecha = date('d-m-Y', strtotime($fecha));
        $dias = explode('-', $fecha, 3);
        $dias = mktime(0, 0, 0, $dias[1], $dias[0], $dias[2]);
        return (int)((time() - $dias) / 31556926);
    }

    /**
     * array con edad completa [ [0] => año, [1] => mes, [2] => dia ]
     */
    public static function getEdadCompleta($fecha)
    {
        $interval = date_diff(date_create(), date_create($fecha));
        // $edadYMD = $interval->format('%Y-%M-%d-%H-%i-%s'); // edad años-meses-dias-horas-minutos-segundos
        $edadYMD = $interval->format('%Y-%M-%d'); // edad años-meses-dias
        return explode('-', $edadYMD);
    }

    /**
     * Consume el webservice de RC para actualizar la tupla de la tabla personas de asignacion, correspondiente al run
     * si esta no existe el run en dicha tabla, consume el webservice de RC y la guarda en la tabla personas
     * en ambos casos devuelve la tupla de la tabla personas actualizada o nueva
     */
    public static function getPersona($runSinPuntosNiGuionNiDv)
    {
        if (!is_numeric($runSinPuntosNiGuionNiDv)) {
            return -1;
        }
        if (strpos(strtolower($runSinPuntosNiGuionNiDv), 'e') !== false) {
            return -1;
        }
        $persona = Personas::where('per_run', $runSinPuntosNiGuionNiDv)
            ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->first();
        $fechaNacim = null;
        if (!empty($persona)) {
            if ($persona->PER_FECHADEFUNCION == 0) {
                $fechaNacim = $persona->PER_FECHANACIMIENTO;
            }
            // Constante CONSUMIR_WS_RUNIFICADOR debe ser false para evitar el consumo del runificador cuando ya existe en tabla PERSONAS,
            // ya que este está presentando fallas intermitentes. Esta constante fue encargada por Dario y Hans el 19/11/2109
            $runificar = env('CONSUMIR_WS_RUNIFICADOR',Constante::ENV_DEFECTO_CONSUMIR_WS_RUNIFICADOR) ;
            if ($runificar) {
                $runificarRest = env('RUNIFICADOR_REST_CONSUMIR_WS',Constante::ENV_DEFECTO_CONSUMIR_REST_RUNIFICADOR) ;
                if ($runificarRest) {
                    $apiRunificador = new ApiRunificador;
                    $personaWs = $apiRunificador->getPersona($runSinPuntosNiGuionNiDv,$persona->PER_DV);
                } else {
                    $personaWs = Utiles::actualizaPersonaWS($runSinPuntosNiGuionNiDv);
                }

                if (is_numeric($personaWs)) {
                    Log::info("El runificador devuelve error pero persona Persona existe en BD y se permite continuar",['wsPersona' => $personaWs]);
                    return Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL;
                }
                if ($personaWs) {
                    if ($personaWs->Nombres == "") {
                        Log::error("La data vacia enviada del runificador",['wsPersona' => $personaWs]);
                        return Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL;
                    }
                    if ($personaWs->FechaNacimiento == 0) {
                        Log::error("La fecha de nacimiento no puede ser 0 ",['wsPersona' => $personaWs]);
                        return Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL;
                    }
                    $persona->PER_NOMBRES = $personaWs->Nombres;
                    $persona->PER_APELLIDOPATERNO = $personaWs->ApellidoPaterno;
                    $persona->PER_APELLIDOMATERNO = $personaWs->ApellidoMaterno;
                    // $persona->PER_EDAD = Utiles::getEdad($personaWs->FechaNacimiento); // per_edad no sirve aquí, más abajo lo reescribe??
                    $persona->PER_FECHANACIMIENTO = date('Ymd', strtotime($personaWs->FechaNacimiento));
                    $persona->PER_ESTADOCIVIL = $personaWs->EstadoCivil;
                    if ($personaWs->Sexo == 'MASCULINO') {
                        $persona->PER_SEXO = Constante::SEXO_1_MASCULINO;
                    } else {
                        $persona->PER_SEXO = Constante::SEXO_2_FEMENINO;
                    }
                    if($persona->PER_FECHADEFUNCION!=0 && $personaWs->FechaDefuncion==0){
                        Log::error("El runificador devuelve Fecha de defuncion en 0 cuando cuando la persona ya tenia una fecha : ".$persona->PER_FECHADEFUNCION,['wsPersona' => $personaWs]);
                        $persona->PER_FECHADEFUNCION = 0;
                    }
                    if ($personaWs->FechaDefuncion != 0) {
                        $persona->PER_FECHADEFUNCION = date('Ymd', strtotime($personaWs->FechaDefuncion));
                    } else {
                        if($fechaNacim!=$personaWs->FechaNacimiento){
                            Log::info("El runificador devuelve Fecha de nacimiento diferenta a la ingresada bd fecha nacimiento bd: ".$fechaNacim,['wsPersona' => $personaWs]);
                        }
                        $fechaNacim = $personaWs->FechaNacimiento;
                    }
                }
            }
            if (!(is_null($fechaNacim))) {
                $persona->PER_EDAD = Utiles::getEdad($fechaNacim);
            }
            $ver_temp_parentesco = env('TEMPORAL_PARENTESCO',false);
            if ($ver_temp_parentesco == true) {
                if (is_null($persona->PER_RUN_PADRE) || is_null($persona->PER_RUN_MADRE)) {
                    $padres = Utiles::consultarAscendencia($persona->PER_RUN, $persona->PER_DV);
                    $persona->PER_RUN_PADRE = !is_null($padres->padre->rut) ? $padres->padre->rut : $persona->PER_RUN_PADRE;
                    $persona->PER_RUN_MADRE = !is_null($padres->madre->rut) ? $padres->madre->rut : $persona->PER_RUN_MADRE;
                    $persona->PER_FECHA_ACTUALIZACION_PADRES = !is_null($padres->fechaConsulta) ? $padres->fechaConsulta : $persona->PER_FECHA_ACTUALIZACION_PADRES;
                }
            }
            $persona->save();
            return $persona;
        } else {
            try {
                $runificarRest = env('RUNIFICADOR_REST_CONSUMIR_WS',Constante::ENV_DEFECTO_CONSUMIR_WS_RUNIFICADOR) ;
                if ($runificarRest) {
                    $apiRunificador = new ApiRunificador;
                    $personaWs = $apiRunificador->getPersona($runSinPuntosNiGuionNiDv,Utiles::dv($runSinPuntosNiGuionNiDv));
                } else {
                    $personaWs = Utiles::actualizaPersonaWS($runSinPuntosNiGuionNiDv);
                }
                if (is_numeric($personaWs)) {
                    Log::error("La data vacia enviada del runificador",['wsPersona' => $personaWs]);
                    return $personaWs;
                }

                if ($personaWs->Nombres == "") {
                    Log::error("La data vacia enviada del runificador",['wsPersona' => $personaWs]);
                    return Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL;
                }
                if ($personaWs->FechaNacimiento == 0) {
                    Log::error("La fecha de nacimiento no puede ser 0 ",['wsPersona' => $personaWs]);
                    return Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL;
                }

                $personaNew = new Personas();
                $personaNew->PER_ID = Sequence::nextValue('seq_ab_personas');
                $personaNew->PER_RUN = $personaWs->RUN;
                $personaNew->PER_DV = $personaWs->DV;
                $personaNew->PER_NOMBRES = $personaWs->Nombres;
                $personaNew->PER_NACIONALIDAD = Constante::NACIONALIDAD_1_CHILENA; // lo agrega como chileno (el ws de rc no trae nacionalidad)
                $personaNew->PER_APELLIDOPATERNO = $personaWs->ApellidoPaterno;
                $personaNew->PER_APELLIDOMATERNO = $personaWs->ApellidoMaterno;
                $personaNew->PER_FECHANACIMIENTO = date('Ymd', strtotime($personaWs->FechaNacimiento));
                $personaNew->PER_ESTADOCIVIL = $personaWs->EstadoCivil;
                if ($personaWs->Sexo == 'MASCULINO') {
                    $personaNew->PER_SEXO = Constante::SEXO_1_MASCULINO;
                } else {
                    $personaNew->PER_SEXO = Constante::SEXO_2_FEMENINO;
                }
                if ($personaWs->FechaDefuncion == 0) {
                    $personaNew->PER_FECHADEFUNCION = null;
                    $personaNew->PER_EDAD = Utiles::getEdad($personaNew->PER_FECHANACIMIENTO);
                } else {
                    $personaNew->PER_FECHADEFUNCION = date('Ymd', strtotime($personaWs->FechaDefuncion));
                }
                $personaNew->PER_VIGENTE = Constante::VIGENTE_1_VIGENTE;
                $personaNew->PER_CALCULOACTIVO = Constante::CALCULO_ACTIVO_1_APROBADO;
                $padres = Utiles::consultarAscendencia($personaNew->PER_RUN, $personaNew->PER_DV);
                $personaNew->PER_RUN_PADRE = $padres->padre->rut;
                $personaNew->PER_RUN_MADRE = $padres->madre->rut;
                $personaNew->PER_FECHA_ACTUALIZACION_PADRES = $padres->fechaConsulta;

                $p2 = Personas::where('per_run', $runSinPuntosNiGuionNiDv)
                    ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
                    ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                    ->first();
                if ($p2) {
                    Log::notice('Duplicidad. El RUN existe en la BD: ' . $runSinPuntosNiGuionNiDv);
                    return $p2;
                } else {
                    $personaNew->save();
                    $personaNew = Personas::where('per_run', $personaWs->RUN)
                        ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
                        ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                        ->first();
                    return $personaNew;
                }
            } catch (Exception $e) {
                //$q = DB::getQueryLog();
                Log::error('PERSONA: ' . $e->getMessage());
                return Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR;
            }
        }
        return Constante::DATOS_DE_CONTACTO_2_ERROR_INESPERADO;
    }

    /**
     * Si va bien retorna Persona del WerService:
     * EJ: result = {stdClass} [12]
     *    RUN = '13358XXX'
     *    DV = '4'
     *    Nombres = 'XXX XXX'
     *    ApellidoPaterno = 'XXX'
     *    ApellidoMaterno = 'XXX'
     *    FechaNacimiento = '19780919'
     *    FechaDefuncion = '0'
     *    Sexo = 'MASCULINO'
     *    EstadoCivil = 'CASADO'
     *    Estado = 'OK'
     *    Glosa = 'Persona Encontrada en BD'
     *    DiasEnCache = 27
     */
    public static function actualizaPersonaWS($runSinPuntosNiGuionNiDv)
    {
        try {
            if (env('VERSION_1.2_SOAP',false)) {
                $configSOAP = Constante::$CONFIG_SOAP_1_2;
            } else {
                $configSOAP = Constante::$CONFIG_SOAP_DEFAULT;
            }

            if (env('ACTIVAR_TIMEOUT_SOAP',false)) {
                $timeout = array(
                    'connection_timeout' => 3,
                    'default_socket_timeout' => 10
                );
                $configSOAP = array_merge($configSOAP, $timeout);
            }

            if (env('ACTIVAR_CACHE_SOAP',false)) {
                $cache = array('cache_wsdl' => WSDL_CACHE_DISK);
            } else {
                $cache = array('cache_wsdl' => WSDL_CACHE_NONE);
                //$cache = array( 'cache_wsdl'=>WSDL_CACHE_MEMORY);
            }
            $configSOAP = array_merge($configSOAP, $cache);

            $ws = new SoapClient(env('wsrunificador'), $configSOAP);
            if ($ws) {
                $ws->__setLocation(env('wsrunificador'));
                $personaWs = $ws->obtenerPersona([
                    'rut' => $runSinPuntosNiGuionNiDv,
                    'dv' => Utiles::dv($runSinPuntosNiGuionNiDv)
                ]);
                if ($personaWs) {
                    $personaWs = $personaWs->obtenerPersonaResult;
                    if ($personaWs->Estado == 'OK' || $personaWs->Estado == 'OK-CACHE') {
                        return $personaWs;
                    } else {
                        if ($personaWs->Glosa == 'Persona no encontrada en BD') {
                            return Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL;
                        } elseif (strpos($personaWs->Glosa , 'No responde RC')){
                            Log::error('actualizaPersonaWS: ' . Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR . 'RUN : ' . $runSinPuntosNiGuionNiDv);
                            return Constante::DATOS_DE_CONTACTO_6_ERROR_REGISTRO_CIVIL;
                        } else {
                            Log::error('actualizaPersonaWS: ' . Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR . 'RUN : ' . $runSinPuntosNiGuionNiDv);
                            return Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR;
                        }
                    }
                } else {
                    return Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR;
                }
            }
        } catch (Exception $e) {
            Log::error('actualizaPersonaWS (UTILES.php 186): ' . $e->getMessage());
            return Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR;
        }
        return null;
    }

    public static function getNombreCalle($comuna, $localidad, $calle)
    {
        if ($comuna > 0 && $localidad > 0 && $calle > 0) {
            $n_calle = Calle::where(
                [
                    'comuna_ine' => $comuna,
                    'id_localidad' => $localidad,
                    'id_calle' => $calle
                ])
                ->first();
            return $n_calle->nombre;
        } else {
            return 'SIN CALLE';
        }
    }


    /**
     * Diferencia en días entre dos fechas formato DATE (2017-11-24 13:00:04)
     * si fecha2 no viene, se calcula con la fecha actual
     */
    public static function diferenciaEntreFechasEnDias($fecha1, $fecha2 = 'actual')
    {
        if ($fecha2 == 'actual') {
            $fecha2 = date('Y-m-j'); // ej: 2018-09-10
        }
        $fecha1EnSeg = strtotime($fecha1); // Segundos desde el año 1969
        $fecha2EnSeg = strtotime($fecha2); // Segundos desde el año 1969
        $difEntreFechasEnSeg = abs($fecha2EnSeg - $fecha1EnSeg);
        $difEntreFechasEnDias = ((($difEntreFechasEnSeg / 60) / 60) / 24); // convierte los segundos a días
        return floor($difEntreFechasEnDias); // quita la parte decimal
    }

    /**
     * Suma dias a una fecha
     * si fecha2 no viene, se calcula con la fecha actual
     */
    public static function sumarDiasAFecha($sumaDeDias, $fecha = 'actual')
    {
        if ($fecha == 'actual') {
            $fecha = date('Y-m-j'); // ej: 2018-09-10
        }
        $nuevafecha = strtotime('+' . $sumaDeDias . ' day', strtotime($fecha));
        return date('d-m-Y', $nuevafecha);
    }

    public static function verificaSolicitud($tipo, $runCiudadano)
    {
        $solicitud = Solicitud::where('id_tipo_solicitud', $tipo)
            ->where('run_ciudadano', $runCiudadano)
            ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
            ->where('vigente', 1)
            ->first();
        if (!empty($solicitud)) {
            Log::error('Duplicidad. Ya existe en la BD una solicitud de iguales caracteristicas: ' . $solicitud->id_solicitud);
            return true;
        }
        return false;
    }

    /**
     * Retorna el nombre de un mes xx
     * 1 = Enero, etc
     */
    public static function obtenerMes($n)
    {
        $n = intval($n);
        if (array_key_exists($n, Constante::$MESES)) {
            return Constante::$MESES[$n];
        } else {
            return 'Error: No se pudo obtener el Mes';
        }
    }

    /**
     * Retorna el nombre de un mes xx
     * 1 = Enero, etc
     */
    public static function obtenerMesMin($n)
    {
        $n = intval($n);
        if (array_key_exists($n, Constante::$MESESMIN)) {
            return Constante::$MESESMIN[$n];
        } else {
            return 'Error: No se pudo obtener el Mes';
        }
    }

    public static function getCleanField($str)
    {
        $str = str_replace("'", '´', $str);
        return $str;
    }

    // 05/08/2015 -> 20150805
    // Esto debería ser DEPRECATED por el metodo de carbot
    public static function fechaDateToNumber($fecha)
    {
        $fecha_aux = explode('/', $fecha);
        $fechaAnio = (int)$fecha_aux[2] * 10000;
        $fechaMes = (int)$fecha_aux[1] * 100;
        $fechaDia = (int)$fecha_aux[0];
        return (int)$fechaAnio + (int)$fechaMes + ($fechaDia);
    }

    public static function formatFecha($fecha, $serador = '/')
    {
        if ($fecha != -1) {
            $anio = floor($fecha / 10000);
            $diezmil = 10000;
            //$cien = 100;
            $mes = floor(($fecha % $diezmil) / 100);
            $dia = ($fecha % $diezmil) % 100;
            return str_pad($dia, 2, '0', STR_PAD_LEFT) . $serador .
                str_pad($mes, 2, '0', STR_PAD_LEFT) . $serador .
                str_pad($anio, 4, '0', STR_PAD_LEFT);
        } else {
            return '';
        }
    }

    /**
     * Retorna el YYYY-mm-dd espera YYYYmmdd
     */
    public static function formatFechaSeparado($fecha, $serador = '-')
    {
        if ($fecha != -1) {
            return str_pad(substr($fecha, 0, 4), 4, '0', STR_PAD_LEFT) . $serador .
                str_pad(substr($fecha, 4, 2), 2, '0', STR_PAD_LEFT) . $serador .
                str_pad(substr($fecha, 6, 2), 2, '0', STR_PAD_LEFT);
        } else {
            return '';
        }
    }

    public static function formatFechaAnioMes($fecha, $serador = '-')
    {
        if ($fecha != -1) {
            $anio = floor($fecha / 100);
            $diezmil = 100;
            //$cien = 100;
            $mes = floor(($fecha % $diezmil) / 1);
            return str_pad($anio, 4, '0', STR_PAD_LEFT) . $serador .
                str_pad($mes, 2, '0', STR_PAD_LEFT);
        } else {
            return '';
        }
    }

    /**
     * Retorna el dv, de un run que viene sin puntos ni dv
     */
    public static function dv($r)
    {
        $s = 1;
        for ($m = 0; $r != 0; $r /= 10) {
            $s = ($s + $r % 10 * (9 - $m++ % 6)) % 11;
        }
        return chr($s ? $s + 47 : 75);
    }

    /**
     * Verifica si el dv de un run xxx.xxx-y corresponde realmente al dv correcto
     */
    public static function validaRUN($value)
    {
        $tmp = explode('-', $value);
        if (count($tmp) == 2) {
            return !strcmp(Utiles::dv(str_replace('.', '', $tmp[0])), strtoupper($tmp[1]));
        }
        return false;
    }

    public static function checkPasswordNumerico($pwd)
    {
        if (!preg_match('#[0-9]+#', $pwd)) {
            return false;
        }
        return true;
    }

    public static function checkPasswordLetra($pwd)
    {
        if (!preg_match('#[a-zA-Z]+#', $pwd)) {
            return false;
        }
        //return ($errors == $errors_init);
        return true;
    }

    /**
     * se agrega funcion que valida si la solicituda la resuelve el nivel central
     */
    function validaResuelveNivelCentral($id_tipo_solicitud)
    {
        //Los tipos de solicitudes que resuelve el Nivel Central son (70,71,72,73,74,76)
        if (in_array($id_tipo_solicitud, [
            Constante::TIPO_SOLICITUD_70_RECTIFICACION_INGRESO_TRABAJADOR_DEPENDIENTE,
            Constante::TIPO_SOLICITUD_71_RECTIFICACION_INGRESO_TRABAJADOR_INDEPENDIENTE,
            Constante::TIPO_SOLICITUD_72_RECTIFICACION_DE_INGRESOS_DE_CAPITAL,
            Constante::TIPO_SOLICITUD_73_RECTIFICACION_DE_COTIZACIONES_DE_SALUD,
            Constante::TIPO_SOLICITUD_74_RECTIFICACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL,
            Constante::TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO
        ])) {
            return true;
        } else {
            return false;
        }
    }

    public static function generaContrasena($rut)
    {
        $datos = Utiles::getPersona($rut);
        $apellido_paterno = self::cambiaTildeASuLetra(trim($datos->PER_APELLIDOPATERNO));
        if (strlen($apellido_paterno) >= 4) {
            $passwd = ucfirst(strtolower(trim(substr($apellido_paterno, 0, 4))));
        } else {
            $passwd = ucfirst(strtolower(trim($apellido_paterno)));
        }
        $largo = strlen($passwd);
        $aleatorio = rand(1000, 9999);
        $aleatorio2 = rand(0, 99);
        $passwd = (substr($passwd, 0, 1) . $aleatorio2 . substr($passwd, 1, $largo) . '.' . $aleatorio);
        return $passwd;
    }

    public static function cambiaTildeASuLetra($string)
    {
        //$string = trim($string);
        $string = str_replace(
            ['á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'],
            ['a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'],
            $string
        );
        $string = str_replace(
            ['é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'],
            ['e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'],
            $string
        );
        $string = str_replace(
            ['í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'],
            ['i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'],
            $string
        );
        $string = str_replace(
            ['ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'],
            ['o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'],
            $string
        );
        $string = str_replace(
            ['ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'],
            ['u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'],
            $string
        );
        $string = str_replace(
            ['ñ', 'Ñ', 'ç', 'Ç'],
            ['n', 'N', 'c', 'C',],
            $string
        );
        return $string;
    }

    /**
     * Evalua casos para usuarios nuevos usuarios existencias en SSO y RSH
     */
    public static function buscarUsuariosRSHySSO($rut_ws_sso, $dv_ws_sso)
    {
        $ambienteDebug = env('ambienteDebug');
        $viaTokenSSO = env('VIA_TOKEN_SSO');
        $usuario = [];
        if (!$ambienteDebug && $viaTokenSSO) {
            $busquedaSSO = SSOSoapCliente::getSSO_BuscarUsuario($rut_ws_sso, $dv_ws_sso);
        } else {
            $busquedaSSO['estado'] = SSOSoapCliente::USUARIO_NO_ENCONTRADO;
        }
        // Busca en SOAP SSO
        $busquedaRSH = Usuario::find($rut_ws_sso);
        if ($busquedaRSH && $busquedaSSO['estado'] == SSOSoapCliente::USUARIO_ENCONTRADO) {
            // Rechazado, Usuario existe en SSO y RSH.
            $usuario ['SSO'] = true;
            $usuario ['RSH'] = true;
            $usuario ['tipo'] = $busquedaSSO ['tipo'];
            $usuario ['intentosLogin'] = $busquedaRSH->INTENTOS_LOGIN;
        } elseif (!$busquedaRSH && $busquedaSSO['estado'] == SSOSoapCliente::USUARIO_ENCONTRADO) {
            // Usuario no existe en RSH pero si en SSO
            $usuario ['SSO'] = true;
            $usuario ['RSH'] = false;
            $usuario ['tipo'] = $busquedaSSO ['tipo'];
        } elseif ($busquedaRSH && ($busquedaSSO['estado']) == SSOSoapCliente::USUARIO_NO_ENCONTRADO) {
            // Usuario no existe en SSO pero si en RSH
            $usuario ['SSO'] = false;
            $usuario ['RSH'] = true;
            $usuario ['tipo'] = 'EXTERNO';
        } elseif (!$busquedaRSH && $busquedaSSO['estado'] == SSOSoapCliente::USUARIO_NO_ENCONTRADO) {
            // Usuario completamente nuevo sin existencia en SSO ni RSH.
            $usuario ['SSO'] = false;
            $usuario ['RSH'] = false;
            $usuario ['tipo'] = self::EXTERNO;
        }
        return ($usuario);
    }

    /**
     * Para nuevos usarios
     */
    public static function validaCamposNuevosUsuarios($rut_ws_sso, $dv_ws_sso, $input)
    {
        $input['rut_usuario'] = $rut_ws_sso . '-' . $dv_ws_sso;
        $msgerr = array(
            'rut_usuario.required' => 'El campo RUN es requerido',
            'nombres_usuario.required' => 'El campo Nombres usuario es requerido',
            'telefono.min' => 'El campo Nombres usuario debe tener al menos :min caracteres',
            'apat_usuario.required' => 'El campo Primer Apellido del usuario es requerido',
            'apat_usuario.min' => 'El campo Primer Apellido del usuario debe tener al menos :min caracteres',
            'amat_usuario.required' => 'El campo Segundo Apellido del usuario es requerido',
            'amat_usuario.min' => 'El campo Segundo Apellido del usuario debe tener al menos :min caracteres',
            'mail.required' => 'El campo Correo Electrónico del usuario es requerido',
            'id_estado.required' => 'El campo Estado del usuario es requerido',
            'id_perfil.required' => 'El campo Perfil del usuario es requerido',
            'id_perfil.numeric' => 'El campo Perfil del usuario es requerido',
            'reg_id.required' => 'El campo Región del usuario es requerido',
            'reg_id.numeric' => 'El campo Región del usuario es requerido',
            'com_id.required' => 'El campo Comuna del usuario es requerido',
            'com_id.numeric' => 'El campo Comuna del usuario es requerido'
        );

        $validator = Validator::make(
            $input,
            array(
                'rut_usuario' => 'required|run|max:12',
                'nombres_usuario' => 'required|min:3',
                'apat_usuario' => 'required|min:3',
                'amat_usuario' => 'required|min:3',
                'mail' => 'required|email',
                'id_institucion' => 'required|numeric',
                'id_perfil' => 'required|numeric',
                'reg_id' => 'required|numeric',
                'com_id' => 'required|numeric'
            ),
            $msgerr
        );
        if ($validator->fails()) {
            /* se itera por cada mensaje de error para pasar un solo string en la respuesta */
            $messages = $validator->messages();
            $msg = '';
            foreach ($messages->all('<li>:message</li>') as $message) {
                $msg .= $message;
            }
            return $msg;
        } else {
            $msg = null;
        }
        return $msg;
    }

    /**
     * Para  usuarios a modificar
     */
    public static function validaCamposModificarUsuarios($rut_ws_sso, $dv_ws_sso, $input)
    {
        $input['rut_usuario'] = $rut_ws_sso . '-' . $dv_ws_sso;
        $msgerr = array(
            'rut_usuario.required' => 'El campo RUN es requerido',
            'nombres_usuario.required' => 'El campo Nombres usuario es requerido',
            'telefono.min' => 'El campo Nombres usuario debe tener al menos :min caracteres',
            'apat_usuario.required' => 'El campo Primer Apellido del usuario es requerido',
            'apat_usuario.min' => 'El campo Primer Apellido del usuario debe tener al menos :min caracteres',
            'amat_usuario.required' => 'El campo Segundo Apellido del usuario es requerido',
            'amat_usuario.min' => 'El campo Segundo Apellido del usuario debe tener al menos :min caracteres',
            'mail.required' => 'El campo Correo Electrónico del usuario es requerido',
            'id_estado.required' => 'El campo Estado del usuario es requerido',
            'id_perfil.required' => 'El campo Perfil del usuario es requerido',
            'id_perfil.numeric' => 'El campo Perfil del usuario es requerido',
            'reg_id.required' => 'El campo Región del usuario es requerido',
            'reg_id.numeric' => 'El campo Región del usuario es requerido',
            'com_id.required' => 'El campo Comuna del usuario es requerido',
            'com_id.numeric' => 'El campo Comuna del usuario es requerido'
        );

        $validator = Validator::make(
            $input,
            array(
                'rut_usuario' => 'required|run|max:12',
                'nombres_usuario' => 'required|min:3',
                'apat_usuario' => 'required|min:3',
                'amat_usuario' => 'required|min:3',
                'mail' => 'required|email',
                'id_institucion' => 'required|numeric',
                'id_perfil' => 'required|numeric',
                'reg_id' => 'required|numeric',
                'com_id' => 'required|numeric'
            ),
            $msgerr
        );
        if ($validator->fails()) {
            /* se itera por cada mensaje de error para pasar un solo string en la respuesta */
            $messages = $validator->messages();
            $msg = '';
            foreach ($messages->all('<li>:message</li>') as $message) {
                $msg .= $message;
            }
            return $msg;
        } else {
            $msg = null;
        }
        return $msg;
    }

    public static function revisarPassword($rut, $password)
    {
        $error = SSOSoapCliente::RESULTADO_OK;
        $datos = Utiles::getPersona($rut);
        $nombres = self::cambiaTildeASuLetra(trim($datos->PER_NOMBRES));
        $apellido_paterno = self::cambiaTildeASuLetra(trim($datos->PER_APELLIDOPATERNO));
        $apellido_materno = self::cambiaTildeASuLetra(trim($datos->PER_APELLIDOMATERNO));

        $nombres = explode(' ', $nombres);
        foreach ($nombres as $nombreInicial) {
            $nombreCompleto[] = Utiles::cambiaTildeASuLetra($nombreInicial);
        }
        $nombreCompleto [] = $apellido_paterno;
        $nombreCompleto [] = $apellido_materno;
        foreach ($nombreCompleto as $elemento) {
            $position = stripos($password, $elemento);
            if ($position !== false) {
                $error = SSOSoapCliente::RESULTADO_NO_OK;
            }
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $error = SSOSoapCliente::RESULTADO_NO_OK;
        }
        if (!preg_match('~[0-9]~', $password)) {
            $error = SSOSoapCliente::RESULTADO_NO_OK;
        }
        if (!preg_match('/[\'^£$%&*()}{@#~?><,|=_+¬-]/', $password)) {
            $error = SSOSoapCliente::RESULTADO_NO_OK;
        }
        if (strlen($password) < 6) {
            $error = SSOSoapCliente::RESULTADO_NO_OK;
        }
        return ($error);
    }

    /**
     * Pone puntos al run. Si no viene $dv pinta .XXX-X al final como run enmascarado
     */
    public static function ponePuntosAlRun($runSinPuntosSinDv, $dv = '')
    {
        $out = null;
        try {
            $runConPuntos = number_format($runSinPuntosSinDv, 0, ',', '.');
            if ($dv == '') {
                $out = substr($runConPuntos, 0, -4) . '.XXX-X';
            } else {
                $out = $runConPuntos . '-' . $dv;
            }
        } catch (Exception $ex) {
        }
        return $out;
    }

    /**
     * Pone puntos a un número
     */
    public static function ponePuntosNumero($numero)
    {
        try {
            return number_format($numero, 0, ',', '.');
        } catch (Exception $ex) {
        }
        return null;
    }

    /**
     * FUNCION DEPRECATED USAR textoEstadoSolicitudCiudadano()
     * 1  Pendiente Entregar Documentos
     * 2  Agendado
     * 3  En Gestión Municipal
     * 4  Pendiente por Resolver Ejecutor - Ex Encargado Comunal
     * 5  Aprobado
     * 6  En Espera Ciudadano
     * 7  Rechazada
     * 8  Por Aprobar SIT
     * 9  Desistida
     * 10 En Nivel Central
     * 11 En Digitacion
     * 12 Por aprobar UGIT
     * 14 Inactivo por Defuncion
     * 15 En Ruta
     * 16 Supervisión
     */
    public static function textoEstadoSolicitudHomeCiudadano($idEstado)
    {
        $pasoActual = '';
        if (in_array($idEstado, Constante::$ESTADO_SOLICITUD_NO_FINALIZADOS)) {
            $pasoActual .= '1/3';
        }
        if (in_array($idEstado, Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)) {
            $pasoActual .= '3/3';
        }
        if (in_array($idEstado, [])) {
            $pasoActual .= '2/3';
        }

        $icono = '';
        if (in_array($idEstado, Constante::$ESTADO_SOLICITUD_NO_FINALIZADOS)) { // en tramite
            $icono .= '<img src="' . asset('assets2.0/img/icons/ico-pause.svg') . '" class="px-2">';
        }
        if (in_array($idEstado, [
            Constante::ESTADO_SOLICITUD_5_APROBADO
        ])) {
            $icono .= '<img src="' . asset('assets2.0/img/icons/ico-aprobada.svg') . '" class="px-2">';
        }
        if (in_array($idEstado, Constante::$ESTADO_SOLICITUD_7_9_RECHAZADOS_DESISTIDOS)) {
            $icono .= '<img src="' . asset('assets2.0/img/icons/ico-anulada.svg') . '" class="px-2">';
        }
        if (in_array($idEstado, [])) { // en trámite
            $icono .= '<img src="' . asset('assets2.0/img/icons/ico-agenda.svg') . '" class="px-2">';
        }

        $textoEstado = '';
        if (in_array($idEstado, Constante::$ESTADO_SOLICITUD_NO_FINALIZADOS)) { // en tramite
            $textoEstado .= 'En tramitación';
        }
        if (in_array($idEstado, [5])) { // aprobada
            $textoEstado .= 'Aprobada';
        }
        if (in_array($idEstado, [7, 9])) { // anulada
            $textoEstado .= 'Anulada';
        }
        if (in_array($idEstado, [])) { // en trámite
            $textoEstado .= 'En trámite';
        }
        return '<span class="td-texto rsh_content-flex">' . $pasoActual . ' ' . $icono . ' <span>' .
            $textoEstado . '</span></span>';
    }

    public static function iconoPorEstadoCiudadanoSolicitud($nombreEstadoCiudadano)
    {
        switch (mb_convert_case($nombreEstadoCiudadano, MB_CASE_LOWER)) {
            case 'en trámite':
                return 'ico-agenda.svg';
            case 'aprobado':
            case 'confirmado':
                return 'ico-aprobada.svg';
            case 'desistida':
            case 'rechazada':
                return 'ico-anulada.svg';
            case 'borrador':
                return 'ico-borrador.svg';
            default;
                return 'ico-blank.png';
        }
    }

    /* Capitaliza solo la primera palabra en una oración*/
    public static function capitalizaPrimeraPalabra($texto) {
        $textoCapitalizadoPrimeraPalabra = ucfirst(strtolower($texto));
        return $textoCapitalizadoPrimeraPalabra;
    }

    /**
     * Funcion traida desde Registro Calle
     * Separa texto por ' ' o '.' y Capitaliza los segmentos, no capitaliza si pertenece al array $noCapitalizar
     */
    public static function capitalizaTexto($texto, $noCapitalizarParam = [])
    {
        try {
            $noCapitalizarPredeterminados = [
                'el',
                'la',
                'los',
                'las',
                'un',
                'unos',
                'una',
                'unas',
                'a',
                'y',
                'de',
                'del',
                'con'
            ];
            $noCapitalizar = array_merge($noCapitalizarPredeterminados, $noCapitalizarParam);
            $contador = 0;
            $textoConcatenado = '';
            $textoPorEspacio = explode(' ', $texto);
            foreach ($textoPorEspacio as $valorEsp) {
                $textoPorPunto = explode('.', $valorEsp);
                foreach ($textoPorPunto as $valorPunto) {
                    $textoConcatenado .= (in_array(mb_strtolower($valorPunto), $noCapitalizar) && $contador != 0) ?
                        mb_strtolower($valorPunto) . '.' :
                        mb_convert_case($valorPunto, MB_CASE_TITLE, 'UTF-8') . '.';
                    $contador++;
                }
                $textoConcatenado = substr($textoConcatenado, 0, -1) . ' ';
            }
            return substr($textoConcatenado, 0, -1);
        } catch (Exception $ex) {
            return null;
        }
    }

    /**
     * Quita el segundo nombre de per_nombres
     */
    public static function quitaPalabrasLuegoDelEspacio($texto)
    {
        return explode(' ', $texto)[0];
    }

    /**
     * Convierte 201801 a Enero 2018
     */
    static function conviertePeriodoATexto($periodoYYYYMM)
    {
        return Utiles::obtenerMes(substr($periodoYYYYMM, 4)) . ' ' . substr($periodoYYYYMM, 0, -2);
    }

    /**
     * Convierte 2018-01 a Enero 2018
     */
    static function conviertePeriodoBTexto($periodoYYYYMM)
    {
        $periodoYYYYMM = explode('-', $periodoYYYYMM);
        return Utiles::obtenerMes($periodoYYYYMM[1]) . ' ' . $periodoYYYYMM[0];
    }

    /**
     * Convierte 20180101 a Enero 2018
     */
    static function conviertePeriodoCTexto($periodoYYYYMMDD)
    {
        return Utiles::obtenerMes(substr($periodoYYYYMMDD, 4, -2)) . ' ' . substr($periodoYYYYMMDD, 0, -4);
    }

    /**
     * Recursivo que devuelve array de periodos entre dos periodos incluyendo el inicio y el final
     * formatos entrada: YYYYMM
     */
    static function devuelvePeriodosEntre($periodoInicio, $periodoFinal, $arrayPeriodos = [])
    {
        $arrayPeriodos[$periodoInicio] = Utiles::conviertePeriodoATexto($periodoInicio);
        if ($periodoInicio == $periodoFinal) {
            return $arrayPeriodos;
        } else {
            return Utiles::devuelvePeriodosEntre(
                Utiles::sumarUnMesAlPeriodo($periodoInicio),
                $periodoFinal,
                $arrayPeriodos
            );
        }
    }

    /**
     * Resta un mes al periodo, de 201801 a 201712
     */
    static function restarUnMesAlPeriodo($periodoYYYYMM)
    {
        $mesIngreso = intval(substr($periodoYYYYMM, 4));
        $anioIngreso = intval(substr($periodoYYYYMM, 0, -2));
        if ($mesIngreso == 1) {
            $mesSalida = 12;
            $anioSalida = $anioIngreso - 1;
        } else {
            $mesSalida = $mesIngreso - 1;
            $anioSalida = $anioIngreso;
        }
        if ($mesSalida < 10) {
            $mesSalida = '0' . $mesSalida;
        }
        return $anioSalida . $mesSalida;
    }

    /**
     * Suma un mes al periodo, de 201712 a 201801
     */
    static function sumarUnMesAlPeriodo($periodoYYYYMM)
    {
        $mesIngreso = intval(substr($periodoYYYYMM, 4));
        $anioIngreso = intval(substr($periodoYYYYMM, 0, -2));
        if ($mesIngreso == 12) {
            $mesSalida = 1;
            $anioSalida = $anioIngreso + 1;
        } else {
            $mesSalida = $mesIngreso + 1;
            $anioSalida = $anioIngreso;
        }
        if ($mesSalida < 10) {
            $mesSalida = '0' . $mesSalida;
        }
        return $anioSalida . $mesSalida;
    }

    /**
     * Limpia de caracteres espaciales un string para poder ocuparlo como nombre de variable
     */
    public static function cleanNombreVariables($stringConCaracteresEspeciales)
    {
        $stringConCaracteresEspecialesConSeparador = str_replace(
            [' ', '/', '?', '-'],
            '_',
            $stringConCaracteresEspeciales
        );
        return preg_replace('/[^A-Za-z0-9\_]/', '', $stringConCaracteresEspecialesConSeparador);
    }

    /**
     * Consume un servicio $uriRest
     *
     * @param string $verboRest con verbo o método
     * @param string $uriRest
     * @param array $dataBodyJson para json en la petición
     * @param bool $download true si se debe descargar la respuesta
     * @return stdClass resultado del consumo con Guzzle
     */
    public static function consumoConGuzzle(
        $verboRest,
        $uriRest,
        $dataBodyArray = null,
        $dataHeadersArray = null,
        $download = false,
        $timeout = 0,
        $localhost = false
    ) {
        $apiRestHost = $localhost ? "http://127.0.0.1" : env('API_REST_HOST');
        $uriRestBaseService = env('API_REST_BASE_URL');

        $uriCompletaRestToken = $uriRestBaseService . '/auth-api-rest/token';
        $uriCompletaRest = $uriRestBaseService . $uriRest;

        $handler = new CurlHandler([
            'handle_factory' => new CurlFactory(2)
        ]);

        $client = new Client([
            'handler' => HandlerStack::create($handler),
            'base_uri' => $apiRestHost
        ]);

        $responseToken = $client->request('GET',
            $uriCompletaRestToken); // si el servicio token se cae, volve al login ciudadano
        $token = json_decode($responseToken->getBody(), true)['token'];
        $responseToken->getBody()->close();
        if (env('PERMITE_INGRESO_POR_CLAVE_UNICA',false) == false) {
            $tokenClaveUnica = env('CLAVE_UNICA_TOKEN_FALSO');
        } else {
            $tokenClaveUnica = Session::get('claveunica_token');
        }

        $requestOptions = [
            'headers' => [
                'Content-type' => 'application/json; charset=utf-8',
                'Accept' => 'application/json',
                'token' => $token,
                'token-clave-unica' => $tokenClaveUnica
            ],
            'http_errors' => false,
            'connect_timeout' => $timeout,
            'timeout' => $timeout,
            'read_timeout' => $timeout,
            'verify' => false
        ];
        if (!is_null($dataBodyArray)) {
            $requestOptions = array_merge($requestOptions, [
                'json' => $dataBodyArray,
            ]);
        }
        if (!is_null($dataHeadersArray)) {
            $requestOptions['headers'] = array_merge($requestOptions['headers'], $dataHeadersArray);
        }
        //$requestOptions['query']['XDEBUG_SESSION_START'] = 'ciudadano';   //descomentar para depurar consumo
        $responseConsumoRest = $client->request($verboRest, $uriCompletaRest, $requestOptions);
        if ($download) {
            $dataRest = Response::make($responseConsumoRest->getBody(), $responseConsumoRest->getStatusCode());
            $dataRest->header('Content-Type', $responseConsumoRest->getHeaders()['Content-Type']);
            $dataRest->header('Pragma', 'no-cache');
            if (!empty($responseConsumoRest->getHeaders()['Content-Disposition'])) {
                $contentDisposition = str_replace('"', '',
                    $responseConsumoRest->getHeaders()['Content-Disposition'][0]);
                $dataRest->header('Content-Disposition', $contentDisposition);
            }
        } else {
            $dataRest = new stdClass();
            $body = $responseConsumoRest->getBody();
            if (json_decode($body, true) === null) {
                Log::error("Response no parseable: " . $verboRest . " " . $uriCompletaRest .
                    PHP_EOL . "[REQUEST]:" . json_encode($dataBodyArray) . PHP_EOL . "[RESPONSE]:" . $body);
                $dataRest->statusCode = Constante::STATUS_500_ERROR_INTERNAL_ERROR;
                $dataRest->data = ['errors' => ['Error inesperado, debe reiniciar su sesión']];
            } else {
                if (isset(json_decode($body, true)['errors'])) {
                    Log::info("Response error controlado por el servicio: " . $verboRest . " " . $uriCompletaRest . PHP_EOL . "[REQUEST]:" . json_encode($dataBodyArray) . PHP_EOL . "[RESPONSE]:" . $body);
                    $dataRest->statusCode = $responseConsumoRest->getStatusCode();
                    $dataRest->data = json_decode($body, true);
                } else {
                    $dataRest->statusCode = $responseConsumoRest->getStatusCode();
                    $dataRest->data = json_decode($body, true);
                }
            }
        }
        $responseConsumoRest->getBody()->close();
        return $dataRest;
    }


    /**
     * Consume un servicio $uriRestMunicipal
     *
     * @param string $verboRest con verbo o método
     * @param string $uriRest
     * @param array $dataBodyJson para json en la petición
     * @param bool $download true si se debe descargar la respuesta
     * @return stdClass resultado del consumo con Guzzle
     */
    public static function consumoConGuzzleMunicipal(
        $verboRest,
        $uriRest,
        $dataBodyArray = null,
        $dataHeadersArray = null,
        $download = false,
        $timeout = 0,
        $localhost = false
    ) {

        $uriRestBaseService = env('API_REST_BASE_URL_MUNICIPAL');
        $apiRestHost = env('API_REST_HOST_MUNICIPAL');

        $uriCompletaRestToken = $uriRestBaseService . '/auth-api-rest/token';
        $uriCompletaRest = $uriRestBaseService . $uriRest;
        $handler = new CurlHandler([
            'handle_factory' => new CurlFactory(2)
        ]);


        $stack = HandlerStack::create($handler);
        $logger = new Logger('guzzle_logger');
        $logger->pushHandler(new StreamHandler(storage_path('logs/guzzle.log')));
        $stack->push(Middleware::log($logger, new \GuzzleHttp\MessageFormatter('{req_headers} - {res_body}')));
        $clientConfig = [
            'handler' => $stack,
            'base_uri' => $apiRestHost,
            'verify' => false,
        ];

        $client = new Client($clientConfig);

        $responseToken = $client->request('GET', $uriCompletaRestToken);
        $token = $responseToken->getBody();

        if (isset(json_decode($token, true)['token']) === null) {
            \Log::notice("Petición de token fallida " . 'GET' . " " . $uriCompletaRestToken . PHP_EOL .
                "[RESPONSE]:" . $token);
            $dataRest = new stdClass();
            $dataRest->statusCode = Constante::STATUS_500_ERROR_INTERNAL_ERROR;
            $dataRest->data = ['errors' => ['Error token']];
            return $dataRest;
        } else {
            $token = json_decode($responseToken->getBody(), true)['token'];
        }
        $responseToken->getBody()->close();

        //solo para uso interno entre aplicativos
        $tokenClaveUnica = '94355a1824e3492298ccfbc7c96f04e4';

        $clientInfo = \Request::header('client-info');

        $requestOptions = [
            'headers' => [
                'token' => $token,
                'token-clave-unica' => $tokenClaveUnica,
                'client-info' => $clientInfo,
            ],
            'http_errors' => false,
            'timeout' => $timeout,
        ];
        if (!is_null($dataBodyArray)) {
            if (array_key_exists('multipart', $dataBodyArray)) {
                $requestOptions['multipart'] = $dataBodyArray['multipart'];
            } else {
                $requestOptions['headers']['Content-type'] = 'application/json; charset=utf-8';
                $requestOptions['headers']['Accept'] = 'application/json';
                $requestOptions['json'] = $dataBodyArray;
            }
        }
        if (!is_null($dataHeadersArray)) {
            $requestOptions['headers'] = array_merge($requestOptions['headers'], $dataHeadersArray);
        }

        //$requestOptions['query']['XDEBUG_SESSION_START'] = 'ciudadano';   //descomentar para depurar consumo
        $responseConsumoRest = $client->request($verboRest, $uriCompletaRest, $requestOptions);
        if ($download) {
            $dataRest = \Response::make($responseConsumoRest->getBody(), $responseConsumoRest->getStatusCode());
            $dataRest->header('Content-Type', $responseConsumoRest->getHeaders()['Content-Type']);
            $dataRest->header('Pragma', 'no-cache');
            if (!empty($responseConsumoRest->getHeaders()['Content-Disposition'])) {
                $contentDisposition = str_replace('"', '',
                    $responseConsumoRest->getHeaders()['Content-Disposition'][0]);
                $dataRest->header('Content-Disposition', $contentDisposition);
            }
        } else {
            $dataRest = new stdClass();
            $body = $responseConsumoRest->getBody();
            if (json_decode($body, true) === null) {
                \Log::error("Response no parseable: ".$verboRest." ".$uriCompletaRest.
                    PHP_EOL."[REQUEST]:".json_encode($dataBodyArray).PHP_EOL."[RESPONSE]:".$body);
                $dataRest->statusCode = Constante::STATUS_500_ERROR_INTERNAL_ERROR;
                $dataRest->data = ['errors' => ['Error inesperado, debe reiniciar su sesión']];
            } else {
                if (isset(json_decode($body, true)['errors'])) {
                    Log::info("Response error controlado por el servicio: ".$verboRest." ".$uriCompletaRest.PHP_EOL."[REQUEST]:".json_encode($dataBodyArray).PHP_EOL."[RESPONSE]:".$body);
                    $dataRest->statusCode = $responseConsumoRest->getStatusCode();
                    $dataRest->data = json_decode($body, true);
                } else {
                    $dataRest->statusCode = $responseConsumoRest->getStatusCode();
                    $dataRest->data = json_decode($body, true);
                }
            }
        }
        $responseConsumoRest->getBody()->close();

        return $dataRest;
    }

    public static function enviandoCorreo($verboRest, $uriRest, $dataBodyArray = null, $timeout = 0)
    {
        $apiRestHost = "http://" . $_SERVER['SERVER_ADDR'];
        $url_base = str_replace(realpath($_SERVER["DOCUMENT_ROOT"]), "", public_path());
        $url_base = str_replace("\\", "/", $url_base);
        $uriRestBaseService = $url_base . '/api';

        $uriCompletaRestToken = $uriRestBaseService . '/auth-api-rest/token';
        $uriCompletaRest = $uriRestBaseService . $uriRest;

        $client = new Client(['base_uri' => $apiRestHost]);

        $responseToken = $client->request('GET',
            $uriCompletaRestToken); // si el servicio token se cae, volve al login ciudadano
        $token = json_decode($responseToken->getBody(), true)['token'];
        if (env('PERMITE_INGRESO_POR_CLAVE_UNICA',false) == false) {
            $tokenClaveUnica = env('CLAVE_UNICA_TOKEN_FALSO',false);
        } else {
            $tokenClaveUnica = Session::get('claveunica_token');
        }

        $requestOptions = [
            'headers' => [
                'Content-type' => 'application/json; charset=utf-8',
                'Accept' => 'application/json',
                'token' => $token,
                'token-clave-unica' => $tokenClaveUnica
            ],
            'http_errors' => false,
            'timeout' => $timeout,
        ];
        if (!is_null($dataBodyArray)) {
            $requestOptions = array_merge($requestOptions, [
                'json' => $dataBodyArray,
            ]);
        }
        $responseConsumoRest = $client->request($verboRest, $uriCompletaRest, $requestOptions);
        $dataRest = new stdClass();
        $body = $responseConsumoRest->getBody();
        if (json_decode($body, true) === null) {
            Log::error("Problema envio Mail: " . $verboRest . " " . $uriCompletaRest . PHP_EOL . "[REQUEST]:" . json_encode($dataBodyArray) . PHP_EOL . "[RESPONSE]:" . $body);
            $dataRest->statusCode = Constante::STATUS_500_ERROR_INTERNAL_ERROR;
            $dataRest->data = ['errors' => ['Error inesperado, debe reiniciar su sesión']];
        } else {
            $dataRest->statusCode = $responseConsumoRest->getStatusCode();
            $dataRest->data = json_decode($body, true);
        }

        return $dataRest;
    }

    /**
     * Retorna si se está ejecutando en plataforma movil
     */
    public static function esMovil()
    {
        return preg_match('/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i',
            $_SERVER['HTTP_USER_AGENT']);
    }

    public static function getRealIP()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            return $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            return $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return 'Desconocido';
        }
    }

    /**
     * guarda el detalle del consumo en la tabla LOG_SERVICIOS de asignacion
     */
    public static function guardadoDatosServicios() // $idSolicitud)
    {
        try {
//            Log::info(Request::all());
            $contentType = Request::header('content-type'); // application/json
            $token = Request::header('token'); //  cd76faaea74c6f13e628bbab0feae6c0754d55f0
            $agente = Request::header('user-agent');  //  -->> PostmanRuntime/7.12.0
            $cabecera = "Content-type: " . $contentType .
                "\nToken: " . $token .
                "\nUser-agent: " . $agente;

            $origen = Request::url(); // origen ->> http://localhost/api/domicilio-vivienda
//            $path = Request::path();  // nombre del Servicio api-domicilio/vivienda
            $uri = $origen;

            $cuerpoBodyJson = Request::getContent();  // Contenido JSON / Body
            $metodo = Request::method(); // metodo HTTP -->> POST
            $estampaTiempo = date('Y-m-d H:i:s');

            $logServicios = new LogServicios();

            $logServicios->ID = Sequence::nextValue('SEQ_LOG_SERVICIOSPK');
            $logServicios->CABECERA = $cabecera;
            $logServicios->REQUEST = $cuerpoBodyJson;
            $logServicios->SERVICIO = $uri;
            $logServicios->ACCION = $metodo;
            $logServicios->FECHA_CONSUMO = $estampaTiempo;

            if ($logServicios->save()) {
                if (env('ACTIVAR_COMMIT_LOG',false)) {
                    DB::connection()->getPdo()->commit();
                }
//                return Response::json(array('estado' => '1', 'msg' => $mensaje, 'sid' => $sid));
//            } else {
//                return Response::json(array('estado' => '0', 'msg' => 'Error en la solicitud de Complemento: complemento_salud', 'sid' => 0));
            }

        } catch (Exception $ex) {
//            return ServiceError::response($ex);
        }
    }

    /*
     * Trae el texto para los tramos de ingreso del trabajo, pensiones, capital y total
     */
    public static function traeTramoIngreso($tramoMin, $tramoMax)
    {
        if ($tramoMin == 0 && $tramoMax == 0) {
            return 'no tiene';
        } elseif ($tramoMin == 0) {
            return 'menor que $' . number_format($tramoMax, 0, ',', '.');
        } else {
            return 'entre $' . number_format($tramoMin, 0, ',', '.') . ' y $' .
                number_format($tramoMax, 0, ',', '.');
        }
    }

    public static function traeTramoIngresoAsignacion($tramo)
    {
        if ($tramo <= 50000) {
            return 'menor que $50.000';
        } else {
            return 'entre $' . number_format($tramo - 50000, 0, ',', '.') .
                ' y $' . number_format($tramo, 0, ',', '.');
        }
    }

    /**
     * Esta función debe reemplazarse por lo que trae el servicio api/datos-globales
     */
    public static function mensajeTramoQuintilCartola($tamoQuintil)
    {
        $porcentajeTramoText = '';
        if ($tamoQuintil == 40) {
            $porcentajeTramoText = '0% y el 40% de menores ingresos o mayor ';
        } elseif ($tamoQuintil == 50) {
            $porcentajeTramoText = '41% y el 50% de menores ingresos o mayor ';
        } elseif ($tamoQuintil == 60) {
            $porcentajeTramoText = '51% y el 60% de menores ingresos o mayor ';
        } elseif ($tamoQuintil == 70) {
            $porcentajeTramoText = '61% y el 70% de menores ingresos o mayor ';
        } elseif ($tamoQuintil == 80) {
            $porcentajeTramoText = '71% y el 80% de mayores ingresos o menor';
        } elseif ($tamoQuintil == 90) {
            $porcentajeTramoText = '81% y el 90% de mayores ingresos o menor';
        } elseif ($tamoQuintil == 100) {
            $porcentajeTramoText = '91% y el 100% de mayores ingresos o menor';
        }
        return $porcentajeTramoText;
    }

    /** Uso
     *    Utiles::tInicio('t1');
     *    function_lenta();
     *    Utiles::tFinal('t1');
     *
     * retorna: tiempo en lavavel.log
     */
    var $tiemposInicio = [];

    public static function tInicio($etiqueta)
    {
        if (env('ambienteDebug',false)) {
            list($usec, $sec) = explode(' ', microtime());
            global $tiemposInicio;
            $tiemposInicio[$etiqueta] = ((float)$usec + (float)$sec);
        }
    }

    public static function tFinal($etiqueta)
    {
        if (env('ambienteDebug',false)) {
            list($usec, $sec) = explode(' ', microtime());
            global $tiemposInicio;
            $time = ((float)$usec + (float)$sec) - $tiemposInicio[$etiqueta];
            Log::info("Tiempo diferencia ($etiqueta): $time segundos\n");
        }
    }

    /**
     * Fin Consulta Modulo
     */
    public static function limpiaErrorFuncionDB($resultado)
    {
        return ((stripos($resultado, 'Error:') === false) ? $resultado : null);
    }

    /**
     * Retorna concatenado el primer y último elemento de un arreglo
     */
    public static function InicioYFin($array, $conector = ' y ')
    {
        return reset($array) . $conector . end($array);
    }

    /**
     * Se quita provincia y comuna al ComunaINE para obtener el nro de la region
     */
    public static function getRegionDesdeCodigoComuna($codigoComuna)
    {
        $region = substr($codigoComuna, 0, -3); // quita los 3 últimos caracteres
        if (Utiles::condicionEntreDosNumeros($region, Constante::$REGIONES_1_A_16_POSIBLES_RESPUESTAS)) {
            return $region;
        } else {
            return null;
        }
    }

    /**
     * Condición si $variable está entre dos números (incluyéndolos)
     * $array siempre es de dos elementos
     */
    public static function condicionEntreDosNumeros($variable, $array)
    {
        return ($array[0] <= $variable && $variable <= $array[1]);
    }

    /**
     * function inArrayMinuscula($variable, $array1, ... , $arrayN)
     * $arrayMerge = $array1, ... , $arrayN
     *
     * Verifica si $variable está dentro de $arrayMerge sin importar minúsculas, mayúsculas, tildes o eñes
     */
    public static function inArrayMinuscula()
    {
        $argList = func_get_args();
        $variableAComparar = $argList[0];

        $arrayMerge = [];
        for ($i = 1; $i < func_num_args(); $i++) {
            $arrayMerge = array_merge($arrayMerge, $argList[$i]);
        }
        return in_array(mb_strtolower(self::cambiaTildeASuLetra($variableAComparar)), $arrayMerge);
    }

    public static function obtenerPreguntasModulo($runCiudadano, $modulo)
    {
        $url = '/integrantes-grupo-familiar/' . $runCiudadano . '/' . $modulo;
        try {
            $dataRest = Utiles::consumoConGuzzle("GET", $url, null);
            if ($modulo == "educacion") {
//                $dataRest->data['datos-respuestas']['e2']['tipo_input'] = 1;
            }

            if ($modulo == "ingreso") {
                if (array_key_exists("o2", $dataRest->data['datos-respuestas'])) {
                    $dataRest->data['datos-respuestas']['o2']['tipo_input'] = 1;
                }
                if (array_key_exists("o3", $dataRest->data['datos-respuestas'])) {
                    $dataRest->data['datos-respuestas']['o3']['tipo_input'] = 1;
                }
                if (array_key_exists("o4", $dataRest->data['datos-respuestas'])) {
                    $dataRest->data['datos-respuestas']['o4']['tipo_input'] = 1;
                }
                if (array_key_exists("o6", $dataRest->data['datos-respuestas'])) {
                    $dataRest->data['datos-respuestas']['o6']['tipo_input'] = 1;
                }
                if (array_key_exists("o9", $dataRest->data['datos-respuestas'])) {
                    $dataRest->data['datos-respuestas']['o9']['tipo_input'] = 1;
                }
            }
            return $dataRest->data['datos-respuestas'];
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return Response::json(['errors' => ['Error inesperado']], Constante::STATUS_500_ERROR_INTERNAL_ERROR);
        }
    }

    public static function obtenerRespuestasModulos($runCiudadano, $sid = null)
    {
        $metodo = 'GET';
        if (is_null($sid)) {
            $url = '/tramite/' . $runCiudadano;
        } else {
            $url = '/tramite/' . $runCiudadano . '/' . $sid;
        }
        try {
            $dataRest1 = Utiles::consumoConGuzzle($metodo, $url, null);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return null;
            //return Response::json(['errors' => ['Error inesperado']], Constante::STATUS_500_ERROR_INTERNAL_ERROR);
        }
        return $dataRest1->data;
    }

    public static function obtenerDatosContacto($runCiudadano)
    {
        $metodo = 'GET';
        $url = '/datos-de-contacto/show/' . $runCiudadano;
        try {
            $dataRest1 = Utiles::consumoConGuzzle($metodo, $url, null);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            return null;
            //return Response::json(['errors' => ['Error inesperado']], Constante::STATUS_500_ERROR_INTERNAL_ERROR);
        }
        return $dataRest1->data;
    }

    public static function guardaFechaConsultaPersona($runCiudadano)
    {
        //guardar marca fecha de consulta en tabla persona
        $persona = Personas::where('per_run', $runCiudadano)
            ->first();
        if (!empty($persona) > 0) {
            try {
                DB::connection()->getPdo()->beginTransaction();
                $persona->PER_FECHA_CONSULTA = date('Y-m-d H:i:s');
                $persona->save();
                DB::connection()->getPdo()->commit();
            } catch (Exception $ex) {
                Log::info("Error en guardado Fecha Consulta " . $ex->getMessage());
                DB::connection()->getPdo()->rollback();
            }
        }
    }

    public static function getPuedeSerIncorporado($persona)
    {
        $beneficiarios = null;
        $puedeserincorporado = Personas::where('per_run', $persona->PER_RUN)
            ->select(['bnfgrupfami_grupofamiliar', 'bnfgrupfami_institucionalizado'])
            ->join('beneficiarios_gruposfamiliares', 'per_id', '=', 'bnfgrupfami_persona')
            ->join('ab_solicitud', 'beneficiarios_gruposfamiliares.bnfgrupfami_idsolicitud', '=', 'ab_solicitud.id_solicitud')
            ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->whereIn('id_tipo_solicitud', [
                Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
                Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
                Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
                Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE,
                Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA,
                Constante::TIPO_SOLICITUD_19_CAMBIO_EN_RELACION_DE_PARENTESCO,
                Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR,
                Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR,
                Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA,
                Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA
            ])
            ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
            ->first();
        $solicitudtramitable = true;

        if (!empty($puedeserincorporado)) {
            $solicitudtramitable = false;
            $beneficiarios = $puedeserincorporado;
        } else {
            if ((env('SOLICITUD_MENORES_ULTIMOS_MESES')) && $persona->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD) {
                $queryIncorporado = Personas::where('per_run', $persona->PER_RUN)
                    ->select(['bnfgrupfami_grupofamiliar', 'bnfgrupfami_institucionalizado'])
                    ->join('beneficiarios_gruposfamiliares', 'per_id', '=', 'bnfgrupfami_persona')
                    ->join('ab_solicitud', 'beneficiarios_gruposfamiliares.bnfgrupfami_idsolicitud', '=', 'ab_solicitud.id_solicitud')
                    ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
                    ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                    ->whereIn('id_tipo_solicitud', [
                        Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
                        Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA,
                        Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE
                    ])
                    ->where('ab_solicitud.fecha_creacion', '>=', DB::RAW('ADD_MONTHS(sysdate,-6)'))
                    ->where('BNFGRUPFAMI_VIGENTE', Constante::VIGENTE_1_VIGENTE)
                    ->where('BNFGRUPFAMI_CALCULOACTIVO', Constante::CALCULO_ACTIVO_1_APROBADO)
                    ->where('id_estado', Constante::ESTADO_SOLICITUD_5_APROBADO);
                if (is_null($persona->getAttribute('BNFGRUPFAMI_INSTITUCIONALIZADO'))) {
                    $puedeserincorporado = $queryIncorporado->where('BNFGRUPFAMI_INSTITUCIONALIZADO', Constante::INSTITUCIONALIZADO_0_NO_INSTITUCIONALIZADO)
                        ->first();
                } elseif ($persona->getAttribute('BNFGRUPFAMI_INSTITUCIONALIZADO') == 0) {
                    $puedeserincorporado = $queryIncorporado->first();
                }
            }
            if (!empty($puedeserincorporado)) {
                $solicitudtramitable = false;
                $beneficiarios = $puedeserincorporado;
            } else {
                if($persona->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD){
                    $buscaIngresoRegistro = Solicitud::where('run_ciudadano', $persona->PER_RUN)
                    ->where('id_estado', Constante::ESTADO_SOLICITUD_5_APROBADO)
                    ->whereIn('id_tipo_solicitud', [
                        Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
                        Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA,
                    ])
                    ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                    ->first();
                    $diferenciaDias = 31;
                    if (!empty($buscaIngresoRegistro)) {
                        $fecha_actual = strtotime(substr($buscaIngresoRegistro->FECHA_MODIFICACION, 0, -9));
                        $fecha_aprobacion = strtotime(date("Y") . "/" . date("m") . "/" . date("d"));
                        $diferenciaDias = round(($fecha_aprobacion - $fecha_actual) / 86400);
                    }
                    if ((!empty($buscaIngresoRegistro)) && ($diferenciaDias <= 30)) {
                        $solicitudtramitable = false;
                        $beneficiarios = $puedeserincorporado;
                    }
                }

                $puedeserincorporado = Solicitud::where('run_ciudadano', $persona->PER_RUN)
                    ->select(['beneficiarios_gruposfamiliares.bnfgrupfami_grupofamiliar',
                        'beneficiarios_gruposfamiliares.bnfgrupfami_institucionalizado'])
                    ->join('beneficiarios_gruposfamiliares',
                        'beneficiarios_gruposfamiliares.bnfgrupfami_idsolicitud', '=', 'ab_solicitud.id_solicitud')
                    ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                    ->whereIn('id_tipo_solicitud', [
                        Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
                        Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
                        Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
                        Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE,
                        Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA,
                        Constante::TIPO_SOLICITUD_19_CAMBIO_EN_RELACION_DE_PARENTESCO,
                        Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR,
                        Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR,
                        Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA,
                        Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA
                    ])
                    ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
                    ->first();
                if (!empty($puedeserincorporado)) {
                    $solicitudtramitable = false;
                    $beneficiarios = $puedeserincorporado;
                }
            }
        }
        if ($beneficiarios == null) {
            $beneficiarios = BeneficiarioGrupoFamiliar::select(['bnfgrupfami_grupofamiliar',
                'bnfgrupfami_institucionalizado'])
                ->where('bnfgrupfami_persona', $persona->PER_ID)
                ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->first();
        }

        $grupoFamiliarId = null;
        if ($beneficiarios) {
            $grupoFamiliar = GrupoFamiliar::select('grupfami_nuevofolio')
                ->where('grupfami_id', $beneficiarios->BNFGRUPFAMI_GRUPOFAMILIAR)
                ->where('grupfami_estado', Constante::ESTADO_GRUPO_FAMILIAR_7_APROBADO)
                ->where('grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('grupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->first();
            if (!empty($grupoFamiliar)) {
                if ($beneficiarios->BNFGRUPFAMI_INSTITUCIONALIZADO != Constante::INSTITUCIONALIZADO_1_INSTITUCIONALIZADO) {
                    $grupoFamiliarId = $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;
                    if ($solicitudtramitable == true) {
                        $puedeserincorporado = Solicitud::select(1)
                            ->join('GRUPOS_FAMILIARES',
                                'GRUPOS_FAMILIARES.GRUPFAMI_IDSOLICITUD', '=', 'ab_solicitud.ID_SOLICITUD')
                            ->where('grupfami_nuevofolio', $grupoFamiliarId)
                            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                            ->whereIn('id_tipo_solicitud', [
                                Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
                                Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
                                Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE,
                                Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA,
                                Constante::TIPO_SOLICITUD_19_CAMBIO_EN_RELACION_DE_PARENTESCO,
                                Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR,
                                Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR,
                                Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA,
                                Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA
                            ])
                            ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
                            ->first();
                        if (!empty($puedeserincorporado)) {
                            $solicitudtramitable = false;
                        }

                    }
                }
            }
        }
        return [
            'puedeserincorporado' => $solicitudtramitable,
            'grupfami_nuevofolio-asignacion' => $grupoFamiliarId
        ];
    }

    public static function consultarAscendencia($run, $dv)
    {
        $runCompleto = $run . '-' . $dv;
        $salida = (object)[
            'padre' => (object)['rut' => null, 'dv' => null],
            'madre' => (object)['rut' => null, 'dv' => null],
            'fechaConsulta' => null
        ];

        if (env('CERTIFICADO_NACIMIENTO_WS_URL',false)) {
            try {
                $data = WSCertificadoNacimiento::get($runCompleto, $runCompleto, 1);
            } catch (Exception $ex) {
                return $salida;
            }
            if ($data->estado == Constante::STATUS_200_SUCCESS_OK) {
                $salida->padre->rut = $data->rutpadre;
                $salida->padre->dv = $data->dvpadre;
                $salida->madre->rut = $data->rutmadre;
                $salida->madre->dv = $data->dvmadre;
            } else {
                $salida->padre->rut = Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE;
                $salida->padre->dv = Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE;
                $salida->madre->rut = Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE;
                $salida->madre->dv = Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE;
            }
            $salida->fechaConsulta = date('Y-m-d H:i:s');
        }
        return $salida;
    }

    public static function getPensionPorRUN($runCiudadano)
    {
        $regPagadorEloq = ComplementoIngresos::join('ab_solicitud', 'ab_complemento_ingresos.id_solicitud', '=',
            'ab_solicitud.id_solicitud')
            ->where('ab_complemento_ingresos.rut_pagador', $runCiudadano)
            ->whereIn('ab_solicitud.vigente', [Constante::VIGENTE_1_VIGENTE,Constante::VIGENTE_3_BORRADOR])
            ->whereIn('ab_solicitud.id_estado', Constante::$ESTODOS_DE_SOLICITUD_TRAMITE)
            ->orderBy('ab_solicitud.fecha_modificacion', 'DESC')
            ->orderBy('ab_complemento_ingresos.rut_causante', 'ASC')
            ->orderBy('ab_complemento_ingresos.rut_receptor', 'ASC')
            ->get();
        $regReceptorEloq = ComplementoIngresos::join('ab_solicitud', 'ab_complemento_ingresos.id_solicitud', '=',
            'ab_solicitud.id_solicitud')
            ->where('ab_complemento_ingresos.rut_receptor', $runCiudadano)
            ->whereIn('ab_solicitud.vigente', [Constante::VIGENTE_1_VIGENTE,Constante::VIGENTE_3_BORRADOR])
            ->whereIn('ab_solicitud.id_estado', Constante::$ESTODOS_DE_SOLICITUD_TRAMITE)
            ->orderBy('ab_complemento_ingresos.complemento_id', 'DESC')
            ->orderBy('ab_complemento_ingresos.rut_pagador', 'ASC')
            ->orderBy('ab_complemento_ingresos.rut_causante', 'ASC')
            ->get();
        $regBeneficiarioEloq = ComplementoIngresos::join('ab_solicitud', 'ab_complemento_ingresos.id_solicitud', '=',
            'ab_solicitud.id_solicitud')
            ->where('ab_complemento_ingresos.rut_causante', $runCiudadano)// RUT_CAUSANTE = BENEFICIARIO
            ->whereIn('ab_solicitud.vigente', [Constante::VIGENTE_1_VIGENTE,Constante::VIGENTE_3_BORRADOR])
            ->whereIn('ab_solicitud.id_estado', Constante::$ESTODOS_DE_SOLICITUD_TRAMITE)
            ->orderBy('ab_complemento_ingresos.complemento_id', 'DESC')
            ->orderBy('ab_complemento_ingresos.rut_pagador', 'ASC')
            ->orderBy('ab_complemento_ingresos.rut_causante', 'ASC')
            ->get();

        $regPagador = [];
        foreach ($regPagadorEloq as $element) {
            $causante = Personas::select(['per_nombres', 'per_apellidopaterno', 'per_fechanacimiento'])
                ->where('per_run', $element->RUT_CAUSANTE)
                ->first();
            $elementFiltroPagador['nombre-causante'] = isset($causante->PER_NOMBRES) ? Utiles::capitalizaTexto($causante->PER_NOMBRES . ' ' . $causante->PER_APELLIDOPATERNO . ' ' . $causante->PER_APELLIDOMATERNO) : null;
            $elementFiltroPagador['edad-causante'] = isset($causante->PER_FECHANACIMIENTO) ? Utiles::getEdad($causante->PER_FECHANACIMIENTO) : null;
            $receptor = Personas::select(['per_nombres', 'per_apellidopaterno', 'per_fechanacimiento'])
                ->where('per_run', $element->RUT_RECEPTOR)
                ->first();
            $elementFiltroPagador['nombre-receptor'] = isset($receptor->PER_NOMBRES) ? Utiles::capitalizaTexto($receptor->PER_NOMBRES . ' ' . $receptor->PER_APELLIDOPATERNO . ' ' . $receptor->PER_APELLIDOMATERNO) : null;
            $elementFiltroPagador['edad-receptor'] = isset($receptor->PER_FECHANACIMIENTO) ? Utiles::getEdad($receptor->PER_FECHANACIMIENTO) : null;
            $pagador = Personas::select(['per_nombres', 'per_apellidopaterno', 'per_fechanacimiento'])
                ->where('per_run', $element->RUT_PAGADOR)
                ->first();
            $elementFiltroPagador['nombre-pagador'] = isset($pagador->PER_NOMBRES) ? Utiles::capitalizaTexto($pagador->PER_NOMBRES . ' ' . $pagador->PER_APELLIDOPATERNO . ' ' . $pagador->PER_APELLIDOMATERNO) : null;
            $elementFiltroPagador['edad-pagador'] = isset($pagador->PER_FECHANACIMIENTO) ? Utiles::getEdad($pagador->PER_FECHANACIMIENTO) : null;
            $elementFiltroPagador['id'] = $element->COMPLEMENTO_ID;
            $elementFiltroPagador['tipo'] = Utiles::getNombreTipoYSubtipoSolicitud($element->ID_TIPO_SOLICITUD,
                $element->C_TCOMPLEMENTO_INGRESO);
            $elementFiltroPagador['monto'] = $element->MONTO;
            $elementFiltroPagador['rut-causante'] = $element->RUT_CAUSANTE;
            $elementFiltroPagador['dv-causante'] = $element->DV_CAUSANTE;
            $elementFiltroPagador['rut-receptor'] = $element->RUT_RECEPTOR;
            $elementFiltroPagador['dv-receptor'] = $element->DV_RECEPTOR;
            $elementFiltroPagador['rut-pagador'] = $element->RUT_PAGADOR;
            $elementFiltroPagador['dv-pagador'] = $element->DV_PAGADOR;
            $elementFiltroPagador['periodo-read'] = $element->PERIODO_READ;
            $elementFiltroPagador['fecha-inicio-pago'] = $element->FECHA_INICIO_PAGO;
            $elementFiltroPagador['nro-cuotas'] = $element->NUMERO_CUOTAS;
            $elementFiltroPagador['fecha-vigente'] = $element->FECHA_MODIFICACION;
            $elementFiltroPagador['complemento-ingreso'] = $element->C_TCOMPLEMENTO_INGRESO;
            $elementFiltroPagador['run-solicitante'] = $element->RUN_CIUDADANO;
            $element->ID_ESTADO = ($element->ID_ESTADO == 11 && $element->VIGENTE == Constante::VIGENTE_3_BORRADOR) ? 99 : $element->ID_ESTADO;
            $elementFiltroPagador['estado-solicitud'] = Constante::$estadoDeSolicitud[$element->ID_ESTADO];
            if (isset($regPagador[$element->RUT_CAUSANTE])) {
                $regPagador[$element->RUT_CAUSANTE]['historico'][] = $elementFiltroPagador;
            } else {
                if($element->RUT_CAUSANTE != ""){
                    $regPagador[$element->RUT_CAUSANTE] = $elementFiltroPagador;
                }
            }
        }

        $regReceptor = [];
        foreach ($regReceptorEloq as $element) {
            $causante = Personas::select(['per_nombres', 'per_apellidopaterno', 'per_fechanacimiento'])
                ->where('per_run', $element->RUT_CAUSANTE)
                ->first();
            $elementFiltroReceptor['nombre-causante'] = isset($causante->PER_NOMBRES) ? Utiles::capitalizaTexto($causante->PER_NOMBRES . ' ' . $causante->PER_APELLIDOPATERNO . ' ' . $causante->PER_APELLIDOMATERNO) : null;
            $elementFiltroReceptor['edad-causante'] = isset($causante->PER_FECHANACIMIENTO) ? Utiles::getEdad($causante->PER_FECHANACIMIENTO) : null;
            $receptor = Personas::select(['PER_NOMBRES', 'PER_APELLIDOPATERNO', 'per_fechanacimiento'])
                ->where('per_run', $element->RUT_RECEPTOR)
                ->first();
            $elementFiltroReceptor['nombre-receptor'] = isset($receptor->PER_NOMBRES) ? Utiles::capitalizaTexto($receptor->PER_NOMBRES . ' ' . $receptor->PER_APELLIDOPATERNO . ' ' . $receptor->PER_APELLIDOMATERNO) : null;
            $elementFiltroReceptor['edad-receptor'] = isset($receptor->PER_FECHANACIMIENTO) ? Utiles::getEdad($receptor->PER_FECHANACIMIENTO) : null;
            $pagador = Personas::select(['PER_NOMBRES', 'PER_APELLIDOPATERNO', 'per_fechanacimiento'])
                ->where('per_run', $element->RUT_PAGADOR)
                ->first();
            $elementFiltroReceptor['nombre-pagador'] = isset($pagador->PER_NOMBRES) ? Utiles::capitalizaTexto($pagador->PER_NOMBRES . ' ' . $pagador->PER_APELLIDOPATERNO . ' ' . $pagador->PER_APELLIDOMATERNO) : null;
            $elementFiltroReceptor['edad-pagador'] = isset($pagador->PER_FECHANACIMIENTO) ? Utiles::getEdad($pagador->PER_FECHANACIMIENTO) : null;
            $elementFiltroReceptor['id'] = $element->COMPLEMENTO_ID;
            $elementFiltroReceptor['tipo'] = Utiles::getNombreTipoYSubtipoSolicitud($element->ID_TIPO_SOLICITUD,
                $element->C_TCOMPLEMENTO_INGRESO);
            $elementFiltroReceptor['monto'] = $element->MONTO;
            $elementFiltroReceptor['rut-causante'] = $element->RUT_CAUSANTE;
            $elementFiltroReceptor['dv-causante'] = $element->DV_CAUSANTE;
            $elementFiltroReceptor['rut-receptor'] = $element->RUT_RECEPTOR;
            $elementFiltroReceptor['dv-receptor'] = $element->DV_RECEPTOR;
            $elementFiltroReceptor['rut-pagador'] = $element->RUT_PAGADOR;
            $elementFiltroReceptor['dv-pagador'] = $element->DV_PAGADOR;
            $elementFiltroReceptor['periodo-read'] = $element->PERIODO_READ;
            $elementFiltroReceptor['fecha-inicio-pago'] = $element->FECHA_INICIO_PAGO;
            $elementFiltroReceptor['nro-cuotas'] = $element->NUMERO_CUOTAS;
            $elementFiltroReceptor['fecha-vigente'] = $element->FECHA_MODIFICACION;
            $elementFiltroReceptor['complemento-ingreso'] = $element->C_TCOMPLEMENTO_INGRESO;
            $elementFiltroReceptor['run-solicitante'] = $element->RUN_CIUDADANO;
            $element->ID_ESTADO = ($element->ID_ESTADO == 11 && $element->VIGENTE == Constante::VIGENTE_3_BORRADOR) ? 99 : $element->ID_ESTADO;
            $elementFiltroReceptor['estado-solicitud'] = Constante::$estadoDeSolicitud[$element->ID_ESTADO];
            if (isset($regReceptor[$element->RUT_PAGADOR])) {
                $regReceptor[$element->RUT_PAGADOR]['historico'][] = $elementFiltroReceptor;
            } else {
                if($element->RUT_PAGADOR != ""){
                    $regReceptor[$element->RUT_PAGADOR] = $elementFiltroReceptor;
                }
            }
        }

        $regBeneficiario = [];
        foreach ($regBeneficiarioEloq as $element) {
            $causante = Personas::select(['per_nombres', 'per_apellidopaterno', 'per_fechanacimiento'])
                ->where('per_run', $element->RUT_CAUSANTE)
                ->first();
            $elementFiltroBeneficiario['nombre-causante'] = isset($causante->PER_NOMBRES) ? Utiles::capitalizaTexto($causante->PER_NOMBRES . ' ' . $causante->PER_APELLIDOPATERNO . ' ' . $causante->PER_APELLIDOMATERNO) : null;
            $elementFiltroBeneficiario['edad-causante'] = isset($causante->PER_FECHANACIMIENTO) ? Utiles::getEdad($causante->PER_FECHANACIMIENTO) : null;
            $receptor = Personas::select(['per_nombres', 'per_apellidopaterno', 'per_fechanacimiento'])
                ->where('per_run', $element->RUT_RECEPTOR)
                ->first();
            $elementFiltroBeneficiario['nombre-receptor'] = isset($receptor->PER_NOMBRES) ? Utiles::capitalizaTexto($receptor->PER_NOMBRES . ' ' . $receptor->PER_APELLIDOPATERNO . ' ' . $receptor->PER_APELLIDOMATERNO) : null;
            $elementFiltroBeneficiario['edad-receptor'] = isset($receptor->PER_FECHANACIMIENTO) ? Utiles::getEdad($receptor->PER_FECHANACIMIENTO) : null;
            $pagador = Personas::select(['per_nombres', 'per_apellidopaterno', 'per_fechanacimiento'])
                ->where('per_run', $element->RUT_PAGADOR)
                ->first();
            $elementFiltroBeneficiario['nombre-pagador'] = isset($pagador->PER_NOMBRES) ? Utiles::capitalizaTexto($pagador->PER_NOMBRES . ' ' . $pagador->PER_APELLIDOPATERNO . ' ' . $pagador->PER_APELLIDOMATERNO) : null;
            $elementFiltroBeneficiario['edad-pagador'] = isset($pagador->PER_FECHANACIMIENTO) ? Utiles::getEdad($pagador->PER_FECHANACIMIENTO) : null;
            $elementFiltroBeneficiario['id'] = $element->COMPLEMENTO_ID;
            $elementFiltroBeneficiario['tipo'] = Utiles::getNombreTipoYSubtipoSolicitud($element->ID_TIPO_SOLICITUD,
                $element->C_TCOMPLEMENTO_INGRESO);
            $elementFiltroBeneficiario['monto'] = $element->MONTO;
            $elementFiltroBeneficiario['rut-causante'] = $element->RUT_CAUSANTE;
            $elementFiltroBeneficiario['dv-causante'] = $element->DV_CAUSANTE;
            $elementFiltroBeneficiario['rut-receptor'] = $element->RUT_RECEPTOR;
            $elementFiltroBeneficiario['dv-receptor'] = $element->DV_RECEPTOR;
            $elementFiltroBeneficiario['rut-pagador'] = $element->RUT_PAGADOR;
            $elementFiltroBeneficiario['dv-pagador'] = $element->DV_PAGADOR;
            $elementFiltroBeneficiario['periodo-read'] = $element->PERIODO_READ;
            $elementFiltroBeneficiario['fecha-inicio-pago'] = $element->FECHA_INICIO_PAGO;
            $elementFiltroBeneficiario['nro-cuotas'] = $element->NUMERO_CUOTAS;
            $elementFiltroBeneficiario['fecha-vigente'] = $element->FECHA_MODIFICACION;
            $elementFiltroBeneficiario['complemento-ingreso'] = $element->C_TCOMPLEMENTO_INGRESO;
            $elementFiltroBeneficiario['run-solicitante'] = $element->RUN_CIUDADANO;
            $element->ID_ESTADO = ($element->ID_ESTADO == 11 && $element->VIGENTE == Constante::VIGENTE_3_BORRADOR) ? 99 : $element->ID_ESTADO;
            $elementFiltroBeneficiario['estado-solicitud'] = Constante::$estadoDeSolicitud[$element->ID_ESTADO];
            if (isset($regBeneficiario[$element->RUT_PAGADOR])) {
                $regBeneficiario[$element->RUT_PAGADOR]['historico'][] = $elementFiltroBeneficiario;
            } else {
                if($element->RUT_PAGADOR != ""){
                    $regBeneficiario[$element->RUT_PAGADOR] = $elementFiltroBeneficiario;
                }
            }
        }

        $dataCiudadanoPension = [];
        if (count($regPagador) > 0) {
            $dataCiudadanoPension['pagador']['estado'] = true;
            $dataCiudadanoPension['pagador']['detalle'] = $regPagador;
        } else {
            $dataCiudadanoPension['pagador']['estado'] = false;
        }

        if (count($regReceptor) > 0) {
            $dataCiudadanoPension['receptor']['estado'] = true;
            $dataCiudadanoPension['receptor']['detalle'] = $regReceptor;
        } else {
            $dataCiudadanoPension['receptor']['estado'] = false;
        }

        if (count($regBeneficiario) > 0) {
            $dataCiudadanoPension['beneficiario']['estado'] = true;
            $dataCiudadanoPension['beneficiario']['detalle'] = $regBeneficiario;
        } else {
            $dataCiudadanoPension['beneficiario']['estado'] = false;
        }

//        $dataCiudadanoPension['beneficiario'] = $regBeneficiario;

//        return [
//            'pagador' => [
//                'estado' => (is_null($runCiudadano->PENSION_DESCUENTO) || $runCiudadano->PENSION_DESCUENTO == 0) ?
//                    false :
//                    true,
//                'detalle' => $regPagador,
//            ],
//            'receptor' => [
//                'estado' => (is_null($runCiudadano->PENSION_APORTE) || $runCiudadano->PENSION_APORTE == 0) ?
//                    false :
//                    true,
//                'detalle' => $regReceptor,
//            ],
//            'beneficiario' => $regBeneficiario
//        ];
        return $dataCiudadanoPension;
    }

    public static function getIsaprePorRUN($runCiudadano, $periodoRSHActual, $ciudadanoConsultado = null)
    {
        $dataCiudadanoIsapre = [];
        $dataCiudadanoExport = PersonasExport::where('per_vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('per_run', $runCiudadano)
            ->first();

        $dataCiudadanoIsapre['puede-realizar-solicitud-65'] = false;
        $dataCiudadanoIsapre['sub-tipo-solicitud-65'] = [];
        $dataCiudadanoIsapre['puede-realizar-solicitud-51'] = false;
        $dataCiudadanoIsapre['sub-tipo-solicitud-51'] = [];

        $dataCiudadanoIsapre['isapre-alto-costo'] = isset($dataCiudadanoExport->CALC_ISAPRECARGA_COSTOSO) && $dataCiudadanoExport->CALC_ISAPRECARGA_COSTOSO == 1 ? true : false;
        //------------------------------
        //---

        if ($dataCiudadanoIsapre['isapre-alto-costo'] == true) {
            $busqueda65 = DatosDeSolicitud::busquedaSolicitudexRunIntegrante($runCiudadano,
                Constante::TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD);
            $busqueda65 = DatosDeSolicitud::busquedaSolicitudexRunIntegrante($runCiudadano,
                Constante::TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD);
            $busqueda51 = DatosDeSolicitud::busquedaSolicitudexRunIntegrante($runCiudadano,
                Constante::TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD);

            if (count($busqueda65) == 0 || $busqueda65 == null) {
                if ($ciudadanoConsultado == true) {
                    $dataCiudadanoIsapre['puede-realizar-solicitud-65'] = true;
                    $dataCiudadanoIsapre['sub-tipo-solicitud-65'] = [1, 2];
                } elseif ($dataCiudadanoExport->PER_EDAD >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                    // si es mayor de edad subtipo 1
                    $dataCiudadanoIsapre['puede-realizar-solicitud-65'] = false;
                    $dataCiudadanoIsapre['sub-tipo-solicitud-65'] = [1];
                } elseif ($dataCiudadanoExport->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD) {
                    // si es menor de edad subtipo 2
                    $dataCiudadanoIsapre['puede-realizar-solicitud-65'] = true;
                    $dataCiudadanoIsapre['sub-tipo-solicitud-65'] = [2];
                }
            }
            if (count($busqueda51) == 0 || $busqueda51 == null) {
                if ($ciudadanoConsultado == true) {
                    // Ciudadano por quien se consulta
                    $dataCiudadanoIsapre['puede-realizar-solicitud-51'] = true;
                    $dataCiudadanoIsapre['sub-tipo-solicitud-51'] = [1, 2];
                } elseif ($dataCiudadanoExport->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD) {
                    // Menores de Edad
                    // $dataCiudadanoIsapre['sub-tipo-solicitud-51'] = [];
                    $dataCiudadanoIsapre['puede-realizar-solicitud-51'] = true;
                    $dataCiudadanoIsapre['sub-tipo-solicitud-51'] = [
                        1,
                        2
                    ];// "1 y revisar si aplica 2"; // revisar si tambien aplica para la 2.
                } elseif ($dataCiudadanoExport->PER_EDAD >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                    $dataCiudadanoIsapre['puede-realizar-solicitud-51'] = false;
                    $dataCiudadanoIsapre['sub-tipo-solicitud-51'] = [];
                }
            }
        }
        //------------------------------
        $query1 = DB::table('ever_super_salud_cotizantes')
            ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('run', $runCiudadano)
            ->where('periodo_rsh', $periodoRSHActual)
            ->select(DB::raw('1 as marca'));
        $query2 = DB::table('tbl_super_salud_cargas')
            ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('run_carga', $runCiudadano)
            ->where('periodo_rsh', $periodoRSHActual)
            ->select(DB::raw('2 as marca'));
        $cargaOCotizante = $query1->union($query2)->first();

        $ultimoPeriodoCargadoDelCiudadano = isset($periodoRSHActual) ? $periodoRSHActual : null;

        $ultimoPeriodoCargadoFila = DB::table('isapres_cotizan_meses_cargados')
            ->where('periodo_rsh', $ultimoPeriodoCargadoDelCiudadano)
            ->first();
        if (isset($cargaOCotizante->MARCA)) {
            switch ($cargaOCotizante->MARCA) {
                case 1:
                    $dataCiudadanoIsapreEloq = DB::table('ever_super_salud_cotizantes')
                        ->where('run', $runCiudadano)
                        ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                        ->orderBy('periodo_rsh', 'desc')
                        ->first();

                    if (isset($dataCiudadanoIsapreEloq->TIPO_TRABAJADOR)) {

                        $dataCiudadanoCargasEloq = DB::table('tbl_super_salud_cargas')
                            ->where('run_cotizante', $runCiudadano)
                            ->where('periodo_rsh', $ultimoPeriodoCargadoDelCiudadano)
                            ->get();

                        $dataCiudadanoIsapre['cotizante']['estado'] = true;
                        $dataCiudadanoIsapre['cotizante']['tipo-trabajador'] = $dataCiudadanoIsapreEloq->TIPO_TRABAJADOR;
                        $dataCiudadanoIsapre['cotizante']['periodo'] = $ultimoPeriodoCargadoDelCiudadano;
                        $dataCiudadanoIsapre['cotizante']['nombre-isapre'] = $dataCiudadanoIsapreEloq->ISAPRE_NOM;
                        $dataCiudadanoIsapre['cotizante']['codigo-plan'] = $dataCiudadanoIsapreEloq->IDENTIFICACION_PLAN;
                        $dataCiudadanoIsapre['cotizante']['valor-plan'] = $dataCiudadanoIsapreEloq->MONTO_PLAN;

                        $dataCiudadanoCargas = [];
                        foreach ($dataCiudadanoCargasEloq as $element) {
                            $elementFiltroCargaAsignacion = Utiles::getPersona($element->RUN_CARGA);
                            if($elementFiltroCargaAsignacion instanceof Personas) {
                                $elementFiltroCargaExport = PersonasExport::where('per_vigente',
                                    Constante::VIGENTE_1_VIGENTE)
                                    ->where('per_run', $element->RUN_CARGA)
                                    ->first();

                                $elementFiltroCarga['nombre'] = $elementFiltroCargaAsignacion->PER_NOMBRES . ' ' . $elementFiltroCargaAsignacion->PER_APELLIDOPATERNO . ' ' . $elementFiltroCargaAsignacion->PER_APELLIDOMATERNO;
                                $elementFiltroCarga['edad'] = Utiles::getEdad($elementFiltroCargaAsignacion->PER_FECHANACIMIENTO);
                                $elementFiltroCarga['isapre-alto-costo'] = isset($elementFiltroCargaExport->CALC_ISAPRECARGA_COSTOSO) && $elementFiltroCargaExport->CALC_ISAPRECARGA_COSTOSO == 1 ? true : false;

                                $dataCiudadanoCargas[$element->RUN_CARGA] = $elementFiltroCarga;
                            }
                        }
                        $dataCiudadanoIsapre['cotizante']['cargas'] = $dataCiudadanoCargas;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_1] = $dataCiudadanoIsapreEloq->RENTA_DEP_00;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_2] = $dataCiudadanoIsapreEloq->RENTA_DEP_01;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_3] = $dataCiudadanoIsapreEloq->RENTA_DEP_02;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_4] = $dataCiudadanoIsapreEloq->RENTA_DEP_03;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_5] = $dataCiudadanoIsapreEloq->RENTA_DEP_04;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_6] = $dataCiudadanoIsapreEloq->RENTA_DEP_05;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_8] = $dataCiudadanoIsapreEloq->RENTA_DEP_07;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_7] = $dataCiudadanoIsapreEloq->RENTA_DEP_06;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_9] = $dataCiudadanoIsapreEloq->RENTA_DEP_08;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_10] = $dataCiudadanoIsapreEloq->RENTA_DEP_09;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_11] = $dataCiudadanoIsapreEloq->RENTA_DEP_10;
                        $dataCiudadanoIsapre['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_12] = $dataCiudadanoIsapreEloq->RENTA_DEP_11;
                        $dataCiudadanoIsapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_1] = $dataCiudadanoIsapreEloq->RENTA_IND_00;
                        $dataCiudadanoIsapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_2] = $dataCiudadanoIsapreEloq->RENTA_IND_01;
                        $dataCiudadanoIsapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_3] = $dataCiudadanoIsapreEloq->RENTA_IND_02;
                        $dataCiudadanoIsapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_4] = $dataCiudadanoIsapreEloq->RENTA_IND_03;
                        $dataCiudadanoIsapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_5] = $dataCiudadanoIsapreEloq->RENTA_IND_04;
                        $dataCiudadanoIsapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_6] = $dataCiudadanoIsapreEloq->RENTA_IND_05;
                        $dataCiudadanoIsapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_7] = $dataCiudadanoIsapreEloq->RENTA_IND_06;
                        $dataCiudadanoIsapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_8] = $dataCiudadanoIsapreEloq->RENTA_IND_07;
                        $dataCiudadanoIsapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_9] = $dataCiudadanoIsapreEloq->RENTA_IND_08;
                        $dataCiudadanoIsapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_10] = $dataCiudadanoIsapreEloq->RENTA_IND_09;
                        $dataciudadanoisapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_11] = $dataCiudadanoIsapreEloq->RENTA_IND_10;
                        $dataCiudadanoIsapre['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_12] = $dataCiudadanoIsapreEloq->RENTA_IND_11;
                    } else {
                        $dataCiudadanoIsapre['cotizante']['estado'] = false;
                    }
                    $dataCiudadanoIsapre['carga']['estado'] = false;
                    break;
                case 2:
                    $dataCiudadanoIsapre['cotizante']['estado'] = false;
                    $dataCiudadanoIsapre['carga']['estado'] = true;
                    $dataCiudadanoCargasEloq = DB::table('tbl_super_salud_cargas tssc')
                        ->where('run_carga', $runCiudadano)
                        //->where('periodo_rsh', $ultimoPeriodoCargadoDelCiudadano)
                        ->orderBy('periodo_rsh', 'desc')
                        ->first();
                    $dataCotizanteEloq = DB::table('ever_super_salud_cotizantes essc')
                        ->where('run', $dataCiudadanoCargasEloq->RUN_COTIZANTE)
                        ->where('periodo_rsh', $ultimoPeriodoCargadoDelCiudadano)
                        ->where('vigente', 1)
                        ->where('calculoactivo', 1)
                        ->first();
                    if($dataCotizanteEloq) {
                        $dataCotizanteCargasEloq = DB::table('tbl_super_salud_cargas')
                            ->where('run_cotizante', $dataCiudadanoCargasEloq->RUN_COTIZANTE)
                            ->where('periodo_rsh', $ultimoPeriodoCargadoDelCiudadano)
                            ->where('run_carga',$runCiudadano)
                            ->get();

                        $datosCotizante = self::getPersona($dataCiudadanoCargasEloq->RUN_COTIZANTE);

                        $dataCiudadanoIsapre['carga']['fecha-nacimiento'] = $dataCiudadanoCargasEloq->FECHA_NAC;
                        $dataCiudadanoIsapre['carga']['sexo'] = $dataCiudadanoCargasEloq->SEXO;
                        $dataCiudadanoIsapre['carga']['tipo-carga'] = $dataCiudadanoCargasEloq->TIPO_CARGA;
                        $dataCiudadanoIsapre['carga']['cotizante'] = [];
                        $dataCiudadanoIsapre['carga']['cotizante']['run'] = $datosCotizante->PER_RUN;
                        $dataCiudadanoIsapre['carga']['cotizante']['nombres'] = $datosCotizante->PER_NOMBRES . ' ' . $datosCotizante->PER_APELLIDOPATERNO . ' ' . $datosCotizante->PER_APELLIDOMATERNO;
                        $dataCiudadanoIsapre['carga']['cotizante']['tipo-trabajador'] = $dataCotizanteEloq->TIPO_TRABAJADOR;
                        $dataCiudadanoIsapre['carga']['cotizante']['periodo'] = $ultimoPeriodoCargadoDelCiudadano;
                        $dataCiudadanoIsapre['carga']['cotizante']['nombre-isapre'] = $dataCotizanteEloq->ISAPRE_NOM;
                        $dataCiudadanoIsapre['carga']['cotizante']['codigo-plan'] = $dataCotizanteEloq->IDENTIFICACION_PLAN;
                        $dataCiudadanoIsapre['carga']['cotizante']['valor-plan'] = $dataCotizanteEloq->MONTO_PLAN;
                        $dataCiudadanoCargas = [];
                        foreach ($dataCotizanteCargasEloq as $element) {
                            $elementFiltroCargaAsignacion = Utiles::getPersona($element->RUN_CARGA);
                            $elementFiltroCarga['nombre'] = $elementFiltroCargaAsignacion->PER_NOMBRES . ' ' . $elementFiltroCargaAsignacion->PER_APELLIDOPATERNO . ' ' . $elementFiltroCargaAsignacion->PER_APELLIDOMATERNO;
                            $elementFiltroCarga['edad'] = Utiles::getEdad($elementFiltroCargaAsignacion->PER_FECHANACIMIENTO);
                            $dataCiudadanoCargas[$element->RUN_CARGA] = $elementFiltroCarga;
                        }
                        $dataCiudadanoIsapre['carga']['cotizante']['cargas'] = $dataCiudadanoCargas;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_1] = $dataCotizanteEloq->RENTA_DEP_00;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_2] = $dataCotizanteEloq->RENTA_DEP_01;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_3] = $dataCotizanteEloq->RENTA_DEP_02;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_4] = $dataCotizanteEloq->RENTA_DEP_03;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_5] = $dataCotizanteEloq->RENTA_DEP_04;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_6] = $dataCotizanteEloq->RENTA_DEP_05;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_7] = $dataCotizanteEloq->RENTA_DEP_06;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_8] = $dataCotizanteEloq->RENTA_DEP_07;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_9] = $dataCotizanteEloq->RENTA_DEP_08;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_10] = $dataCotizanteEloq->RENTA_DEP_09;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_11] = $dataCotizanteEloq->RENTA_DEP_10;
                        $dataCiudadanoIsapre['carga']['cotizante']['dependiente'][$ultimoPeriodoCargadoFila->MES_12] = $dataCotizanteEloq->RENTA_DEP_11;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_1] = $dataCotizanteEloq->RENTA_IND_00;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_2] = $dataCotizanteEloq->RENTA_IND_01;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_3] = $dataCotizanteEloq->RENTA_IND_02;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_4] = $dataCotizanteEloq->RENTA_IND_03;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_5] = $dataCotizanteEloq->RENTA_IND_04;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_6] = $dataCotizanteEloq->RENTA_IND_05;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_7] = $dataCotizanteEloq->RENTA_IND_06;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_8] = $dataCotizanteEloq->RENTA_IND_07;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_9] = $dataCotizanteEloq->RENTA_IND_08;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_10] = $dataCotizanteEloq->RENTA_IND_09;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_11] = $dataCotizanteEloq->RENTA_IND_10;
                        $dataCiudadanoIsapre['carga']['cotizante']['independiente'][$ultimoPeriodoCargadoFila->MES_12] = $dataCotizanteEloq->RENTA_IND_11;
                        break;
                    }
                    else {
                        $dataCiudadanoIsapre['cotizante']['estado'] = false;
                        $dataCiudadanoIsapre['carga']['estado'] = false;
                    }
            }
        } else {
            $dataCiudadanoIsapre['cotizante']['estado'] = false;
            $dataCiudadanoIsapre['carga']['estado'] = false;
        }

        return $dataCiudadanoIsapre;
    }

    public static function getBienRaizPorRUN($runCiudadano)
    {
        $dataPropiedadesCiudadanoEloq = VWPropiedad::where('rut', $runCiudadano)
            ->where('vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->get();
        $regPropiedades = [];
        foreach ($dataPropiedadesCiudadanoEloq as $element) {
            $elementFiltroPropiedad['rol-comuna'] = $element->ROL_COMUNA;
            $elementFiltroPropiedad['rol-manzana'] = $element->ROL_MANZANA;
            $elementFiltroPropiedad['rol-predio'] = $element->ROL_PREDIO;
            $elementFiltroPropiedad['direccion-ofuscada'] = $element->DIRECCION_OFUSCADA;
            $elementFiltroPropiedad['rol'] = $element->ROL;
            $elementFiltroPropiedad['tasacion'] = $element->AVALUO_TOTAL;
            $elementFiltroPropiedad['fecha-fuente'] = $element->FECHAFUENTE;
            $elementFiltroPropiedad['run-juridico'] = $element->R_JURIDICO;
            $elementFiltroPropiedad['run-sociedad'] = $element->RUN_SOCIEDAD;
            $elementFiltroPropiedad['run-sociedad-ofuscado'] = $element->RUN_SOCIEDAD;
            $regPropiedades[$element->ROL] = $elementFiltroPropiedad;
        }
        return $regPropiedades;
    }

    /**
     * Retorna el nombre del trámite por $idTipoSolicitud y/o $idSubTipoSolicitud
     */
    public static function getNombreTipoYSubtipoSolicitud(
        $idTipoSolicitud,
        $idSubTipoSolicitud = null,
        $separador = ' - '
    ) {
        $lstTipoSolicitud = TipoSolicitud::where('vigente', 1)
            ->pluck('TIPO_SOLICITUD', 'ID_TIPO_SOLICITUD');
        $lstSubTipoSolicitud = SubTipoSolicitud::where('vigente', 1)
            ->where('id_tipo_solicitud', $idTipoSolicitud)
            ->pluck('NOMBRE_SUB_TIPO_SOLICITUD', 'ID_SUB_TIPO_SOLICITUD');
        $textoNombreTipoSolictud = isset($lstTipoSolicitud[$idTipoSolicitud]) ?
            $lstTipoSolicitud[$idTipoSolicitud] :
            'Tipo de solicitud no definido';
        $textoNombreSubTipoSolicitud = isset($lstSubTipoSolicitud[$idSubTipoSolicitud]) ?
            $lstSubTipoSolicitud[$idSubTipoSolicitud] :
            null;
        return is_null($textoNombreSubTipoSolicitud) ?
            $textoNombreTipoSolictud :
            $textoNombreTipoSolictud . $separador . $textoNombreSubTipoSolicitud;
    }

    public static function a_romano($numero)
    {
        $tabla = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );
        $retorno = '';
        while ($numero > 0) {
            foreach ($tabla as $rom => $arb) {
                if ($numero >= $arb) {
                    $numero -= $arb;
                    $retorno .= $rom;
                    break;
                }
            }
        }
        return $retorno;
    }

    public static function verificaRunTieneGrupoFamiliar($runIntegrante)
    {
        $tieneGrupoFamiliar = (object)[ // se inicializa
            'ESTADO' => '0',
            'MSG' => 'No posee grupo Grupo Familiar'
        ];
        $persona = Personas::where('per_run', $runIntegrante)
            ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
            ->first();
        if (!empty($persona)) {
            $beneficiario = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $persona->PER_ID)
                ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->first();
            if (!empty($beneficiario)) {
                $grupoFamiliar = GrupoFamiliar::where('grupfami_id', $beneficiario->BNFGRUPFAMI_GRUPOFAMILIAR)
                    ->where('grupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                    ->where('grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                    ->first();
                if (!empty($grupoFamiliar)) {
                    $tieneGrupoFamiliar->ESTADO = '1';
                    $tieneGrupoFamiliar->MSG = 'Grupo Familiar válido';
                }
            }
        }
        return $tieneGrupoFamiliar;
    }

    public static function verificaRunesTienenMismoGrupoFamiliar($runIntegrante1, $runIntegrante2)
    {
        $tienenMismoGrupoFamiliar = (object)[
            'ESTADO' => '0',
            'MSG' => 'No pertenecen al mismo Grupo familiar',
            'NUEVOFOLIO' => null
        ];
        $persona1 = Personas::where('per_run', $runIntegrante1)
            ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
            ->first();
        $persona2 = Personas::where('per_run', $runIntegrante2)
            ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
            ->first();
        if (!empty($persona1) && !empty($persona2)) {
            $beneficiario1 = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $persona1->PER_ID)
                ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->first();
            $beneficiario2 = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $persona2->PER_ID)
                ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->first();
            if (!empty($beneficiario1) && !empty($beneficiario2)) {
                if ($beneficiario1->BNFGRUPFAMI_GRUPOFAMILIAR == $beneficiario2->BNFGRUPFAMI_GRUPOFAMILIAR) {
                    $grupoFamiliar = GrupoFamiliar::where('grupfami_id', $beneficiario1->BNFGRUPFAMI_GRUPOFAMILIAR)
                        ->where('grupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                        ->where('grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                        ->first();
                    if (!empty($grupoFamiliar)) {
                        $tienenMismoGrupoFamiliar->ESTADO = '1';
                        $tienenMismoGrupoFamiliar->MSG = 'Pertenecen al mismo Grupo Familiar';
                        $tienenMismoGrupoFamiliar->NUEVOFOLIO = $grupoFamiliar->GRUPFAMI_NUEVOFOLIO;
                    }
                }
            } elseif (!(!empty($beneficiario1) || !empty($beneficiario2))) {
                $tienenMismoGrupoFamiliar->ESTADO = '-1';
                $tienenMismoGrupoFamiliar->MSG = 'Ambos no tiene Grupo familiar';
                $tienenMismoGrupoFamiliar->NUEVOFOLIO = null;
            }
        }
        return $tienenMismoGrupoFamiliar;
    }

    public static function busquedaPadres($runCiudadano)
    {
        $data = Personas::where('per_run', $runCiudadano)
            ->first();
        $info['padre'] = $data->PER_RUN_PADRE;
        $info['madre'] = $data->PER_RUN_MADRE;
        return ($info);
    }

    public static function quitaPuntos($numero)
    {
        try {
            return str_replace(['.', ' '], '', $numero);
        } catch (Exception $ex) {
            return null;
        }
    }

    public static function remplazaComaPorPunto($numero)
    {
        try {
            $numero = str_replace('.', '', $numero);
            return str_replace(',', '.', $numero);
        } catch (Exception $ex) {
            return null;
        }
    }

    public static function cleanNombreVariables2($stringConCaracteresEspeciales)
    {
        $cambiaEnie = str_replace(['ñ', 'Ñ'], 'n', $stringConCaracteresEspeciales);

        $cambiaEnie = str_replace(['@', '/', '?', '-', ';', '´'], '_', $cambiaEnie);
        return preg_replace('/[^A-Za-z0-9.\_]/', '', $cambiaEnie);
    }

    /*
     * Devuelve la instancia de un objeto en particular en la pila de llamada de la funcion actual
     */
    public static function getCallerInstance(array $options = [])
    {
        $defaults = [
            'className' => null,
            // clase de la instancia a buscar
            'limitTrace' => 0,
            // limita la pila de llamadas a un numero determinado de niveles (ahorro de memoria)
            'startTraceOn' => 0,
            // inicia la busqueda en un nivel en particular
            'ignoreArgs' => true,
            // ignora los argumentos de las funciones en la pila de llamadas (ahorro de memoria)
        ];
        $options = array_replace($defaults, $options);
        $optionsBackTrace = DEBUG_BACKTRACE_PROVIDE_OBJECT | (($options['ignoreArgs']) ? DEBUG_BACKTRACE_IGNORE_ARGS : 0);
        $trace = debug_backtrace($optionsBackTrace, $options['limitTrace']);
        $instance = null;
        $start = 2 + $options['startTraceOn'];  // 2: comienza a partir de un nivel debajo de la instancia que llamó a Utiles::getCallerInstance()
        for ($i = $start; $i < count($trace); $i++) {
            if (!is_null($options['className']) && isset($trace[$i]['object']) && $trace[$i]['object'] instanceof $options['className']) {
                $instance = $trace[$i]['object'];
                break;
            }
        }
        return $instance;
    }

    /*
     * Cambia un termino en camelCase a kebab-case (conocido tambien como dash-case)
     * (No son brochetas de camello ;-) )
     */
    public static function camelToKebab($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('-', $ret);
    }

    public static function obtenerCalleExport($export)
    {
        if ($export->GRUPFAMI_C_CALLE >= Constante::ID_CALLE_11011_CORTE_GRUPO_FAMILIAR) {
            $calle_aux = Calle::where('ID_LOCALIDAD', $export->GRUPFAMI_LOCALIDAD)
                ->where('ID_CALLE', $export->GRUPFAMI_C_CALLE)
                ->where('COMUNA_INE', $export->GRUPFAMI_COMUNAINE)
                ->first();
            if (!empty($calle_aux)) {
                $calle = $calle_aux['NOMBRE'];
            } else {
                $calle_aux = Calle::where('ID_CALLE', $export->GRUPFAMI_C_CALLE)
                    ->where('COMUNA_INE', $export->GRUPFAMI_COMUNAINE)
                    ->first();
                $calle = $calle_aux['NOMBRE'];
            }
            if ((!empty($calle_aux)) < 1) {
                $calle = $export->GRUPFAMI_N_CALLE;
            }
        } elseif ($export->GRUPFAMI_N_CALLE) {
            $calle = $export->GRUPFAMI_N_CALLE;
        } else {
            $calle = "Calle no encontrada";
        }
        return $calle;
    }

    public static function obtenerFuentes()
    {
        $cacheKey = 'fuente-ingreso';
        $fuenteIngreso = [];
        try {
            if (Cache::has($cacheKey)) {
                $fuenteIngreso = Cache::get($cacheKey);
            } else {
                $metodo = 'GET';
                $url = '/fuentes';
                $dataRest = Utiles::consumoConGuzzle($metodo, $url, null);
                $fuenteIngreso = $dataRest->data;
                Cache::put($cacheKey, $fuenteIngreso, Constante::CACHE_API_MINUTOS);
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
        return $fuenteIngreso;
    }

    public static function RemoverEspecialChar($str)
    {
        $res = str_replace(array(
            '°',
            '*',
            '|',
            '\'',
            '"',
            ',',
            ';',
            '!',
            '$',
            '¨',
            '´',
            '+',
            '%',
            '[',
            ']',
            '(',
            ')',
            '{',
            '}',
            '?',
            '¿',
            '¡',
            '@'
        ), '', $str);
        return $res;
    }

    public static function evaluarOrigen($s)
    {
        $origen = null;
        if (in_array($s->ORIGEN_REGISTRO, Constante::$ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL_VALIDOS)) {
            $origen = "Plataforma Municipal";
        } elseif (in_array($s->ORIGEN_REGISTRO, Constante::$ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA_VALIDOS)) {
            $origen = "Plataforma Ciudadana";
        } elseif (in_array($s->ORIGEN_REGISTRO, Constante::$ORIGEN_REGISTRO_3_PLATAFORMA_CLASE_MEDIA_PROTEGIDA_VALIDOS)) {
            $origen = "Plataforma Red Clase Media";
        }
        if ($origen == null) {
            if ($s->CANAL == Constante::CANAL_1_SAVE_MUNICIPAL) {
                $origen = "Plataforma Municipal";
            }
            if (($s->CANAL == Constante::CANAL_2_SAVE_CLAVE_UNICA) ||
                ($s->CANAL == Constante::CANAL_3_SAVE_RUN_CIUDADANO)
            ) {
                $origen = "Plataforma Ciudadana";
            }
            if ($s->CANAL == Constante::CANAL_4_SAVE_CHILE_ATIENDE_EJECUTOR) {
                $origen = "Plataforma Municipal - Chile Atiende Ejecutor";
            }
        }
        return ($origen);
    }

    public static function mensajeTramoIngreso($promedio = 0, $tramosIngresos)
    {
        $promedioFormateado = number_format($promedio,0,".","");
        $data = $tramosIngresos;
        $mensaje = "";

        if ($promedioFormateado < 50000){
            Log::info("entra aca11");
            return 'menor que $50.000';
        }elseif ($promedioFormateado> 50000 && $promedioFormateado < 100000){
            Log::info("entra aca12");
            return 'entre $50.000 y $100.000';
        }

        for ($i = 1; $i <= count($data) - 1; $i++) {
            if ($promedioFormateado > intval($data[$i]['TING_INICIAL']) && $promedioFormateado < intval($data[$i]['TING_FINAL'])) {
                $mensaje = $data[$i]['TING_DESCRIPCION'];
                break;
            }
        }

        return strtolower($mensaje);
    }

    public static function obtenerTramosIngresos(){
        $consultaTramos = DB::table('AB_TRAMO_INGRESOS')
            ->get();
        return json_decode(json_encode($consultaTramos), true);
    }

//    public static function formatearMontosMensualesSinString($integrante)
//    {
//        for ($mes = 1; $mes <= 12; $mes++) {
//            $data['Y1_M' . $mes] = str_replace('.', '', $integrante['y1-m' . $mes]);
//            $data['Y2_M' . $mes] = str_replace('.', '', $integrante['y2-m' . $mes]);
//        }
//        return $data;
//    }

    public static function formatearDireccion($data)
    {
        $folio = $data['grupfami-nuevo-folio'] ?? null;
        $comunaIne = $data['grupfami-comuna-ine'] ?? null;

        $partes = [
            Calle::getNombreCalle($folio),
            !empty($data['grupfami-num-domicilio']) ? '#' . $data['grupfami-num-domicilio'] : null,
            !empty($data['grupfami-dpto']) ? 'Dpto: ' . $data['grupfami-dpto'] : null,
            !empty($data['grupfami-block']) ? 'Block: ' . $data['grupfami-block'] : null,
            !empty($data['grupfami-sitio']) ? 'Sitio: ' . $data['grupfami-sitio'] : null,
            !empty($data['grupfami-casa']) ? 'Casa: ' . $data['grupfami-casa'] : null,
            ($ah = AgrupacionHabitacional::getNombreAH($folio)) ? 'Agrupación Habitacional: ' . $ah : null,
            ($loc = LocacionLocalidad::getNombreLocalidad($folio)) ? 'Localidad: ' . $loc : null,
            LocacionComuna::getNombreComuna($comunaIne),
        ];

        return implode(', ', array_filter($partes));
    }

}
