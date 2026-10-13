<?php


namespace App\Servicios\ReglasCuidadores;

use App\Constantes\Constante;
use Closure;
use Exception;


class NormalizacionDependienteRsh
{
    private $consultaCuidado;
    private $tipoSolicitud;
    private $cantidadMaxima;
    private $bloqueos = array();
    private $data_dependiente;

    public function __construct()
    {
        $this->consultaCuidado = new ConsultaCuidadoresCuidado();
        $this->tipoSolicitud = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO;
        $this->cantidadMaxima = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_CANTMAX_CUIDADO;
    }

    public function handle($request, Closure $next)
    {
        $this->data_dependiente = $request[Constante::DATOS_DEPENDIENTE];
//        $bloqueos = $request["motivos_bloqueo"];
        ## Trae la lista de dependiente actual que tiene el cuidador
        $lista_dependiente = isset($this->data_dependiente[Constante::DEPENDIENTE_LISTA_CUIDADOR])
            ? $this->data_dependiente[Constante::DEPENDIENTE_LISTA_CUIDADOR]
            : array();

        ## lista de solicitudes que hay en borrar en estado 11
        $lista_borrador = isset($this->data_dependiente[Constante::DEPENDIENTE_LISTA_ESTADO11_CUIDADOR])
            ? $this->data_dependiente[Constante::DEPENDIENTE_LISTA_ESTADO11_CUIDADOR]
            : [
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR  => array(),
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO  => array(),
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE => array(),
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL => array()
            ];

        ## lista de solicitudes en tramite de estado tramotacion de cese y cambio de rol
        $lista_solicitudes = isset($this->data_dependiente[Constante::DEPENDIENTE_LISTA_ESTADO_SOLICITUD_CUIDADOR])
            ? $this->data_dependiente[Constante::DEPENDIENTE_LISTA_ESTADO_SOLICITUD_CUIDADOR]
            : [
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE => array(),
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL => array()
            ];

        ## lista de solicitudes aprobadas por municipal de cese y cambio de rol
        $lista_aprobadas = isset($this->data_dependiente[Constante::DEPENDIENTE_LISTA_APROBADO_SOLICITUD_CUIDADOR])
            ? $this->data_dependiente[Constante::DEPENDIENTE_LISTA_APROBADO_SOLICITUD_CUIDADOR]
            : [
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE => array(),
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL => array()
            ];


        // sacar lista de cese si hay en tramite
        if(count($lista_solicitudes[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE]) > 0){
            $NuevoArrayLimpio = $this->consultaCuidado->actulizarData($lista_solicitudes[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE],$lista_dependiente);
            $lista_dependiente = $NuevoArrayLimpio;
            $mensaje = Constante::$bloqueosGloblalesCuidadores[6];
            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
        }
        // sacar lista de Cambio de si hay en tramite
        if(count($lista_solicitudes[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL]) > 0){
            $msg = Constante::$bloqueosListadeCambioRol[4];
            $NuevoArrayLimpio = $this->consultaCuidado->actulizarData($lista_solicitudes[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL],$lista_dependiente,$msg);
            $lista_dependiente = $NuevoArrayLimpio;
            $mensaje = Constante::$bloqueosGloblalesCuidadores[6];
            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
        }

        // una vez sacado los valores en tramite de cese se elimina la propiedad  dependiete_solicitud
        unset($this->data_dependiente[Constante::DEPENDIENTE_LISTA_ESTADO_SOLICITUD_CUIDADOR]);

#####################################################################################################################################################
        // Sacar de la lista de tramites los ya aprobados en cese
        if(count($lista_aprobadas[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE]) > 0){
            $filtroSacarDuplicados = $this->consultaCuidado->actulizarData($lista_aprobadas[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE],$lista_dependiente);
            $lista_dependiente = $filtroSacarDuplicados;

            $mensaje = Constante::$bloqueosGloblalesCuidadores[5];
            $filtroEliminado = array();
            foreach ($lista_dependiente as $valores){
                if($valores['estado_solicitud'] == "5" && $valores['subtipo_solicitud'] == Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE &&
                    $valores['is_solicitud'] == true && strtoupper($valores['origen']) == 'RSH'){
                    continue;
                }else{
                    $filtroEliminado[] = $valores;
                }
            }
            $lista_dependiente = $filtroEliminado;

            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
        }

        // Sacar de la lista de tramites los ya aprobados en cese
        if(count($lista_aprobadas[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL]) > 0){
            $NuevoArrayLimpio = $this->consultaCuidado->actulizarData($lista_aprobadas[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL],$lista_dependiente);
            $lista_dependiente = $NuevoArrayLimpio;
            $mensaje = Constante::$bloqueosGloblalesCuidadores[5];
            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
        }

        // bloque de tramites en solicitud estado 11
        if(count($lista_borrador[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR]) > 0){
            $arrayList = $lista_borrador[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR];
            if($this->consultaCuidado->sacarSalicanteBorrador($this->data_dependiente['run'], $arrayList)){
                $mensaje = Constante::$bloqueosGloblalesCuidadores[7];

                if (!in_array($mensaje,$this->bloqueos)) {
                    $this->bloqueos[] = $mensaje;
                }
            }
        }
        if(count($lista_borrador[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO]) > 0){
            $arrayList = $lista_borrador[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO];
            if($this->consultaCuidado->sacarSalicanteBorrador($this->data_dependiente['run'], $arrayList)){
                $mensaje = Constante::$bloqueosGloblalesCuidadores[7];

                if (!in_array($mensaje,$this->bloqueos)) {
                    $this->bloqueos[] = $mensaje;
                }
            }
        }
        if(count($lista_borrador[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE]) > 0){
            $mensaje = Constante::$bloqueosGloblalesCuidadores[7];
            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
        }
        if(count($lista_borrador[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL]) > 0){
            $mensaje = Constante::$bloqueosGloblalesCuidadores[7];
            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
        }
        // integracion de resultados esperados en todos los elementos

        $this->data_dependiente["habilitar_tramite"] = true;
        $this->data_dependiente["cantidad_cuidadores"] = count($lista_dependiente);
        $cantidadPersonas = count($this->data_dependiente[Constante::DEPENDIENTE_LISTA_CUIDADOR]);
        if($cantidadPersonas >= 3){
        $mensaje = $cantidadPersonas == 3 ? Constante::$bloqueosListadeDependiente[7] : Constante::$bloqueosGloblalesCuidadores[8];
            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
            $this->data_dependiente["habilitar_tramite"] = false;
        }

        //contar la lista de cuidadores principales y secundarios
        $resultado = $this->consultaCuidado->cantador($lista_dependiente);
        $megeTotal = array_merge($this->data_dependiente,$resultado);
        $megeTotal["global_is_principal"] = $this->consultaCuidado->isModificar($lista_dependiente);
        $megeTotal["is_principal_solicitud"] = $this->consultaCuidado->isPrincipalSolicitud($lista_dependiente);

        $this->data_dependiente = $megeTotal;

        $this->data_dependiente['motivo_bloque'] = $this->bloqueos;

        $this->data_dependiente[Constante::DEPENDIENTE_LISTA_CUIDADOR] = $lista_dependiente;

        $request[Constante::DATOS_DEPENDIENTE] = $this->data_dependiente;

        return $next($request);

    }

}