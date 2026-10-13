<?php
namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Models\Fuente;
use App\Models\GrupoFamiliarExport;
use App\Models\LocacionComuna;
use App\Models\LocacionLocalidad;
use App\Models\LocacionRegion;
use App\Models\Parentesco;
use App\Models\Personas;
use App\Models\PersonasExport;
use App\Models\QrCuidadoras;
use App\Models\TramoQuintil;
use App\Utiles;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;
use App\Clases\DebugTrait;
use App\Http\ResponseMappers\DescargaArchivo;
use PDF;
use stdClass;
use DB;
use App\Models\Usuario;
use App\Models\UsuarioComuna;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Sequence;



class DatosDeCertificadoCuidadora
{
    private $portal;
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

    public function crearCertificado($portal, $runCiudadano, $ip = null, $runUsuario = null)
    {
        $this->runCiudadano = $runCiudadano;
        $this->portal = $portal;
        $this->ip = $ip;
        $this->runUsuario = $runUsuario;

        if($this->portal =='ciudadano'){
            $this->canal = Constante::CANAL_2_CLAVE_UNICA;
        }else{
            $this->canal = Constante::CANAL_1_MUNICIPAL;
        }

        if($this->portal =='municipal'){
            $oResults = Usuario::where('RUT_USUARIO',$this->runUsuario)->get();
            if($oResults->count()>0){
                $this->usuario = $oResults->first();
            }
            $oResults = UsuarioComuna::select('COM_ID')
                ->where('RUT_USUARIO',$this->runUsuario)->get();
            if($oResults->count()>0){
                $comuna = $oResults->first()->COM_ID;
                $this->comunaUsuario = LocacionComuna::findByComunaIne($comuna)[0];
                switch ($this->usuario->ID_PERFIL){
                    case Constante::PERFIL_2_ADMINISTRADOR_NACIONAL:
                    case Constante::PERFIL_3_CONSULTA_NACIONAL:
                    case Constante::PERFIL_6_COORDINADOR_NACIONAL:
                    case Constante::PERFIL_18_NIVEL_CENTRAL:
                        $this->descripcionPerfil="el Ministerio de Desarrollo Social y Familia";
                        break;
                    case Constante::PERFIL_14_CHILE_ATIENDE:
                    case Constante::PERFIL_25_CHILE_ATIENDE_EJECUTOR:
                        $this->descripcionPerfil="Chile Atiende";
                        break;
                    case Constante::PERFIL_29_FUNFA:
                        $this->descripcionPerfil="la Fundación de la Familia";
                        break;
                    default:
                        $this->descripcionPerfil="la I.Municipalidad de ".Utiles::capitalizaTexto($this->comunaUsuario->DESCRIPCION);
                        break;
                }
            }
        }

        $getpersona = Personas::where('PER_RUN',$this->runCiudadano)->where('PER_VIGENTE',Constante::ESTADO_1_ACTIVO)->where('PER_CALCULOACTIVO',Constante::ESTADO_1_ACTIVO)->first();

        if(!$getpersona){
            return $this->errorCertificado();
        }

        if ($getpersona->PER_FECHADEFUNCION != 0) {
            //Cuidadora se encuentra registrada como fallecida
            $this->vista = 'certificados.certificado-cuidadora';
            $this->data = [
                'titulo' => 'Certificado Cuidador',
                'mensaje' => "La persona existe en los registros como fallecida.",
                'error' => true
            ];

            return $this->generarPdf();
        }

        //consultar si existe credencial generada en QRcuidadores
        $getQRCuidadores = QrCuidadoras::where('RUN_CUIDADOR',$this->runCiudadano)->where('ESTADO',1)->first();

        if($getQRCuidadores && $getQRCuidadores->RUTA_CREDENCIAL_PDF != null){
            //ya esta generado el PDF
            $credencialcode = storage_path().$getQRCuidadores->RUTA_CREDENCIAL_PDF;
        }else{
            $credencialcode = Credenciales::generarCredencialPDFCuidadoraQR($this->runCiudadano);
        }

        $nombre = 'credencial_'.$this->runCiudadano.'_'.date('Y-m-d-H-i-s').'.pdf';

        return DescargaArchivo::desdeRuta($credencialcode, $nombre);

    }

    public function crearCertificadoImage($portal, $runCiudadano, $ip = null, $runUsuario = null)
    {
        $this->runCiudadano = $runCiudadano;
        $this->portal = $portal;
        $this->ip = $ip;
        $this->runUsuario = $runUsuario;

        if($this->portal =='ciudadano'){
            $this->canal = Constante::CANAL_2_CLAVE_UNICA;
        }else{
            $this->canal = Constante::CANAL_1_MUNICIPAL;
        }

        $getpersona = Personas::where('PER_RUN',$this->runCiudadano)->where('PER_VIGENTE',Constante::ESTADO_1_ACTIVO)->where('PER_CALCULOACTIVO',Constante::ESTADO_1_ACTIVO)->first();

        if(!$getpersona){
            return $this->errorCertificado();
        }

        if ($getpersona->PER_FECHADEFUNCION != 0) {
            //Cuidadora se encuentra registrada como fallecida
            $this->vista = 'certificados.certificado-cuidadora';
            $this->data = [
                'titulo' => 'Certificado Cuidador',
                'mensaje' => "La persona existe en los registros como fallecida.",
                'error' => true
            ];

            return $this->generarPdf();
        }

        //consultar si existe credencial generada en QRcuidadores
        $getQRCuidadores = QrCuidadoras::where('RUN_CUIDADOR',$this->runCiudadano)->where('ESTADO',1)->first();

        if($getQRCuidadores && $getQRCuidadores->RUTA_CREDENCIAL_JPG != null){
            //ya esta generado el PDF
            $credencialcode = storage_path().$getQRCuidadores->RUTA_CREDENCIAL_JPG;
        }else{
            $credencialcode = Credenciales::generarCredencialJPGCuidadoraQR($this->runCiudadano);
        }

        $nombre = 'credencial_'.$this->runCiudadano.'_'.date('Y-m-d-H-i-s').'.jpeg';

        return DescargaArchivo::desdeRuta($credencialcode, $nombre);

    }

    private function cargarDataCertificado()
    {
        $this->vista = 'certificados.certificado-cuidadora';
        $this->ciudadanoExport = $this->obtenerCiudadanoExport($this->runCiudadano);
        $this->cuidador = $this->obtenerCiudadanoCuidador($this->runCiudadano);

        $this->data = ['portal' => $this->portal,
            'titulo' => 'Certificado Cuidador',
            'ciudadanoExport' => $this->ciudadanoExport,
            'dataCuidador' => $this->cuidador,
            'usuario' => $this->usuario,
            'descripcionPerfil' => $this->descripcionPerfil,
            'comunaUsuario' => $this->comunaUsuario,
            'qrcode' => $this->qrcode
            ];
    }

    public function errorCertificado()
    {
        $this->vista = 'certificados.certificado-cuidadora';
        if(isset($this->data)){
            $this->data = ['titulo' => 'Certificado Cuidador',
                           'error' => true,
                           'mensaje' => "Certificado no válido"
                           ];
        }else{
            $this->data = ['titulo' => 'Certificado Cuidador',
                'mensaje' => "Tenemos problemas para descargar su credencial",
                'error' => true];
        }
        return $this->generarPdf();
    }

    private function generarPdf()
    {
        $nombre = 'cuidador_' . date('Y-m-d-His') . '.pdf';
        $pdf = PDF::loadView($this->vista, $this->data);
        return $pdf->download($nombre);
    }

    private function obtenerCiudadanoExport($runCiudadano)
    {
        return Personas::findByRun($runCiudadano);
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

        $Cuidadora = QrCuidadoras::where('RUN_CUIDADOR', $runCuidadoras)
            ->where('ESTADO',Constante::ESTADO_1_ACTIVO)
            ->first();

        if(!$Cuidadora){
            throw ExcepcionValidacion::new("Error no qrcode")
                ->setTitular($runCuidadoras);
        }

        $hashMd5 = base64_encode($runCuidadoras.'&'.$Cuidadora->ID_RUN_FECHA_ORIGEN_ESTADO);

        if($imagen){
            $qrcode = QrCode::format('png')->size(350)->generate($hashMd5);
        } else {
            $qrcode = base64_encode(QrCode::format('png')->size(350)->generate($hashMd5));
        }

        return $qrcode;
    }


}
