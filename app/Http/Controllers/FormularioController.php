<?php


namespace App\Http\Controllers;


use App\Clases\Tramite;
use App\Clases\FormularioSolicitud;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Response;
use Throwable;
use App\Http\Inputs\DatosGlobalesInput;


class FormularioController extends Controller
{
    protected $idSolicitud;
    protected $tramite;

    public function descargarFormulario($portal, $idSolicitud)
    {
        $this->idSolicitud = $idSolicitud;
        $portal = strtolower($portal);

        try {
            $this->validarParametros(['idSolicitud' => $idSolicitud, 'portal' => $portal]);

            $this->tramite = Tramite::instanciarSubClase(null, $idSolicitud);

            $formulario = new FormularioSolicitud($portal, $this->tramite);

            return $formulario->descargarFormulario();

        } catch (Throwable $ex) {
            return $this->handleException($ex);
        }
    }


//    public function guardarFormularioEnStorage($portal, $idSolicitud)
    public function guardarFormularioEnStoragePdf()
    {
        $info = request()->all();

        $this->idSolicitud = $info['idSolicitud'];
        $portal = strtolower($info['portal']);

        try {
            $this->validarParametros(['idSolicitud' => $this->idSolicitud , 'portal' => $portal]);

            $this->tramite = Tramite::instanciarSubClase(null, $this->idSolicitud );

            $formulario = new FormularioSolicitud($portal, $this->tramite);

            return $formulario->guardarFormularioEnStoragePDF($info);

        } catch (Throwable $ex) {
            return $this->handleException($ex);
        }
    }

    public function validarParametros(array $parametros)
    {
        $reglas = [
            'idSolicitud' => 'numeric',
            'portal' => 'string|in:' . implode(',', Constante::$PORTAL_VALIDOS)
        ];
        $mensajes = [
            'idSolicitud.numeric' => 'Id solicitud no valido.',
            'portal.in' => 'Portal no valido.',
        ];
        $validador = Validator::make($parametros, $reglas, $mensajes);
        if ($validador->fails()) {
            throw new ExcepcionValidacion($validador->errors());
        }
    }

    public function handleException(Throwable $exception)
    {
        Log::error("Ha ocurrido un problema al obtener formulario de id Solicitud " . $this->idSolicitud .
            " Detalles del error  -> " . $exception);

        if (empty($this->tramite) || !($this->tramite instanceof Tramite)) {
            $this->tramite = Tramite::crearDummyParaFormulario($this->idSolicitud);
        }
        return FormularioSolicitud::descargarFormularioError($this->tramite);
    }
}
