<?php


namespace App\Http\ResponseMappers;


class EstablecimientoEducacionalCompMapper extends ResponseMapper
{
    public function __construct($tramite)
    {
        $this->campos = [
            'porcentaje' => self::mapRespuesta($tramite->compEducacion,"PORCENTAJE"),
            'activo' => self::mapRespuesta($tramite->compEducacion,"ACTIVO"),
            'fuente' => self::mapRespuesta($tramite->compEducacion,"FUENTE"),
            'monto_pension' => self::mapRespuesta($tramite->compEducacion,"MONTO_PENSION"),
            'run-titular' => $tramite->compEducacion->RUN_DATO,
            'dv-titular' => $tramite->compEducacion->DV_DATO,
            'monto-hogar' => self::mapRespuesta($tramite->compEducacion,"MONTO"),
            'monto-externo' => self::mapRespuesta($tramite->compEducacion,"MONTO_EXTERNO"),
            'monto-beca' => self::mapRespuesta($tramite->compEducacion,"MONTO_BECA"),
            'nombre-completo' =>  $tramite->compEducacion->DATO_COMPLEMENTO,
            'calculo-activo' => self::mapRespuesta($tramite->compEducacion,"CALCULO_ACTIVO"),
            'subtipo' => self::mapRespuesta($tramite->compEducacion,"C_TCOMPLEMENTO_EDUCACION"),
            'periodo-read' => $tramite->compEducacion->PERIODO_READ,
            'complemento-id' => self::mapRespuesta($tramite->compEducacion,"COMPLEMENTO_ID"),
            'fecha-creacion' => $tramite->compEducacion->FECHA_CREACION,
        ];
    }
    private function mapRespuesta($Data, $campo)
    {
        //esta funcion permite retornar una estructura si en necesario a futuro
        return intval($Data->$campo);
    }
}
