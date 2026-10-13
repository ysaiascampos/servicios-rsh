<?php

namespace App\Http\ResponseMappers;

class PersonaCuidadoresMapper
{
  public function map($ciudadano)
    {
        return [
            'run' => self::entero($ciudadano->PER_RUN),
            'dv' => $ciudadano->PER_DV,
            'nombres' => $ciudadano->PER_NOMBRES." ".$ciudadano->PER_APELLIDOPATERNO." ".$ciudadano->PER_APELLIDOMATERNO,
            'edad' => self::entero($ciudadano->PER_EDAD),
            'menor_edad' => $ciudadano->MENOR,
            'difunto' => (isset($ciudadano->PER_FECHADEFUNCION) && $ciudadano->PER_FECHADEFUNCION) != 0 ? true : false,
        ];
    }

    public function mapcuidadordependiente($data){
        return [
            'nombres' => isset($data['nombres']) ? $data['nombres'] : null,
            'edad' => isset($data['edad']) ? $data['edad'] : null,
            'menor_edad' => (isset($data['edad']) && $data['edad'] > 18 )? false : true,
            'run_falso' => isset($data['run_falso']) ? $data['run_falso'] : null,
            'difunto' => isset($data['difunto']) ? $data['difunto'] : null,
            'id' => isset($data['id']) ? $data['id'] : null,
            'run_cuidador' => isset($data['run_cuidador']) ? $data['run_cuidador'] : null,
            'run_dependiente' => isset($data['run_dependiente']) ? $data['run_dependiente'] : null,
            'is_principal' => isset($data['is_principal']) ? $data['is_principal'] : null,
            'id_solicitud' => isset($data['id_solicitud']) ? $data['id_solicitud'] : null,
            'origen' => isset($data['origen']) ? $data['origen'] : null,
            'is_solicitud'=> isset($data['is_solicitud']) ? $data['is_solicitud'] : null,
            'modificar_tramite' => isset($data['is_solicitud']) && $data['is_solicitud'] == 0 ? false : true,
            'fecha_carga' => isset($data['fecha_carga']) ? $data['fecha_carga'] : null,
            'folio' => isset($data['folio']) ? $data['folio'] : null,
            'nuevofolio' => isset($data['nuevofolio']) ? $data['nuevofolio'] : null,
            'mismo_grupofamiliar' => isset($data['mismo_grupofamiliar']) ? $data['mismo_grupofamiliar'] : null,
            'solicitud_en_tramite' => isset($data['solicitud_en_tramite']) ? $data['solicitud_en_tramite'] : false,
            "subtipo_solicitud" =>  isset($data['subtipo_solicitud']) ? $data['subtipo_solicitud'] : null,
            "tipo_solicitud" =>  isset($data['tipo_solicitud']) ? $data['tipo_solicitud'] : null,
            'tipo_canal' => isset($data['tipo_canal']) ? $data['tipo_canal'] : null,
            'estado_solicitud' => isset($data['estado_solicitud']) ? $data['estado_solicitud'] : null,
            "fecha_creacion" =>  isset($data['fecha_creacion']) ? $data['fecha_creacion'] : null,
            "fecha_modificacion" =>  isset($data['fecha_modificacion']) ? $data['fecha_modificacion'] : null,
            "id_padre_solicitud"=>  isset($data['id_padre_solicitud']) ? $data['id_padre_solicitud'] : null,
            "run_ciudadano"=>  isset($data['run_ciudadano']) ? $data['run_ciudadano'] : null,
            "principal_secundario"=>  isset($data['principal_secundario']) ? $data['principal_secundario'] : null,
            'msg_bloqueo' => isset($data['msg_bloqueo']) ? $data['msg_bloqueo'] : null,
            'msg' => isset($data['msg']) && $data['msg'] ? $data['msg'] : false,
        ];
    }


    private function entero($campo)
    {
        if (is_null($campo)) {
            $out = null;
        } elseif (is_numeric($campo)) {
            $out = intval($campo);
        } else {
            $out = $campo;
        }
        return $out;
    }
}