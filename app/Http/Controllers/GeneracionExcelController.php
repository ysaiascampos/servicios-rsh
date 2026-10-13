<?php

namespace App\Http\Controllers;

use App\Clases\GenerarExcelQueue;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Http\ResponseMappers\DescargaArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class GeneracionExcelController extends Controller
{
    // Descargar un archivo de excel existente

    public function descargarExcel(Request $request)
    {
        $porciones = explode("/", $request->path());
        if( is_numeric($porciones[3]) ){
            if(is_dir(storage_path(Constante::DIRECTOIRIO_FILE_EXCEL_BANDEJA))){
                $file = storage_path(Constante::DIRECTOIRIO_FILE_EXCEL_BANDEJA) . '/' . Constante::STRING_IDENTIFICADOR_EXCEL . $porciones[3] . '.' . Constante::FILE_EXCEL_EXTENSION;
                $nombre_file = Constante::STRING_IDENTIFICADOR_EXCEL . $porciones[3] . '.' . Constante::FILE_EXCEL_EXTENSION;
                if( ! File::exists( $file ) ){
                    $file = storage_path(Constante::DIRECTOIRIO_FILE_EXCEL_BANDEJA) . '/' . Constante::STRING_IDENTIFICADOR_EXCEL . 'Default' . '.' . Constante::FILE_EXCEL_EXTENSION;
                }
                //$headers = ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                Log::info('Descragar el arcvhivo de excel, con ruta ' . $file );
                $cabeceras = [
                    'Content-Disposition' => 'attachment; filename=' . $nombre_file,
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ];
                $borrarDespuesDeEnviar = false;
                return Response::download($file, $nombre_file, $cabeceras)
                    ->deleteFileAfterSend($borrarDespuesDeEnviar);
                //return response()->download($file, $nombre_file, $headers);
            }else{
                Log::info('No Existe el directorio donde se ubican los archivos');
                ExcepcionValidacion::new( "Directorio no Encontrado");
            }
        }else{
            Log::info('Error en la Ruta para descarga de archivo');
            ExcepcionValidacion::new( "No es una ruta valida");
        }

    }
    // Ejecuta la generacion del cron por ruta
    public function jobExcel(Request $request)
    {
        $porciones = explode("/", $request->path());
        if( is_numeric($porciones[2]) ){
            Log::info('Ejecuta funcion GenerarExcel con comuna :' . $porciones[2]);
            GenerarExcelQueue::GenerarExcel($porciones[2]);
        }else{
            ExcepcionValidacion::new( "No es una ruta valida");
        }
    }
}
