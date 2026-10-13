<?php


namespace App\Http\ResponseMappers;


class CotizacionSaludCompMapper extends ResponseMapper
{
    public function __construct($tramite)
    {
        $this->campos = [
            "rn" => '',
            "complemento_id" => self::mapRespuesta($tramite->complementoCotizaSalud,"COMPLEMENTO_ID"),
            "id_solicitud" => self::mapRespuesta($tramite->complementoCotizaSalud,"ID_SOLICITUD"),
            "c_tcomplemento_cotizasalud" => self::mapRespuesta($tramite->complementoCotizaSalud,"C_TCOMPLEMENTO_COTIZASALUD"),
            "monto" => self::mapRespuesta($tramite->complementoCotizaSalud,"MONTO"),
            "rut" => $tramite->complementoCotizaSalud->RUT,
            "dv" => $tramite->complementoCotizaSalud->DV,
            "vigente" => self::mapRespuesta($tramite->complementoCotizaSalud,"VIGENTE"),
            "calculoactivo" => self::mapRespuesta($tramite->complementoCotizaSalud,"CALCULOACTIVO"),
            "fuente" => self::mapRespuesta($tramite->complementoCotizaSalud,"FUENTE"),
            "fecha_creacion" => $tramite->complementoCotizaSalud->FECHA_CREACION,
            "periodo_read" => $tramite->complementoCotizaSalud->PERIODO_READ,
            "beneficiario" => self::mapBeneficiario($tramite->beneficiario),
            "receptor" => self::mapReceptor($tramite->receptor),
            "pagador" => self::mapPagador($tramite->pagador),
            "integrante" => self::mapIntegrante($tramite->integrante)
        ];

    }

    private function mapBeneficiario($beneficiario)
    {
        $resultado = null;
        if(isset($beneficiario)){
            if(!is_numeric($beneficiario)) {
                $resultado =  new PersonaCortaMapper($beneficiario);
            }
        }
        return $resultado;
    }

    private function mapReceptor($receptor)
    {
        $resultado = null;
        if(isset($receptor)){
            if(!is_numeric($receptor)) {
                $resultado = new PersonaCortaMapper($receptor);
            }
        }
        return $resultado;
    }

    private function mapPagador($pagador)
    {
        $resultado = null;
        if(isset($pagador)){
            if(!is_numeric($pagador)) {
                $resultado = new PersonaCortaMapper($pagador);
            }
        }
        return $resultado;
    }

    private function mapIntegrante($integrante)
    {
        $resultado = null;
        if(isset($integrante)){
            if(!is_numeric($integrante)) {
                $resultado = new PersonaCortaMapper($integrante);
            }
        }
        return $resultado;
    }

    private function mapRespuesta($Data, $campo)
    {
        //esta funcion permite retornar una estructura si en necesario a futuro
        return intval($Data->$campo);
    }
}
