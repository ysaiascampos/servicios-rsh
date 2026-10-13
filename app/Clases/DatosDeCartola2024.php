<?php
namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionEjecucion;
use App\Exceptions\ExcepcionValidacion;
use Exception;
use Throwable;
use App\Models\LocacionComuna;
use App\Models\SupervisionRSHExport;
use App\Utiles;
use Illuminate\Support\Facades\Log;
use PDF;
use DB;
use App\Models\Usuario;
use App\Models\UsuarioComuna;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Sequence;



class DatosDeCartola2024
{
    private $runCiudadano;
    private $vista;
    private $ip;
    private $usuario;
    private $runUsuario;
    private $comunaUsuario;
    private $descripcionPerfil;
    private $data;
    private $canal;
    private $validacionCartola;

    public $personaExport;
    public $grupoFamiliarExport;
    public $integrantesExport;
    public $grupoFamiliaraAsignacion;

    private $nombrePDF;
    private $options;
    private $entendiendo;
    private $formato;
    private $codigoValidacion;

public function crearCartola($canal, $runCiudadano, $entendiendo = true, $ip = null, $runUsuario = null, $validacionCartola = false, $formato = 'download')
{
    $this->runCiudadano = $runCiudadano;
    $this->ip = $ip;
    $this->runUsuario = $runUsuario;
    $this->validacionCartola = $validacionCartola;
    $this->entendiendo = $entendiendo;
    $this->options = [
        'margin-top'    => '40mm',
    ];

    $this->formato = $formato;

    // Aquí continúa el resto del código que no cambia

    $canalTexto = strtolower(trim($canal));
    $this->descripcionPerfil = "desde un canal no identificado.";

    switch ($canalTexto) {
        case 'municipal':
            $this->canal = Constante::CANAL_1_MUNICIPAL;

            $oResults = Usuario::where('RUT_USUARIO', $this->runUsuario)->get();
            if ($oResults->count() > 0) {
                $this->usuario = $oResults->first();
            }
            $oResults = UsuarioComuna::select('COM_ID')
                ->where('RUT_USUARIO', $this->runUsuario)->get();
            if ($oResults->count() > 0) {
                $comuna = $oResults->first()->COM_ID;
                $this->comunaUsuario = LocacionComuna::findByComunaIne($comuna)[0];

                switch ($this->usuario->ID_PERFIL) {
                    case Constante::PERFIL_2_ADMINISTRADOR_NACIONAL:
                    case Constante::PERFIL_3_CONSULTA_NACIONAL:
                    case Constante::PERFIL_6_COORDINADOR_NACIONAL:
                    case Constante::PERFIL_18_NIVEL_CENTRAL:
                        $this->descripcionPerfil = "desde el Ministerio de Desarrollo Social y Familia.";
                        break;
                    case Constante::PERFIL_14_CHILE_ATIENDE:
                        $this->descripcionPerfil = "desde Chile Atiende.";
                        break;
                    case Constante::PERFIL_29_FUNFA:
                        $this->canal = Constante::CANAL_6_FUNFA;
                        $this->descripcionPerfil = "desde la Fundación de la Familia.";
                        break;
                    default:
                        $this->descripcionPerfil = "desde la I.Municipalidad de " . Utiles::capitalizaTexto($this->comunaUsuario->DESCRIPCION . ".");
                        break;
                }
            }
            break;

        case 'claveunica':
        case 'ciudadano':
            $this->canal = Constante::CANAL_2_CLAVE_UNICA;
            $this->descripcionPerfil = "Clave Unica";
            break;

        case 'funfa':
            $this->canal = Constante::CANAL_6_FUNFA;
            $this->descripcionPerfil = "FUNFA";
            break;

        case 'chileatiende':
            $this->canal = Constante::CANAL_4_CHILE_ATIENDE_EJECUTOR;
            $this->descripcionPerfil = "Chile Atiende Ejecutor";
            break;

        case 'run_ciudadano':
            $this->canal = Constante::CANAL_3_RUN_CIUDADANO;
            $this->descripcionPerfil = "Run Ciudadano";
            break;

        case 'supervision':
            $this->canal = Constante::CANAL_5_SUPERVISION;
            $this->descripcionPerfil = "Supervision";
            break;

        case 'totem':
            $this->canal = Constante::CANAL_7_TOTEM;
            $this->descripcionPerfil = "Totem";
            break;

        default:
            if (is_numeric($canal)) {
                $canalBD = \App\Models\Canal::where('ID', $canal)->first();
            } else {
                $canalBD = \App\Models\Canal::where('NOMBRE', strtoupper($canal))->first();
            }
            if (!$canalBD) {
                throw new \Exception("Error: el canal '{$canal}' no existe en la base de datos.");
            }
            $this->canal = is_numeric($canal) ? (int) $canalBD->ID : (int) $canalBD->ID;
            $this->descripcionPerfil = $canalBD->NOMBRE;
            break;
    }

    $this->cargarDataCartola();

    return $this->generarPdf();
}
    private function cargarDataCartola()
    {
        if($this->formato=='html'){
            $this->vista = 'cartolas.ciudadano-main-encuesta-cartola-html';
        } else {
            $this->vista = 'cartolas.ciudadano-main-encuesta-cartola';

        }

        $error = false;
        try {
            $dataCartola = $this->getCartolabyRun($this->runCiudadano);
            $this->personaExport = $dataCartola['persona-export'];
            $this->grupoFamiliarExport = $dataCartola['grupo-familiar-export'];
            $this->integrantesExport = $dataCartola['integrantes-export'];
            $this->grupoFamiliaraAsignacion = $dataCartola['grupo-familiar-asignacion'];
            $this->codigoValidacion = $dataCartola['codigo-validacion'];
            if($this->formato!='html')
            $this->options = [
                'page-size'     => 'letter',
                'margin-top'    => '15mm',
                'margin-bottom'    => '15mm',
                //'footer-right' => 'Hoja [page] de [toPage]',
                'footer-html' => view()->make('cartolas.footer',['descripcionPerfil'=> $this->descripcionPerfil,
                    'comunaUsuario' => $this->comunaUsuario,
                'codigoValidacion' => $this->codigoValidacion])->render(),
            ];


        } catch (ExcepcionValidacion $ex) {
            $error = true;
        } catch (ErrorException $ex) {
            $error = true;
        } catch (Throwable $ex) {
            try {
                $error = true;
            } catch (Exception $e) {

            }
            if($ex->getCode()!= Constante::STATUS_200_SUCCESS_OK){
                $mensaje = $ex->getMessage();
                if(isset($mensaje)){
                    throw new ExcepcionValidacion($mensaje,
                        $ex->getCode());
                } else {
                    throw new ExcepcionEjecucion('Error al consumir el servicio ruta no encontrada' ,
                        Constante::ERR_EJECUCION_RUTA_NO_ENCONTRADA);
                }
            }
        } catch (Exception $ex) {
            $error = true;
        }
        $this->vistaCartola = 't2a';
        if($error){
            Log::error("Ha ocurrido un problema al obtener cartola del Ciudadano RUN ". $this->runCiudadano.
                " Detalles del error  -> ". $ex);

            throw $ex;
            //$this->errorCartola();
        } else {
            $nuevoFolio = isset($this->grupoFamiliarExport['grupfami-nuevo-folio'])?$this->grupoFamiliarExport['grupfami-nuevo-folio']:0;
            $this->nombrePDF = 'cartola_' . $nuevoFolio. '_' . date('Y-m-d-His') . '.pdf';

            $this->data = ['vistaCartola'=> $this->vistaCartola,
                'personaExport'=> $this->personaExport,
                'grupoFamiliarExport'=> $this->grupoFamiliarExport,
                'integrantesExport'=> $this->integrantesExport,
                'grupoFamiliaraAsignacion'=> $this->grupoFamiliaraAsignacion,
                'descripcionPerfil'=> $this->descripcionPerfil,
                'comunaUsuario' => $this->comunaUsuario,
                'entendiendo' => $this->entendiendo,
                'formato'=> 'html'];
        }
    }

    private function getCartolabyRun($run)
    {
        $dataHeadersArray = [
        'getRealIP' => $this->ip,
        'usuario' => $this->runUsuario,
        'entendiendo' => $this->entendiendo];
        $metodo = 'GET';
        $url = '/cartola-rsh-api/'.$this->canal.'/' . $run;
        $dataRest = Utiles::consumoConGuzzleMunicipal($metodo, $url, null,$dataHeadersArray);
        if($dataRest->statusCode != Constante::STATUS_200_SUCCESS_OK){
            if(isset($dataRest->data["errors"])) {
                if(is_array($dataRest->data["errors"])) {
                    foreach ($dataRest->data["errors"] as $error) {
                        if (is_array($error)) {
                            foreach ($error as $err) {
                                throw new ExcepcionValidacion($err,
                                    $dataRest->statusCode);
                            }
                        } else {
                            throw new ExcepcionValidacion(json_encode($dataRest->data["errors"]),
                                $dataRest->statusCode);
                        }
                    }
                }else {
                    throw new ExcepcionValidacion(json_encode($dataRest->data["errors"]),
                        $dataRest->statusCode);
                }
            } elseif (isset($dataRest->data["message"])){
                throw new ExcepcionValidacion($dataRest->data["message"],
                    $dataRest->statusCode);
            } else {
                throw new ExcepcionEjecucion('Error al consumir el servicio. ' . $url,
                    Constante::ERR_EJECUCION_RUTA_NO_ENCONTRADA);
            }
        }
        $data = $dataRest->data;
        $resultadoCodigo = $this->codigoCartola($data);

        $dataRest->data['codigo-validacion'] = $resultadoCodigo['codigo']??null;
        return $dataRest->data;
    }

    public function errorCartola()
    {
        $this->vista = 'cartolas.cartola-error';
        if(isset($this->data)){
            $this->data = ['titulo' => 'Cartola Hogar',
                           'error' => true,
                           'mensaje' => "Cartola no valida no se pudo cargar, por favor intentelo más tarde"
                           ];
        }else{
            $this->data = ['titulo' => 'Cartola Hogar',
                'error' => true];
        }
        return $this->generarPdf();
    }
    private function generarPdf()
    {
        $nombre = $this->nombrePDF;
        //return view()->make($this->vista,$this->data)->render();
        $pdf = PDF::loadView($this->vista, $this->data)
            ->setOptions($this->options);
        $response = '';
        if($this->formato == 'base64'){
            $xlsData = base64_encode($pdf->output());
            $response = '{"estado":1,"mensaje":"Cartola emitida correctamente.","contenido":"' . $xlsData . '"}';
        } else if($this->formato == 'html'){
            $xlsData = base64_encode(view()->make($this->vista,$this->data)->render());
            $response = '{"estado":1,"mensaje":"Cartola emitida correctamente.","contenido":"' . $xlsData . '"}';
        } else {
            $response = $pdf->download($nombre);
        }
        return $response;
    }

    public static function cacheCartola($runCiudadano){
        $key = 'Cartola-' . $runCiudadano;
        $minutos = 150;
        $horas = $minutos/60;
        $numeroCartolas = 30;
        if (Cache::has($key)) {
            $valorKey = Cache::get($key);
            if ($valorKey > $numeroCartolas) {
                throw ExcepcionValidacion::new("Ha superado el limite de consultas por el mismo RUN ".$runCiudadano."
                , espere ".$horas." horas para volver a consultar.")
                    ->setTitular($runCiudadano);
            } elseif ($valorKey < $numeroCartolas) {
                Cache::increment($key);
            } else {
                Cache::put($key, 99, now()->addMinutes($minutos));
            }
        } else {
            Cache::put($key, 1, now()->addMinutes($minutos));
        }
    }
    private function generarQR()
    {
        $this->idLog = (int)Sequence::nextValue('LOG_CARTOLA_ID');
        $fechaHora = date('Y-m-d H:i:s');
        $runSolicitante = $this->runCiudadano;
        if(isset($this->runUsuario)){
            $runSolicitante = $this->runUsuario;
        }
        $hashMd5 = $this->validacionCartola;
        if(!$this->validacionCartola){
            $hashMd5 = base64_encode($this->idLog.'&'.$fechaHora.'&'.$this->runCiudadano.'&'.$runSolicitante.'&'.$this->canal.'&'.$this->ip);
        }

        //CODIGO QR
        //TODO CAMBIAR RUTA DINAMICA POR RSHMUNICIPAL
        $qrcode = base64_encode(QrCode::format('png')->size(100)->generate(asset('/api/validacartola').'/'.$hashMd5));

        return $qrcode;
    }

    public function validacionCartolaQr($hash)
    {
        if (!isset($hash) && !$hash) {
            //TODOC CREAR PANTALLA ERROR 404
            Log::error("Ha ocurrido un problema en el hash al obtener cartola del Ciudadano.");
            return $this->errorCartola();
        }
        $error = false;
        try {
            $hashDecode =base64_decode($hash);
            $arrayHash= explode('&', $hashDecode);
            $logCartola = DB::connection('oracle_export')
                ->select("SELECT * FROM AB_LOG_CARTOLA WHERE ID_LOG =".$arrayHash[0]);
            if($arrayHash[4] =='ciudadano'){
                $this->canal = Constante::CANAL_2_CLAVE_UNICA;
            } else {
                $this->canal = Constante::CANAL_1_MUNICIPAL;
            }
            $novalida=false;
            if(isset($logCartola[0])
                && isset($arrayHash[1])
                && $arrayHash[2] == $logCartola[0]->RUN_CONSULTADO
                && $arrayHash[3] == $logCartola[0]->USUARIO
                && $this->canal == $logCartola[0]->CANAL
                && $arrayHash[5] == $logCartola[0]->IP){
                //TODO AGREGAR VALIDACION FECHA GENERADA SEA IGUAL AL MES DE LA FECHA ACTUALL
                if (date('m') === date('m', strtotime($arrayHash[1]))) {
                    $result = $this->crearCartola($arrayHash[4],
                        $logCartola[0]->RUN_CONSULTADO,
                        $logCartola[0]->IP,
                        $logCartola[0]->USUARIO,
                        $hash);
                }else{
                    $novalida = true;
                }

            }else{
                //TODO AGREGAR VISTA CON MENSAJE DE CARTOLA NO VALIDA
                $novalida = true;
            }
            if($novalida){
                $this->data['mensaje'] = "Cartola no válida";
                $result = $this->errorCartola();
            }
        } catch (\Throwable $ex) {
            $error = true;
        }
        if($error){
            Log::error("Ha ocurrido un problema al obtener cartola del Ciudadano. Detalles del error  -> ". $ex);
            $result = $this->errorCartola();
        }
        return $result;

    }
    private function codigoCartola($data)
    {
        $personaExport  = $data["persona-export"];
        $runCiudadano = $personaExport["run"];
        $nombresEmisor  = $personaExport["nombres"]." ".
            $personaExport["apellido-paterno"]." ".$personaExport["apellido-materno"];
        $grupoFamiliarAsignacion  = $data["grupo-familiar-asignacion"];
        $comuna = $grupoFamiliarAsignacion['comuna'];
        $grupoFamiliarExport  = $data["grupo-familiar-export"];
        $tramo = $grupoFamiliarExport['calc-quintil-validacion-2'];
        $dataBody = [
            'run-emisor' => $runCiudadano,
            'nombres-emisor' => $nombresEmisor,
            'comuna' => $comuna,
            'tramo' => $tramo
        ];
        $apiGenerarCodigoCartola = new ApiGenerarCodigoCartola;
        $resultadoCodigo = $apiGenerarCodigoCartola->generarCodigo($dataBody);
        return $resultadoCodigo;
    }

}
