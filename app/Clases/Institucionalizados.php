<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Models\BeneficiarioGrupoFamiliar;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class Institucionalizados
{
    public static function evaluarExistenciaInstitucionalizados($persona)
    {
        $institucionalizado = null;
        try {
            $institucionalizado = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $persona->PER_ID)
                ->where('bnfgrupfami_institucionalizado', Constante::INSTITUCIONALIZADO_1_INSTITUCIONALIZADO)
                ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->join('ab_institucion_ever', 'id_grupofamiliar', '=', 'bnfgrupfami_grupofamiliar')
                ->first();

            if (empty($institucionalizado)) {
                $institucionalizado = BeneficiarioGrupoFamiliar::where('bnfgrupfami_persona', $persona->PER_ID)
                    ->where('bnfgrupfami_institucionalizado', Constante::INSTITUCIONALIZADO_1_INSTITUCIONALIZADO)
                    ->where('bnfgrupfami_vigente', Constante::VIGENTE_1_VIGENTE)
                    ->where('bnfgrupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                    ->first();
                if(empty($institucionalizado)){
                    $institucionalizado = new stdClass();
                    $institucionalizado->ES_INSTITUCIONALIZADO = false;
                }
                else{
                    if($persona->PER_EDAD >= Constante::EDAD_18_MAYOR_DE_EDAD){
                        $institucionalizado->ES_INSTITUCIONALIZADO = true;
                    }
                }

            } else {
                $institucionalizado->ES_INSTITUCIONALIZADO = true;
            }

            $institucionalizado->PER_EDAD = $persona->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD;
        } catch (Exception $ex) {
            Log::critical('Problema al consultar si es Institucionalizado');
        }
        return $institucionalizado;
    }

    public static function getIntegrantesIntitucionalizados($idGrupoFami)
    {
        $sql = "SELECT COUNT(1) integrante_institucionalizado
                FROM grupos_familiares gf
                WHERE gf.grupfami_id IN (
                    SELECT bnfgrupfami_grupofamiliar bnf
                    FROM beneficiarios_gruposfamiliares
                    WHERE bnfgrupfami_persona IN (
                        SELECT bnfgrupfami_persona
                        FROM beneficiarios_gruposfamiliares
                        WHERE bnfgrupfami_grupofamiliar = :idGrupoFamiliar
                    )
                    AND bnfgrupfami_vigente = " . Constante::VIGENTE_1_VIGENTE . "
                    AND bnfgrupfami_calculoactivo = " . Constante::CALCULO_ACTIVO_1_APROBADO . "
                    AND bnfgrupfami_institucionalizado = " . Constante::INSTITUCIONALIZADO_1_INSTITUCIONALIZADO . "
                )
                AND gf.grupfami_vigente = " . Constante::VIGENTE_1_VIGENTE . "
                AND gf.grupfami_calculoactivo = " . Constante::CALCULO_ACTIVO_1_APROBADO . "
                AND gf.grupfami_institucionalizado = " . Constante::INSTITUCIONALIZADO_1_INSTITUCIONALIZADO . "
                AND gf.grupfami_estado = " . Constante::ESTADO_GRUPO_FAMILIAR_7_APROBADO . " ";
        $resultSet = DB::select($sql, [
            'idGrupoFamiliar' => $idGrupoFami
        ]);

        return ($resultSet[0]->INTEGRANTE_INSTITUCIONALIZADO > Constante::INSTITUCIONALIZADO_0_NO_INSTITUCIONALIZADO) ?
            Constante::INSTITUCIONALIZADO_1_INSTITUCIONALIZADO :
            Constante::INSTITUCIONALIZADO_0_NO_INSTITUCIONALIZADO;
    }
}
