<?php

namespace App\Http\ResponseMappers;


class CotizacionSaludMapper extends ResponseMapper
{
    public function __construct($tramite)
    {
        $this->campos = ['isapre'=> self::mapIsapre($tramite->isapre),
                'carga'=> self::mapCarga($tramite->carga),
        ];
    }

    private function mapIsapre($isapre)
    {
        $resultado = null;
        if(isset($isapre)){
            $resultado = ["isa-cot-id" => self::mapRespuesta($isapre, "ISA_COT_ID"),
                "periodo-rsh" => self::mapRespuesta($isapre, "PERIODO_RSH"),
                "run" => self::mapRespuesta($isapre, "RUN"),
                "id-tipo-trabajador" => self::mapRespuesta($isapre, "ID_TIPO_TRABAJADOR"),
                "tipo-trabajador" => $isapre->TIPO_TRABAJADOR,
                "isapre-nom" => $isapre->ISAPRE_NOM,
                "isapre-cod" => self::mapRespuesta($isapre, "ISAPRE_COD"),
                "identificacion-plan" => $isapre->IDENTIFICACION_PLAN,
                "monto-plan" => self::mapRespuesta($isapre, "MONTO_PLAN"),
                "renta-dep-11" => self::mapRespuesta($isapre, "RENTA_DEP_11"),
                "renta-dep-10" => self::mapRespuesta($isapre, "RENTA_DEP_10"),
                "renta-dep-09" => self::mapRespuesta($isapre, "RENTA_DEP_09"),
                "renta-dep-08" => self::mapRespuesta($isapre, "RENTA_DEP_08"),
                "renta-dep-07" => self::mapRespuesta($isapre, "RENTA_DEP_07"),
                "renta-dep-06" => self::mapRespuesta($isapre, "RENTA_DEP_06"),
                "renta-dep-05" => self::mapRespuesta($isapre, "RENTA_DEP_05"),
                "renta-dep-04" => self::mapRespuesta($isapre, "RENTA_DEP_04"),
                "renta-dep-03" => self::mapRespuesta($isapre, "RENTA_DEP_03"),
                "renta-dep-02" => self::mapRespuesta($isapre, "RENTA_DEP_02"),
                "renta-dep-01" => self::mapRespuesta($isapre, "RENTA_DEP_01"),
                "renta-dep-00" => self::mapRespuesta($isapre, "RENTA_DEP_00"),
                "renta-ind-11" => self::mapRespuesta($isapre, "RENTA_IND_11"),
                "renta-ind-10" => self::mapRespuesta($isapre, "RENTA_IND_10"),
                "renta-ind-09" => self::mapRespuesta($isapre, "RENTA_IND_09"),
                "renta-ind-08" => self::mapRespuesta($isapre, "RENTA_IND_08"),
                "renta-ind-07" => self::mapRespuesta($isapre, "RENTA_IND_07"),
                "renta-ind-06" => self::mapRespuesta($isapre, "RENTA_IND_06"),
                "renta-ind-05" => self::mapRespuesta($isapre, "RENTA_IND_05"),
                "renta-ind-04" => self::mapRespuesta($isapre, "RENTA_IND_04"),
                "renta-ind-03" => self::mapRespuesta($isapre, "RENTA_IND_03"),
                "renta-ind-02" => self::mapRespuesta($isapre, "RENTA_IND_02"),
                "renta-ind-01" => self::mapRespuesta($isapre, "RENTA_IND_01"),
                "renta-ind-00" => self::mapRespuesta($isapre, "RENTA_IND_00"),
                "monto-cot-comp-dep-03" => self::mapRespuesta($isapre, "MONTO_COT_COMP_DEP_03"),
                "monto-cot-comp-dep-02" => self::mapRespuesta($isapre, "MONTO_COT_COMP_DEP_02"),
                "monto-cot-comp-dep-01" => self::mapRespuesta($isapre, "MONTO_COT_COMP_DEP_01"),
                "monto-cot-comp-dep-00" => self::mapRespuesta($isapre, "MONTO_COT_COMP_DEP_00"),
                "monto-cot-comp-ind-03" => self::mapRespuesta($isapre, "MONTO_COT_COMP_DEP_03"),
                "monto-cot-comp-ind-02" => self::mapRespuesta($isapre, "MONTO_COT_COMP_DEP_02"),
                "monto-cot-comp-ind-01" => self::mapRespuesta($isapre, "MONTO_COT_COMP_DEP_01"),
                "monto-cot-comp-ind-00" => self::mapRespuesta($isapre, "MONTO_COT_COMP_DEP_00"),
                "vigente" => self::mapRespuesta($isapre, "VIGENTE"),
                "fecha-expiracion" => self::mapRespuesta($isapre, "FECHAEXPIRACION"),
                "id-solicitud" => self::mapRespuesta($isapre, "IDSOLICITUD"),
                "calculo-activo" => self::mapRespuesta($isapre, "CALCULOACTIVO"),
                "fuente" => self::mapRespuesta($isapre, "FUENTE"),
                "fecha-fuente" => self::mapRespuesta($isapre, "FECHAFUENTE"),
                "c-tactualizacion-salud" => self::mapRespuesta($isapre, "C_TACTUALIZACION_SALUD"),
                "fecha-cambio" => self::mapRespuesta($isapre, "FECHA_CAMBIO"),
                "fecha-desafiliacion" => self::mapRespuesta($isapre, "FECHA_DESAFILIACION"),
                "c-trectificacion-salud" => self::mapRespuesta($isapre, "C_TRECTIFICACION_SALUD"),
                "fecha-rectificacion" => self::mapRespuesta($isapre, "FECHA_RECTIFICACION"),
                "periodo-read" => self::mapRespuesta($isapre, "PERIODO_READ"),
                "dep-o-indep" => self::mapRespuesta($isapre, "DEP_O_INDEP"),
                "monto-plan-uf" => self::mapRespuesta($isapre, "MONTO_PLAN_UF"),
                "valor-uf-calculo" => self::mapRespuesta($isapre, "VALOR_UF_CALCULO")
            ];
        }
        return $resultado;
    }
    private function mapCarga($carga)
    {
        $resultado = null;
        if(isset($carga)){
            $resultado = ["rn" => self::mapRespuesta($carga, "RN"),
                "isa-carg-id" => self::mapRespuesta($carga, "ISA_CARG_ID"),
                "run-carga" => self::mapRespuesta($carga, "RUN_CARGA"),
                "run-cotizante" => self::mapRespuesta($carga, "RUN_COTIZANTE"),
                "periodo" => self::mapRespuesta($carga, "PERIODO"),
                "fecha-carga" => self::mapRespuesta($carga, "FECHA_CARGA"),
                "tipo-registro" => self::mapRespuesta($carga, "TIPO_REGISTRO"),
                "dv-cotizante" => self::mapRespuesta($carga, "DV_COTIZANTE"),
                "dv-carga" => self::mapRespuesta($carga, "DV_CARGA"),
                "fecha-nac" => $carga->FECHA_NAC,
                "sexo" => $carga->SEXO,
                "tipo-carga" => $carga->TIPO_CARGA,
                "codigo-relacion" => self::mapRespuesta($carga, "CODIGO_RELACION"),
                "vigente" => self::mapRespuesta($carga, "VIGENTE"),
                "fecha-expiracion" => $carga->FECHAEXPIRACION,
                "id-solicitud" => self::mapRespuesta($carga, "IDSOLICITUD"),
                "calculo-activo" => self::mapRespuesta($carga, "CALCULOACTIVO"),
                "fuente" => self::mapRespuesta($carga, "FUENTE"),
                "fecha-fuente" => $carga->FECHAFUENTE,
                "periodo-rsh" => self::mapRespuesta($carga, "PERIODO_RSH"),
                "monto-demanda" => self::mapRespuesta($carga, "MONTO_DEMANDA"),
                "fecha-desafiliacion" => self::mapRespuesta($carga, "FECHA_DESAFILIACION"),
                "monto-plan" => self::mapRespuesta($carga, "MONTO_PLAN"),
                "c-tactualizacion-salud" => self::mapRespuesta($carga, "C_TACTUALIZACION_SALUD"),
                "fecha-cambio" => $carga->FECHA_CAMBIO,
                "c-trectificacion-salud" => $carga->C_TRECTIFICACION_SALUD,
                "fecha-rectificacion" => $carga->FECHA_RECTIFICACION,
                "periodo-read" => $carga->PERIODO_READ,
                "monto-plan-uf" => self::mapRespuesta($carga, "MONTO_PLAN_UF"),
                "valor-uf-calculo" => self::mapRespuesta($carga, "VALOR_UF_CALCULO")
            ];
        }
        return $resultado;
    }

    private function mapRespuesta($Data, $campo)
    {
        //esta funcion permite retornar una estructura si en necesario a futuro
        return intval($Data->$campo);
    }

}
