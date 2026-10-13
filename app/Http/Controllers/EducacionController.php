<?php

namespace App\Http\Controllers;

use App\Http\ResponseMappers\JsonMapper;
use App\Models\Educacion;
use App\Models\InstitucionEducacionSuperior;
use App\Models\MotivoEducacionSuperior;
use Exception;

class EducacionController extends Controller
{
    private $modeloEducacion;
    private $modeloMotivoEducacionSuperior;
    private $modeloInstitucionEducacionSuperior;

    public function __construct()
    {
        $this->modeloEducacion = new Educacion();
        $this->modeloMotivoEducacionSuperior = new MotivoEducacionSuperior();
        $this->modeloInstitucionEducacionSuperior = new InstitucionEducacionSuperior();
    }

    public function EducacionSuperiorMotivo()
    {
        try {
            $motivo = $this->modeloMotivoEducacionSuperior->getAllSuperiorMotivo();

            return new JsonMapper($motivo);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function EducacionSuperiorTipo()
    {
        try {
            $tipoInst = $this->modeloInstitucionEducacionSuperior->getAllSuperiorTipoInstitucion();

            return new JsonMapper($tipoInst);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function EducacionSuperiorInstitucion($tipoInstitucion)
    {
        try {
            $instituciones = $this->modeloInstitucionEducacionSuperior->getSuperiorInstitucion($tipoInstitucion);

            return new JsonMapper($instituciones);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function EducacionSuperiorInstitucionTodas()
    {
        try {
            $listaInstituciones = $this->modeloInstitucionEducacionSuperior->getAllSuperiorInstitucion();

            return new JsonMapper($listaInstituciones);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }

    public function EducacionColegiosInstitucion()
    {
        try {
            $colegios = $this->modeloEducacion->getAllColegios();

            return new JsonMapper($colegios);
        } catch (Exception $ex) {
            return $this->handleException($ex);
        }
    }
}
