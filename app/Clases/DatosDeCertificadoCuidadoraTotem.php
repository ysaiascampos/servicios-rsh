<?php
namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Models\LogCertCuidadorExport;
use App\Models\LocacionComuna;
use App\Models\PersonasExport;
use App\Models\QrCuidadoras;
use App\Models\TramoQuintil;
use App\Utiles;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;
use App\Clases\DebugTrait;
use PDF;
use stdClass;
use DB;
use App\Models\Usuario;
use App\Models\UsuarioComuna;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Sequence;



class DatosDeCertificadoCuidadoraTotem
{
    private $runCiudadano;
    private $vista;
    private $ip;
    private $usuario;
    private $runUsuario;
    private $descripcionPerfil;
    private $data;
    private $canal;

    public $ciudadano;
    public $ciudadanoExport;
    public $cuidador;


    private $comunaUsuario;
    public $qrcode;

    public function crearCertificado($portal, $runCiudadano, $ip = null, $runUsuario = null, $comunaUsuario = null)
    {
        $this->runCiudadano = $runCiudadano;
        $this->portal = $portal;
        $this->ip = $ip;
        $this->runUsuario = $runUsuario;
        $this->comunaUsuario = $comunaUsuario;

        if($this->portal =='ciudadano'){
            $this->canal = Constante::CANAL_2_CLAVE_UNICA;
        }if($this->portal =='totem'){
            $this->canal = Constante::CANAL_7_TOTEM;
        } else{
            $this->canal = Constante::CANAL_1_MUNICIPAL;
        }

        $this->obtenerCiudadanoExport($this->runCiudadano);
        $this->qrcode = $this->generarQR();

        $this->cargarDataCertificado();
        $this->generarLogCertificado();
        return $this->generarPdf();
    }

    public function crearCertificadoBase64($portal, $runCiudadano, $ip = null, $runUsuario = null, $comunaUsuario = null)
    {
        $this->runCiudadano = $runCiudadano;
        $this->portal = $portal;
        $this->ip = $ip;
        $this->runUsuario = $runUsuario;
        $this->comunaUsuario = $comunaUsuario;

        if($this->portal =='ciudadano'){
            $this->canal = Constante::CANAL_2_CLAVE_UNICA;
        }if($this->portal =='totem'){
            $this->canal = Constante::CANAL_7_TOTEM;
        } else{
            $this->canal = Constante::CANAL_1_MUNICIPAL;
        }

        $this->obtenerCiudadanoExport($this->runCiudadano);
        $this->qrcode = $this->generarQR();
        $this->cargarDataCertificado();
        $this->generarLogCertificado();
        return ["cuidador"=> 1,
                "certificado"=> base64_encode($this->generarPdf())];
    }

    private function cargarDataCertificado()
    {
        $runCuidadoras = $this->runCiudadano;
        $getcuidador = Qr::obtenerDatosCuidadores($runCuidadoras);

        if(!$getcuidador){
            return $this->errorCertificadoBase64('Error Run no registrado');
        }

        $getnombre = $getcuidador->PER_NOMBRES;
        $explodenombre = explode(' ',$getnombre);

        $this->vista = 'credenciales.layout_PDF_credenciales';
        $this->cuidador = null;
        $this->data = [
            'nombreapellido' => $explodenombre[0].' '.$getcuidador->PER_APELLIDOPATERNO,
            'rut' => number_format($runCuidadoras,0,"",".").'-'.$getcuidador->PER_DV,
            'imagenqr' => $this->qrcode,
        ];
    }

    public function errorCertificado()
    {
        $this->vista = 'certificados.totem-certificado-cuidadora';
        if(isset($this->data)){
            $this->data = ['titulo' => 'Certificado Cuidador',
                           'error' => true,
                           'mensaje' => "Certificado no válido"
                           ];
        }else{
            $this->data = ['titulo' => 'Certificado Cuidador',
                'mensaje' => "Ciudadano no presenta registro de cuidador",
                'error' => true];
        }
        $this->generarLogCertificado($this->data['mensaje']);
        return $this->generarPdf();
    }

    public function errorCertificadoBase64($mensajeError)
    {
        $this->vista = 'certificados.certificado-cuidadora';
        if(isset($this->data)){
            $this->data = ['titulo' => 'Certificado Cuidador',
                'error' => true,
                'mensaje' => "Certificado no válido"
            ];
            $result = [
                "cuidador"=> 2,
                "certificado"=> null
            ];
        }elseif ($mensajeError == "Error Run no registrado"){
            $this->data = ['titulo' => 'Certificado Cuidador',
                'mensaje' => $mensajeError,
                'error' => true];
            $result = [
                "cuidador"=> 2,
                "certificado"=> null
            ];
        } else{
            $this->data = ['titulo' => 'Certificado Cuidador',
                'mensaje' => "Ciudadano no presenta registro de cuidador",
                'error' => true];
            $result = [
                "cuidador"=> 0,
                "certificado"=> null
            ];
        }
        $this->generarLogCertificado($this->data['mensaje']);
        return  $result;
    }

    private function generarPdf()
    {
        $nombre = 'cuidador_' . date('Y-m-d-His') . '.pdf';
        $pdf = PDF::loadView($this->vista, $this->data);
        return $pdf->download($nombre);
    }

    private function obtenerCiudadanoExport($runCiudadano)
    {
        $ciudadano = PersonasExport::findByRunCiudadanoCuidadora($runCiudadano);
        if(!$ciudadano){
            throw ExcepcionValidacion::new("Error Run no registrado")
                ->setTitular($runCiudadano);
        }
        if($ciudadano->CUIDADOR == null || $ciudadano->CUIDADOR==0){
            throw ExcepcionValidacion::new("Ciudadano no presenta registro de cuidador")
                ->setTitular($runCiudadano);
        }
        $this->ciudadanoExport = $ciudadano;
    }

    private function obtenerCiudadanoCuidador($runCiudadano)
    {
        return QrCuidadoras::findByRunCiudadano($runCiudadano);
    }

    public static function cacheCertificado($runCiudadano){
        $key = 'certificado-' . $runCiudadano;
        $minutos = 150;
        $horas = $minutos/60;
        $numeroCertificados = 30;
        if (Cache::has($key)) {
            $valorKey = Cache::get($key);
            if ($valorKey > $numeroCertificados) {
                throw ExcepcionValidacion::new("Ha superado el limite de consultas por el mismo RUN ".$runCiudadano."
                , espere ".$horas." horas para volver a consultar.")
                    ->setTitular($runCiudadano);
            } elseif ($valorKey < $numeroCertificados) {
                Cache::increment($key);
            } else {
                Cache::put($key, 99, now()->addMinutes($minutos));
            }
        } else {
            Cache::put($key, 1, now()->addMinutes($minutos));
        }
    }

    private function generarQR($imagen = false)
    {
        $fechaHora = date('Y-m-d H:i:s');
        $runCuidadoras = $this->runCiudadano;

        $Cuidadora = $this->ciudadanoExport;

        if(!$Cuidadora){
            throw ExcepcionValidacion::new("Error no qrcode")
                ->setTitular($runCuidadoras);
        }

        $hashMd5 = env('URL_SCANNING_QR').'/qrscan/'.base64_encode($runCuidadoras.'&'.$Cuidadora->ID_RUN_FECHA_ORIGEN_ESTADO);

        if($imagen){
            $qrcode = QrCode::format('png')->size(350)->generate($hashMd5);
        } else {
            $qrcode = base64_encode(QrCode::format('png')->size(350)->generate($hashMd5));
        }

        return $qrcode;
    }

    private function generarLogCertificado($nota = null)
    {
        try {
            $logCertCuidadorExport = new LogCertCuidadorExport();
            $logCertCuidadorExport->ID_LOG = LogCertCuidadorExport::nextValue('seq_log_certificado_cuidador');
            $logCertCuidadorExport->CANAL = $this->canal;
            $logCertCuidadorExport->RUN_USUARIO = $this->runUsuario;
            $logCertCuidadorExport->RUN_CUIDADOR = $this->runCiudadano;
            $logCertCuidadorExport->COMUNA_USUARIO = $this->comunaUsuario;
            $logCertCuidadorExport->IP = $this->ip;
            $logCertCuidadorExport->FECHA = date('Y-m-d H:i:s');
            $logCertCuidadorExport->NOTA = $nota;
            $logCertCuidadorExport->save();
        } catch (Exception $ex) {
            throw $ex;
        }
    }


}
