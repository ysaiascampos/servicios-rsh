<?php


namespace App\Http\ResponseMappers;


use App\Constantes\Constante;

class CompensacionEconomicaMapper extends ResponseMapper
{
    public function __construct($tramite)
    {
        $this->campos = [
            "id" => $tramite->ingresos[0]->COMPLEMENTO_ID,
            "subtipo" =>  self::mapSubTipo($tramite->solicitud->ID_SUB_TIPO_SOLICITUD),
            "monto" => $tramite->ingresos[0]->MONTO,
            "cuotas" => $tramite->ingresos[0]->NUMERO_CUOTAS,
            "fecha-inicio" => $tramite->ingresos[0]->FECHA_INICIO_PAGO,
            "beneficiario" => self::mapBeneficiario($tramite->beneficiario),
            "receptor" => self::mapReceptor($tramite->receptor),
            "pagador" => self::mapPagador($tramite->pagador),
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

    private function mapSubTipo($tipo)
    {
        $subTipo="";
        switch ($tipo) {
            case Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_1_PAGO:
                $subTipo = Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_STRING_PAGO;
                break;
            case Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_2_RECEPCION:
                $subTipo = Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_STRING_RECEPCION;
                break;
            case Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_3_CESE:
                $subTipo = Constante::SUB_TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS_STRING_CESE;
                break;
        }
        return $subTipo;
    }

}
