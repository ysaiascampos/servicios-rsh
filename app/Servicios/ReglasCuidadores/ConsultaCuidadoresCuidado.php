<?php


namespace App\Servicios\ReglasCuidadores;

use App\Clases\DatosDeContacto;
use App\Constantes\Constante;
use App\Http\ResponseMappers\PersonaCuidadoresMapper;
use App\Models\ComplementoCuidador;
use DB;
use Exception;

class ConsultaCuidadoresCuidado
{
    private $mapear;

    public function __construct()
    {
        $this->mapear = new PersonaCuidadoresMapper();
    }

    public function mostrarData($tipo)
    {
        try {
            $rsh = $tipo['rsh'];

            $result = DB::table('CUIDADOR_DEPENDIENTE')
                ->select(
                    'CUIDADOR_DEPENDIENTE.ID',
                    'CUIDADOR_DEPENDIENTE.RUN_CUIDADOR',
                    'CUIDADOR_DEPENDIENTE.RUN_DEPENDIENTE',
                    'CUIDADOR_DEPENDIENTE.IS_PRINCIPAL',
                    'CUIDADOR_DEPENDIENTE.ID_SOLICITUD',
                    'CUIDADOR_DEPENDIENTE.ORIGEN',
                    'CUIDADOR_DEPENDIENTE.IS_SOLICITUD',
                    'CUIDADOR_DEPENDIENTE.TIPO_MENSAJE'
                )
                ->when($tipo['tipo'] == Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CUIDADO,
                    function ($query) use ($tipo) {
                        return $query->where('CUIDADOR_DEPENDIENTE.RUN_DEPENDIENTE', '=', $tipo['run']);
                    }, function ($query) use ($tipo) {
                        return $query->where('CUIDADOR_DEPENDIENTE.RUN_CUIDADOR', '=', $tipo['run']);
                    })
                ->where('CUIDADOR_DEPENDIENTE.ESTADO', '=', Constante::VIGENTE_1_VIGENTE)
                ->where('CUIDADOR_DEPENDIENTE.PERIODO_RSH', '=', $tipo['rsh'])
                ->get();

        } catch (Exception $e) {
            throw new Exception("Tenemos un error en de consulta cuidadores cuidados en el metodo mostrarData");
        }


        return $result;
    }


    ## el tipo viene en verdadero para sacar las solicitudes 5.7.9 pero cuando viene falso saca los que estan en posible gestion pero no se ha realizado
    ## son los tramites pendientes
    public function CuidadoresTramitePendiente($rut_dependiente, $rut_cuidador, $tipo = null, $fecha = null)
    {
        try {
            // saca si hay un tramite con la diada, ahora el tipo 11 es de los que estan en borrador para eso se aplica otra regla
            $result = ComplementoCuidador::join('ab_solicitud', 'ab_solicitud.id_solicitud', '=', 'ab_complemento_cuidadores.id_solicitud')
                ->when($rut_cuidador, function ($query, $rut_cuidador) {
                    return $query->where('ab_complemento_cuidadores.rut_cuidador', '=', $rut_cuidador);
                })
                ->when($rut_dependiente, function ($query, $rut_dependiente) {
                    return $query->where('ab_complemento_cuidadores.rut_cuidado', '=', $rut_dependiente);
                })
                ->when($tipo === null, function ($query) {
                    return $query->where('AB_COMPLEMENTO_CUIDADORES.CALCULOACTIVO', '=', Constante::CALCULO_ACTIVO_2_PENDIENTE)
                        ->where('AB_COMPLEMENTO_CUIDADORES.VIGENTE', '=', Constante::CALCULO_ACTIVO_2_PENDIENTE)
                        ->whereNotIn('AB_SOLICITUD.ID_ESTADO', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS);
                }, function ($query) use ($fecha) {
                    return $query->where('AB_COMPLEMENTO_CUIDADORES.CALCULOACTIVO', '=', Constante::CALCULO_ACTIVO_1_APROBADO)
                        ->where('AB_COMPLEMENTO_CUIDADORES.VIGENTE', '=', Constante::CALCULO_ACTIVO_1_APROBADO)
                        ->where('AB_SOLICITUD.ID_ESTADO', '=', Constante::ESTADO_SOLICITUD_5_APROBADO)
                        ->whereRaw("TO_CHAR(AB_SOLICITUD.FECHA_MODIFICACION, 'YYYYMM') >= '".$fecha."'"); // Filtra por el mes actual
                })
                ->get();

        } catch (Exception $e) {
            throw new Exception("Tenemos un error de consulta de cuidadores cuidados en el metodo cuidadoresTamite");
        }
        return $result;
    }

    public function filtrarDataCuidadoCuidadores($DatosRequeridos)
    {
        $result = array();
        $dataGeneral = $DatosRequeridos; // trae todos los datos
        $filtro_array = [
            Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR => array(), #pertenece al grupo tramite cuidador
            Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO => array(), #pertenece al grupo tramite cuidado
            Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE => array(), #petenece al grupo tramite cese
            Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL => array(), #petenece al grupo de tramite cambio de rol
        ];

        foreach ($dataGeneral as $key => $data) {
            if ($data['estado_solicitud'] == "11") {
                switch ($data['subtipo_solicitud']) {
                    case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR:
                        $filtro_array[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR][] = $data;
                        unset($dataGeneral[$key]);
                        break;
                    case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO:
                        ## todo: se ponen en blanco los array y se extraen los comunes
                        $filtro_array[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO][] = $data;
                        unset($dataGeneral[$key]);
                        break;

                    case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE:
                        ## todo: se ponen en blanco los array y se extraen los comunes
                        $filtro_array[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE][] = $data;
                        unset($dataGeneral[$key]);
                        break;

                    case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL:
                        ## todo: se ponen en blanco los array y se extraen los comunes
                        $filtro_array[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL][] = $data;
                        unset($dataGeneral[$key]);
                        break;

                    default:
                        // No hacer nada si no es ninguno de los anteriores
                        break;
                }
            }
        }

        $result["borradores"] = $filtro_array;
        $result["nuevo_listado"] = array_values($dataGeneral);

        return $result;
    }


    public function filtrarDataCeseCambioRol($DatosRequeridos)
    {
        $result = array();
        $dataGeneral = $DatosRequeridos; // trae todos los datos
        $filtro_array = [
            Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE => array(), #pertenece al grupo de cese
            Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL => array(), #pertenece al grupo de Cambio de Rol
        ];

        foreach ($dataGeneral as $key => $data) {
            if ($data['estado_solicitud'] != "11" && $data['estado_solicitud'] != "5") {
                switch ($data['subtipo_solicitud']) {
                    case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE:
                        ## todo: se ponen en blanco los array y se extraen los comunes
                        $filtro_array[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE][] = $data;

                        unset($dataGeneral[$key]);
                        break;

                    case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL:
                        ## todo: se ponen en blanco los array y se extraen los comunes
                        $filtro_array[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL][] = $data;
                        unset($dataGeneral[$key]);
                        break;

                    default:
                        // No hacer nada si no es ninguno de los anteriores
                        break;
                }
            }
        }

        $result["borradores"] = $filtro_array;
        $result["nuevo_listado"] = array_values($dataGeneral);

        return $result;
    }

    public function filtrarAprobadaCeseCambioRol($DatosRequeridos)
    {
        $result = array();
        $dataGeneral = $DatosRequeridos; // trae todos los datos
        $filtro_array = [
            Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE => array(), #pertenece al grupo de cese
            Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL => array(), #pertenece al grupo de Cambio de Rol
        ];

        foreach ($dataGeneral as $key => $data) {
            if ($data["estado_solicitud"] == "5") {
                switch ($data['subtipo_solicitud']) {
                    case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE:
                        ## todo: se ponen en blanco los array y se extraen los comunes
                        $filtro_array[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE][] = $data;
                        unset($dataGeneral[$key]);
                        break;

                    case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL:
                        ## todo: se ponen en blanco los array y se extraen los comunes
                        $filtro_array[Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL][] = $data;
                        unset($dataGeneral[$key]);
                        break;

                    default:
                        // No hacer nada si no es ninguno de los anteriores
                        break;
                }
            }
        }

        $result["borradores"] = $filtro_array;
        $result["nuevo_listado"] = array_values($dataGeneral);

        return $result;
    }

    public function normalizarData($data)
    {
        return [
            'run_cuidador' => isset($data->RUT_CUIDADOR) ? $data->RUT_CUIDADOR : null,
            'run_dependiente' => isset($data->RUT_CUIDADO) ? $data->RUT_CUIDADO : null,
            'id_solicitud' => isset($data->ID_SOLICITUD) ? $data->ID_SOLICITUD : null,
            'subtipo_solicitud' => isset($data->ID_SUB_TIPO_SOLICITUD) ? $data->ID_SUB_TIPO_SOLICITUD : null,
            'tipo_solicitud' => isset($data->SOLICITUD) ? $data->SOLICITUD : null,
            'fecha_creacion' => isset($data->FECHA_CREACION) ? $data->FECHA_CREACION : null,
            'fecha_modificacion' => isset($data->FECHA_MODIFICACION) ? $data->FECHA_MODIFICACION : null,
            'tipo_canal' => isset($data->CANAL) ? $data->CANAL : null,
            'estado_solicitud' => isset($data->ID_ESTADO) ? $data->ID_ESTADO : null,
            'id_padre_solicitud' => isset($data->ID_PADRE_SOLICITUD) ? $data->ID_PADRE_SOLICITUD : null,
            'run_ciudadano' => isset($data->RUN_CIUDADANO) ? $data->RUN_CIUDADANO : null,
            'principal_secundario' => isset($data->PRINCIPAL) ? $data->PRINCIPAL : null,
        ];
    }

    public function traerDatosIntegrante($rut)
    {
        try {
            $Datosdependiente = DatosDeContacto::obtenerDatosContacto($rut);
            $Datosdependiente = $this->mapear->map($Datosdependiente);
        } catch (Exception $e) {
            throw new Exception("Tenemos un inconveniente al traer los datos de la persona solicitada");
        }
        return $Datosdependiente;
    }

    public function folioGrupoFamiliar($run)
    {
        $datos = array();
        $resultado = $this->traerFolio($run);
        $datos['folio'] = isset($resultado[0]->FOLIO) ? $resultado[0]->FOLIO : null;
        $datos['nuevofolio'] = isset($resultado[0]->NUEVOFOLIO) ? $resultado[0]->NUEVOFOLIO : null;
        return $datos;
    }

    public function traerFolio($run)
    {
        $validar = true;
        try {
            $result = DB::table('GRUPOS_FAMILIARES as gf')
                ->join('BENEFICIARIOS_GRUPOSFAMILIARES as bgf', 'gf.GRUPFAMI_ID', '=', 'bgf.BNFGRUPFAMI_GRUPOFAMILIAR')
                ->join('PERSONAS as p', 'bgf.BNFGRUPFAMI_PERSONA', '=', 'p.PER_ID')
                ->select(
                    'gf.GRUPFAMI_FOLIO as folio',
                    'gf.GRUPFAMI_NUEVOFOLIO as nuevofolio'
                )
                ->where('p.PER_RUN', '=', $run)
                ->where('gf.GRUPFAMI_VIGENTE', '=', Constante::VIGENTE_1_VIGENTE)
                ->where('gf.GRUPFAMI_CALCULOACTIVO', '=', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('gf.GRUPFAMI_ESTADO', '=', Constante::ESTADO_GRUPO_FAMILIAR_7_APROBADO)
                ->where('bgf.BNFGRUPFAMI_VIGENTE', '=', Constante::VIGENTE_1_VIGENTE)
                ->where('bgf.BNFGRUPFAMI_CALCULOACTIVO', '=', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('p.PER_VIGENTE', '=', Constante::VIGENTE_1_VIGENTE)
                ->where('p.PER_CALCULOACTIVO', '=', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->get();


        } catch (Exception $e) {
            throw new Exception('Error en la base de datos al traer los folios del integrante');
        }

        return $result->toArray();
    }

    ## une los datos correspondiente a los que estan haciendo el tramite
    public function filtroDeTramite($dataCaso, $dataGeneral)
    {
        $resultado_combinado = array_map(function ($itemGeneral) use ($dataCaso) {
            // Filtrar en dataCese para encontrar coincidencias por run_cuidador y run_dependiente
            $cuidador_encontrado = array_filter($dataCaso, function ($itemCese) use ($itemGeneral) {
               return $itemCese['run_cuidador'] === $itemGeneral['run_cuidador'] &&
                   $itemCese['run_dependiente'] === $itemGeneral['run_dependiente'];
            });

            // Si encontramos una coincidencia, combinamos los datos de ambos arrays
            if (!empty($cuidador_encontrado)) {
                // Tomamos el primer elemento encontrado en la coincidencia
                $listafiltro = reset($cuidador_encontrado);
                $listaGeneral = $itemGeneral;
                // Devolvemos un nuevo array que combina los datos seleccionados de ambos arrays
                $mensajes_array = array_merge($listaGeneral['msg_bloqueo'],$listafiltro['msg_bloqueo']);

                return [
                    "nombres"=> $listaGeneral['nombres'],
                    "edad"=> $listaGeneral['edad'],
                    "run_falso"=> $listaGeneral['run_falso'],
                    "menor_edad"=> $listaGeneral['menor_edad'],
                    "difunto"=> $listaGeneral['difunto'],
                    "id"=> $listaGeneral['id'],
                    "run_cuidador"=> $listaGeneral['run_cuidador'],
                    "run_dependiente"=> $listaGeneral['run_dependiente'],
                    "is_principal"=> $listaGeneral['is_principal'],
                    "origen"=> $listaGeneral['origen'],
                    "is_solicitud" =>$listaGeneral['is_solicitud'],
                    "modificar_tramite"=> $listaGeneral['modificar_tramite'],
                    "fecha_carga"=> $listaGeneral['fecha_carga'],
                    "folio"=> $listafiltro['folio'],
                    "nuevofolio"=> $listafiltro['nuevofolio'],
                    "mismo_grupofamiliar"=> $listafiltro['mismo_grupofamiliar'],
                    "solicitud_en_tramite"=> $listafiltro['solicitud_en_tramite'],
                    "subtipo_solicitud"=> $listafiltro['subtipo_solicitud'],
                    "tipo_solicitud"=> $listafiltro['tipo_solicitud'],
                    "tipo_canal"=> $listafiltro['tipo_canal'],
                    "estado_solicitud"=> $listafiltro['estado_solicitud'],
                    "fecha_creacion"=> $listafiltro['fecha_creacion'],
                    "fecha_modificacion"=> $listafiltro['fecha_modificacion'],
                    "id_solicitud"=> $listafiltro['id_solicitud'],
                    "id_padre_solicitud"=> $listafiltro['id_padre_solicitud'],
                    "run_ciudadano"=> $listafiltro['run_ciudadano'],
                    "principal_secundario" => $listafiltro['principal_secundario'],
                    "msg_bloqueo" => $mensajes_array,
                    "msg" => $listafiltro['msg'],
                ];
            }

            return null; // Si no hay coincidencia, retorna null
        }, $dataGeneral);

        return $resultado_combinado;
    }

    ## une los datos correspondiente a los que estan haciendo el tramite
    public function limpiarArray($dataCaso, $dataGeneral)
    {
        $resultado_combinado = array_map(function ($itemGeneral) use ($dataCaso) {
            // Filtrar en dataCaso para encontrar coincidencias por run_cuidador y run_dependiente
            $cuidador_encontrado = array_filter($dataCaso, function ($itemCese) use ($itemGeneral) {
                return $itemCese['run_cuidador'] === $itemGeneral['run_cuidador'] &&
                    $itemCese['run_dependiente'] === $itemGeneral['run_dependiente'];
            });

            // Si NO encontramos coincidencia, retornamos el itemGeneral
            if (empty($cuidador_encontrado)) {
                $infoItem = $itemGeneral;

                return $itemGeneral;
            }

            return null; // Si hay coincidencia, retornamos null
        }, $dataGeneral);

        // Filtramos los elementos que no sean null
        $resultado_combinado = array_filter($resultado_combinado);
        $resultado = array_values($resultado_combinado);

        return $resultado;
    }

    public function cantador($dato){
        $conteo = array_reduce($dato, function ($carry, $item) {
            if ($item['is_principal'] === "1") {
                $carry['cantidad_principal']++;
            } elseif ($item['is_principal'] === "0") {
                $carry['cantidad_secundario']++;
            }
            return $carry;
        }, ['cantidad_principal' => 0, 'cantidad_secundario' => 0]);

        return $conteo;
    }


    public function isModificar($dataGeneral){
        $principalNoModificable = array_filter($dataGeneral, function($item) {
            $valor = $item['is_principal'];
            $valor2 = $item['modificar_tramite'];
            return  $item['is_principal'] == "1"  && $item['modificar_tramite'] == false;
        });

        $result = (count($principalNoModificable) > 0) ? false : true;

        return $result;
    }

    public function isPrincipalSolicitud($dataGeneral){
        $principalNoModificable = array_filter($dataGeneral, function($item) {
            return   $item['principal_secundario'] == "1";
        });

        $result = (count($principalNoModificable) > 0) ? false : true;

        return $result;
    }



    public function removerData($datos,$keyes){
        foreach ($keyes as $key) {
            unset($datos[$key]);
        }
        return $datos;
    }

    public function actulizarData($listaGeneral,$lista_filtrado){
        $resultadoCaso = self::filtroDeTramite($listaGeneral,$lista_filtrado);
        $filtroSacarDuplicados = self::limpiarArray($listaGeneral,$lista_filtrado);
        $NuevoArray = array_merge($filtroSacarDuplicados,$resultadoCaso);
        $resultado = array_filter($NuevoArray); // limpia o extrae los null del array
        $NuevoArrayLimpio = array_values($resultado);
        return $NuevoArrayLimpio;
    }

    public function normalizarInfo($data)
    {
        $info = array();
        $msg = false;
        if(isset($data['IS_SOLICITUD'])) {
            if($data['IS_SOLICITUD'] == "0" || $data['IS_SOLICITUD'] == null) {
                $info[] = $data['TIPO_MENSAJE'];
                $msg = true;
            }elseif($data['IS_SOLICITUD'] == "2") {
                $info[] = $data['TIPO_MENSAJE'];
                $msg = true;
            }
        }
        return [
            'id' => isset($data['ID']) ? $data['ID'] : null,
            'id_solicitud' => isset($data['ID_SOLICITUD']) ? $data['ID_SOLICITUD'] : null,
            'run_cuidador' => isset($data['RUN_CUIDADOR']) ? $data['RUN_CUIDADOR'] : null,
            'run_dependiente' => isset($data['RUN_DEPENDIENTE']) ? $data['RUN_DEPENDIENTE'] : null,
            'is_principal' => isset($data['IS_PRINCIPAL']) ? $data['IS_PRINCIPAL'] : null,
            'origen' => isset($data['ORIGEN']) ? $data['ORIGEN'] : null,
            'is_solicitud' => isset($data['IS_SOLICITUD']) &&  ($data['IS_SOLICITUD'] == "1" || $data['IS_SOLICITUD'] == "2") ? true : false,
            'fecha_carga' => isset($data['FECHA_CARGA']) ? $data['FECHA_CARGA'] : null,
            'folio' => isset($data['folio']) ? $data['folio'] : null,
            'nuevofolio' => isset($data['nuevofolio']) ? $data['nuevofolio'] : null,
            'run_falso' => isset($data['run_falso']) ? $data['run_falso'] : null,
            'mismo_grupofamiliar' => isset($data['mismo_grupofamiliar']) ? $data['mismo_grupofamiliar'] : null,
            "msg_bloqueo" => $info,
            "msg" => $msg,
        ];
    }


    public function bloqueosDeadasRepetidas($lista,$resultado_combinado){
        // saca los datos que estan repetidos y pone el mensaje de bloqueos a la diadas del array correspondiente los que no tienen solicitud
        $resultado = array_map(function($item) use ($resultado_combinado) {
            $coincidencia = array_filter($resultado_combinado, function($caso) use ($item) {
                return $item['run_cuidador'] == $caso['run_cuidador'] &&
                    $item['run_dependiente'] == $caso['run_dependiente'];
            });
            if (!empty($coincidencia)) {
                $listaMsg = array();
                if($item['solicitud_en_tramite'] == false){
                    $mensaje = Constante::$bloqueosGloblalesCuidadores[3];
                    if(!array_key_exists($mensaje,$listaMsg)){
                        $listaMsg[] = $mensaje;
                    }
                }

                if($item['solicitud_en_tramite']){
                    if($item['subtipo_solicitud'] != null){
                        switch ($item['subtipo_solicitud']) {
                            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR:
                                $mensaje = Constante::$bloqueosListadeCuidador[6];
                                if(!array_key_exists($mensaje,$listaMsg)){
                                    $listaMsg[] = $mensaje;
                                }
                                break;
                            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO:
                                $mensaje = Constante::$bloqueosListadeDependiente[6];
                                if(!array_key_exists($mensaje,$listaMsg)){
                                    $listaMsg[] = $mensaje;
                                }
                                break;
                            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE:
                                $mensaje = Constante::$bloqueosListadeCese[7];
                                if(!array_key_exists($mensaje,$listaMsg)){
                                    $listaMsg[] = $mensaje;
                                }
                                break;
                            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL:
                                $mensaje = Constante::$bloqueosListadeCambioRol[7];
                                if(!array_key_exists($mensaje,$listaMsg)){
                                    $listaMsg[] = $mensaje;
                                }
                                break;
                        }
                    }
                }

                // incorporación de nuevos mensajes al bloque de array unitario
                $limpiaData = array_values($listaMsg);
                if(!isset($item['msg_bloqueo'])){
                    $item['msg_bloqueo'] = $limpiaData;
                }else{
                    $limpiaData = array_merge($item['msg_bloqueo'],$limpiaData);
                    $limpiaData = array_values($limpiaData);
                    $item['msg_bloqueo'] = $limpiaData;
                }
                $item['msg'] = true;
            }

            return $item;
        }, $lista);

        $resultado = array_filter($resultado);
        $resultado = array_values($resultado);
        return $resultado;
    }


    public function mensajeria($subsolicitud,$estado = false, $msg = false){
        $listaMsg = array();
        switch ($subsolicitud) {
            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR:
                $mensaje = Constante::$bloqueosListadeCuidador[1];
                if($estado == "11"){
                    $mensaje = Constante::$bloqueosListadeCuidador[4];
                }
                if($estado == "5"){
                    $mensaje = Constante::$bloqueosListadeCuidador[5];
                }
                if(!array_key_exists($mensaje,$listaMsg)){
                    $listaMsg[] = $mensaje;
                }
                break;
            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO:
                $mensaje = Constante::$bloqueosListadeDependiente[1];
                if($estado == "11"){
                    $mensaje = Constante::$bloqueosListadeDependiente[4];
                }
                if($estado == "5"){
                    $mensaje = Constante::$bloqueosListadeDependiente[5];
                }
                if(!array_key_exists($mensaje,$listaMsg)){
                    $listaMsg[] = $mensaje;
                }
                break;
            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE:
                $mensaje = Constante::$bloqueosListadeCese[1];
                if($estado == "11"){
                    $mensaje = Constante::$bloqueosListadeCese[4];
                }
                if($estado == "5" && $msg){
                    $mensaje = Constante::$bloqueosListadeCese[5];
                }elseif($estado == "5"){
                    $mensaje = Constante::$bloqueosListadeCese[5];
                }
                if(!array_key_exists($mensaje,$listaMsg)){
                    $listaMsg[] = $mensaje;
                }
                break;
            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL:
                $mensaje = Constante::$bloqueosListadeCambioRol[1];
                if($estado == "11"){
                    $mensaje = Constante::$bloqueosListadeCambioRol[4];
                }
                if($estado == "5" && $msg){
                    $mensaje = Constante::$bloqueosListadeCambioRol[5];
                }elseif($estado == "5"){
                    $mensaje = Constante::$bloqueosListadeCambioRol[6];
                }
                if(!array_key_exists($mensaje,$listaMsg)){
                    $listaMsg[] = $mensaje;
                }
                break;
        }

        $resultado = array_filter($listaMsg);
        $resultado = array_values($resultado);
        return $resultado;
    }

    // TODO: Permite si el solicitante tiene una solicitud en borrador que la haya hecho el sino devuelve false
    public function sacarSalicanteBorrador($rut,$Listasolicitudes){
        // Filtrar el array para encontrar coincidencias con el RUT
        $resultado = array_filter($Listasolicitudes, function($item) use ($rut) {
            return $item['run_ciudadano'] == $rut;
        });

        // Verificar si se encontraron coincidencias
        if (!empty($resultado)) {
            return true;
        } else {
            return false;
        }
    }

    public function filtrarLosAprobadosCSO($array1, $array2){
        // Extraemos los IDs de Array1
        $array1_ids = array_column($array1, "id_solicitud");

        // Usamos array_filter para filtrar según las condiciones
        $resultado = array_filter($array2, function ($item) use ($array1_ids) {
            // Mantener elementos con subtipo_solicitud 3 y 4 sin cambios
            if (!in_array($item["subtipo_solicitud"], [1, 2])) {
                return true;
            }
            // Filtrar los elementos de subtipo_solicitud 1 y 2 si su id_solicitud no está en Array1
            return !in_array($item["id_solicitud"], $array1_ids);
        });

        // Reindexamos el resultado
        $resultado = array_values($resultado);

        return $resultado;

    }

}


