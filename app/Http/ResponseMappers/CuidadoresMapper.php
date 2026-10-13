<?php


namespace App\Http\ResponseMappers;


use App\Constantes\Constante;

class CuidadoresMapper extends ResponseMapper
{
    public function __construct($tramite)
    {
        $this->campos = [
            "id" => $tramite->registroCuidador[0]->CUIDADORES_ID,
            "subtipo" =>  self::mapSubTipo($tramite->solicitud->ID_SUB_TIPO_SOLICITUD),
            "cuidador" => self::mapCuidador($tramite->cuidador),
            "cuidado" => self::mapCuidado($tramite->cuidado),
        ];
    }

    private function mapCuidador($cuidador)
    {
        $resultado = null;
        if(isset($cuidador)){
            if(!is_numeric($cuidador)) {
                $resultado =  new PersonaCortaMapper($cuidador);
            }
        }
        return $resultado;
    }

    private function mapCuidado($cuidado)
    {
        $resultado = null;
        if(isset($cuidado)){
            if(!is_numeric($cuidado)) {
                $resultado = new PersonaCortaMapper($cuidado);
            }
        }
        return $resultado;
    }

    private function mapSubTipo($tipo)
    {
        $subTipo="";
        switch ($tipo) {
            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_1_CUIDADOR:
                $subTipo = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CUIDADOR;
                break;
            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_2_CUIDADO:
                $subTipo = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CUIDADO;
                break;
            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE:
                $subTipo = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_CESE;
                break;
            case Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_4_CAMBIO_ROL:
                $subTipo = Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_STRING_ROL;
                break;
        }
        return $subTipo;
    }
}
