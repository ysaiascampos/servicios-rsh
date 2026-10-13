<?php

namespace App\Http\Controllers;

use App\Constantes\Constante;
use Illuminate\Http\Request;
use App\Models\SolicitudUrl;
use PhpOffice\PhpSpreadsheet\Writer\Xls\ErrorCode;

class SolicitudUrlController extends Controller
{
    public function infoSolicitud(){

        try {

            $input = request()->all();
            $subtiposolicitud=$input['sub_tipo_solicitud'];
            $tiposolicitud=$input['tipo_solicitud'];
            if(is_null($subtiposolicitud)){
                $infosolicitud = SolicitudUrl::where('ID_TIPO_SOLICITUD','=',$tiposolicitud)
                    ->where('ESTADO','=',Constante::VIGENTE_1_VIGENTE)
                    ->select('URL','TIPO_SOLICITUD')
                    ->get();
            }
            else{
                $infosolicitud = SolicitudUrl::where('ID_TIPO_SOLICITUD','=',$tiposolicitud)
                    ->where('ID_SUB_TIPO_SOLICITUD','=',$subtiposolicitud)
                    ->where('ESTADO','=',Constante::VIGENTE_1_VIGENTE)
                    ->select('URL','TIPO_SOLICITUD')
                    ->get();
            }
            if(count($infosolicitud) > 0)
                return $infosolicitud;
            else
                throw new Exception(Constante::ERROR_INFO_SOLICITUD);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
