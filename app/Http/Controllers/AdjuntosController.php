<?php

namespace App\Http\Controllers;

use App\Clases\Adjuntos;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Http\Inputs\AdjuntosInput;
use App\Http\ResponseMappers\AdjuntosMapper;
use App\Http\ResponseMappers\DescargaArchivo;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use function GuzzleHttp\Psr7\str;


class AdjuntosController extends Controller
{
    private $adjuntos;

    public function __construct(Adjuntos $adjuntos)
    {
        $this->adjuntos = $adjuntos;
    }

    public function show($idSolicitud)
    {
        try {
            $this->validarParametros(['idSolicitud' => $idSolicitud]);

            $datos = $this->adjuntos->recuperarAdjuntos($idSolicitud);

            return AdjuntosMapper::new()->show($datos);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function zip($idSolicitud)
    {
        try {
            $this->validarParametros(['idSolicitud' => $idSolicitud]);

            $zip = $this->adjuntos->recuperarZipAdjuntos($idSolicitud);

            return DescargaArchivo::desdeRuta($zip->ruta, $zip->nombre, $zip->borrarDespues);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function download($idSolicitud, $idTipoDocumento, $idDocumento = null)
    {
        try {
            $this->validarParametros([
                'idSolicitud' => $idSolicitud,
                'idTipoDocumento' => $idTipoDocumento,
                'idDocumento' => $idDocumento
            ]);

            $archivo = $this->adjuntos->recuperarDocumento($idSolicitud, $idTipoDocumento, $idDocumento);

            return DescargaArchivo::desdeRuta($archivo->ruta, $archivo->nombre, $archivo->borrarDespues);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function store($idSolicitud, $idTipoDocumento, $idDocumento = null)
    {
        try {
            $this->validarParametros([
                'idSolicitud' => $idSolicitud,
                'idTipoDocumento' => $idTipoDocumento,
                'idDocumento' => $idDocumento
            ]);
            $this->validacionAdicionalParaSubirArchivo();

            $input = new AdjuntosInput(request()->all());
            $input->validarCamposObligatorios(['run-usuario', 'documento']);

            $resultado = $this->adjuntos->guardarAdjunto($input, $idSolicitud, $idTipoDocumento, $idDocumento);

            return AdjuntosMapper::new()->store($resultado);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function delete($idSolicitud, $idTipoDocumento, $idDocumento = null)
    {
        try {
            $this->validarParametros([
                'idSolicitud' => $idSolicitud,
                'idTipoDocumento' => $idTipoDocumento,
                'idDocumento' => $idDocumento
            ]);

            $this->adjuntos->quitarAdjunto($idSolicitud, $idTipoDocumento, $idDocumento);

            return AdjuntosMapper::new()->delete();
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function listarDocumentosRequeridos($idTipoSolicitud, $idSubTipoSolicitud = null)
    {
        try {
            $this->validarParametros([
                'idTipoSolicitud' => $idTipoSolicitud,
                'idSubTipoSolicitud' => $idSubTipoSolicitud
            ]);

            $datos = $this->adjuntos->obtieneListaDocumentosRequeridos($idTipoSolicitud, $idSubTipoSolicitud);

            return AdjuntosMapper::new()->requeridos($datos);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    private function validarParametros(array $parametros)
    {
        $reglas = [
            'idSolicitud' => 'numeric',
            'idTipoSolicitud' => 'numeric',
            'idSubTipoSolicitud' => 'nullable|numeric',
            'idDocumento' => 'nullable|numeric',
            'idTipoDocumento' => 'numeric',
            'runCiudadano' => 'numeric|between:' . implode(',',
                    Constante::$RUN_1_Y_99999999_POSIBLES_RESPUESTAS_NUMERIC)
        ];
        $mensajes = [
            'idSolicitud.numeric' => 'Id solicitud no valido.',
            'idTipoSolicitud.numeric' => 'Tipo de solicitud no válida.',
            'idSubTipoSolicitud.numeric' => 'Sub tipo de solicitud no válida.',
            'idDocumento.numeric' => 'Id documento no valido.',
            'idTipoDocumento.numeric' => 'Tipo de documento no válido.',
            'runCiudadano.required' => 'El RUN es un parámetro obligatorio',
            'runCiudadano.numeric' => 'El RUN debe ser de tipo númerico',
            'runCiudadano.between' => 'Verifique la longitud del RUN ingresado',
        ];
        $validador = Validator::make($parametros, $reglas, $mensajes);
        if ($validador->fails()) {
            throw new ExcepcionValidacion($validador->errors());
        }
    }

    private function validacionAdicionalParaSubirArchivo()
    {
        $variablesIni = [
            'upload_max_filesize' => trim(ini_get('upload_max_filesize')),
            'post_max_size' => trim(ini_get('post_max_size'))
        ];
        foreach ($variablesIni as $variable => $valor) {
            $valorEnKB = 0;
            if (is_numeric($valor)) {
                $valorEnKB = $valor / 1024;
            } else {
                $valorEntero = (int)filter_var($valor, FILTER_SANITIZE_NUMBER_INT);
                if (stripos($valor, 'K') !== false) {
                    $valorEnKB = $valorEntero;
                } elseif (stripos($valor, 'M') !== false) {
                    $valorEnKB = $valorEntero * 1024;
                } elseif (stripos($valor, 'G') !== false) {
                    $valorEnKB = $valorEntero * pow(1024, 2);
                }
            }
            if ($valorEnKB < Constante::ADJUNTOS_MAX_SIZE_KB) {
                $mensaje = 'La variable \'' . $variable . '\' de php.ini debe ser mayor a ' . Constante::ADJUNTOS_MAX_SIZE_KB . 'KB. (valor actual:' . $valor . ')';
                Log::warning($mensaje);
                Log::channel('adjuntos')->warning($mensaje);
            }
        }

        if (!Request::hasFile(AdjuntosInput::$campoArchivo)) {
            throw ExcepcionValidacion::new('Debe seleccionar un archivo. También revisar que su tamaño no supere los ' . ((int)Constante::ADJUNTOS_MAX_SIZE_KB / 1024) . 'MB')
                ->setStatusCode(Constante::STATUS_413_ERROR_REQUEST_ENTITY_TOO_LARGE);
        }
    }

    public function requeridosExtrasShow($idSolicitud)
    {
        try {
            $this->validarParametros(['idSolicitud' => $idSolicitud]);

            $datos = $this->adjuntos->listaRequeridosExtras($idSolicitud);

            return AdjuntosMapper::new()->listaRequeridosExtras($datos);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
