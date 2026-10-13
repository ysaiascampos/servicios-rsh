<?php


namespace App\Http\Controllers;

use App\Clases\DatosDePreguntasFicha;
use App\Clases\FichaIngresoMensual;
use App\Clases\Respuestas;
use App\Constantes\Constante;
use App\Http\ResponseMappers\DatosPreguntasFichaMapper;
use App\Http\ResponseMappers\FichaMapper;
use App\Http\ResponseMappers\IntegranteGrupoFamiliarMapper;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\EstadoSolicitud;
use App\Models\Ficha;
use App\Models\FichaMontoMensual;
use App\Models\GrupoFamiliarExport;
use App\Models\LocacionComuna;
use App\Models\NNARepresentanteMigrante;
use App\Models\Personas;
use App\Models\PersonasExport;
use App\Models\Solicitud;
use App\Models\UsuarioComuna;
use App\Utiles;
use Carbon\Carbon;
use function GuzzleHttp\json_encode;

class TrazabilidadController extends Controller{

    /**
     * Obtener la trazabilidad de una persona.
     *
     * @param string $run El RUN de la persona.
     * @return \Illuminate\Http\JsonResponse La lista de folios, preguntas del formulario y el historial de solicitudes.
     */
    public function trazabilidadFolio($runCiudadano){
        $persona = Utiles::getPersona($runCiudadano);
        if (is_numeric($persona)) {
            if ($persona == Constante::DATOS_DE_CONTACTO_3_ERROR_INESPERADO_RUNIFICADOR) {
                $mensaje = Constante::MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR;
            } elseif ($persona == Constante::DATOS_DE_CONTACTO_5_CIUDADANO_NO_OK_REGITRO_CIVIL) {
                $mensaje = "El RUN ingresado no se encuentra en el registro civil.";
            } else {
                $mensaje = "El RUN ingresado no se encuentra en nuestros registros.";
            }
            return response()->json($mensaje, Constante::STATUS_401_ERROR_UNAUTHORIZED);
        }
        $nombre = Utiles::capitalizaTexto($persona->PER_NOMBRES." ".$persona->PER_APELLIDOPATERNO . " " . $persona->PER_APELLIDOMATERNO);
        $edad = Utiles::getEdad($persona->PER_FECHANACIMIENTO);
        $fecha_nacimiento =  new Carbon($persona->PER_FECHANACIMIENTO);

        $datos_persona = [
            "run" => $runCiudadano,
            "edad" => $edad,
            "nombre" => $nombre
        ];

        if ($persona->PER_FECHADEFUNCION > Constante::FECHA_100000_MINIMA_DEFUNCION) {
            return response()->json(array('estado' => '0', 'msg' => 'Error: La persona existe en los registros como fallecida')
                , Constante::STATUS_404_ERROR_NOT_FOUND);
        }
        $id_persona = $persona->PER_ID;
        $solicitudes_historicas = $this->buscandoHistoricoSolicitudes($id_persona);

        $sol_incorporacion = [
            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
            Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA,
            //Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE,
            Constante::TIPO_SOLICITUD_30_SOLICITUD_DE_INGRESO_PARA_INSTITUCIONALIZADOS
        ];

        $solicitudes_historicas = $solicitudes_historicas->filter(function ($sol)use($sol_incorporacion){
            if(in_array($sol["id-tipo-solicitud"], $sol_incorporacion)){
                if($sol["bnf-vigente"] != Constante::VIGENTE_1_VIGENTE
                    && $sol["id-estado"] == Constante::ESTADO_SOLICITUD_5_APROBADO){
                    return false;
                }
            }
            return true;
        })->values();
        $beneficiarios = $solicitudes_historicas;


        // las preguntas ESO estas personalizadas segun la edad que tenia el ciudadano cuando hizo la solicitud
        $preguntas_edad = [
            "lista-preguntas-eso" => [], // preguntas1, preguntas2, preguntas3
            "diccionario-edad" => [] // edad1: index1,  12: 1, 34: 0
        ];

        $estados_solicitud = EstadoSolicitud::select("ID_ESTADO as id-estado",
            "NOMBRE_ESTADO as nombre")
        ->get();
        $estados_solicitud = $this->formatearLower($estados_solicitud);

        $preguntas  = Respuestas::findAllActivePorCategoriaESOV();
        $lista_folios = [];
        //foreach (array_reverse($beneficiarios) as $beneficiario)
        $folio_actual = null;
        for ($i = count($beneficiarios) - 1; $i >= 0; $i--)
        {
            //$sol_tiene_monto_m = FichaIngresoMensual::check($beneficiario->FECHA_CREACION,
            //    $beneficiario->FECHA_CREACION_CIUDADANO);
            //calcular la edad de cuando se creo la solicitud
            $beneficiario = $beneficiarios[$i];

            // filtrando las solicitudes que no fueron aprobadas
            if($beneficiario["id-estado"] != Constante::ESTADO_SOLICITUD_5_APROBADO ||
                 is_null($beneficiario["folio-nuevo"])){
                continue;
            }
            //filtra todas las solicitudes que estan dentro del mismo folio
            if($beneficiario["folio-nuevo"]!==$folio_actual){
                $folio_actual = $beneficiario["folio-nuevo"];
            }else{
                continue;
            }

            $fecha_creacion_sol = new Carbon($beneficiario["fecha-creacion"]);
            $edad_momento = $fecha_nacimiento->diffInYears($fecha_creacion_sol);

            $ficha_mensual = FichaMontoMensual::where("mensual_idsolicitud",  $beneficiario["id-solicitud"])->first();
            $sol_tiene_monto_m = $ficha_mensual? true : false;
            $fichaSolicitud = Ficha::findDetallePersonaByIdSolicitudMontoMensual($id_persona, $beneficiario["id-solicitud"], $sol_tiene_monto_m);
            $fichaFormateada = null;
            if($fichaSolicitud){
                $fichaFormateada = new FichaMapper($fichaSolicitud, $preguntas);
                $fichaFormateada = array_merge($fichaFormateada->campos, ["edad-momento"=>$edad_momento]);
            }
            else{
                $tipo_solicitud=$beneficiario["id-tipo-solicitud"];
                if($tipo_solicitud==Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA){
                    $fichaSolicitud = Ficha::findDetallePersonaByIdSolicitudMontoMensual($id_persona, null, $sol_tiene_monto_m);
                    if($fichaSolicitud){
                        $fichaFormateada = new FichaMapper($fichaSolicitud, $preguntas);
                        $fichaFormateada = array_merge($fichaFormateada->campos, ["edad-momento"=>$edad_momento]);
                    }
                }
            }


            $comuna = LocacionComuna::findByComunaIne($beneficiario["id-comuna"])->first();

            $preguntas_edad = $this->construirDiccionarioPreguntasESO($preguntas_edad, $edad_momento);


            $solicitud = array(
                "id-solicitud" => $beneficiario["id-solicitud"],
                "id-tipo-solcitud"=>$beneficiario["id-tipo-solicitud"],
                "fecha-aprobada" =>$beneficiario["fecha-modificacion"],
                "fecha-creacion" => $beneficiario["fecha-creacion"],
                "fecha-creacion-ciudadano" => $beneficiario["fecha-creacion-ciudadano"],
                "comuna"=>$comuna->DESCRIPCION
            );
            $fecha_creacion_folio = $beneficiario["fecha-modificacion"];//Solicitud::where("nuevofolio", $beneficiario->GRUPFAMI_NUEVOFOLIO)
                //->where("id_estado", Constante::ESTADO_SOLICITUD_5_APROBADO)
                //->min('fecha_creacion');

            $folio = [
                "folio" => $beneficiario["folio"],
                "folio-nuevo" => $beneficiario["folio-nuevo"],
                "fecha-ingreso" => $fecha_creacion_folio
            ];

            $datos_folio = [
                "ficha"=>$fichaFormateada,
                "solicitud" => $solicitud,
                "folio" => $folio,
                "adulto-responsable" => null
            ];

            $adulto_responsable = NNARepresentanteMigrante::where("MIGRAN_IDSOLICITUD",  $beneficiario["id-solicitud"])
                ->first();
            if($adulto_responsable){
                $nombre_adulto = Utiles::capitalizaTexto($adulto_responsable->MIGRAN_NOMBRES . " " . $adulto_responsable->MIGRAN_APELLIDOPATERNO . " " . $adulto_responsable->MIGRAN_APELLIDOMATERNO);
                $datos_folio["adulto-responsable"] = [
                    "DNI" => $adulto_responsable->MIGRAN_NROIDENTIFICADORNAC,
                    "nombre" => $nombre_adulto,
                    "email"=>$adulto_responsable->MIGRAN_EMAIL,
                    "telefono-fijo"=>$adulto_responsable->MIGRAN_TELEFONO,
                    "telefono-celular"=>$adulto_responsable->MIGRAN_CELULAR
                ];
            }
            $lista_folios[] = $datos_folio;

        }
        if(count($lista_folios) == 0){
            $personaExport = PersonasExport::findByRunCiudadano($runCiudadano);
            if ($personaExport) {
                $fecha_creacion_sol = new Carbon($personaExport->PER_FECHANACIMIENTO);
                $edad_momento = $fecha_nacimiento->diffInYears($fecha_creacion_sol);
                $grupoFamiliarExport = GrupoFamiliarExport::findByIdGrupoFamiliar($personaExport->PER_FIC_GRUPOFAMILIAR_ID);
                $comuna = isset($grupoFamiliarExport->GRUPFAMI_COMUNAINE) ?
                    $grupoFamiliarExport->GRUPFAMI_COMUNAINE : null;
                if ($grupoFamiliarExport) {
                    $ficha_mensual = FichaMontoMensual::findVigenteByIdPersona($personaExport->PER_ID);//12497339
                    $sol_tiene_monto_m = $ficha_mensual? true : false;
                    $fichaSolicitud = Ficha::findDetallePersonaByIdSolicitudMontoMensual($id_persona, null, $sol_tiene_monto_m);
                    if($fichaSolicitud){
                        $fichaFormateada = new FichaMapper($fichaSolicitud, $preguntas);
                        $fichaFormateada = array_merge($fichaFormateada->campos, ["edad-momento"=>$edad_momento]);
                    }
                    $comuna = LocacionComuna::findByComunaIne($grupoFamiliarExport->GRUPFAMI_COMUNAINE)->first();

                    $preguntas_edad = $this->construirDiccionarioPreguntasESO($preguntas_edad, $edad_momento);

                    $folio = [
                        "folio" => $grupoFamiliarExport->GRUPFAMI_FOLIO,
                        "folio-nuevo" => $grupoFamiliarExport->GRUPFAMI_NUEVOFOLIO,
                        "fecha-ingreso" => $grupoFamiliarExport->GRUPFAMI_FECHA_MODIFICACION,
                        "fecha-creacion" => $grupoFamiliarExport->GRUPFAMI_FECHA_MODIFICACION,
                        "fecha-creacion-ciudadano" => $grupoFamiliarExport->GRUPFAMI_FECHA_MODIFICACION,
                        "comuna" => $comuna->DESCRIPCION
                    ];
                    $personaExportJefeFamiliar = PersonasExport::findByIdGrupoFamiliarExportJefeFamiliar($personaExport->PER_FIC_GRUPOFAMILIAR_ID);
                    $datos_adulto_responsable = null;

                    if($personaExportJefeFamiliar){
                        $nombre_adulto = Utiles::capitalizaTexto($personaExportJefeFamiliar->PER_NOMBRES. " " . $personaExportJefeFamiliar->PER_APELLIDOPATERNO . " " . $personaExportJefeFamiliar->PER_APELLIDOMATERNO);
                        $datos_adulto_responsable = [
                            "DNI" => $personaExportJefeFamiliar->PER_RUN."-".$personaExportJefeFamiliar->PER_DV,
                            "nombre" => $nombre_adulto,
                            "email"=>$personaExportJefeFamiliar->PER_EMAIL,
                            "telefono-fijo"=>$personaExportJefeFamiliar->PER_TELEFONO,
                            "telefono-celular"=>$personaExportJefeFamiliar->PER_CELULAR
                        ];
                    }
                    $lista_folios[] = [
                        "ficha"=>$fichaFormateada,
                        "solicitud" =>null,// $solicitud,
                        "folio" => $folio,
                        "adulto-responsable" => $datos_adulto_responsable
                    ];
                }
            }


        }

        $datos = [
            "persona" =>$datos_persona,
            "lista-folios" => array_reverse($lista_folios),
            "diccionario-preguntas-eso" => $preguntas_edad,
            "solicitudes" => $solicitudes_historicas,
            "estados-solicitud" => $estados_solicitud
        ];
        return response()->json($datos, Constante::STATUS_200_SUCCESS_OK);
    }

    private function construirDiccionarioPreguntasESO($diccionario, $edad){
        if(!array_key_exists($edad, $diccionario["diccionario-edad"])){
            $preguntasd_eso = $this->obtenerPreguntasESO($edad);
            if(count($diccionario["diccionario-edad"])>0){
                $mismas_preguntas_indice = -1;
                $preguntasd_eso_str = json_encode($preguntasd_eso);
                foreach ($diccionario["diccionario-edad"] as $edad_=> $indice){
                    $preguntas_ = $diccionario["lista-preguntas-eso"][$indice];
                    $preguntas_str_ = json_encode($preguntas_);
                    if($preguntas_str_ === $preguntasd_eso_str){
                        $mismas_preguntas_indice = $indice;
                    }
                }
                if ($mismas_preguntas_indice>-1){
                    $diccionario["diccionario-edad"][$edad] = $mismas_preguntas_indice;
                }else{
                    $diccionario["diccionario-edad"][$edad] = count($diccionario["lista-preguntas-eso"]);
                    $diccionario["lista-preguntas-eso"][] = $preguntasd_eso;

                }
            }else{
                $diccionario["lista-preguntas-eso"][] = $preguntasd_eso;
                $diccionario["diccionario-edad"][$edad] = 0;
            }
        }
        return $diccionario;
    }

    private function obtenerPreguntasESO($edad){
        $datosDePreguntasFicha = new DatosDePreguntasFicha();
        $preguntas_mudolos = $datosDePreguntasFicha->showModuloEdad("todos", $edad);
        $preguntas_mudolos["educacion"]["e2"]["tipo-input"] = 1;
        if(array_key_exists("o2",$preguntas_mudolos["ingreso"])){
            $preguntas_mudolos["ingreso"]["o2"]["tipo-input"] = 1;
        }
        if(array_key_exists("o3",$preguntas_mudolos["ingreso"])){
            $preguntas_mudolos["ingreso"]["o3"]["tipo-input"] = 1;
        }
        if(array_key_exists("o4",$preguntas_mudolos["ingreso"])){
            $preguntas_mudolos["ingreso"]["o4"]["tipo-input"] = 1;
        }
        if(array_key_exists("o6",$preguntas_mudolos["ingreso"])){
            $preguntas_mudolos["ingreso"]["o6"]["tipo-input"] = 1;
        }
        if(array_key_exists("o9",$preguntas_mudolos["ingreso"])){
            $preguntas_mudolos["ingreso"]["o9"]["tipo-input"] = 1;
        }
        return $preguntas_mudolos;
    }


    private function buscandoHistoricoSolicitudes($id_persona){

        // buscando todas las solicitudes relaciona con la ficha de la persona
        $fichas = Ficha::join('ab_solicitud as sol', 'sol.id_solicitud', '=', 'fichas.ficha_idsolicitud')
            ->leftJoin("beneficiarios_gruposfamiliares as bnf", "bnf.bnfgrupfami_idsolicitud"
                , "=", "fichas.ficha_idsolicitud")
            ->leftJoin('grupos_familiares as gf', 'gf.grupfami_id', '=', 'bnf.bnfgrupfami_grupofamiliar')
            ->join("comunas_ine", "comunas_ine.com_id" , "=", "sol.com_id")
            ->select("sol.id_solicitud as id-solicitud",
                "sol.id_padre_solicitud as id-padre-solicitud",
                "sol.solicitud as titulo",
                "sol.id_tipo_solicitud as id-tipo-solicitud",
                "sol.id_estado as id-estado",
                "sol.com_id as id-comuna",
                "sol.FECHA_CREACION_CIUDADANO as fecha-creacion-ciudadano",
                "bnf.BNFGRUPFAMI_GRUPOFAMILIAR as grupo-familiar",
                "bnf.bnfgrupfami_vigente as bnf-vigente",
                "gf.GRUPFAMI_NUEVOFOLIO as folio-nuevo",
                "gf.GRUPFAMI_FOLIO as folio",
                "comunas_ine.com_descripcion as comuna",
                "sol.fecha_creacion as fecha-creacion",
                "sol.fecha_modificacion as fecha-modificacion",
                "sol.run_ciudadano as run-solicitante"
            )
            ->where("fichas.ficha_persona", $id_persona)
            //->orderBy('sol.fecha_creacion', 'desc')
            //->get()
        ;

        $solicitudes_excluidas = [
            Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
            Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA,
            Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR,
            Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA
        ];

        //solicituces que no tienen que ver con la ficha de la persona
        $solicitudes_no_ficha = BeneficiarioGrupoFamiliar::join('ab_solicitud as sol', 'sol.id_solicitud', '=', 'bnfgrupfami_idsolicitud')
            ->join("comunas_ine", "comunas_ine.com_id" , "=", "sol.com_id")
            ->leftJoin('grupos_familiares as gf', 'gf.grupfami_id', '=', 'bnfgrupfami_grupofamiliar')
            ->where("bnfgrupfami_persona", $id_persona)
            ->whereIn("sol.id_tipo_solicitud",$solicitudes_excluidas)
            ->select("sol.id_solicitud as id-solicitud",
                "sol.id_padre_solicitud as id-padre-solicitud",
                "sol.solicitud as titulo",
                "sol.id_tipo_solicitud as id-tipo-solicitud",
                "sol.id_estado as id-estado",
                "sol.com_id as id-comuna",
                "sol.FECHA_CREACION_CIUDADANO as fecha-creacion-ciudadano",
                "beneficiarios_gruposfamiliares.BNFGRUPFAMI_GRUPOFAMILIAR as grupo-familiar",
                "beneficiarios_gruposfamiliares.bnfgrupfami_vigente as bnf-vigente",
                "gf.GRUPFAMI_NUEVOFOLIO as folio-nuevo",
                "gf.GRUPFAMI_FOLIO as folio",
                "comunas_ine.com_descripcion as comuna",
                "sol.fecha_creacion as fecha-creacion",
                "sol.fecha_modificacion as fecha-modificacion",
                "sol.run_ciudadano as run-solicitante")
            //->orderBy('sol.FECHA_MODIFICACION', 'desc')
            //->get()
        ;

        $resultado = $fichas->union($solicitudes_no_ficha)
            ->orderBy('fecha-modificacion', 'desc')
            ->get();



        return $this->formatearLower($resultado);
    }

    private function formatearLower($resultado){
        $resultado = $resultado->map(function ($item) {
            return collect($item)->mapWithKeys(function ($value, $key) {
                return [strtolower($key) => $value];
            })->all();
        });
        return $resultado;
    }
};
