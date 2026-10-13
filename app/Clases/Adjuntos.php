<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionEjecucion;
use App\Exceptions\ExcepcionServicio;
use App\Exceptions\ExcepcionValidacion;
use App\Http\Inputs\AdjuntosInput;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\GrupoFamiliar;
use App\Models\Solicitud;
use App\Models\SolicitudAdjuntos;
use App\Models\SolicitudDocumentos;
use App\Models\ActualizacionIngreso;
use App\Models\SupervisionDocExtra;
use App\Utiles;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use stdClass;
use Throwable;
use ZipArchive;

class Adjuntos
{
    use DebugTrait;

    private $runUsuario;

    public function __construct()
    {
        $this->configurarDebug();
    }

    /**
     * @throws ExcepcionEjecucion
     */
    public function recuperarAdjuntos($idSolicitud, $incluirRutaArchivo = false)
    {
        try {
            if (!is_numeric($idSolicitud)) {
                $this->debug("ID solcitiud invalida: " . $idSolicitud);
                throw ExcepcionValidacion::new('Solicitud no válida.')
                    ->setStatusCode(Constante::STATUS_412_ERROR_PRECONDITION_FAILED);
            }
            $response = SolicitudAdjuntos::recuperarAdjuntos($idSolicitud, $incluirRutaArchivo);
            $this->debug("Luego de obtener respuesta de documentosAdjuntos: " . json_encode($response));
            return $response;
        } catch (Exception $exception) {
            throw ExcepcionEjecucion::new("Error inesperado recuperarAdjuntos. ", 0, $exception);
        }
    }

    /**
     * @throws ExcepcionConsistencia
     * @throws ExcepcionEjecucion
     * @throws ExcepcionServicio
     * @throws ExcepcionValidacion
     */
    public function recuperarZipAdjuntos($idSolicitud)
    {
        $archivoSalida = new stdClass();
        $docs = [];

        $listadoDocumentos = $this->recuperarAdjuntos($idSolicitud, true);
        if (count($listadoDocumentos) === 0) {
            throw new ExcepcionValidacion('No se han adjuntado documentos a esta solicitud.');
        }
        foreach ($listadoDocumentos as $listadoPorTipo) {
            $docs = array_merge($docs, $listadoPorTipo);
        }

        $archivoSalida->nombre = $idSolicitud . "_" . date("Y-m-d") . ".zip";
        $archivoSalida->ruta = $this->generarZip($archivoSalida->nombre, $docs);
        $archivoSalida->borrarDespues = true;

        return $archivoSalida;
    }

    /**
     * @throws ExcepcionConsistencia
     * @throws ExcepcionEjecucion
     * @throws ExcepcionServicio
     */
    public function recuperarDocumento($idSolicitud, $idTipoDocumento, $idDocumento = null)
    {
        $documentos = SolicitudAdjuntos::recuperarDocumento($idSolicitud, $idTipoDocumento, $idDocumento);

        $archivoSalida = new stdClass();
        if (count($documentos) == 1 || Constante::ADJUNTOS_DESCARGAR_SOLO_PRIMER_ARCHIVO_POR_TIPO) {
            $archivoSalida->ruta = $documentos[0]['ruta-archivo'];
            $archivoSalida->nombre = $documentos[0]['archivo-carga'];
            $archivoSalida->borrarDespues = false;
        } else {
            $archivoSalida->nombre = $idSolicitud . "_" . $idTipoDocumento . "_" . date("Y-m-d") . ".zip";
            $archivoSalida->borrarDespues = true;
            $archivoSalida->ruta = $this->generarZip(
                $archivoSalida->nombre,
                $documentos
            );
        }
        return $archivoSalida;
    }

    /**
     * @throws ExcepcionEjecucion
     */
    public function obtieneListaDocumentosRequeridos($idTipoSolicitud, $idSubTipoSolicitud)
    {
        try {
            if (!is_numeric($idTipoSolicitud)) {
                $this->debug("Tipo de solicitud no válida. " . $idTipoSolicitud);
                throw ExcepcionValidacion::new('Tipo de solicitud no válida.')
                    ->setStatusCode(Constante::STATUS_412_ERROR_PRECONDITION_FAILED);
            }

            $mostrarDocumentosParaIntitucionalizados = true;

            $obligatorios = SolicitudDocumentos::findActivosObligatorioByIdTipoSolicitud(
                $idTipoSolicitud,
                $mostrarDocumentosParaIntitucionalizados,
                $idSubTipoSolicitud
            );
            $opcionales = SolicitudDocumentos::findActivosNoObligatorioByIdTipoSolicitud(
                $idTipoSolicitud,
                $mostrarDocumentosParaIntitucionalizados,
                $idSubTipoSolicitud
            );
            $response = [
                'obligatorios' => $obligatorios,
                'opcionales' => $opcionales
            ];

            $this->debug("Luego de obtener respuesta de obtieneListaDocumentosRequeridos: " . json_encode($response));
            return $response;
        } catch (Exception $exception) {
            throw ExcepcionEjecucion::new("Error inesperado recuperarAdjuntos.");
        }
    }

    /**
     * @return SolicitudAdjuntos|Builder|mixed|null
     * @throws ExcepcionConsistencia
     * @throws ExcepcionValidacion
     * @throws Throwable
     */
    public function guardarAdjunto(AdjuntosInput $input, $idSolicitud, $idTipoDocumento, $idDocumento = null)
    {
        $this->infoInicioDepuracion($idSolicitud, $idTipoDocumento, $input->runUsuario);
        try {
            $documentoSalida = null;
            $documento = null;
            $solicitud = Solicitud::findByIdSolicitud($idSolicitud);
            $this->validarTipoDocumentoCorrespondeASolicitud($idTipoDocumento, $solicitud);

            $escribirArchivo = true;
            $y = date("Y");
            $m = date("m");
            $fechaCompleta = date("YmdHis");
            $textoTipoSolicitud = $this->obtenerTextoTipoSolicitud($solicitud->ID_TIPO_SOLICITUD);

            $nombreBase = $solicitud->ID_PADRE_SOLICITUD . "-" . $textoTipoSolicitud . "-" . $fechaCompleta;
            $ruta = implode(DIRECTORY_SEPARATOR,
                [storage_path(), Constante::ADJUNTOS_DIR_ANTECEDENTES, $textoTipoSolicitud, $y, $m, '']);

            $esDocumentoCompartido = $this->esDocumentoCompartido($solicitud->ID_TIPO_SOLICITUD, $idTipoDocumento);
            $solicitudes = $this->obtenerSolicitudesSegunDocumentosCompartidos($solicitud, $esDocumentoCompartido);

            foreach ($solicitudes as $solicitudActual) {
                $extension = $input->documento->getClientOriginalExtension();
                $nombre = $nombreBase . "-" . substr(md5($nombreBase), 1, 5) . "." . $extension;
                if ($escribirArchivo) {
                    $this->moverArchivoManual($ruta, $input->documento, $nombre);
                    $escribirArchivo = false;
                }

                $rutaLocal = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR,
                    [Constante::ADJUNTOS_DIR_ANTECEDENTES, $textoTipoSolicitud, $y, $m, $nombre]);
                $documento = SolicitudAdjuntos::actualizarRegistroDocumento(
                    $solicitudActual->ID_SOLICITUD,
                    $idTipoDocumento,
                    $input->runUsuario,
                    $input->documento->getClientOriginalName(),
                    $rutaLocal,
                    $idDocumento,
                    $input->runTitular,
                    $esDocumentoCompartido
                );
                if($documento->DOC_IDSOLICITUD == $idSolicitud){
                    $documentoSalida = $documento;
                }
            }

            $this->debug('Fin manipulación de archivo.');
            return $documentoSalida ?? $documento; //en caso que $documentoSalida pueda llegar a ser null

        } catch (Exception $ex) {
            $this->debug('Fin manipulación de archivo.');
            throw $ex;
        }
    }

    /**
     * @param $idSolicitud
     * @param $idTipoDocumento
     * @param null $idDocumento
     * @return bool|int|null
     * @throws ExcepcionConsistencia
     * @throws ExcepcionValidacion
     * @throws Exception
     */
    public function quitarAdjunto($idSolicitud, $idTipoDocumento, $idDocumento = null)
    {
        $solicitud = Solicitud::findByIdSolicitud($idSolicitud);
        $this->validarTipoDocumentoCorrespondeASolicitud($idTipoDocumento, $solicitud);
        $esDocumentoCompartido = $this->esDocumentoCompartido($solicitud->ID_TIPO_SOLICITUD, $idTipoDocumento);

        return SolicitudAdjuntos::eliminarRegistroDocumento(
            $solicitud->ID_SOLICITUD,
            $idTipoDocumento,
            $idDocumento,
            $esDocumentoCompartido
        );
    }

    /**
     * @param $nombre
     * @param $adjuntos
     * @return string
     * @throws ExcepcionConsistencia
     * @throws ExcepcionEjecucion
     * @throws ExcepcionServicio
     */
    public function generarZip($nombre, $adjuntos)
    {

        try {
            $path = storage_path() . DIRECTORY_SEPARATOR . "antecedentes" . DIRECTORY_SEPARATOR . $nombre;

            $zip = new ZipArchive();
            if ($zip->open($path, ZipArchive::OVERWRITE | ZipArchive::CREATE) !== true) {
                throw new ExcepcionEjecucion('No se puede crear el archivo: "' . $path . '"',
                    Constante::ERR_EJECUCION_GENERACION_ARCHIVO);
            }

            $nombresArchivos = array_column($adjuntos, 'archivo-carga');
            $repetidos = array_count_values($nombresArchivos);
            $contador = [];
            $archivosNoEncontrados = [];
            $archivosNoAgregados = [];

            foreach ($adjuntos as $archivo) {
                if (File::exists($archivo['ruta-archivo'])) {
                    $nombreArchivo = null;
                    if ($repetidos[$archivo['archivo-carga']] > 1) {
                        $n = isset($contador[$archivo['archivo-carga']]) ? $contador[$archivo['archivo-carga']] : 1;
                        $info = pathinfo($archivo['archivo-carga']);
                        $extension = isset($info["extension"]) ? '.' . $info["extension"] : '';
                        $nombreArchivo = ($n > 1) ?
                            $info["filename"] . "(" . $n . ")" . $extension : $archivo['archivo-carga'];
                        $contador[$archivo['archivo-carga']] = ++$n;
                    } else {
                        $nombreArchivo = $archivo['archivo-carga'];
                    }
                    if ($zip->addFile($archivo['ruta-archivo'], $nombreArchivo) !== true) {
                        $archivosNoAgregados[] = $archivo['ruta-archivo'];
                    }
                } else {
                    $archivosNoEncontrados[] = $archivo['ruta-archivo'];
                }
            }
            if (count($archivosNoEncontrados) > 0) {
                self::debug('Archivos no encontrados en el servidor: [' . implode(' ; ', $archivosNoEncontrados) . ']');
            }
            if (count($archivosNoAgregados) > 0) {
                self::debug('Archivos que no se pudieron agregar: [' . implode(' ; ', $archivosNoAgregados) . ']');
            }
            if (count($adjuntos) == count($archivosNoEncontrados)) {
                throw new ExcepcionConsistencia('Los documentos no están disponibles en la plataforma.',
                    Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE);
            }
            if (count($adjuntos) == count($archivosNoAgregados)) {
                throw new ExcepcionEjecucion('No se pudo cargar los documentos.',
                    Constante::ERR_EJECUCION_GENERACION_ARCHIVO);
            }
            if ($zip->numFiles > 0) {
                $zip->close();
            } else {
                throw new ExcepcionEjecucion('No se pudo cargar los documentos.',
                    Constante::ERR_EJECUCION_GENERACION_ARCHIVO);
            }
            return $path;
        } catch (ExcepcionServicio $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new ExcepcionEjecucion(
                'No se pudo crear el archivo zip.',
                Constante::ERR_EJECUCION_GENERACION_ARCHIVO,
                $e
            );
        }
    }

    /**
     * @param Solicitud $solicitud
     * @param false $esDocumentoCompartido
     * @return Solicitud[]|Builder[]|Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     * @throws ExcepcionConsistencia
     */
    private function obtenerSolicitudesSegunDocumentosCompartidos(Solicitud $solicitud, $esDocumentoCompartido = false)
    {
        $tipoSolicitud = $solicitud->ID_TIPO_SOLICITUD;
        $idPadreSolicitud = $solicitud->ID_PADRE_SOLICITUD;

        if ($esDocumentoCompartido) {
            $solicitudes = Solicitud::findByIdPadreSolicitud($idPadreSolicitud, $tipoSolicitud);
        } else {
            $solicitudes = [$solicitud];
        }
        return $solicitudes;
    }

    public function esDocumentoCompartido($tipoSolicitud, $idTipoDocumento)
    {
        $documentosCompartidosPorTipoSolicitud = $this->obtenerListadoDocumentosCompartidos();
        return isset($documentosCompartidosPorTipoSolicitud[$tipoSolicitud]) &&
            in_array($idTipoDocumento, $documentosCompartidosPorTipoSolicitud[$tipoSolicitud]);
    }

    private function obtenerListadoDocumentosCompartidos()
    {
        $out = [];
        $result = SolicitudDocumentos::whereIn('id_tipo_solicitud', Constante::$tiposSolcitudesConDocumentosCompartidos)
            ->whereIn('clasificacion_documento', Constante::$categoriasDocumentosCompartidos)
            ->get(['id_documento', 'id_tipo_solicitud']);
        if (!empty($result)) {
            foreach ($result as $elemento) {
                if (!isset($out[$elemento->ID_TIPO_SOLICITUD])) {
                    $out[$elemento->ID_TIPO_SOLICITUD] = [];
                }
                $out[$elemento->ID_TIPO_SOLICITUD][] = $elemento->ID_DOCUMENTO;
            }
        }
        return $out;
    }

    /**
     * @param $idTipoDocumento
     * @param Solicitud $solicitud
     * @throws ExcepcionValidacion
     * @throws Exception
     */
    private function validarTipoDocumentoCorrespondeASolicitud($idTipoDocumento, Solicitud $solicitud)
    {
        $incluirDocumentosParaInstitucionalizados = true;
        $documentosObligatorio = SolicitudDocumentos::findActivosObligatorioByIdTipoSolicitud(
            $solicitud->ID_TIPO_SOLICITUD,
            $incluirDocumentosParaInstitucionalizados,
            $solicitud->ID_SUB_TIPO_SOLICITUD
        );
        $documentosOpcional = SolicitudDocumentos::findActivosNoObligatorioByIdTipoSolicitud(
            $solicitud->ID_TIPO_SOLICITUD,
            $incluirDocumentosParaInstitucionalizados,
            $solicitud->ID_SUB_TIPO_SOLICITUD
        );

        $esObligatorio = in_array($idTipoDocumento, array_column($documentosObligatorio->toArray(), 'ID_DOCUMENTO'));
        $esOpcional = in_array($idTipoDocumento, array_column($documentosOpcional->toArray(), 'ID_DOCUMENTO'));
        $esOpcional = $esOpcional || $this->esOpcionalEnActualizacionIngresoLaboral($solicitud, $idTipoDocumento);

        if (!$esObligatorio && !$esOpcional) {
            throw ExcepcionValidacion::new('El tipo de documento no corresponde a la solicitud')
                ->setContexto([
                    'tipo documento' => $idTipoDocumento,
                    'tipo solicitud' => $solicitud->ID_TIPO_SOLICITUD
                ]);
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    private function esOpcionalEnActualizacionIngresoLaboral(Solicitud $solicitud, $idTipoDocumento)
    {
        $esOpcional = false;
        if (in_array($solicitud->ID_TIPO_SOLICITUD, [
            Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE,
            Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE
        ])) {
            $documentosOpcional = SolicitudDocumentos::findActivosNoObligatorioByIdTipoSolicitud(
                Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE,
                false,
                $solicitud->ID_SUB_TIPO_SOLICITUD
            );
            $esOpcional = $esOpcional || in_array($idTipoDocumento,
                    array_column($documentosOpcional->toArray(), 'ID_DOCUMENTO'));
        }
        if (in_array($solicitud->ID_TIPO_SOLICITUD, [
            Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE,
            Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE
        ])) {
            $documentosOpcional = SolicitudDocumentos::findActivosNoObligatorioByIdTipoSolicitud(
                Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE,
                false,
                $solicitud->ID_SUB_TIPO_SOLICITUD
            );
            $esOpcional = $esOpcional || in_array($idTipoDocumento,
                    array_column($documentosOpcional->toArray(), 'ID_DOCUMENTO'));
        }
        return $esOpcional;
    }

    private function obtenerTextoTipoSolicitud($tipoSolicitud)
    {
        $textos = [
            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO => "ingreso",
            Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO => "domicilio",
            Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE => "incorporacion",
            Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE => "desvinculacion",
            Constante::TIPO_SOLICITUD_5_ACTUALIZACION_DE_MODULO_SALUD => "salud",
            Constante::TIPO_SOLICITUD_6_ACTUALIZACION_DE_MODULO_EDUCACION => "educacion",
            Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA => "vivienda",
            Constante::TIPO_SOLICITUD_14_ACTUALIZACION_OCUPACION_DE_INGRESOS => "ocupacion-ingresos",
            Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA => "domicilio-vivienda",
            Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR => "desvinculacion",
            Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR => "desvinculacion",

            /*complemento*/
            Constante::TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS => "complemento_ingreso",
            Constante::TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD => "complemento_salud",
            Constante::TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL => "complemento_educacion",
            Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ => "complemento_bien_raiz",
            Constante::TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA => "complemento_compensacion_economica",
            Constante::TIPO_SOLICITUD_81_COMPLEMENTO_DE_SOCIEDAD_SIN_LUCRO => "complemento_bien_raiz_sin_lucro",

            /*actualizacion*/
            Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE => "actualizacion_ingreso_dependiente",
            Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE => "actualizacion_ingreso_independiente",
            Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE => "actualizacion_ingreso_mixto",
            Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION => "actualizacion_ingreso_pension",
            Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL => "actualizacion_ingreso_capital",
            Constante::TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD => "actualizacion_salud",
            Constante::TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL => "actualizacion_educacion",
            Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ => "actualizacion_bien_raiz",
            Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL => "actualizacion_tenencia_vehiculo",
            Constante::TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR => "actualizacion_educacion_superior",

            /*rectificacion*/
            Constante::TIPO_SOLICITUD_70_RECTIFICACION_INGRESO_TRABAJADOR_DEPENDIENTE => "actualizacion_ingreso_dependiente",
            Constante::TIPO_SOLICITUD_71_RECTIFICACION_INGRESO_TRABAJADOR_INDEPENDIENTE => "actualizacion_ingreso_independiente",
            Constante::TIPO_SOLICITUD_72_RECTIFICACION_DE_INGRESOS_DE_CAPITAL => "actualizacion_ingreso_pension",
            Constante::TIPO_SOLICITUD_73_RECTIFICACION_DE_COTIZACIONES_DE_SALUD => "actualizacion_salud",
            Constante::TIPO_SOLICITUD_74_RECTIFICACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL => "actualizacion_educacion",
            Constante::TIPO_SOLICITUD_75_RECTIFICACION_DE_DOMINIO_DE_BIEN_RAIZ => "actualizacion_bien_raiz",
            Constante::TIPO_SOLICITUD_76_RECTIFICACION_DE_PROPIEDAD_DE_VEHICULO => "actualizacion_bien_raiz",
        ];
        return array_key_exists($tipoSolicitud, $textos) ? $textos[$tipoSolicitud] : 'desconocido';
    }

    /**
     * Guarda un archivo usando codigo nativo PHP, teniendo un control absoluto sobre el proceso. Intenta una
     * determinada cantidad de veces en caso de errores y loguea cada paso.
     * @param string $carpeta
     * @param UploadedFile $file
     * @param string $nombre
     * @return false|string
     * @throws Exception
     */
    private function moverArchivoManual(string $carpeta, UploadedFile $file, string $nombre)
    {
        if (strpos($carpeta, storage_path()) !== 0) {
            $carpeta = storage_path() . $carpeta;
        }
        $infoUsuario = ' Usuario:' . $this->runUsuario . '-' . Utiles::dv($this->runUsuario);
        $NombreArchivoCompleto = $carpeta . $nombre;
        $this->debug('Nuevo archivo: Destino ' . $NombreArchivoCompleto);

        $this->debug('Comprobacion $_FILES[\'' . AdjuntosInput::$campoArchivo . '\']');
        if (empty($_FILES[AdjuntosInput::$campoArchivo])) {
            throw new Exception('$_FILES[\'' . AdjuntosInput::$campoArchivo . '\'] vacío.');
        }
        $this->debug('$_FILES[] Correcto.');

        if (is_array($_FILES[AdjuntosInput::$campoArchivo]['name'])) {
            $indice = array_search($file->getClientOriginalName(), $_FILES[AdjuntosInput::$campoArchivo]['name']);
            $error = $_FILES[AdjuntosInput::$campoArchivo]['error'][$indice];
            $tmp_name = $_FILES[AdjuntosInput::$campoArchivo]['tmp_name'][$indice];
        } else {
            $error = $_FILES[AdjuntosInput::$campoArchivo]['error'];
            $tmp_name = $_FILES[AdjuntosInput::$campoArchivo]['tmp_name'];
        }

        $this->debug('Comprobacion $_FILES[\'' . AdjuntosInput::$campoArchivo . '\'][error]');
        if ($error != UPLOAD_ERR_OK) {
            $phpFileUploadErrors = [
                UPLOAD_ERR_INI_SIZE => 'El archivo subido excede la directiva upload_max_filesize de php.ini.',
                UPLOAD_ERR_FORM_SIZE => 'El archivo subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.',
                UPLOAD_ERR_PARTIAL => 'El archivo fue sólo parcialmente subido.',
                UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo.',
                UPLOAD_ERR_NO_TMP_DIR => 'Falta la carpeta temporal.',
                UPLOAD_ERR_CANT_WRITE => 'No se pudo escribir el archivo en el disco.',
                UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la subida de archivos.',
            ];
            throw new Exception($phpFileUploadErrors[$error]);
        }
        $this->debug('$_FILES[][error] Correcto.');

        for ($intento = 1; $intento <= Constante::ADJUNTOS_INTENTOS_MAXIMO; $intento++) {
            try {
                $this->debug('Antes de guardar el archivo. Intento nro ' . $intento . '.');

                $this->debug('Comprobacion is_uploaded_file(' . $tmp_name . ')');
                if (is_uploaded_file($tmp_name) === false) {
                    throw new Exception('is_uploaded_file() falló.');
                }
                $this->debug('is_uploaded_file() Correcto.');

                $this->debug('Comprobacion is_dir(' . $carpeta . ')');
                if (!is_dir($carpeta)) {
                    $this->debug('is_dir() falló.');
                    $this->debug('Ejecutando mkdir(' . $carpeta . ',0700, true)');
                    if ((!mkdir($carpeta, 0700, true)) && (!mkdir($carpeta, 448, true))) {
                        throw new Exception('mkdir(' . $carpeta . ') falló.');
                    }
                    $this->debug('mkdir() Correcto.');
                } else {
                    $this->debug('is_dir() Correcto.');
                }

                $this->debug('Comprobacion move_uploaded_file(' . $tmp_name . ',' . $NombreArchivoCompleto . ')');
                if (!move_uploaded_file($tmp_name, $NombreArchivoCompleto)) {
                    throw new Exception('move_uploaded_file() falló . ');
                }
                $this->debug('move_uploaded_file() Correcto.');

                $this->debug('Comprobacion file_exists(' . $NombreArchivoCompleto . ')');
                if (!file_exists($NombreArchivoCompleto)) {
                    throw new Exception('file_exists() falló.');
                }
                $this->debug('file_exists() Correcto.');
                break;

            } catch (Exception $exception) {
                $this->debug($exception->getMessage(), 'error');
                Log::error($exception, ['Intento nro ' . $intento, $infoUsuario]);
                if ($intento < Constante::ADJUNTOS_INTENTOS_MAXIMO) {
                    continue;
                }
                $this->debug('Falló el guardado de archivo ' . $NombreArchivoCompleto);
                throw $exception;
            }
        }
        $this->debug('Archivo guardado correctamente.');
        return $NombreArchivoCompleto;
    }

    /**
     * @return bool
     * @throws ExcepcionConsistencia
     * @throws Exception
     */
    public function validarDocumentosRequeridos(Solicitud $solicitud, array $parametros = [])
    {
        $defaults = [
            'v1' => null,
            'integrantes' => [],
            'grupo_familiar_institucionalizado' => false
        ];
        $parametros = array_replace($defaults, $parametros);

        //inicializa variables
        $requiereDocumentoOpcionales = true;
        $tipoSolicitud = $solicitud->ID_TIPO_SOLICITUD;
        $subTipoSolicitud = $solicitud->ID_SUB_TIPO_SOLICITUD;
        $detalleDocumentosObligatorio = SolicitudDocumentos::findActivosObligatorioByIdTipoSolicitud($tipoSolicitud,
            false, $subTipoSolicitud)
            ->toArray();
        $detalleDocumentosNoObligatorio = self::recuperarDocumentosOpcionalesSegunSolicitud($solicitud);

        //comprueba si requiere documentos no obligatorios
        if ($tipoSolicitud == Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION) {
            $requiereDocumentoOpcionales = false;
        }
        if (in_array($tipoSolicitud, [
            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
            Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA
        ])) {
            //no se requiere adjuntar documento cuando el tipo de vivienda es 8:Hospedería, 9:Caleta o Punto de calle, 10:Vivienda colectiva, 11:Residencial, pensión
            $tipo_vivienda = $parametros['v1'];
            if (in_array($tipo_vivienda, [
                    Constante::RESPUESTA_V1_8_HOSPEDERIA,
                    Constante::RESPUESTA_V1_9_CALETA_O_PUNTO_DE_CALLE,
                    Constante::RESPUESTA_V1_10_VIVIENDA_COLECTIVA,
                    Constante::RESPUESTA_V1_11_RESIDENCIAL_PENSION
                ]) && !is_null($tipo_vivienda)
            ) {
                $requiereDocumentoOpcionales = false;
            }
        }

        //ejecuta validacion
        if ($tipoSolicitud == Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE) {
            $valido = true;
            $integrantes = [];
            $runesAdultos = [];
            $runesGrupoFamiliar = [];
            $menoresConPadreyMadre = [];
            $menoresQueRequierenDocumentacion = [];

            $solicitudesIncluidas = (new DatosDeSolicitud())->findByIdPadreSolicitud($solicitud->ID_PADRE_SOLICITUD);
            $grupoFamiliar = BeneficiarioGrupoFamiliar::findBeneficiariosByRun($solicitud->RUN_CIUDADANO);
            foreach ($grupoFamiliar as $beneficiario) {
                $runesGrupoFamiliar[] = $beneficiario->PER_RUN;
            }

            // analiza los integrantes
            foreach ($solicitudesIncluidas as $solicitudActual) {

                $integrante = $solicitudActual->Integrante->Persona;
                $integranteVieneEnRequest = in_array($integrante->PER_RUN, $parametros['integrantes']);
                $solicitudEstaFinalizada = in_array($solicitudActual->ID_ESTADO,
                    Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS);
                if (!$integranteVieneEnRequest || $solicitudEstaFinalizada) {
                    continue;
                }

                $edad = Utiles::getEdad($integrante->PER_FECHANACIMIENTO);
                if ($edad >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                    $runesAdultos[] = $integrante->PER_RUN;
                } else {
                    $menoresConPadreyMadre[$integrante->PER_RUN] = [
                        'padre' => $integrante->PER_RUN_PADRE,
                        'madre' => $integrante->PER_RUN_MADRE
                    ];
                }
                $integrantes[$solicitudActual->ID_SOLICITUD] = (object)[
                    'run' => $integrante->PER_RUN,
                    'edad' => $edad
                ];
            }

            //analiza si hay menores sin padres en el grupo familiar
            foreach ($menoresConPadreyMadre as $runMenor => $menor) {
                $menorRegistraPadres = !($menor['padre'] == -99 && $menor['madre'] == -99);
                $padresEstanEnGrupoFamiliarActual = in_array($menor['padre'], $runesGrupoFamiliar) ||
                    in_array($menor['madre'], $runesGrupoFamiliar);
                $padresVienenEnRequest = in_array($menor['padre'], $runesAdultos) ||
                    in_array($menor['madre'], $runesAdultos);

                if (!$menorRegistraPadres || !$padresVienenEnRequest && !$padresEstanEnGrupoFamiliarActual) {
                    $menoresQueRequierenDocumentacion[] = $runMenor;
                }
            }

            foreach ($solicitudesIncluidas as $solicitudActual) {
                if (in_array($solicitudActual->ID_ESTADO, Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)) {
                    continue;
                }
                $integrante = $integrantes[$solicitudActual->ID_SOLICITUD];
                if (!in_array($integrante->run, $parametros['integrantes'])) {
                    continue;
                }
                /*if ($parametros['grupo_familiar_institucionalizado'] == Constante::INSTITUCIONALIZADO_1_INSTITUCIONALIZADO) {
                    $requiereDocumentoOpcionales = false;
                } else*/
                if ($integrante->edad >= Constante::EDAD_18_MAYOR_DE_EDAD) {
                    $requiereDocumentoOpcionales = false;
                } else {
                    $requiereDocumentoOpcionales = in_array($integrante->run, $menoresQueRequierenDocumentacion);
                }

                $resp = self::comprobarDiferenciaEntreRequeridosYAdjuntados(
                    $solicitudActual,
                    $detalleDocumentosObligatorio,
                    $detalleDocumentosNoObligatorio,
                    $requiereDocumentoOpcionales
                );
                $valido = $valido && $resp;
            }
        } else {
            $valido = self::comprobarDiferenciaEntreRequeridosYAdjuntados(
                $solicitud,
                $detalleDocumentosObligatorio,
                $detalleDocumentosNoObligatorio,
                $requiereDocumentoOpcionales
            );
        }
        return $valido;
    }

    /**
     * @return array
     * @throws Exception
     */
    private static function recuperarDocumentosOpcionalesSegunSolicitud($solicitud)
    {
        $tipoSolicitud = $solicitud->ID_TIPO_SOLICITUD;
        $subTipoSolicitud = $solicitud->ID_SUB_TIPO_SOLICITUD;

        //--chequear solicitudes de vivienda
        if (in_array($tipoSolicitud, [
            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO,
            Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO,
            Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA
        ])) {
            $tiposDeViviendaNoAcreditable = [
                Constante::RESPUESTA_V1_8_HOSPEDERIA,
                Constante::RESPUESTA_V1_9_CALETA_O_PUNTO_DE_CALLE,
                Constante::RESPUESTA_V1_10_VIVIENDA_COLECTIVA,
                Constante::RESPUESTA_V1_11_RESIDENCIAL_PENSION
            ];
            $tipoVivienda = GrupoFamiliar::findByIdGrupoFamiliar($solicitud->DATO_NUEVO)->GRUPFAMI_TIPOVIVIENDA;
            if (in_array($tipoVivienda, $tiposDeViviendaNoAcreditable)) {
                return [];
            }
        }
        $institucionalizados = null;

        if ($tipoSolicitud == Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE) {
            $ingresos = ActualizacionIngreso::where('idsolicitud', $solicitud->ID_SOLICITUD)->get();
            $salida = [];
            $tipoSolcitudFicticia = [
                1 => Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE,
                2 => Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE,
            ];
            foreach ($ingresos as $ingreso) {
                $suma = 0;
                for ($i = 1; $i <= 12; $i++) {
                    $suma = $suma + $ingreso->{'MES_' . $i};
                }
                if ($suma > 0) {
                    $salida[$ingreso->DEP_O_INDEP] = SolicitudDocumentos::findActivosNoObligatorioByIdTipoSolicitud(
                        $tipoSolcitudFicticia[$ingreso->DEP_O_INDEP],
                        $institucionalizados,
                        $subTipoSolicitud
                    )->toArray();
                }
            }
            //compatibilidad c/ componente sin regla especial de adjuntos dep/indep
            $salida[3] = SolicitudDocumentos::findActivosNoObligatorioByIdTipoSolicitud(
                $tipoSolicitud,
                $institucionalizados,
                $subTipoSolicitud
            )->toArray();
        } else {
            $salida = SolicitudDocumentos::findActivosNoObligatorioByIdTipoSolicitud(
                $tipoSolicitud,
                $institucionalizados,
                $subTipoSolicitud
            )->toArray();
        }

        return $salida;
    }

    /**
     * @return bool
     * @throws ExcepcionEjecucion
     */
    private function comprobarDiferenciaEntreRequeridosYAdjuntados(
        $solicitud,
        $detalleDocumentosObligatorio,
        $detalleDocumentosNoObligatorio,
        $requiereDocumentoOpcionales
    ) {
        $detalleDocumentosAdjuntados = self::recuperarAdjuntos($solicitud->ID_SOLICITUD);
        if (count($detalleDocumentosAdjuntados) == 0) {
            return false;
        }
        $docsAdjuntados = array_keys($detalleDocumentosAdjuntados);
        $docsObligatorios = array_column($detalleDocumentosObligatorio, 'ID_DOCUMENTO');
        $diffObligatorio = array_diff($docsObligatorios, $docsAdjuntados);
        if (count($diffObligatorio) > 0) {
            return false;
        }

        if ($requiereDocumentoOpcionales) {
            if ($solicitud->ID_TIPO_SOLICITUD == Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE) {
                $valido = [];
                foreach ($detalleDocumentosNoObligatorio as $indice => $caso) {
                    $docsOpcionales = array_column($caso, 'ID_DOCUMENTO');
                    $diffNoObligatorio = array_diff($docsOpcionales, $docsAdjuntados);
                    if ($indice == 3) {       //compatibilidad c/ componente sin regla especial de doc dep./indep.
                        if (count($diffNoObligatorio) <= count($caso) - 2) {   //requiere 2 documentos
                            if (isset($detalleDocumentosNoObligatorio[1])) {
                                $valido[1] = 1;
                            }
                            if (isset($detalleDocumentosNoObligatorio[2])) {
                                $valido[2] = 1;
                            }
                        }
                    } else {
                        if (count($diffNoObligatorio) != count($caso)) {
                            $valido[$indice] = 1;
                        }
                    }
                }
                if (count($valido) != count($detalleDocumentosNoObligatorio) - 1) { //-1 por compatibilidad c/ comp. sin regla especial
                    return false;
                }
            } /*
             * regla especia de bien raiz
             *
            elseif ($solicitud->id_tipo_solicitud == Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ) {
                if (count($detalleDocumentosNoObligatorio) > 0) {
                    $docsEmparejados = [1923, 1924];
                    $emparejadosAdjuntados = array_intersect($docsEmparejados, $docsAdjuntados);
                    if (count($emparejadosAdjuntados) == 1) {
                        return false;
                        /*$listaNoObligatorios = array_column($detalleDocumentosNoObligatorio, 'id_documento');
                        $presente = array_search(current($emparejadosAdjuntados), $listaNoObligatorios);
                        $ausente = array_search(
                            $docsEmparejados[abs(key($emparejadosAdjuntados) - 1)],
                            $listaNoObligatorios
                        );
                        throw new ValidacionException("El documento '" .
                            $detalleDocumentosNoObligatorio[$presente]['nombre'] . "' debe ser complementado con '" .
                            $detalleDocumentosNoObligatorio[$ausente]['nombre'] . "'"); * /

                    }
                    $docsOpcionales = array_column($detalleDocumentosNoObligatorio, 'id_documento');
                    $diffNoObligatorio = array_diff($docsOpcionales, $docsAdjuntados);
                    if (count($diffNoObligatorio) == count($detalleDocumentosNoObligatorio)) {
                        return false;
                    }
                }
            } elseif ($solicitud->id_tipo_solicitud == Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ) {
                if (count($detalleDocumentosNoObligatorio) > 0) {
                    $docsEmparejados = [1920, 1921];
                    $emparejadosAdjuntados = array_intersect($docsEmparejados, $docsAdjuntados);
                    if (count($emparejadosAdjuntados) == 1) {
                        return false;
                        /*$listaNoObligatorios = array_column($detalleDocumentosNoObligatorio, 'id_documento');
                        $presente = array_search(current($emparejadosAdjuntados), $listaNoObligatorios);
                        $ausente = array_search(
                            $docsEmparejados[abs(key($emparejadosAdjuntados) - 1)],
                            $listaNoObligatorios
                        );
                        throw new ValidacionException("El documento '" .
                            $detalleDocumentosNoObligatorio[$presente]['nombre'] . "' debe ser complementado con '" .
                            $detalleDocumentosNoObligatorio[$ausente]['nombre'] . "'"); * /
                    }
                    $docsOpcionales = array_column($detalleDocumentosNoObligatorio, 'id_documento');
                    $diffNoObligatorio = array_diff($docsOpcionales, $docsAdjuntados);
                    if (count($diffNoObligatorio) == count($detalleDocumentosNoObligatorio)) {
                        return false;
                    }
                }
            }*/
            else {
                if (count($detalleDocumentosNoObligatorio) > 0) {
                    $docsOpcionales = array_column($detalleDocumentosNoObligatorio, 'ID_DOCUMENTO');
                    $diffNoObligatorio = array_diff($docsOpcionales, $docsAdjuntados);
                    if (count($diffNoObligatorio) == count($detalleDocumentosNoObligatorio)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    protected function configurarDebug()
    {
        $this->logChannel = 'adjuntos';
        if (env('ACTIVAR_DEPURACION_ADJUNTOS', false)) {
            $this->logDebug = true;
        }
    }

    private function infoInicioDepuracion($idSolicitud, $idTipoDocumento, $runUsuario)
    {
        $this->runUsuario = $runUsuario;
        $ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];

        $this->debug("****************************************************************");
        $this->debug('Inicio manipulacion de archivo.');
        $this->debug('IP Cliente: ' . $ip . ' | IP Servidor: ' . $_SERVER['SERVER_ADDR'] . ' | Usuario: ' . $runUsuario . '-' . Utiles::dv($runUsuario));
        $this->debug('ID Solicitud : ' . $idSolicitud . ' | ID Tipo Documento : ' . $idTipoDocumento);
    }

    public function listaRequeridosExtras($idSolicitud)
    {
        try {
            if (!is_numeric($idSolicitud)) {
                $this->debug("ID solcitiud invalida: " . $idSolicitud);
                throw ExcepcionValidacion::new('Solicitud no válida.')
                    ->setStatusCode(Constante::STATUS_412_ERROR_PRECONDITION_FAILED);
            }
            $response = SupervisionDocExtra::Where('id_solicitud',$idSolicitud)->get();
            return $response;
        } catch (Exception $exception) {
            throw ExcepcionEjecucion::new("Error inesperado documentos Extras. ", 0, $exception);
        }
    }

}
