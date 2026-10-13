<?php


namespace App\Servicios\ReglasCuidadores;

use App\Constantes\Constante;
use Closure;
use Exception;

class NormalizacionCuidadoresRsh
{
    private $consultaCuidado;
    private $tipoSolicitud;
    private $cantidadMaxima;
    private $bloqueos = array();
    private $data_cuidador;

    public function __construct()
    {
        $this->consultaCuidado = new ConsultaCuidadoresCuidado();
        $this->tipoSolicitud = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CUIDADOR;
        $this->cantidadMaxima = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES__CANTMAX_CUIDADOR;
    }

    public function handle($request, Closure $next)
    {
        $this->data_cuidador = $request[Constante::DOTOS_CUIDADOR];
//        $bloqueos = $request["motivos_bloqueo"];
        ## Trae la lista de dependiente actual que tiene el cuidador
        $lista_cuidadores = isset($this->data_cuidador[Constante::CUIDADOR_LISTA_DEPENDIENTE])
            ? $this->data_cuidador[Constante::CUIDADOR_LISTA_DEPENDIENTE]
            : array();

        ## lista de solicitudes que hay en borrar en estado 11
        $lista_borrador = isset($this->data_cuidador[Constante::CUIDADOR_LISTA_ESTADO11_DEPENDIENTE])
            ? $this->data_cuidador[Constante::CUIDADOR_LISTA_ESTADO11_DEPENDIENTE]
            : [
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR  => array(),
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO  => array(),
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE => array(),
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL => array()
            ];

        ## lista de solicitudes en tramite de estado tramotacion de cese y cambio de rol
        $lista_solicitudes = isset($this->data_cuidador[Constante::CUIDADOR_LISTA_ESTADO_SOLICITUD_DEPENDIENTE])
            ? $this->data_cuidador[Constante::CUIDADOR_LISTA_ESTADO_SOLICITUD_DEPENDIENTE]
            : [
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE => array(),
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL => array()
            ];

        ## lista de solicitudes aprobadas por municipal de cese y cambio de rol
        $lista_aprobadas = isset($this->data_cuidador[Constante::CUIDADOR_LISTA_APROBADO_SOLICITUD_DEPENDIENTE])
            ? $this->data_cuidador[Constante::CUIDADOR_LISTA_APROBADO_SOLICITUD_DEPENDIENTE]
            : [
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE => array(),
                Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL => array()
            ];



        // sacar lista de cese si hay en tramite
        if(count($lista_solicitudes[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE]) > 0){
            $NuevoArrayLimpio = $this->consultaCuidado->actulizarData($lista_solicitudes[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE],$lista_cuidadores);
            $lista_cuidadores = $NuevoArrayLimpio;
            $mensaje = Constante::$bloqueosGloblalesCuidadores[6];
            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
        }
        // sacar lista de Cambio de Rol si hay en tramite
        if(count($lista_solicitudes[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL]) > 0){
            $NuevoArrayLimpio = $this->consultaCuidado->actulizarData($lista_solicitudes[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL],$lista_cuidadores);
            $lista_cuidadores = $NuevoArrayLimpio;
            $mensaje = Constante::$bloqueosGloblalesCuidadores[6];
            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
        }

        // una vez sacado los valores en tramite de cese se elimina la propiedad  dependiete_solicitud
        unset($this->data_cuidador[Constante::CUIDADOR_LISTA_ESTADO_SOLICITUD_DEPENDIENTE]);


        // Sacar de la lista de tramites los ya aproBados en cese
        if(count($lista_aprobadas[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE]) > 0){
            $filtroSacarDuplicados = $this->consultaCuidado->actulizarData($lista_aprobadas[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE],$lista_cuidadores);
            $lista_cuidadores = $filtroSacarDuplicados;

            $mensaje = Constante::$bloqueosGloblalesCuidadores[5];
            $filtroEliminado = array();
            foreach ($lista_cuidadores as $valores){
                if($valores['estado_solicitud'] == "5" && $valores['subtipo_solicitud'] == Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE &&
                    $valores['is_solicitud'] == true && strtoupper($valores['origen']) == 'RSH'){
                    continue;
                }else{
                    $filtroEliminado[] = $valores;
                }
            }
            $lista_cuidadores = $filtroEliminado;

            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
        }

        // Sacar de la lista de tramites los ya aproBados en cambio de rol
        if(count($lista_aprobadas[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL]) > 0){
            $NuevoArrayLimpio = $this->consultaCuidado->actulizarData($lista_aprobadas[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL],$lista_cuidadores);
            $lista_cuidadores= $NuevoArrayLimpio;
            $mensaje = Constante::$bloqueosGloblalesCuidadores[5];
            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
        }

        // bloque de tramites en solicitud estado 11
        if(count($lista_borrador[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR]) > 0){
            $arrayList = $lista_borrador[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR];
            if($this->consultaCuidado->sacarSalicanteBorrador($this->data_cuidador['run'], $arrayList)){
                $mensaje = Constante::$bloqueosGloblalesCuidadores[7];

                if (!in_array($mensaje,$this->bloqueos)) {
                    $this->bloqueos[] = $mensaje;
                }
            }
        }
        if(count($lista_borrador[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO]) > 0){
            $arrayList = $lista_borrador[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO];
            if($this->consultaCuidado->sacarSalicanteBorrador($this->data_cuidador['run'], $arrayList)){
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
        $this->data_cuidador["habilitar_tramite"] = true;
        $this->data_cuidador["cantidad_dependientes"] = count($lista_cuidadores);
        $cantidadPersonas = count($lista_cuidadores);
        if($cantidadPersonas >= 3){
            $mensaje = $cantidadPersonas == 3 ? Constante::$bloqueosListadeCuidador[7] : Constante::$bloqueosGloblalesCuidadores[2];
            if (!in_array($mensaje,$this->bloqueos)) {
                $this->bloqueos[] = $mensaje;
            }
            $this->data_cuidador["habilitar_tramite"] = false;
        }

        //contar la lista de cuidadores principales y secundarios
        $resultado = $this->consultaCuidado->cantador($lista_cuidadores);
        $megeTotal = array_merge($this->data_cuidador,$resultado);
        $megeTotal["global_is_principal"] = $this->consultaCuidado->isModificar($lista_cuidadores);
        $megeTotal["is_principal_solicitud"] = $this->consultaCuidado->isPrincipalSolicitud($lista_cuidadores);

        $this->data_cuidador = $megeTotal;

        $this->data_cuidador['motivo_bloque'] = $this->bloqueos;

        $this->data_cuidador[Constante::CUIDADOR_LISTA_DEPENDIENTE] = $lista_cuidadores;

        $request[Constante::DOTOS_CUIDADOR] = $this->data_cuidador;

        return $next($request);

    }
}
