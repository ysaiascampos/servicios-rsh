<?php


namespace App\Http\ResponseMappers;


use App\Clases\DetalleGrupoFamiliar;

class DetalleGrupoFamiliarMapper extends ResponseMapper
{

    public function __construct(DetalleGrupoFamiliar $detalle)
    {
        $this->campos = [
            "bien-raiz" => $detalle->bienRaiz,
            "educacion" => $detalle->educacion,
            "ingresos" => $detalle->ingresos,
            "pension-alimentos" => $detalle->pensionAlimentos,
            "salud-isapre" => $detalle->saludIsapre,
            "vehiculo" => $detalle->vehiculo,
            "persona-juridica" =>  $detalle->juridico,
            "mensaje" => $detalle->mensaje
        ];
    }
}
