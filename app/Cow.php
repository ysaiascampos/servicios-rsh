<?php

namespace App;

use Exception;
use GuzzleHttp\Client;
use SoapClient;
use SoapHeader;
use SoapVar;
use stdClass;


class Cow
{
    protected $COW_URL = 'http://wscow.ministeriodesarrollosocial.gob.cl/cow/';
    protected $COW_WSDL = 'https://terceros.sidiv.registrocivil.cl:8443/InteroperabilityPlateform/TercerosCOWProxyService?wsdl';
    /* --------------------
     * ACTIVAR O DESACTIVAR SERVICIO COW
     * -------------------- */
    public $cowActivado = true;
    /* --------------------
     * CREDENCIALES
     * -------------------- */
    protected $user = "usr_mindes_01";
    protected $pass = "DErgfr7WelSi";
    /* --------------------
     * CONSTANTES COW
     * -------------------- */
    public $COW_ESTADO_RESPUESTA = array(
        000 => "OK",
        201 => "Error técnico",
        207 => "Error autenticación",
        301 => "Datos inexistentes"
    );
    public $COW_TIPO_DOCUMENTO = array(
        "C" => "Cedula",
        "P" => "Pasaporte",
        "S" => "Salvoconducto",
        "T" => "Titulo de viaje",
        "D" => "Documento de viaje"
    );
    public $COW_CLASE_DOCUMENTO = array(
        10 => "Cédula de identidad para Chileno",
        11 => "Cedula de identidad para los extranjeros",
        20 => "Pasaporte 32 paginas",
        21 => "Pasaporte diplomático",
        22 => "Pasaporte oficial",
        23 => "Pasaporte 64 páginas ",
        24 => "Copia de pasaporte diplomático",
        30 => "Salvoconducto",
        40 => "Titulo de viaje",
        50 => "Documento de viaje"
    );
    /* -------------------- */
    public $rutEmpresa = '61980240';
    public $dvEmpresa = '3';
    public $codTipoDocumento = "C";
    public $numRun;
    public $numSerie;
    public $estadoRespuesta;
    public $runPersonaRespuesta;
    public $dvPersonaRespuesta;
    public $codTipoDocumentoRespuesta;
    public $codClaseDocumentoRespuesta;
    public $estado_c = true;

    public function __construct()
    {
        if(env('URL_COW')){
            $this->COW_URL = env('URL_COW');
        }
    }

    public static function getEstado()
    {
        $cow = new Cow();
        return $cow->cowActivado;
    }

    public static function getWsdl()
    {
        $cow = new Cow();
        return $cow->COW_WSDL;
    }

    public static function newCow()
    {
        $cow = new Cow();
        if (isset($cow->COW_WSDL) && !empty($cow->COW_WSDL)) {
            try {
                $context = stream_context_create(array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                ));

                $clienteCow = new SoapClient($cow->COW_WSDL, array(
                    'exceptions' => true,
                    'trace' => true,
                    'local_cert' => storage_path('certificados/itsslPKCS12Cert.pem'),
                    'passphrase' => 'mds2020',
                    'stream_context' => $context
                ));

                $clienteCow->__setSoapHeaders(Cow::wssecurity_header());

                $clienteCow->estado = 'ok';
                $clienteCow->estado_c = true;
                $clienteCow->estado_msg = 'Cow conectado correctamente.';
            } catch (Exception $e) {
                $clienteCow = new stdClass();
                $clienteCow->estado = 'error';
                $clienteCow->estado_c = false;
                $clienteCow->estado_msg = 'Error COW: ' . $e->getMessage();
            }
            return $clienteCow;
        }else {
            $clienteCow = new stdClass();
            $clienteCow->estado = 'error';
            $clienteCow->estado_c = false;
            $clienteCow->estado_msg = 'Error COW: Debe configurar el ambiente del COW, variable "COW_WSDL".';
            return $clienteCow;
        }
    }

    public function getDataDocument($numRun, $numSerie)
    {
        $clienteCow = Cow::newCow();
        $cow = new Cow();
        if ($clienteCow->estado_c === true) {
            return $clienteCow->GetDataDocument(array(
                'RUTEmpresa' => $cow->rutEmpresa,
                'DVEmpresa' => $cow->dvEmpresa,
                'CodTipoDocumento' => $cow->codTipoDocumento,
                'NumRUN' => $numRun,
                'NumSerie' => $numSerie
            ));
        } else {
            return $clienteCow;
        }
    }

    /* Generates a WS-Security header */

    public static function wssecurity_header()
    {
        $cow = new Cow();
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');

        $auth = '
              <wsse:Security SOAP-ENV:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                  <wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="Id-0001369141253585-0000000060e9540a-1">
                      <wsse:Username>' . $cow->user . '</wsse:Username>
                      <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">' . $cow->pass . '</wsse:Password>
                      <wsu:Created>' . $timestamp . '</wsu:Created>
                  </wsse:UsernameToken>
              </wsse:Security>
              ';
        $authvalues = new SoapVar($auth, XSD_ANYXML);
        $header = new SoapHeader("http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd",
            "Security", $authvalues, true);

        return $header;
    }
    public function getDataDocumentRest($numRun, $numSerie)
    {
        $url = $this->COW_URL;
        $client = new Client(['base_uri' => $url]);
        $uriCompletaRest = $numRun."/".$numSerie;
        $verboRest = "GET";
        $responseConsumoRest = $client->request($verboRest, $uriCompletaRest);
        $respuesta = json_decode($responseConsumoRest->getBody(), false);
        if(!is_object($respuesta)){
            $respuesta = new stdClass();
            $respuesta->estado = 'error';
            $respuesta->estado_c = false;
            $respuesta->msg = 'Advertencia: Servicio de Registo Civil con problemas de comunicacion o
                    error no codificable';
        }if(is_object($respuesta) && !isset($respuesta->datos)){
            if(isset($respuesta->msg)){
                $msg = $respuesta->msg;
            }else if(isset($respuesta->descripción)){
                $msg = $respuesta->descripción;
            }else{
                $msg = 'No Definido';
            }
            $respuesta = new stdClass();
            $respuesta->estado = 'error';
            $respuesta->estado_c = false;
            $respuesta->msg = $msg;
        } else {
            $datos = $respuesta->datos;
            $objRespuesta = new stdClass();
            $objRespuesta->estado = $respuesta->estado;
            $objRespuesta->msg = isset($respuesta->msg)?$respuesta->msg:null;

            if(isset($respuesta->descripción)){
                $objRespuesta->descripcion = $respuesta->descripción;
            }else{
                $objRespuesta->descripcion = isset($respuesta->descripcion)?$respuesta->descripcion:null;
            }

            if(isset($datos->EstadoRespuesta)){
                $objRespuesta->EstadoRespuesta = $datos->EstadoRespuesta;
                $objRespuesta->RUNPersona = $datos->RUNPersona;
                $objRespuesta->DvPersona = $datos->DvPersona;
                $objRespuesta->CodTipoDocumento = $datos->CodTipoDocumento;
                $objRespuesta->CodClaseDocumento = $datos->CodClaseDocumento;
                $objRespuesta->NumDocumento = $datos->NumDocumento;
                $objRespuesta->NumSerie = $datos->NumSerie;
                $objRespuesta->IndVigencia = $datos->IndVigencia;
                $objRespuesta->FhoVcto = $datos->FhoVcto;
                $objRespuesta->IndBloqueo = $datos->IndBloqueo;
            }else{
                $objRespuesta->EstadoRespuesta = $datos->estadorespuesta;
                $objRespuesta->RUNPersona = $datos->runpersona;
                $objRespuesta->DvPersona = $datos->dvpersona;
                $objRespuesta->CodTipoDocumento = $datos->codtipodocumento;
                $objRespuesta->CodClaseDocumento = $datos->codclasedocumento;
                $objRespuesta->NumDocumento = $datos->numdocumento;
                $objRespuesta->NumSerie = $datos->numserie;
                $objRespuesta->IndVigencia = $datos->indvigencia;
                $objRespuesta->FhoVcto = $datos->fhovcto;
                $objRespuesta->IndBloqueo = $datos->indbloqueo;
            }
            $respuesta = $objRespuesta;
        }

        return $respuesta;
    }

}
