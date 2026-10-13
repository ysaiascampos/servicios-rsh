<?php


namespace App\Clases;

use App\Constantes\Constante;
use App\Models\QrCuidadoras;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\Snappy\Facades\SnappyImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Exceptions\ExcepcionEjecucion;
use App\Models\CuidadorNoAprobado;
use App\Models\Personas;
use Exception;
use PDF;

class Credenciales
{
    public static function generarCredencialPDFCuidadoraQR($runCuidadoras)
    {
        $anio = date("Y");
        $mes = date("m");
        $dia = date("d");

        $qrCuidadoras = QrCuidadoras::where('RUN_CUIDADOR', $runCuidadoras)->where('ESTADO',Constante::ESTADO_1_ACTIVO)->first();
        $getcuidador = Qr::obtenerDatosCuidadores($runCuidadoras);
        $getpersona = Personas::where('PER_RUN',$runCuidadoras)->where('PER_VIGENTE',Constante::ESTADO_1_ACTIVO)->where('PER_CALCULOACTIVO',Constante::ESTADO_1_ACTIVO)->first();

        if(!$qrCuidadoras){
            return 0;
        }

        if(!$getcuidador){
            return 0;
        }

        if(!$getpersona){
            return 0;
        }

        if ($getpersona->PER_FECHADEFUNCION != 0) {
            //Cuidadora se encuentra registrada como fallecida
            return 0;
        }

        try {
            $hashMd5 = env('URL_SCANNING_QR').'/qrscan/'.base64_encode($runCuidadoras.'&'.$qrCuidadoras->ID_RUN_FECHA_ORIGEN_ESTADO);

            $qrcode = base64_encode(QrCode::format('png')->size(350)->generate($hashMd5));

            $nombre = 'credencialPDF_'.$qrCuidadoras->ID_RUN_FECHA_ORIGEN_ESTADO.'_'.date('Y-m-d-H-i-s').'.pdf';
            $vista = 'credenciales.layout_PDF_credenciales';

            //gestion nombre cuidador
            $getnombre = $getcuidador->PER_NOMBRES;
            $explodenombre = explode(' ',$getnombre);

            //gestion run cuidador
            $getruncuidador = $getcuidador->PER_RUN;

            $data = array(
                'nombreapellido' => $explodenombre[0].' '.$getcuidador->PER_APELLIDOPATERNO,
                'rut' => number_format($getruncuidador,0,"",".").'-'.$getcuidador->PER_DV,
                'imagenqr' => $qrcode,
            );

            $pdf = PDF::loadView($vista, $data);
            $destinationPath = storage_path().DIRECTORY_SEPARATOR.Constante::ADJUNTOS_DIR_ANTECEDENTES.DIRECTORY_SEPARATOR.'credenciales'.DIRECTORY_SEPARATOR.$anio.DIRECTORY_SEPARATOR.$mes.DIRECTORY_SEPARATOR.$dia;
            $rutapdf = storage_path().DIRECTORY_SEPARATOR.Constante::ADJUNTOS_DIR_ANTECEDENTES.DIRECTORY_SEPARATOR.'credenciales'.DIRECTORY_SEPARATOR.$anio.DIRECTORY_SEPARATOR.$mes.DIRECTORY_SEPARATOR.$dia.DIRECTORY_SEPARATOR.$nombre;
            $rutapdfSave = DIRECTORY_SEPARATOR.Constante::ADJUNTOS_DIR_ANTECEDENTES.DIRECTORY_SEPARATOR.'credenciales'.DIRECTORY_SEPARATOR.$anio.DIRECTORY_SEPARATOR.$mes.DIRECTORY_SEPARATOR.$dia.DIRECTORY_SEPARATOR.$nombre;

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true );
            }

            File::put($rutapdf, $pdf->output());

            //actualizar la ruta en la base de datos
            $qrCuidadoras->RUTA_CREDENCIAL_PDF = $rutapdfSave;
            if ($qrCuidadoras->save()){
                return $rutapdf;
            }

            return 0;

        } catch (\Throwable $th) {
            Log::info('error en Credenciales -> generarCredencialPDFCuidadoraQR');
            Log::info(print_r($th->getMessage(),true));

            return 0;
        }
    }

    public static function generarCredencialJPGCuidadoraQR($runCuidadoras)
    {
        $anio = date("Y");
        $mes = date("m");
        $dia = date("d");

        $qrCuidadoras = QrCuidadoras::where('RUN_CUIDADOR', $runCuidadoras)->where('ESTADO',Constante::ESTADO_1_ACTIVO)->first();
        $getcuidador = Qr::obtenerDatosCuidadores($runCuidadoras);
        $getpersona = Personas::where('PER_RUN',$runCuidadoras)->where('PER_VIGENTE',Constante::ESTADO_1_ACTIVO)->where('PER_CALCULOACTIVO',Constante::ESTADO_1_ACTIVO)->first();

        if(!$qrCuidadoras){
            return 0;
        }

        if(!$getcuidador){
            return 0;
        }

        if(!$getpersona){
            return 0;
        }

        if ($getpersona->PER_FECHADEFUNCION != 0) {
            //Cuidadora se encuentra registrada como fallecida
            return 0;
        }

        try {
            $hashMd5 = env('URL_SCANNING_QR').'/qrscan/'.base64_encode($runCuidadoras.'&'.$qrCuidadoras->ID_RUN_FECHA_ORIGEN_ESTADO);

            $qrcode = base64_encode(QrCode::format('png')->size(350)->generate($hashMd5));

            $nombre = 'credencialJPG_'.$qrCuidadoras->ID_RUN_FECHA_ORIGEN_ESTADO.'_'.date('Y-m-d-H-i-s').'.jpeg';
            $vista = 'credenciales.layout_JPG_credenciales';

            //gestion nombre cuidador
            $getnombre = $getcuidador->PER_NOMBRES;
            $explodenombre = explode(' ',$getnombre);

            //gestion run cuidador
            $getruncuidador = $getcuidador->PER_RUN;

            $data = array(
                'nombreapellido' => $explodenombre[0].' '.$getcuidador->PER_APELLIDOPATERNO,
                'rut' => number_format($getruncuidador,0,"",".").'-'.$getcuidador->PER_DV,
                'imagenqr' => $qrcode,
            );

            $destinationPath = storage_path().DIRECTORY_SEPARATOR.Constante::ADJUNTOS_DIR_ANTECEDENTES.DIRECTORY_SEPARATOR.'credenciales'.DIRECTORY_SEPARATOR.$anio.DIRECTORY_SEPARATOR.$mes.DIRECTORY_SEPARATOR.$dia;
            $rutajpg = storage_path().DIRECTORY_SEPARATOR.Constante::ADJUNTOS_DIR_ANTECEDENTES.DIRECTORY_SEPARATOR.'credenciales'.DIRECTORY_SEPARATOR.$anio.DIRECTORY_SEPARATOR.$mes.DIRECTORY_SEPARATOR.$dia.DIRECTORY_SEPARATOR.$nombre;
            $rutajpgSave = DIRECTORY_SEPARATOR.Constante::ADJUNTOS_DIR_ANTECEDENTES.DIRECTORY_SEPARATOR.'credenciales'.DIRECTORY_SEPARATOR.$anio.DIRECTORY_SEPARATOR.$mes.DIRECTORY_SEPARATOR.$dia.DIRECTORY_SEPARATOR.$nombre;

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true );
            }

            $img = SnappyImage::loadView($vista, $data);
            $img->setOption('format', 'jpeg');
            $img->setOption('width', 400);
            $img->setOption('height', 610);
            $img->setOption('quality', 100);

            File::put($rutajpg, $img->output());

            //actualizar la ruta en la base de datos
            $qrCuidadoras->RUTA_CREDENCIAL_JPG =  $rutajpgSave;
            if ($qrCuidadoras->save()){
                return $rutajpg;
            }

            return 0;

        } catch (\Throwable $th) {
            Log::info('error en Credenciales -> generarCredencialJPGCuidadoraQR');
            Log::info(print_r($th->getMessage(),true));

            return 0;
        }
    }

    public static function obtenerRunesFaltantes(){
        try {
            $qrCuidadoras = QrCuidadoras::where('RUTA_CREDENCIAL_JPG', null)->where('ESTADO',1)->get();

        } catch (Exception $ex) {
            Log::info('error en Credenciales -> obtenerRunesFaltantes');
            Log::info(print_r($ex->getMessage(),true));

            throw new ExcepcionEjecucion(Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST,
                Constante::ERR_EJECUCION_COMUNICACION_SERVICIO_EXTERNO);
        }

        return $qrCuidadoras;
    }

    public static function obtenerEmailPositivosAutomaticosFaltantes(){
        try {
            $CuidadorasNoAvisados = QrCuidadoras::where('AUTOMATICO',1)->where('ESTADO',1)->where('EMAIL_AVISO_ENVIADO',0)->get();
        } catch (Exception $ex) {
            Log::info('error en Credenciales -> obtenerEmailPositivosAutomaticosFaltantes');
            Log::info(print_r($ex->getMessage(),true));

            throw new ExcepcionEjecucion(Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST,Constante::ERR_EJECUCION_COMUNICACION_SERVICIO_EXTERNO);
        }

        return $CuidadorasNoAvisados;
    }

    public static function obtenerEmailPositivosNoAutomaticosFaltantes(){
        try {
            $CuidadorasNoAvisados = QrCuidadoras::where('AUTOMATICO',0)->where('ESTADO',1)->where('EMAIL_AVISO_ENVIADO',0)->get();
        } catch (Exception $ex) {
            Log::info('error en Credenciales -> obtenerEmailPositivosNoAutomaticosFaltantes');
            Log::info(print_r($ex->getMessage(),true));

            throw new ExcepcionEjecucion(Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST,Constante::ERR_EJECUCION_COMUNICACION_SERVICIO_EXTERNO);
        }

        return $CuidadorasNoAvisados;
    }

    public static function obtenerEmailNegativosFaltantes(){
        try {
            $listadoCuidadoresPositivos = QrCuidadoras::where('ESTADO',1)->where('EMAIL_AVISO_ENVIADO', 0)->get()->pluck('RUN_CUIDADOR')->toArray();
            $CuidadorasNoAvisados = CuidadorNoAprobado::where('EMAIL_AVISO_ENVIADO', 0)->whereNotIn('RUN_CUIDADOR',$listadoCuidadoresPositivos)->get();
        } catch (Exception $ex) {
            Log::info('error en Credenciales -> obtenerEmailNegativosFaltantes');
            Log::info(print_r($ex->getMessage(),true));

            throw new ExcepcionEjecucion(Constante::MENSAJE_ERROR_CONSUMO_SERVICIO_REST,
                Constante::ERR_EJECUCION_COMUNICACION_SERVICIO_EXTERNO);
        }

        return $CuidadorasNoAvisados;
    }

}
