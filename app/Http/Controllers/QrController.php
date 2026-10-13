<?php

namespace App\Http\Controllers;

use App\Clases\Credenciales;
use App\Clases\NumeroSerie;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Http\Inputs\NumeroSerieInput;
use App\Http\ResponseMappers\QrCuidadorasMapper;
use App\Http\ResponseMappers\DescargaArchivo;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use App\Clases\Qr;
use App\Clases\QRInstitucionesService;
use App\Models\QrScanCodigosInstituciones;
use stdClass;
use Throwable;
use ZipArchive;
use RecursiveIteratorIterator;

class QrController extends Controller
{
    private $idLog;
    public $qrcode;
    private $runCiudadano;
    public function validarCuidadorQR()
    {
        try {
            $this->validarParametros(request()->all());
            $input=request()->all();
            $validacion = Qr::validar($input);
            return new QrCuidadorasMapper($validacion);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    /**
     * @throws ExcepcionValidacion
     */
    private function validarParametros( $parametros)
    {
        $reglas = [
            'run-usuario' => 'required|numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'hash'=>'required|string|between:' .
                implode(',', Constante::$COMENTARIO_1_Y_3000_POSIBLES_RESPUESTAS_STRING),

        ];
        $mensajes = [];
        $validador = Validator::make($parametros, $reglas, $mensajes);
        if ($validador->fails()) {
            throw new ExcepcionValidacion($validador->errors());
        }
    }

    public function validarCuidadorRunNSerie()
    {
        try {
            $this->validarParametrosRunNSerie(request()->all());
            $input = new NumeroSerieInput(request()->all());
            $resultado = NumeroSerie::validar($input);
            if(isset($resultado->estado) && $resultado->estado != 200) {
                return $resultado;
            }
            $input = request()->all();
            $validacion = Qr::validarRunNSerie($input);
            return new QrCuidadorasMapper($validacion);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    /**
     * @throws ExcepcionValidacion
     */
    private function validarParametrosRunNSerie( $parametros)
    {
        $reglas = [
            'run-usuario' => 'required|numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'run-ciudadano' => 'required|numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC),
            'numero-serie' => 'required|between:8,10'
        ];
        $mensajes = [];
        $validador = Validator::make($parametros, $reglas, $mensajes);
        if ($validador->fails()) {
            throw new ExcepcionValidacion($validador->errors());
        }
    }


    public function generarPdfQR($runCiudadano)
    {
        $qrcode = Qr::generarQR($runCiudadano);
        if(is_int($qrcode)){
            return [
                'estado' => '0',
                'msg' => 'Error no qrcode'
            ];
        }
        $nombre = 'certificado_cuidadoras_' . date('Y-m-d-His') . '.pdf';
        $vista = 'formularios.qr-cuidador';
        $data = ['titulo' => 'Cartola Hogar',
            'error' => true,
            'mensaje' => "Cartola no válida",
            'qrcode' => $qrcode
        ];
        $pdf = PDF::loadView($vista, $data);
        return $pdf->download($nombre);
    }

    public function generarImagenQR($runCiudadano)
    {
        $qrcode = Qr::generarQR($runCiudadano,true);
        if(is_int($qrcode)){
            return [
                'estado' => '0',
                'msg' => 'Error no qrcode'
            ];
        }
        return $qrcode;
    }

    public function obtenerDatos($runCiudadano)
    {
        try {
            $ciudadano = Qr::obtenerDatosCuidadores($runCiudadano);

            return new QrCuidadorasMapper($ciudadano);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function GenerarQrMasivo()
    {
        $qrCuidadoras = Qr::obtenerRunes();

        $anio = date("Y");
        $mes = date("m");
        $dia = date("d");
        $contador = 0;
        $cantidadTotal = count($qrCuidadoras);

        if($cantidadTotal != 0){
            ini_set('max_execution_time', 100000);
            set_time_limit(100000);

            foreach($qrCuidadoras as $qrcuidadora){

                $run = $qrcuidadora->RUN_CUIDADOR;
                $destinationPath = storage_path() .DIRECTORY_SEPARATOR . Constante::ADJUNTOS_DIR_ANTECEDENTES . "/imagenesqr/" . $anio . "/" . $mes . "/". $dia . "/";

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true );
                }
                $qrcode = $this->generarImagenQR($run);

                Credenciales::generarCredencialPDFCuidadoraQR($run);
                Credenciales::generarCredencialJPGCuidadoraQR($run);

                $nombreArchivo = $destinationPath . $run . '.png';

                if(!is_int($qrcode)){
                    File::put($nombreArchivo, $qrcode);
                    $contador++;
                    $actualizarEstado = Qr::actualizaEstadoRunes($run);
                    if($actualizarEstado==0){
                        return [
                            'estado' => '0',
                            'msg' => 'Error al actualizar estado del RUN'
                        ];
                    }
                }
            }

            $mensaje = 'Se generaron ' . $contador . ' imagenes QR de un total de '. $cantidadTotal . ' runes cargados.';
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

    public function DescargarQrZip($y = null, $m = null, $d = null)
    {
      if(!is_null($y) && !is_null($m) && !is_null($d)){
          $path = storage_path() .DIRECTORY_SEPARATOR . Constante::ADJUNTOS_DIR_ANTECEDENTES . DIRECTORY_SEPARATOR . "imagenesqr". DIRECTORY_SEPARATOR . $y . DIRECTORY_SEPARATOR . $m . DIRECTORY_SEPARATOR . $d . DIRECTORY_SEPARATOR ;
          $nombre = $y.$m.$d. ".zip";
          $pathfinal = $path.$nombre;
          $subCarpetas = 0;
      }elseif(!is_null($y) && !is_null($m)){
          $path = storage_path() .DIRECTORY_SEPARATOR . Constante::ADJUNTOS_DIR_ANTECEDENTES . DIRECTORY_SEPARATOR. "imagenesqr" . DIRECTORY_SEPARATOR . $y . DIRECTORY_SEPARATOR . $m . DIRECTORY_SEPARATOR;
          $nombre = $y.$m. ".zip";
          $pathfinal = $path.$nombre;
          $subCarpetas = 3;
      }elseif(!is_null($y)){
          $path = storage_path() .DIRECTORY_SEPARATOR . Constante::ADJUNTOS_DIR_ANTECEDENTES . DIRECTORY_SEPARATOR ."imagenesqr". DIRECTORY_SEPARATOR . $y . DIRECTORY_SEPARATOR ;
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

          $archivos = new \RecursiveIteratorIterator(
                      new \RecursiveDirectoryIterator($path),
                      RecursiveIteratorIterator::LEAVES_ONLY
                  );
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

    public function consultarinstitucionesvigentes()
    {
        try {
            $serviceinstituciones = new QRInstitucionesService();
            $instituciones = $serviceinstituciones->consultarInstitucionesVigentes();
            $mappedResponse = $serviceinstituciones->map($instituciones);

            return $mappedResponse;
        } catch (Exception $ex) {
            Log::info('Error QrController consultarinstitucionesvigentes');
            Log::info(print_r($ex->getMessage(),true));
            return $this->handleException($ex);
        }
    }

    public function validarCodigoInstitucion($idinstitucion,$codigo)
    {
        try {
            $existsregistro = QrScanCodigosInstituciones::where('ID_INSTITUCION',$idinstitucion)->where('CODIGO',$codigo)->exists();

            if($existsregistro){
                return 'true';
            }else{
                return 'false';
            }
        } catch (Exception $ex) {
            Log::info('Error QrController validarCodigoInstitucion');
            Log::info(print_r($ex->getMessage(),true));
            return $this->handleException($ex);
        }
    }


}
