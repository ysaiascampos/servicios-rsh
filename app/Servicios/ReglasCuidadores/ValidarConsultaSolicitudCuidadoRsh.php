<?php


namespace App\Servicios\ReglasCuidadores;

use App\Constantes\Constante;
use App\Http\ResponseMappers\PersonaCuidadoresMapper;
use App\Utiles;
use Closure;

class ValidarConsultaSolicitudCuidadoRsh
{
    private $CuidadoresTramite;
    private $mapear;

    public function __construct()
    {
        $this->CuidadoresTramite = new ConsultaCuidadoresCuidado();
        $this->mapear = new PersonaCuidadoresMapper();
    }

    ## todo: Esto saca las solicitudes que estan en curso, de todas las solicitudes
    public function handle($request, Closure $next)
    {
        $cantidadMaxima = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES__CANTMAX_CUIDADOR;
        $array_request = $request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_DEPENDIENTE];
        $nuevoFolioCuidador = $request[Constante::DOTOS_CUIDADOR]['nuevofolio'];
        $antiguofolioCuidador = $request[Constante::DOTOS_CUIDADOR]['folio'];
        $grupoSolicitudes = array();

        $rut_dependiente = null;
        $rut_cuidador = isset($request[Constante::DOTOS_CUIDADOR]['run'])
            ? $request[Constante::DOTOS_CUIDADOR]['run']
            : "";

        $result = $this->CuidadoresTramite->CuidadoresTramitePendiente($rut_dependiente, $rut_cuidador);

        // trae los datos el dependiente
        $cantidadSolicitud = count($result);
        if ($cantidadSolicitud > 0) {
            foreach ($result as $valor) {
                $grupo = array();
                $datosResult = $this->CuidadoresTramite->normalizarData($valor); // normailza los datos de solicitud
                # traigo los datos del integrante
                $Datosdependiente = $this->CuidadoresTramite->traerDatosIntegrante($valor->RUT_CUIDADO);

                # saco el folio familiar de la persona
                $dataFolio = $this->CuidadoresTramite->folioGrupoFamiliar($valor->RUT_CUIDADO);
                $folioDependiente = $dataFolio['nuevofolio'] != null ? $dataFolio['nuevofolio'] : $dataFolio['folio'];
                $foliocuidador = $nuevoFolioCuidador != null ? $nuevoFolioCuidador : $antiguofolioCuidador;

                #determino si el cuidador y el cuidado son del mismo grupo familiar
                $grupo['mismo_grupofamiliar'] = $folioDependiente == $foliocuidador ? true : false;
                $grupo['solicitud_en_tramite'] = true;
                $grupo['run_falso'] = Utiles::ponePuntosAlRun($valor->RUT_CUIDADO);
                $msg = $datosResult['estado_solicitud'] == "5" ? true : false;
                $grupo['msg_bloqueo'] = $this->CuidadoresTramite->mensajeria($datosResult['subtipo_solicitud'], $datosResult['estado_solicitud'],$msg);
                $grupo['msg'] = true;

                #agrupo los datos extraidos en uno y los normalizo
                $grupoArray = array_merge($grupo, $Datosdependiente, $dataFolio, $datosResult);
                $resultadoMapeado = $this->mapear->mapCuidadorDependiente($grupoArray);

                #todo: agrego todas las solicitudes hechas al array del grupo antes de agruparlos
                $grupoSolicitudes[] = $resultadoMapeado;
            }

            #normalizados los datos en el array, ahora los junto con el que trae a en request
            $array_request = array_merge($array_request, $grupoSolicitudes);
            $request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_DEPENDIENTE] = $array_request;

        }

        return $next($request);
    }

}