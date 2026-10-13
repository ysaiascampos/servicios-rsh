<?php

namespace App\Http\ResponseMappers;

/**
 * Clase que mapea los datos de una consulta y un funcionario a un formato específico.
 */
class RegistroConsultaNnaMapper
{
    /**
     * Mapea los datos de una consulta y un funcionario a un arreglo asociativo.
     *
     * @param mixed $consulta Los datos de la consulta.
     * @param mixed $funcionario Los datos del funcionario.
     * @return array El arreglo asociativo con los datos mapeados.
     */
    public function map($consulta, $funcionario)
    {
        return [
            'id' => $consulta->id_consulta,
            'run_nna' => $consulta->run_nna,
            'nombre_completo_nna' => $consulta->nombre_completo_nna,
            'fecha_nacimiento_nna' => $consulta->fecha_nacimiento_nna,
            'documento_adjunto' => $consulta->documento_adjunto,
            'tipo_documento_adulto' => $consulta->tipo_documento_adulto,
            'dni_pasaporte_adulto' => $consulta->dni_pasaporte_adulto,
            'fecha_consulta' => $consulta->fecha_consulta,
            'id_seccion' => $consulta->id_seccion,
            'nombre_funcionario' => $funcionario->nombre,
            'rut_funcionario' => $funcionario->rut,
            'perfil_funcionario' => $funcionario->perfil,
            'comuna_funcionario' => $funcionario->comuna,
            'ip_cliente' => $funcionario->ip,
            'id_funcionario' => $funcionario->id_funcionario,
        ];
    }
}
