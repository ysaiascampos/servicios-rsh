<?php
namespace App\Clases;

use App\Constantes\Constante;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\Personas;
use App\Utiles;
use Illuminate\Support\Facades\Request;

class ValidacionClaveUnica
{
    public function getModel()
    {
        return new Personas();
    }

    /**
     * @param $input
     * @param $idSolicitud
     * @return bool
     */
    public function validarInput($input, $idSolicitud)
    {
        /*
         * Todo Analizar el canal y si es ciudadano analizar el  tipo de solicitud  ARC
         * manipulamos el datos de run-ciudadano y correjimos por el que viene dentro del cuerpo de cada
         * solicitud ARC.
         * Abrir el cuerpo de la solicitud.. analiza subtipo de ahi ve donde se va...
         * aqui en la pension tendria que evaluar inmnediateamente si el run-usuario = pagador o bien = receptor
         */
        $datosSolicitud = (new DatosDeSolicitud)->findByIdSolicitud($idSolicitud);
        /*
         * Todo Analizar el canal y si es ciudadano analizar el  tipo de solicitud  ARC
         * manipulamos el datos de run-ciudadano y correjimos por el que viene dentro del cuerpo de cada
         * solicitud ARC.
         * Abrir el cuerpo de la solicitud.. analiza subtipo de ahi ve donde se va...
         * aqui en la pension tendria que evaluar inmnediateamente si el run-usuario = pagador o bien = receptor
         */

        return $this->validar(
            strtolower($input->get('canal')),
            $datosSolicitud->id_tipo_solicitud,
            strtolower($input->get('run-ciudadano')),
            strtolower($input->get('run-usuario'))
        );

    }

    /**
     * @param $input
     * @param $idSolicitud
     * @return bool
     */
    public function validarSolicitudes($canal, $idTipoSolicitud, $runCiudadano, $runUsuario, $solicitudRunCiudadano)
    {
        $this->validar($canal, $idTipoSolicitud, $runCiudadano, $runUsuario);
    }

    /**
     * @param $input
     * @param $idSolicitud
     * @return bool
     */
    public function validar($canal, $idTipoSolicitud, $runCiudadano, $runUsuario, $input = "", $idSolicitud = null)
    {
        $validacionfirmaSimple = 0;

        if (!is_null($idSolicitud)) {
            $solicitud = (new DatosDeSolicitud())->findByIdSolicitud($idSolicitud);
            if (!is_null($solicitud) && $solicitud->VIGENTE != Constante::VIGENTE_3_SOLICITUD_BORRADOR) {
                return $solicitud->FIRMA_SIMPLE;
            }
        }

        if ($canal == Constante::CANAL_2_STRING_MINUS_CLAVE_UNICA &&
            Request::header('token-clave-unica') != Constante::PASSWORD_TOKEN_CLAVE_UNICA
            //&& Request::header('token-clave-unica') != (isset($_ENV['LOGIN_RUN_TOKEN_FALSO']) ? $_ENV['LOGIN_RUN_TOKEN_FALSO'] : '')
        ) {
            switch ($idTipoSolicitud) {
                case Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO:
                    $beneficiariosDelGrupoFamiliar = $input->integrantes;
                    $numCiudadanoMayor = 0; // Número de ciudadanos mayores de 18 años en el grupo familiar
                    foreach ($beneficiariosDelGrupoFamiliar as $item) {
                        if ($item->get('parentesco') == Constante::PARENTESCO_1_JEFE_DE_HOGAR &&
                            $item->get('run-titular') != $runCiudadano
                        ) {
                            $numCiudadanoMayor = 0;
                            break;
                        }
                        $edad = $item->get('edad');
                        if ($edad >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                            $numCiudadanoMayor++;
                        }
                    }
                    if ($numCiudadanoMayor == 1) {
                        $validacionfirmaSimple = 1;
                    }
                    break;
                case Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE:
                    $beneficiariosDelGrupoFamiliar = BeneficiarioGrupoFamiliar::findBeneficiariosByRun($runUsuario);
                    $jefeHogar = 0;
                    foreach ($beneficiariosDelGrupoFamiliar as $item) {
                        if ($item->BNFGRUPFAMI_PARENTESCO == Constante::PARENTESCO_1_JEFE_DE_HOGAR &&
                            $item->PER_RUN == $runUsuario
                        ) {
                            $jefeHogar = 1;
                            break;
                        }
                    }
                    $beneficiariosDelGrupo = $input;
                    $runGrupo = [];
                    foreach ($beneficiariosDelGrupo as $item) {
                        if ($item->get('run-titular') != $runCiudadano) {
                            $runGrupo[] = $item->get('run-titular');
                        }
                    }
                    if (count($runGrupo) == 0) {
                        break;
                    }
                    $beneficiarios = $this->getModel()
                        ->whereIn('per_run', $runGrupo)
                        ->get();
                    $numCiudadanoMayor = 0;//Número de ciudadanos mayores de 18 años en el grupo familiar
                    foreach ($beneficiarios as $item) {
                        $edad = Utiles::getEdad($item->PER_FECHANACIMIENTO);
                        if ($edad >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                            $numCiudadanoMayor++;
                        }
                    }
                    if (
                        $numCiudadanoMayor == 0 and
                        $jefeHogar == Constante::PARENTESCO_1_JEFE_DE_HOGAR
                    ) {
                        $validacionfirmaSimple = 1;
                    }
                    break;
                case Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE:
                    $beneficiariosDelGrupoFamiliar = BeneficiarioGrupoFamiliar::findBeneficiariosByRun($runUsuario);
                    $jefeHogar = 0;
                    foreach ($beneficiariosDelGrupoFamiliar as $item) {
                        if ($item->BNFGRUPFAMI_PARENTESCO == Constante::PARENTESCO_1_JEFE_DE_HOGAR &&
                            $item->PER_RUN == $runUsuario
                        ) {
                            $jefeHogar = 1;
                            break;
                        }
                    }
                    $beneficiariosDelGrupo = $input;
                    $runGrupo = [];
                    foreach ($beneficiariosDelGrupo as $item) {
                        $runGrupo[] = $item->get('run-titular');
                    }
                    if (count($runGrupo) == 0) {
                        break;
                    }
                    if ($jefeHogar == 1) {
                        $validacionfirmaSimple = 1;
                    }

                    $integrantesSolicitud = $input->integrantes;
                    if (count($integrantesSolicitud) == 1 &&
                        $integrantesSolicitud[0]->get('run-titular') == $runUsuario
                    ) {
                        $validacionfirmaSimple = 1;
                    }

                    break;
                case Constante::TIPO_SOLICITUD_5_ACTUALIZACION_DE_MODULO_SALUD:
                case Constante::TIPO_SOLICITUD_6_ACTUALIZACION_DE_MODULO_EDUCACION:
                case Constante::TIPO_SOLICITUD_14_ACTUALIZACION_OCUPACION_DE_INGRESOS:
                    if ($runCiudadano == $runUsuario) {
                        $validacionfirmaSimple = 1;
                    } else {
                        $beneficiariosDelGrupoFamiliar =  BeneficiarioGrupoFamiliar::findBeneficiariosByRun($runCiudadano);
                        $numCiudadanoMayor = 0;//Número de ciudadanos mayores de 18 años en el grupo familiar
                        foreach ($beneficiariosDelGrupoFamiliar as $item) {
                            $edad = Utiles::getEdad($item->PER_FECHANACIMIENTO);
                            if (in_array($item->PER_RUN, [$runCiudadano, $runUsuario])) {
                                if ($edad >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                                    $numCiudadanoMayor++;
                                }
                            }
                        }
                        if ($numCiudadanoMayor == 1) {
                            $validacionfirmaSimple = 1;
                        }
                    }
                    break;
                case Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA:
                    $beneficiariosDelGrupoFamiliar =  BeneficiarioGrupoFamiliar::findBeneficiariosByRun($runCiudadano);
                    foreach ($beneficiariosDelGrupoFamiliar as $item) {
                        if (
                            $item->BNFGRUPFAMI_PARENTESCO == Constante::PARENTESCO_1_JEFE_DE_HOGAR &&
                            $item->PER_RUN == $runCiudadano
                        ) {
                            $validacionfirmaSimple = 1;
                            break;
                        }
                    }
                    break;
                case Constante::TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS:
                    if ($runCiudadano == $runUsuario) {
                        $validacionfirmaSimple = 1;
                    }
                    break;
                case Constante::TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL:
                case Constante::TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL:
                    if ($runUsuario == $runCiudadano) {
                        $validacionfirmaSimple = 1;
                    }else{
                        $persona = Personas::where("per_run", $runCiudadano)
                            ->select("per_fechanacimiento")
                            ->first();
                        $edad = Utiles::getEdad($persona->PER_FECHANACIMIENTO);
                        if ($edad<Constante::EDAD_18_MAYOR_DE_EDAD){
                            $validacionfirmaSimple = 1;
                        }
                    }
                    break;
                case Constante::TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA:
                    if (($runUsuario == $input->get('pagador') && $input->get('subtipo') ==
                            Constante::SUB_TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA_STRING_PAGO)
                        || ($runUsuario == $input->get('beneficiario') && $input->get('subtipo') ==
                            Constante::SUB_TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA_STRING_RECEPCION)) {
                        $validacionfirmaSimple = 1;
                    }
                    break;
                case Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ:
                case Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ:
                case Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL:
                    if ($runUsuario == $runCiudadano) {
                        $validacionfirmaSimple = 1;
                    }
                    break;
                case Constante::TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD:
                case Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE:
                case Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE:
                case Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE:
                case Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION:
                case Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL:
                case Constante::TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD:
                case Constante::TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR:
                    if ($runCiudadano == $runUsuario) {
                        $validacionfirmaSimple = 1;
                    } else {
                        $beneficiariosDelGrupoFamiliar =  BeneficiarioGrupoFamiliar::findBeneficiariosByRun($runCiudadano);
                        $numCiudadanoMayor = 0;//Número de ciudadanos mayores de 18 años en el grupo familiar
                        foreach ($beneficiariosDelGrupoFamiliar as $item) {
                            $edad = Utiles::getEdad($item->PER_FECHANACIMIENTO);
                            if (in_array($item->PER_RUN, [$runCiudadano, $runUsuario])) {
                                if ($edad >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                                    $numCiudadanoMayor++;
                                }
                            }
                        }
                        if ($numCiudadanoMayor == 1) {
                            $validacionfirmaSimple = 1;
                        }
                    }
                    break;
                case Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR:
                    //Formulario de Solicitud Actualización - Cambio jefe de hogar.
                    //Recuerda que debe ser firmada por el Jefe(a) de hogar y nuevo Jefe(a) de Hogar.
                    //Esta regla no se cumple ya que por ahora no se va generar docmentos que tengan
                    //que ser firmados manualmente por ahora.
                    $validacionfirmaSimple = 0;
                    break;
                    $beneficiariosDelGrupoFamiliar =  BeneficiarioGrupoFamiliar::findBeneficiariosByRun($runCiudadano);
                    foreach ($beneficiariosDelGrupoFamiliar as $item) {
                        if (
                            $item->BNFGRUPFAMI_PARENTESCO == Constante::PARENTESCO_1_JEFE_DE_HOGAR &&
                            $item->PER_RUN == $runCiudadano
                        ) {
                            $validacionfirmaSimple = 1;
                            break;
                        }
                    }
                    break;
                case Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR:
                    $beneficiariosDelGrupoFamiliar =  BeneficiarioGrupoFamiliar::findBeneficiariosByRun($runCiudadano);
                    $numCiudadanoMayor = 0;//Ciudadanos debe ser mayor de 18 años
                    $jefeHogar = false;
                    $numCiudadanoMayorSolicitante = true;
                    foreach ($beneficiariosDelGrupoFamiliar as $item) {
                        $edad = Utiles::getEdad($item->PER_FECHANACIMIENTO);
                        if ($edad >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                            $numCiudadanoMayor++;
                            if ($runCiudadano == $item->PER_RUN) {
                                $numCiudadanoMayorSolicitante = true;
                            }
                        }
                        if ($item->BNFGRUPFAMI_PARENTESCO == Constante::PARENTESCO_1_JEFE_DE_HOGAR &&
                            $runCiudadano == $item->PER_RUN
                        ) {
                            $jefeHogar = true;
                        }

                    }
                    if ($numCiudadanoMayor < 2 ||
                        $jefeHogar
                    ) {
                        $validacionfirmaSimple = 1;
                        break;
                    }
                    if ($numCiudadanoMayor == 2 &&
                        $numCiudadanoMayorSolicitante
                    ) {
                        $validacionfirmaSimple = 1;
                        break;
                    }
                    break;
                case Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA:
                    $beneficiariosDelGrupoFamiliar =  BeneficiarioGrupoFamiliar::findBeneficiariosByRun($runCiudadano);
                    $numCiudadanoMayor = 0;//Número de ciudadanos mayores de 18 años en el grupo familiar
                    foreach ($beneficiariosDelGrupoFamiliar as $item) {
                        if ($item->BNFGRUPFAMI_PARENTESCO == Constante::PARENTESCO_1_JEFE_DE_HOGAR &&
                            $item->PER_RUN == $runCiudadano
                        ) {
                            $validacionfirmaSimple = 1;
                            break;
                        }
                        /*
                        $edad = Utiles::getEdad($item->per_fechanacimiento);
                        if ($edad >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                            $numCiudadanoMayor++;
                        }
                        */
                    }
                    /*
                    if ($numCiudadanoMayor == 1) {
                        $validacionfirmaSimple = 1;
                    }
                    */
                    break;
                default:
                    break;
            };
        }
        return $validacionfirmaSimple;
    }
}
