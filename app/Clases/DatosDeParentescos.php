<?php

namespace App\Clases;

use App\Exceptions\ExcepcionConsistencia;
use App\Models\TipoSolicitud;
use App\Utiles;
use Exception;
use App\Constantes\Constante;
use App\Models\Solicitud;
use App\Models\GrupoFamiliar;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\Personas;
use App\Models\PersonasExport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatosDeParentescos
{
    const HIJOS_SOLO_PADRE = 1;//hijos de mi padre pero no de mi madre
    const HIJOS_SOLO_MADRE = 2;
    const HIJOS_AMBOS_PADRES = 3;
    const HIJOS_DEL_PADRE = 4;
    const HIJOS_DE_LA_MADRE = 5;
    var $sexo = [
        null,
        Constante::SEXO_STRING_MASCULINO,
        Constante::SEXO_STRING_FEMENINO
    ];
    var $runCiudadano_ = null;
    var $grupoFamiliaresPresentes = array();

    public function arbolGeneologico($runCiudadano)
    {
        $this->runCiudadano_ = $runCiudadano;
        $parentescos = array();
        $parentescos['individuo'] = $this->datosFamiliar($runCiudadano);
        $run_padre = $parentescos['individuo']['run-padre'];
        $padre = (isset($run_padre) and $run_padre != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE and $run_padre != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE) ?
            $this->datosFamiliar($run_padre) : null;
        $run_madre = $parentescos['individuo']['run-madre'];
        $madre = (isset($run_madre) and $run_madre != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE and $run_madre != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE) ?
            $this->datosFamiliar($run_madre) : null;
        $parentescos['individuo']['asendencia'] = array(
            "padre" => $padre,
            "madre" => $madre
        );

        $asendencia =& $parentescos['individuo']['asendencia'];

        if (isset($asendencia['padre'])) {

            $run_abuelo_padre = $asendencia['padre']['run-padre'];
            $abuelo_por_padre = (isset($run_abuelo_padre) and $run_abuelo_padre != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE and $run_abuelo_padre != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE) ?
                $this->datosFamiliar($run_abuelo_padre) : null;

            $run_abuela_padre = $asendencia['padre']['run-madre'];
            $abuela_por_padre = (isset($run_abuela_padre) and $run_abuela_padre != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE and $run_abuela_padre != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE) ?
                $this->datosFamiliar($run_abuela_padre) : null;
            $hermanos_por_padre = (isset($run_padre) and $run_padre != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE and $run_padre != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE) ?
                $this->buscarHijos($asendencia['padre']['run'],
                    $asendencia['madre']['run'], self::HIJOS_SOLO_PADRE) : null;

            $asendencia['padre'] = array_merge($asendencia['padre'],
                array(
                    'asedencia-por-padre' => array(
                        'abuelo-paterno1' => $abuelo_por_padre,
                        'abuela-paterno2' => $abuela_por_padre,
                    ),
                    'hermanastros-por-padre' => $hermanos_por_padre
                ));
        }
        if (isset($asendencia['madre'])) {

            $run_abuelo_madre = $asendencia['madre']['run-padre'];
            $abuelo_por_madre = (isset($run_abuelo_madre) and $run_abuelo_madre != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE and $run_abuelo_madre != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE) ?
                $this->datosFamiliar($run_abuelo_madre) : null;

            $run_abuela_madre = $asendencia['madre']['run-madre'];
            $abuela_por_madre = (isset($run_abuela_madre) and $run_abuela_madre != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE and $run_abuela_madre != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE) ?
                $this->datosFamiliar($run_abuela_madre) : null;

            $hermanos_por_madre = (isset($run_madre) and $run_madre != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE and $run_madre != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE) ?
                $this->buscarHijos($asendencia['padre']['run'],
                    $asendencia['madre']['run'], self::HIJOS_SOLO_MADRE) : null;

            $asendencia['madre'] = array_merge($asendencia['madre'],
                array(
                    'asedencia-por-madre' => array(
                        'abuelo-materno1' => $abuelo_por_madre,
                        'abuela-materno2' => $abuela_por_madre,
                    ),
                    'hermanastros-por-madre' => $hermanos_por_madre
                ));
        }

        $parentescos['individuo']['hermanos'] = $this->buscarHijos($run_padre,
            $run_madre, self::HIJOS_AMBOS_PADRES);

        if (strcmp($parentescos['individuo']['sexo'], Constante::SEXO_STRING_MASCULINO) == 0) {
            $parentescos['individuo']['descendencia']['hijos'] = $this->buscarHijos($runCiudadano, null,
                self::HIJOS_DEL_PADRE);

            $hijos_ciudadano = &$parentescos['individuo']['descendencia']['hijos'];
            foreach ($hijos_ciudadano as $run_hijo => &$hijo) {
                $hijo['padre-o-madre'] = (isset($hijo['run-madre'])
                    and $hijo['run-madre'] != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE
                    and $hijo['run-madre'] != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE) ?
                    $this->datosFamiliar($hijo['run-madre']) : null;
                switch ($hijo['sexo']) {
                    case Constante::SEXO_STRING_MASCULINO:
                        $hijo['nietos'] = $this->buscarHijos($hijo['run'], null,
                            self::HIJOS_DEL_PADRE);
                        break;
                    case Constante::SEXO_STRING_FEMENINO:
                        $hijo['nietos'] = $this->buscarHijos(null, $hijo['run'],
                            self::HIJOS_DE_LA_MADRE);
                        break;
                    default:
                        $hijo['nietos'] = null;
                }

            }
        }

        if (strcmp($parentescos['individuo']['sexo'], Constante::SEXO_STRING_FEMENINO) == 0) {
            $parentescos['individuo']['descendencia']['hijos'] = $this->buscarHijos(null, $runCiudadano,
                self::HIJOS_DE_LA_MADRE);

            $hijos_ciudadano = &$parentescos['individuo']['descendencia']['hijos'];
            foreach ($hijos_ciudadano as $run_hijo => &$hijo) {
                $hijo['padre-o-madre'] = (isset($hijo['run-padre']) and
                    $hijo['run-padre'] != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE
                    and $hijo['run-padre'] != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE) ?
                    $this->datosFamiliar($hijo['run-padre']) : null;
                switch ($hijo['sexo']) {
                    case Constante::SEXO_STRING_MASCULINO:
                        $hijo['nietos'] = $this->buscarHijos($hijo['run'], null,
                            self::HIJOS_DEL_PADRE);
                        break;
                    case Constante::SEXO_STRING_FEMENINO:
                        $hijo['nietos'] = $this->buscarHijos(null, $hijo['run'],
                            self::HIJOS_DE_LA_MADRE);
                        break;
                    default:
                        $hijo['nietos'] = null;
                }
            }
        }
        $parentescos['grupos-familiares-presentes'] = $this->grupoFamiliaresPresentes;
        $parentescos['folio-grupo-familiar-rsh'] = $this->integrantesFolioActual($parentescos['individuo']['folio-gfamiliar-actual']);
        return $parentescos;
    }

    private function datosFamiliar($runCiudadano)
    {
        $ciudadano = Utiles::getPersona($runCiudadano);
        if (empty($ciudadano) || is_numeric($ciudadano)) {
            if($ciudadano==-3){
                throw new ExcepcionConsistencia(
                    'Error de comunicación con el runificador',
                    Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR
                );
            }
            else{
                throw new ExcepcionConsistencia(
                    'El integrante ' . $this->id . ' no existe en la base datos',
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
                );
            }

        }
        $bnf = BeneficiarioGrupoFamiliar::findVigenteByPersona($ciudadano);

        $run_padre = $ciudadano['PER_RUN_PADRE'];
        $run_madre = $ciudadano['PER_RUN_MADRE'];
        $fallecido = $ciudadano['PER_FECHADEFUNCION'] >
        Constante::FECHA_100000_MINIMA_DEFUNCION ? true : false;
        $edad_ciudadano = !$fallecido ? Utiles::getEdad($ciudadano['PER_FECHANACIMIENTO']) :
            $ciudadano['PER_EDAD'];

        //ingreso de alto costo FUENTE_21_OTRAS_ALTOS_INGRESOS_SII == CALC_FUENTE_INGRESOLABORAL
        $personaExport = PersonasExport::findByRunCiudadano($runCiudadano);
        if (is_object($personaExport)) {
            $ingreso_alto_costo = $personaExport['CALC_FUENTE_INGRESOLABORAL'];
            $ingreso_alto_costo = ($ingreso_alto_costo == Constante::FUENTE_21_OTRAS_ALTOS_INGRESOS_SII) ?
                true : false;
        } else {
            $ingreso_alto_costo = false;
        }

        $datos_ciudadano = array(
            'nombre' => Utiles::capitalizaTexto($ciudadano['PER_NOMBRES'] . ' ' .
                $ciudadano['PER_APELLIDOPATERNO'] . ' ' . $ciudadano['PER_APELLIDOMATERNO']),
            'edad' => $edad_ciudadano,
            'run' => $ciudadano['PER_RUN'],
            'sexo' => $this->sexo[$ciudadano['PER_SEXO']],
            'estado-civil' => $ciudadano['PER_ESTADOCIVIL'],
            'run-conyuge' => null,
            'folio-gfamiliar-actual' => isset($bnf) ? $bnf->BNFGRUPFAMI_GRUPOFAMILIAR : null,
            'ingreso-alto-costo' => $ingreso_alto_costo,
            'fallecido' => $fallecido,
            'run-padre' => $run_padre,
            'run-madre' => $run_madre,
        );

        if (isset($bnf->BNFGRUPFAMI_GRUPOFAMILIAR)) {
            if (array_key_exists($bnf->BNFGRUPFAMI_GRUPOFAMILIAR,
                $this->grupoFamiliaresPresentes)) {
                $integrantes =& $this->grupoFamiliaresPresentes[$bnf->BNFGRUPFAMI_GRUPOFAMILIAR];
                if (!in_array($ciudadano['PER_RUN'], $integrantes)) {
                    array_push($integrantes, $ciudadano['PER_RUN']);
                }
            } else {
                $this->grupoFamiliaresPresentes[$bnf->BNFGRUPFAMI_GRUPOFAMILIAR] = [$ciudadano['PER_RUN']];
            }
        } else {
            if (isset($this->grupoFamiliaresPresentes['sin-grupo'])) {
                array_push($this->grupoFamiliaresPresentes['sin-grupo'], $ciudadano['PER_RUN']);
            } else {
                $this->grupoFamiliaresPresentes['sin-grupo'] = [$ciudadano['PER_RUN']];
            }

        }

        return $datos_ciudadano;
    }

    private function integrantesFolioActual($folioActual)
    {
        if (empty($folioActual)) {
            return [];
        }

        $grupoFamiliar = GrupoFamiliar::findByIdGrupoFamiliar($folioActual);
        if (empty($grupoFamiliar)) {
            $grupoFamiliar = GrupoFamiliar::findByNuevoFolio($folioActual);
        }
        if (empty($grupoFamiliar)) {
            return [];
        }

        $integrantes = BeneficiarioGrupoFamiliar::findBeneficiariosByGrupoFamiliarConParentesco($grupoFamiliar);
        if (empty($integrantes)) {
            return [$folioActual => []];
        }

        $runesIntegrantes = [];
        foreach ($integrantes as $integrante) {
            $runesIntegrantes[] = (string) $integrante->PER_RUN;
        }

        return [$folioActual => $runesIntegrantes];
    }

    private function buscarHijos($run_padre, $run_madre, $seleccion_padre)
    {
        $hijos = null;
        $run_valido_padre = ($run_padre != null and $run_padre != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE and $run_padre != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE);
        $run_valido_madre = ($run_madre != null and $run_madre != Constante::WS_CERTIFICADO_NACIMIENTO_VALOR_NO_DISPONIBLE and $run_madre != Constante::WS_CERTIFICADO_NACIMIENTO_ERROR_FUENTE);
        switch ($seleccion_padre) {
            case self::HIJOS_SOLO_PADRE:
                $hijos = Personas::whereRaw('PER_RUN_PADRE=? and (PER_RUN_MADRE<>? or PER_RUN_MADRE is null)',
                    array($run_padre, $run_madre));
                break;
            case self::HIJOS_SOLO_MADRE:
                $hijos = Personas::whereRaw('PER_RUN_MADRE=? and (PER_RUN_PADRE<>? or PER_RUN_PADRE is null)',
                    array($run_madre, $run_padre));
                break;
            case self::HIJOS_AMBOS_PADRES:
                if ($run_valido_padre and $run_valido_madre) {
                    $hijos = Personas::whereRaw('PER_RUN_PADRE=? and PER_RUN_MADRE=?',
                        array($run_padre, $run_madre));
                }
                if ($run_valido_padre and !$run_valido_madre) {
                    $hijos = Personas::whereRaw('PER_RUN_PADRE=?',
                        array($run_padre));
                }
                if (!$run_valido_padre and $run_valido_madre) {
                    $hijos = Personas::whereRaw('PER_RUN_MADRE=?',
                        array($run_madre));
                }
                if (!$run_valido_padre and !$run_valido_madre) {
                    return [];
                }
                break;
            case self::HIJOS_DEL_PADRE:
                $hijos = Personas::whereRaw('PER_RUN_PADRE=?',
                    array($run_padre));
                break;
            case self::HIJOS_DE_LA_MADRE:
                $hijos = Personas::whereRaw('PER_RUN_MADRE=?',
                    array($run_madre));
                break;
        }
        $hijos = $hijos->get();
        $hijos = $hijos->toArray();
        $arreglo_aux = array();
        for ($indice = 0; $indice < count($hijos); $indice++) {
            if ($this->runCiudadano_ != $hijos[$indice]['PER_RUN'])
                $arreglo_aux[$hijos[$indice]['PER_RUN']] = $this->datosFamiliar($hijos[$indice]['PER_RUN']);
        }
        return $arreglo_aux;
    }

}
