<?php

namespace App\Servicios;


use App\Servicios\ReglasCuidadores\ClasePipelineCuidadores;
use App\Servicios\ReglasCuidadores\FiltarCuidadorCeseCambioRol;
use App\Servicios\ReglasCuidadores\FiltarDependienteCeseCambioRol;
use App\Servicios\ReglasCuidadores\FiltrarSegunEstadoCuidadorRsh;
use App\Servicios\ReglasCuidadores\FiltrarSegunEstadoDependienteRsh;
use App\Servicios\ReglasCuidadores\FiltrarSolicitudesAprobadasCuidador;
use App\Servicios\ReglasCuidadores\FiltrarSolicitudesAprobadasDependientes;
use App\Servicios\ReglasCuidadores\IngresoDatosCuidadores;
use App\Servicios\ReglasCuidadores\NormalizacionCuidadoresRsh;
use App\Servicios\ReglasCuidadores\NormalizacionDependienteRsh;
use App\Servicios\ReglasCuidadores\ResultadoCuidadorDependienteRsh;
use App\Servicios\ReglasCuidadores\ResultadoCuidadoresRsh;
use App\Servicios\ReglasCuidadores\ResultadoDependienteRsh;
use App\Servicios\ReglasCuidadores\SiesCuidador;
use App\Servicios\ReglasCuidadores\SiesDependiente;
use App\Servicios\ReglasCuidadores\TieneRsh;
use App\Servicios\ReglasCuidadores\ValidarAprobadaSolicitudCuidadoRsh;
use App\Servicios\ReglasCuidadores\ValidarAprobadaSolicitudDependienteRsh;
use App\Servicios\ReglasCuidadores\ValidarConsultaSolicitudCuidadoRsh;
use App\Servicios\ReglasCuidadores\ValidarConsultaSolicitudDependienteRsh;
use App\Servicios\ReglasCuidadores\ValidarCuidadorRsh;
use App\Servicios\ReglasCuidadores\ValidarDependienteRsh;
use App\Servicios\ReglasCuidadores\ValidarGruposFamiliaresRsh;
use App\Servicios\ReglasCuidadores\ValidarIgualCuidadorCuidadoRsh;
use App\Servicios\ReglasCuidadores\ValidarPeriodoRsh;
use App\Servicios\ReglasCuidadores\ValidaTipoViviendaDependienteRsh;

class DatosCuidadorDependiente
{

    private $validar;
    private $listaClases;
    private $pipelineCuidadores;
    private $datosResultados;

    public function __construct($input)
    {
        $this->datosRespuesta = $input;
        $this->validar = new IngresoDatosCuidadores();
        $this->pipelineCuidadores = new ClasePipelineCuidadores();
        $this->listaClases = [
            ValidarPeriodoRsh::class, // saca la última carga de cuidadores y cuidados del rsh
        ];
    }

    public function show()
    {

        $info = $this->validar->ValidarDatos($this->datosRespuesta);
        if (isset($info['estatus'])) {
            return response()->json([
                'status' => 'error',
                'errores' => $info['errores'],
            ], 200);
        }

        if($info['tipo-registro'] == 3){
            if($info['cuidador'] == "" && $info['dependiente'] == ""){
                return $this->sinRut();
            }
            if($info['dependiente'] != ""){
                $info['cuidador'] = $info['dependiente'];
            }
            if($info['cuidador'] != ""){
                $info['dependiente'] = $info['cuidador'];
            }
        }

        $this->datosResultados = [
            "origen-registro" => $info['origen-registro'],
            "tipo-registro" => $info['tipo-registro'],
            'motivos_bloqueo' => array(),
            'data_cuidador' => $info['cuidador'],
            'data_dependiente' => $info['dependiente'],
        ];

        $Cuidador = $info['cuidador']['run'] ?? null;
        $Dependiente = $info['dependiente']['run'] ?? null;

        if($info['tipo-registro'] != "3"){
            if (!empty($Cuidador) && !empty($Dependiente)) {
                return $this->procesarAmbos();
            } elseif (!empty($Cuidador)) {
                return $this->procesarSoloCuidador();
            } elseif (!empty($Dependiente)) {
                return $this->procesarSoloDependiente();
            } else {
                return $this->sinRut();
            }
        }else{
            return $this->tipoCese();
        }



    }

    private function procedarDatos()
    {
        return $this->pipelineCuidadores->procesarDatos($this->datosResultados, $this->listaClases);
    }

    private function procesarAmbos()
    {
        // Ver datos del cuidador y dependiente, se agrega la lista de clases
        $lista = [
            //valida si tiene rsh la persona cuidadora
            TieneRsh::class,
            // valida el tipo de vivienda del dependiente y si tiene rsh
          //  SiesDependiente::class,
            // trae todos los datos de dependiente y quien los cuida
           // SiesCuidador::class,
            // trae todos los datos de dependiente y quien los cuida
            ValidaTipoViviendaDependienteRsh::class,
            // trae todos los datos de dependiente y quien los cuida
            ValidarDependienteRsh::class,
            // trae todos los datos del cuidador y a quien cuida
            ValidarCuidadorRsh::class,
            // trae los gupos familiares con sus respectivos nombres
            ValidarGruposFamiliaresRsh::class,
            // Saca las solicitudes en tramite del Cuidador
            ValidarConsultaSolicitudCuidadoRsh::class,
            // trae las solicitudes aprobadas de cuidador en tramite de Cuidador, cuidado, cese y Cambio Rol
            ValidarAprobadaSolicitudCuidadoRsh::class,
            // Saca las solicitudes en tramite de Dependiente
            ValidarConsultaSolicitudDependienteRsh::class,
            // trae las solicitudes aprobadas de Dependiente en tramite de Cuidador, cuidado, cese y Cambio Rol
            ValidarAprobadaSolicitudDependienteRsh::class,
            ## sacar los estado 11 de cuidadores
            FiltrarSegunEstadoCuidadorRsh::class,
            ## sacar los estado 11 de dependiente
            FiltrarSegunEstadoDependienteRsh::class,
            // Los tramites que estan aprobados y estan pendiente para registro administrativo cuidador
            FiltarCuidadorCeseCambioRol::class,
            // Los tramites que estan aprobados y estan pendiente para registro administrativo dependiente
            FiltarDependienteCeseCambioRol::class,
            // Saca las solicitudes aprobadas en estado 5 en cese y cambio de rol de cuidadores
            FiltrarSolicitudesAprobadasCuidador::class,
            // Saca las solicitudes aprobadas en estado 5 en cese y cambio de rol de dependiente
            FiltrarSolicitudesAprobadasDependientes::class,
            // compara los datos de cuidador y dependiente despues del friltro
            NormalizacionCuidadoresRsh::class,
            NormalizacionDependienteRsh::class,
            // normaliza los datos para los datos individuales
             ValidarIgualCuidadorCuidadoRsh::class,
            // saca los mensajes y filtra todos los datos ya sacado en el cuidador
            ResultadoCuidadorDependienteRsh::class,

        ];
        $this->listaClases = array_merge($this->listaClases, $lista);

        return self::procedarDatos();
    }


    private function procesarSoloCuidador()
    {
        // Ver datos solo del cuidador
        $lista = [
            TieneRsh::class,
            //valida si tiene rsh la persona cuidadora
        //    SiesCuidador::class,
            // trae todos los datos de dependiente y quien los cuida
            ValidarCuidadorRsh::class,
            // trae los gupos familiares con sus respectivos nombres
            ValidarGruposFamiliaresRsh::class,
            // Saca las solicitudes en tramite del Cuidador
            ValidarConsultaSolicitudCuidadoRsh::class,
            // trae las solicitudes aprobadas de cuidador en tramite de Cuidador, cuidado, cese y Cambio Rol
            ValidarAprobadaSolicitudCuidadoRsh::class,
            FiltrarSegunEstadoCuidadorRsh::class,
            ## sacar los estado 11 de dependiente
            FiltarCuidadorCeseCambioRol::class,
            // Los tramites que estan aprobados y estan pendiente para registro administrativo dependiente
            FiltrarSolicitudesAprobadasCuidador::class,
            // Saca las solicitudes aprobadas en estado 5 en cese y cambio de rol de dependiente
            NormalizacionCuidadoresRsh::class,
            // normaliza los datos para los datos individuales
            ResultadoCuidadoresRsh::class,
        ]; // Agrega las clases necesarias si es necesario
        $this->listaClases = array_merge($this->listaClases, $lista);

        return self::procedarDatos();
    }

    private function procesarSoloDependiente()
    {
        // Ver datos solo del dependiente
        $lista = [
            // valida el tipo de vivienda del dependiente y si tiene rsh
            ValidaTipoViviendaDependienteRsh::class,
            //valida si tiene datos como cuidador para no seleccionarlo
        //    SiesDependiente::class,
            // trae todos los datos de dependiente y quien los cuida
            ValidarDependienteRsh::class,
            // trae todos los datos del cuidador y a quien cuida
            ValidarGruposFamiliaresRsh::class,
            // Saca las solicitudes en tramite del Cuidador
            ValidarConsultaSolicitudDependienteRsh::class,
            // trae las solicitudes aprobadas de Dependiente en tramite de Cuidador, cuidado, cese y Cambio Rol
            ValidarAprobadaSolicitudDependienteRsh::class,
            ## sacar los estado 11 de cuidadores
            FiltrarSegunEstadoDependienteRsh::class,
            // Los tramites que estan aprobados y estan pendiente para registro administrativo cuidador
            FiltarDependienteCeseCambioRol::class,
            // Saca las solicitudes aprobadas en estado 5 en cese y cambio de rol de cuidadores
            FiltrarSolicitudesAprobadasDependientes::class,
            // compara los datos de cuidador y dependiente despues del friltro
            NormalizacionDependienteRsh::class,
            // normaliza los datos para los datos individuales
            ResultadoDependienteRsh::class,

        ]; // Agrega las clases necesarias si es necesario
        $this->listaClases = array_merge($this->listaClases, $lista);

        return self::procedarDatos();
    }

    private function sinRut()
    {
        // Ningún RUT está presente
        return response()->json([
            'status' => 'success',
            'msg' => 'No hay rut ingresados en la aplicación',
        ], 200);
    }


    private function  tipoCese(){
        $resultado = $this->procesarAmbos();
        return $resultado;
    }


}
