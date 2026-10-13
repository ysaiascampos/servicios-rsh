<?php

namespace App\Servicios;
use App\Constantes\Constante;
use App\Constantes\ConstanteTipoSolicitud;
use App\Models\GrupoFamiliar;
use Carbon\Carbon;

class BuscadorDomicilio
{
    private $tipoSolicitud = array(Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
        Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA,
        Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA);
    private $listaCapos = array("comunaine" => "", "localidad" => "",
        "c_calle" => "", "numdomicilio" => "", "sitio" => "",
        "block" => "", "dpto" => "", "casa" => "");
    private $canal;
    private $idSolicitud = null;

    public function __construct($input)
    {
        $numdomicilio = isset($input['numero-domicilio']) || array_key_exists('numero-domicilio', $input)
            ? $input['numero-domicilio']
            : $input['numero'];

        $this->canal = $input['canal'];
        $this->listaCapos['comunaine'] = trim($input['comuna']);
        $this->listaCapos['localidad'] = trim($input['localidad']);
        $this->listaCapos['c_calle'] = trim($input['codigo-calle']);
        $this->listaCapos['numdomicilio'] =  trim($this->validacionDataLimpieza($numdomicilio,'SIN_NUM'));
        $this->listaCapos['sitio'] = trim($this->RemoverEspecialChar($input['sitio']));
        $this->listaCapos['block'] = trim($this->validacionDataLimpieza($input['block'],'BLOCK'));
        $this->listaCapos['dpto'] = trim($this->validacionDataLimpieza($input['departamento'],'DEPARTAMENTO'));
        $this->listaCapos['casa'] = trim($this->validacionDataLimpieza($input['casa'],'CASA'));
        $this->idSolicitud = isset($input['id-solicitud']) ? $input['id-solicitud'] : null;
    }

    private function salidaSinDuplicidad(){
        return response()->json([
            'status' => 'success',
            'tipo-mensaje' => 2,
            'message' => 'No hay duplicidad de domicilio',
            'data' => false
        ], 200);
    }

    private function salidaConDuplicidad(){
        $mjs = "";

        if($this->canal == Constante::CANAL_2_CLAVE_UNICA){
            $mjs = 'El domicilio declarado cuenta con al menos un Registro vigente. Antes de continuar, verifique si corresponde incorporar integrantes al Hogar ya existente o crear un nuevo Registro';
        }else{
            $mjs = 'En la dirección ingresada existen otros registros vigentes,valide con el ciudadano la pertinencia de crear un nuevo registro en esa dirección';
        }
        return response()->json([
            'status' => 'success',
            'tipo-mensaje' => 1,
            'message' => $mjs,
            'data' => true
        ], 200);
    }

    public function show()
    {
        if ( empty($this->listaCapos['numdomicilio']) && empty($this->listaCapos['sitio']) &&
            empty($this->listaCapos['block']) &&  empty($this->listaCapos['dpto']) && empty($this->listaCapos['casa']) ){
            return $this->salidaSinDuplicidad();
        }

        if ($this->consultaSolicitudesMunicipalPreceso()) {
            return $this->salidaConDuplicidad();
        }
        // Método 3 - solo se ejecuta si método2 no encontró nada
        if ($this->consultaSolicitudesAprobadasFecha()) {
            return $this->salidaConDuplicidad();
        }

        if ($this->consultaAsignacionGrupoFamiliarVigente()) {
            return $this->salidaConDuplicidad();
        }

        // Método 4 - solo se ejecuta si método3 no encontró nada
        if ($this->consultaExportGrupoFamiliar()) {
            return $this->salidaConDuplicidad();
        }

        return $this->salidaSinDuplicidad();

    }

    private function consultaSolicitudesMunicipalPreceso()
    {

        $resultado = GrupoFamiliar::join('ab_solicitud', 'grupos_familiares.grupfami_idsolicitud', '=', 'ab_solicitud.id_solicitud')
            ->whereIn('ab_solicitud.id_tipo_solicitud', $this->tipoSolicitud)
            ->whereNotIn('ab_solicitud.id_estado', ConstanteTipoSolicitud::$ESTADO_SOLICITUD_5_7_9_17_FINALIZADOS)
            ->where('ab_solicitud.vigente', ConstanteTipoSolicitud::VIGENTE_1_VIGENTE)
            ->where('grupfami_comunaine', $this->listaCapos['comunaine'])
            //  ->where('grupfami_localidad', $this->listaCapos['localidad'])
            ->where('grupfami_c_calle', $this->listaCapos['c_calle']);

        if (!empty($this->idSolicitud)) {
            $resultado->where('ab_solicitud.id_solicitud', '!=', $this->idSolicitud);
        }

        return $this->existeDomicilioConDatosLimpios($resultado, 'asignacion.fn_limpiar_dato');
    }

    private function consultaSolicitudesAprobadasFecha()
    {

        $fechaLimite = Carbon::now()->subMonth();

        $resultado = GrupoFamiliar::join('ab_solicitud', 'grupos_familiares.grupfami_idsolicitud', '=', 'ab_solicitud.id_solicitud')
            ->whereIn('ab_solicitud.id_tipo_solicitud', $this->tipoSolicitud)
            ->where('ab_solicitud.id_estado', Constante::ESTADO_SOLICITUD_5_APROBADO)
            ->where('ab_solicitud.vigente', ConstanteTipoSolicitud::VIGENTE_1_VIGENTE)
            ->where('ab_solicitud.fecha_modificacion', '>=', $fechaLimite)
            ->where('grupfami_comunaine', $this->listaCapos['comunaine'])
            // ->where('grupfami_localidad', $this->listaCapos['localidad'])
            ->where('grupfami_c_calle', $this->listaCapos['c_calle']);

        return $this->existeDomicilioConDatosLimpios($resultado, 'asignacion.fn_limpiar_dato');

    }

    private function consultaAsignacionGrupoFamiliarVigente()
    {
        $resultado = GrupoFamiliar::where('grupfami_comunaine', $this->listaCapos['comunaine'])
            // ->where('grupfami_localidad', $this->listaCapos['localidad'])
            ->where('grupfami_c_calle', $this->listaCapos['c_calle'])
            ->where('grupfami_vigente', Constante::VIGENTE_1_VIGENTE)
            ->where('grupfami_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
            ->where('grupfami_estado', Constante::ESTADO_GRUPO_FAMILIAR_7_APROBADO);

        return $this->existeDomicilioConDatosLimpios($resultado, 'asignacion.fn_limpiar_dato');
    }

    private function consultaExportGrupoFamiliar()
    {
        $resultado = \DB::connection('oracle_export')
            ->table('grupos_familiares')
            ->where('grupfami_comunaine', $this->listaCapos['comunaine'])
            // ->where('grupfami_localidad', $this->listaCapos['localidad'])
            ->where('grupfami_c_calle', $this->listaCapos['c_calle']);

        return $this->existeDomicilioConDatosLimpios($resultado, 'asignacion_export.fn_limpiar_dato_exp');
    }

    private function existeDomicilioConDatosLimpios($consultaBase, $funcionLimpieza)
    {
        $consulta = $this->crearConsultaCandidatosDomicilio($consultaBase);

        $this->aplicarFiltroDatoLimpio($consulta, 'grupfami_numdomicilio', $this->listaCapos['numdomicilio'], 'SIN_NUM', $funcionLimpieza);
        $this->aplicarFiltroDatoLimpio($consulta, 'grupfami_block', $this->listaCapos['block'], 'BLOCK', $funcionLimpieza);
        $this->aplicarFiltroDatoLimpio($consulta, 'grupfami_dpto', $this->listaCapos['dpto'], 'DEPARTAMENTO', $funcionLimpieza);
        $this->aplicarFiltroDatoLimpio($consulta, 'grupfami_casa', $this->listaCapos['casa'], 'CASA', $funcionLimpieza);

        return $consulta->exists();
    }

    private function crearConsultaCandidatosDomicilio($consultaBase)
    {
        $conexion = method_exists($consultaBase, 'getModel')
            ? $consultaBase->getModel()->getConnection()
            : $consultaBase->getConnection();

        $consultaBase->select([
            'grupos_familiares.grupfami_numdomicilio',
            'grupos_familiares.grupfami_block',
            'grupos_familiares.grupfami_dpto',
            'grupos_familiares.grupfami_casa',
        ]);

        $subConsulta = method_exists($consultaBase, 'toBase')
            ? $consultaBase->toBase()
            : $consultaBase;

        return $conexion->query()->fromSub($subConsulta, 'candidatos_domicilio');
    }

    private function aplicarFiltroDatoLimpio($resultado, $campo, $valor, $tipoLimpieza, $funcionLimpieza)
    {
        $valor = trim($valor);
        $expresionLimpieza = $funcionLimpieza . "(" . $campo . ", '" . $tipoLimpieza . "')";

        if (!empty($valor)) {
            $resultado->where(function ($consulta) use ($campo, $valor, $expresionLimpieza) {
                $consulta->where(\DB::raw($expresionLimpieza), '=', $valor)
                    ->orWhereRaw("TRIM(" . $campo . ") = ?", [$valor]);
            });
        } else {
            $resultado->where(function ($consulta) use ($campo, $expresionLimpieza) {
                $consulta->whereRaw($expresionLimpieza . " IS NULL")
                    ->orWhereRaw("TRIM(" . $campo . ") IS NULL");
            });
        }
    }

    public function validacionDataLimpieza($data,$nombre=''){
        // lista para limpiar datos
        $listaEspeciales= array('BLOCK','DEPARTAMENTO','CASA','SIN_NUM');
        // Limpia los datos si vienen con guiones
        $data = ($data == '-' || $data == '--' || $data == '---' || $data == '----') ? '' : $data;
        $data = ($data == '_' || $data == '__' || $data == '___' || $data == '____') ? '' : $data;

        if($data != ""){
            $data = $this->RemoverEspecialChar($data);
            if($nombre != ''){
                $nombreConstante = 'App\Constantes\ConstanteTipoSolicitud::'.$nombre .'_VARIACION';
                $listasNegra = constant($nombreConstante);

                $data = mb_strtoupper($data, 'UTF-8');

                foreach ($listasNegra as $palabra) {
                    // Eliminamos coincidencias en mayúsculas
                    $pattern = '/\b' . preg_quote($palabra, '/') . '\b/u';
                    $data = preg_replace($pattern, '', $data);
                }

                // Eliminamos signos innecesarios y espacios extras
                //  $data = preg_replace('/[^\w\s]/u', ' ', $data);
                $data = preg_replace('/\s+/', ' ', trim($data));

                $data = in_array($nombre, $listaEspeciales)
                    ? trim(str_replace(['_', '/', '\\', ' ','.'], '', $data))
                    : $data;
            }
        }
        if (isset($data) && trim($data) !== '') {
            $valor_limpio = trim($data);

            // Si es solo ceros, convertir a cadena vacía, Si quedó vacío después de quitar los ceros, dejar vacío
            if (preg_match('/^0+$/', $valor_limpio)) {
                $data = '';
            } else {
                $data = trim($valor_limpio);
                if ($data === '') {
                    $data = '';
                }
            }
        }
        return $data;
    }

    public function RemoverEspecialChar($str)
    {
        $res = str_replace(array(
            '°',
            '*',
            '|',
            '\'',
            '"',
            ',',
            ';',
            '!',
            '$',
            '¨',
            '´',
            '+',
            '%',
            '[',
            ']',
            '(',
            ')',
            '{',
            '}',
            '?',
            '¿',
            '¡',
            '@',
            '#',
        ), '', $str);

        return $res;
    }

}
