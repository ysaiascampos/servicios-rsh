<?php

namespace App\Http\Controllers;

use App\Clases\Tramite;
use App\Exceptions\ExcepcionValidacion;
use App\Http\Inputs\TramiteInput;
use App\Http\ResponseMappers\TramiteMapper;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TramiteController extends Controller
{
    public function show($idSolicitud)
    {
        try {
            $this->validarParametros(['idSolicitud' => $idSolicitud]);

            $tramite = Tramite::instanciarSubClase(null, $idSolicitud);

            return TramiteMapper::new($tramite->tipoSolicitud)
                ->consulta($tramite);
        } catch (Throwable $ex) {
            return $this->handleException($ex);
        }
    }

    public function store()
    {
        try {
            $input = new TramiteInput(request()->all());

            $tramite = Tramite::instanciarSubClase($input);
            $tramite->seguirFlujo();

            return TramiteMapper::new($tramite->tipoSolicitud)
                ->resultado($tramite);
        } catch (Throwable $ex) {
            return $this->handleException($ex);
        }
    }

    public function update($idSolicitud)
    {
        try {
            $this->validarParametros(['idSolicitud' => $idSolicitud]);

            $input = new TramiteInput(request()->all());

            $tramite = Tramite::instanciarSubClase($input, $idSolicitud);
            $tramite->seguirFlujo();

            return TramiteMapper::new($tramite->tipoSolicitud)
                ->resultado($tramite);
        } catch (Throwable $ex) {
            return $this->handleException($ex);
        }
    }

    /**
     * @throws ExcepcionValidacion
     */
    private function validarParametros(array $parametros)
    {
        $reglas = [
            'idSolicitud' => 'numeric',
        ];
        $mensajes = [
            'idSolicitud.numeric' => 'Id solicitud no valido.',
        ];
        $validador = Validator::make($parametros, $reglas, $mensajes);
        if ($validador->fails()) {
            throw new ExcepcionValidacion($validador->errors());
        }
    }
}
