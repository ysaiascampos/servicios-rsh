<?php


namespace App\Clases;

use App\Exceptions\ExcepcionConsistencia;
use App\Models\Personas;
use App\Models\Solicitud;
use App\Constantes\Constante;
use App\Models\ValidaSolicitudTercero;
use Illuminate\Support\Facades\Mail;

/*
 * ToDo: Codigo migrado desde SolicitudController.php de Proyecto rsh-municipal
 * usando codigo legado, migrar usando las funcionalidades de laravel
*/

class EnvioMail
{

    /** envia comprobante de la aprobación de solicitud
     * recibe como parametro un objeto solicitud
     */
    public static function enviaMailAprobado(Solicitud $solicitud)
    {
        if (filter_var($solicitud->EMAIL_CIUDADANO, FILTER_VALIDATE_EMAIL)) {
            $persona = Personas::where('per_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('per_run', $solicitud->RUN_CIUDADANO)
                ->first();
            $data = [
                'solicitud' => $solicitud,
                'persona' => $persona
            ];
            Mail::send('emails.solicitud-aprobado', $data, function ($message) use ($solicitud) {
                $message->to($solicitud->EMAIL_CIUDADANO)
                    ->subject('Registro Social de Hogares - Solicitud Nro ' . $solicitud->ID_PADRE_SOLICITUD . ' Aprobada');
            });
        }
    }

    /** envia comprobante de la validacion de solicitud
 * recibe como parametro un objeto solicitud
 */
    public static function enviaMailValidacionSolicitante(Solicitud $solicitud,$Validacion, $textAccion, $nombreSolicitud)
    {
        if ( filter_var($Validacion->EMAIL, FILTER_VALIDATE_EMAIL)){
            $personaSolicitante = Personas::where('per_vigente',Constante::VIGENTE_1_VIGENTE)
                ->where('per_calculoactivo',Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('per_run',$solicitud->RUN_CIUDADANO)
                ->first();
            $persona = Personas::where('per_vigente',Constante::VIGENTE_1_VIGENTE)
                ->where('per_calculoactivo',Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('per_run',$Validacion->PER_RUN)
                ->first();
            $solicitud = Solicitud::where('id_solicitud',$Validacion->IDSOLICITUD)
                ->first();

            $data =[
                'solicitud' =>$solicitud,
                'persona' =>$persona,
                'personaSolicitante' => $personaSolicitante,
                'validacion' =>$Validacion,
                'textAccion' =>$textAccion,
                'nombreSolicitud' => $nombreSolicitud
            ];

            Mail::send('emails.acepto-rechazo-solicitud-solicitante', $data, function ($message) use ($solicitud) {
                $message->to($solicitud->EMAIL_CIUDADANO)
                    ->subject('Registro Social de Hogares - Solicitud Nro ' . $solicitud->ID_PADRE_SOLICITUD . ' ');
            });

        }
    }

    /** envia comprobante de la validacion de solicitud
     * recibe como parametro un objeto solicitud
     */
    public static function enviaMailValidacion($Validacion, $ValidacionOtro, $textAccion, $nombreSolicitud)
    {
        if ( filter_var($Validacion->EMAIL, FILTER_VALIDATE_EMAIL)){
            $solicitud = Solicitud::where('id_solicitud',$Validacion->IDSOLICITUD)
                ->first();
            $personaSolicitante = Personas::where('per_vigente',Constante::VIGENTE_1_VIGENTE)
                ->where('per_calculoactivo',Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('per_run',$ValidacionOtro->PER_RUN)
                ->first();
            $persona = Personas::where('per_vigente',Constante::VIGENTE_1_VIGENTE)
                ->where('per_calculoactivo',Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('per_run',$Validacion->PER_RUN)
                ->first();
            $data =[
                'solicitud' =>$solicitud,
                'persona' =>$persona,
                'personaSolicitante' => $personaSolicitante,
                'validacion' =>$Validacion,
                'textAccion' =>$textAccion,
                'nombreSolicitud' => $nombreSolicitud
            ];

            Mail::send('emails.acepto-rechazo-solicitud-tercero', $data, function ($message) use ($solicitud,$ValidacionOtro) {
                $message->to($ValidacionOtro->EMAIL)
                    ->subject('Registro Social de Hogares - Solicitud Nro ' . $solicitud->ID_PADRE_SOLICITUD . ' ');
            });

        }
    }

    /** envia comprobante de la validacion de solicitud
     * recibe como parametro un objeto solicitud
     */
    public static function enviaMailAvisoValidacion(Solicitud $solicitud,$Validacion)
    {
        if ( filter_var($Validacion->EMAIL, FILTER_VALIDATE_EMAIL)){
            $personaSolicitante = Personas::where('per_vigente',Constante::VIGENTE_1_VIGENTE)
                ->where('per_calculoactivo',Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('per_run',$solicitud->RUN_CIUDADANO)
                ->first();
            $persona = Personas::where('per_vigente',Constante::VIGENTE_1_VIGENTE)
                ->where('per_calculoactivo',Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('per_run',$Validacion->PER_RUN)
                ->first();
            $solicitud = Solicitud::with('TipoSolicitud')->where('id_solicitud',$Validacion->IDSOLICITUD)
                ->first();
            $data =[
                'solicitud' =>$solicitud,
                'personaSolicitante'=>$personaSolicitante,
                'persona' =>$persona
            ];

            Mail::send('emails.aviso-solicitud-tercero', $data, function ($message) use ($solicitud,$Validacion) {
                $message->to($Validacion->EMAIL)
                    ->subject('Registro Social de Hogares - Solicitud Nro ' . $solicitud->ID_PADRE_SOLICITUD . '');
            });

        }
    }
}
