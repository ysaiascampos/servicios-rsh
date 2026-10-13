<?php

namespace App\Http\Controllers;

use App\Constantes\Constante;
use App\Clases\Credenciales;
use Illuminate\Support\Facades\Mail;
use App\Clases\Qr;
use App\Models\CuidadorNoAprobado;
use App\Models\Personas;
use App\Models\QrCuidadoras;
use App\Models\SolicitudCuidadoras;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Http\ResponseMappers\DescargaArchivo;
use RecursiveIteratorIterator;
use ZipArchive;

class CredencialesController extends Controller
{
    public function GenerarCredencialesMasivo()
    {
        $qrCuidadoras = Credenciales::obtenerRunesFaltantes();

        $anio = date("Y");
        $mes = date("m");
        $dia = date("d");
        $contador = 0;
        $cantidadTotal = count($qrCuidadoras);

        if($cantidadTotal != 0){
            ini_set('max_execution_time', 100000);
            set_time_limit(100000);

            foreach($qrCuidadoras as $qrcuidadora){
                //validar persona no fallecida
                $run = $qrcuidadora->RUN_CUIDADOR;
                $getpersona = Personas::where('PER_RUN',$run)->where('PER_VIGENTE',Constante::ESTADO_1_ACTIVO)->where('PER_CALCULOACTIVO',Constante::ESTADO_1_ACTIVO)->first();
                if($getpersona && $getpersona->PER_FECHADEFUNCION == 0){
                    $contador++;
                    Credenciales::generarCredencialPDFCuidadoraQR($run);
                    Credenciales::generarCredencialJPGCuidadoraQR($run);
                }
            }

            $mensaje = 'Se generaron '.$contador.' credenciales.';
        }else{

            return [
                'estado' => '0',
                'msg' => 'Error no existen RUT cargados para generar QR'
            ];
        }

        return [
            'estado' => '200',
            'msg' => $mensaje
        ];
    }

    public function generarCredencialJPGCuidadoraQR($runCiudadano)
    {
        $credencialcode = Credenciales::generarCredencialJPGCuidadoraQR($runCiudadano);

        if(is_int($credencialcode)){
            return [
                'estado' => '0',
                'msg' => 'Error no credencial jpg code'
            ];
        }
        return $credencialcode;
    }

    public function generarCredencialPDFCuidadoraQR($runCiudadano)
    {
        $credencialcode = Credenciales::generarCredencialPDFCuidadoraQR($runCiudadano);

        if(is_int($credencialcode)){
            return [
                'estado' => '0',
                'msg' => 'Error no credencial pdf code'
            ];
        }
        return $credencialcode;
    }

    public function EnvioEmailMasivosNegativos()
    {
        $emailCuidadorasNegativos = Credenciales::obtenerEmailNegativosFaltantes();

        $contador = 0;
        $cantidadTotal = count($emailCuidadorasNegativos);

        if($cantidadTotal != 0){
            ini_set('max_execution_time', 100000);
            set_time_limit(100000);

            foreach($emailCuidadorasNegativos as $cuidadora){
                try {
                    $runcuidador = $cuidadora->RUN_CUIDADOR;
                    $idsolicitudnoaprobado = $cuidadora->ID_SOLICITUD;

                    $getpersona = Personas::where('PER_RUN',$runcuidador)->where('per_vigente', Constante::VIGENTE_1_VIGENTE)->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)->first();

                    if($getpersona && $getpersona->PER_FECHADEFUNCION == 0){
                        if (filter_var($getpersona->PER_EMAIL, FILTER_VALIDATE_EMAIL)) {
                            //buscar idsolicitud email
                            $getabsolicitud = Solicitud::where('id_solicitud',$idsolicitudnoaprobado)->first();
                            if($getabsolicitud){
                                //gestion nombre cuidador
                                $getnombre = $getpersona->PER_NOMBRES;
                                $explodenombre = explode(' ',$getnombre);

                                $data = [
                                    'primernombrepersona' => $explodenombre[0],
                                    'persona' => $getpersona,
                                    'id_solicitud_padre' => $getabsolicitud->ID_PADRE_SOLICITUD,
                                ];

                                Mail::send('emails.solicitud-credencial-negativo', $data, function ($message) use($getpersona) {
                                    $message->to($getpersona->PER_EMAIL)->subject('Informa que no ha sido validada como persona cuidadora');
                                });

                                //actualizar que ya fue enviado el email
                                $sol = CuidadorNoAprobado::findOrFail($cuidadora->ID);
                                $sol->EMAIL_AVISO_ENVIADO = 1;
                                $sol->update();

                                $contador++;
                            }
                        }
                    }
                } catch (\Throwable $th) {
                    Log::info(print_r($th->getMessage(),true));
                }
            }

            $mensaje = 'Se generaron '.$contador.' envios de correos negativos.';
        }else{

            return [
                'estado' => '0',
                'msg' => 'Error no existen EMAIL negativos cargados para generar envios'
            ];
        }

        return [
            'estado' => '200',
            'msg' => $mensaje
        ];
    }

    public function EnvioEmailMasivosPositivos()
    {
        $emailCuidadorasNoAutomaticosPositivos = Credenciales::obtenerEmailPositivosNoAutomaticosFaltantes();
        $emailCuidadorasAutomaticosPositivos = Credenciales::obtenerEmailPositivosAutomaticosFaltantes();

        $contador = 0;
        $cantidadTotal = count($emailCuidadorasNoAutomaticosPositivos) + count($emailCuidadorasAutomaticosPositivos);

        if($cantidadTotal != 0){
            ini_set('max_execution_time', 100000);
            set_time_limit(100000);

            //listado no automaticos
            foreach($emailCuidadorasNoAutomaticosPositivos as $cuidadora){
                try {
                    $runcuidador = $cuidadora->RUN_CUIDADOR;
                    $urljpgcredencial = $cuidadora->RUTA_CREDENCIAL_JPG;

                    $getpersona = Personas::where('PER_RUN',$runcuidador)->where('per_vigente', Constante::VIGENTE_1_VIGENTE)->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)->first();

                    if($getpersona && $getpersona->PER_FECHADEFUNCION == 0){
                        if (filter_var($getpersona->PER_EMAIL, FILTER_VALIDATE_EMAIL)) {
                            //gestion nombre cuidador
                            $getnombre = $getpersona->PER_NOMBRES;
                            $explodenombre = explode(' ',$getnombre);

                            //buscar idsolicitud email
                            $getsolicitudcuidadoras = SolicitudCuidadoras::where('rut_cuidador',$runcuidador)->where('vigente',1)->orderBy('id_solicitud','desc')->first();
                            if($getsolicitudcuidadoras){
                                $idsolicitudcuidadora = $getsolicitudcuidadoras->ID_SOLICITUD;

                                //buscar AB_SOLICITUD PADRE
                                $getabsolicitud = Solicitud::where('id_solicitud',$idsolicitudcuidadora)->where('id_estado',5)->first();
                                if($getabsolicitud){
                                    $data = [
                                        'primernombrepersona' => $explodenombre[0],
                                        'persona' => $getpersona,
                                        'id_solicitud_padre' => $getabsolicitud->ID_PADRE_SOLICITUD,
                                    ];

                                    //verificar si tiene el pdf creado
                                    if($urljpgcredencial){
                                        //si tiene el pdf creado
                                        $filejpgcredencial = $urljpgcredencial;
                                    }else{
                                        //generar si no esta creado
                                        $filejpgcredencial = Credenciales::generarCredencialJPGCuidadoraQR($runcuidador);
                                    }

                                    Mail::send('emails.solicitud-credencial-positivo-no-automatico', $data, function ($message) use($getpersona,$filejpgcredencial) {
                                        $message->to($getpersona->PER_EMAIL)->subject('Envía credencial digital de persona cuidadora');

                                        if($filejpgcredencial){
                                            $message->attach($filejpgcredencial);
                                        }
                                    });

                                    //actualizar que ya fue enviado el email
                                    $sol = QrCuidadoras::findOrFail($cuidadora->ID);
                                    $sol->EMAIL_AVISO_ENVIADO = 1;
                                    $sol->update();

                                    $contador++;
                                }
                            }

                        }
                    }
                } catch (\Throwable $th) {
                    Log::info(print_r($th->getMessage(),true));
                }
            }

            //listado automaticos
            foreach($emailCuidadorasAutomaticosPositivos as $cuidadora){
                try {
                    $runcuidador = $cuidadora->RUN_CUIDADOR;
                    $urljpgcredencial = $cuidadora->RUTA_CREDENCIAL_JPG;

                    $getpersona = Personas::where('PER_RUN',$runcuidador)->where('per_vigente', Constante::VIGENTE_1_VIGENTE)->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)->first();

                    if($getpersona && $getpersona->PER_FECHADEFUNCION == 0){
                        if (filter_var($getpersona->PER_EMAIL, FILTER_VALIDATE_EMAIL)) {
                            //gestion nombre cuidador
                            $getnombre = $getpersona->PER_NOMBRES;
                            $explodenombre = explode(' ',$getnombre);

                            $data = [
                                'primernombrepersona' => $explodenombre[0],
                                'persona' => $getpersona
                            ];

                            //verificar si tiene el pdf creado
                            if($urljpgcredencial){
                                //si tiene el pdf creado
                                $filejpgcredencial = $urljpgcredencial;
                            }else{
                                //generar si no esta creado
                                $filejpgcredencial = Credenciales::generarCredencialJPGCuidadoraQR($runcuidador);
                            }

                            Mail::send('emails.solicitud-credencial-positivo-automatico', $data, function ($message) use($getpersona,$filejpgcredencial) {
                                $message->to($getpersona->PER_EMAIL)->subject('Informa que ha sido identificada como persona cuidadora');

                                if($filejpgcredencial){
                                    $message->attach($filejpgcredencial);
                                }
                            });

                            //actualizar que ya fue enviado el email
                            $sol = QrCuidadoras::findOrFail($cuidadora->ID);
                            $sol->EMAIL_AVISO_ENVIADO = 1;
                            $sol->update();

                            $contador++;

                        }
                    }
                } catch (\Throwable $th) {
                    Log::info(print_r($th->getMessage(),true));
                }
            }

            $mensaje = 'Se generaron '.$contador.' envios de correos positivos.';
        }else{

            return [
                'estado' => '0',
                'msg' => 'Error no existen EMAIL positivos cargados para generar envios'
            ];
        }

        return [
            'estado' => '200',
            'msg' => $mensaje
        ];
    }

    public static function DescargarCredecialesZip($y = null, $m = null, $d = null)
    {
        if(!is_null($y) && !is_null($m) && !is_null($d)){
            $path = storage_path().DIRECTORY_SEPARATOR.Constante::ADJUNTOS_DIR_ANTECEDENTES.DIRECTORY_SEPARATOR.'credenciales'.DIRECTORY_SEPARATOR.$y.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.$d.DIRECTORY_SEPARATOR;
            $nombre = $y.$m.$d. ".zip";
            $pathfinal = $path.$nombre;
            $subCarpetas = 0;
        }elseif(!is_null($y) && !is_null($m)){
            $path = storage_path().DIRECTORY_SEPARATOR.Constante::ADJUNTOS_DIR_ANTECEDENTES.DIRECTORY_SEPARATOR.'credenciales'.DIRECTORY_SEPARATOR.$y.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR;
            $nombre = $y.$m. ".zip";
            $pathfinal = $path.$nombre;
            $subCarpetas = 3;
        }elseif(!is_null($y)){
            $path = storage_path().DIRECTORY_SEPARATOR.Constante::ADJUNTOS_DIR_ANTECEDENTES.DIRECTORY_SEPARATOR.'credenciales'.DIRECTORY_SEPARATOR.$y.DIRECTORY_SEPARATOR;
            $nombre = $y. ".zip";
            $pathfinal = $path.$nombre;
            $subCarpetas = 6;
        }else{
            return [
                'estado' => '0',
                'msg' => 'No se ha ingreso el año, mes o día a descargar'
            ];
        }

        $zip = new ZipArchive();
        if ($zip->open($pathfinal, ZipArchive::OVERWRITE | ZipArchive::CREATE) != true) {
            return 'No se puede crear el archivo: "' . $pathfinal . '"';
        }
        if (File::exists($path) ) {
            $archivos = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path),RecursiveIteratorIterator::LEAVES_ONLY);
            foreach ($archivos as $archivo ) {
                if ($archivo->isDir()) {
                    continue;
                }

                $rutaAbsoluta = $archivo->getRealPath();
                $largoNombreArchivo = strlen($archivo->getFilename()) + $subCarpetas;
                $nombreArchivo = substr($rutaAbsoluta, (strlen($rutaAbsoluta)-$largoNombreArchivo)  , $largoNombreArchivo);
                $rutaAbsoluta2 = $path.$nombreArchivo;
                $zip->addFile($rutaAbsoluta2, $nombreArchivo);
            }
            $zip->close();
        }else{
            return [
                'estado' => '0',
                'msg' => 'No se encuenta la carpeta para la fecha ingresada'
            ];
        }

        return DescargaArchivo::desdeRuta($pathfinal, $nombre, true);
    }

}
