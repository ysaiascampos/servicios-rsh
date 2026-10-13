<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Models\GrupoFamiliarExport;
use App\Models\PersonasExport;

class TramiteExtends extends Tramite
{

    public function editarSolicitud()
    {
        // TODO: Implement editarSolicitud() method.
    }

    public function enDigitacion()
    {
        // TODO: Implement enDigitacion() method.
    }

    public function pendienteDocumentos()
    {
        // TODO: Implement pendienteDocumentos() method.
    }

    public function pendienteResolver()
    {
        // TODO: Implement pendienteResolver() method.
    }

    public function devolverGestion()
    {
        // TODO: Implement devolverGestion() method.
    }

    protected function cargarDataTramite()
    {
        // TODO: Implement cargarDataTramite() method.
    }

    protected function obtenerPersonasAsociadas()
    {
        // TODO: Implement obtenerPersonasAsociadas() method.
    }

    protected function guardarSolicitud(array $data)
    {
        // TODO: Implement guardarSolicitud() method.
    }

    protected function actualizarRegistrosAprobacion()
    {
        // TODO: Implement actualizarRegistrosAprobacion() method.
    }

    public function obtenerNumeroRegistro($solicitante = null, $error = true)
    {
        $nroGrupoFamiliar = null;
        if(isset($solicitante)){
            $personaExport = PersonasExport::findByRunCiudadano($solicitante->PER_RUN);
            if ($personaExport) {
                $grupoFamiliarExport = GrupoFamiliarExport::findByIdGrupoFamiliar($personaExport->PER_FIC_GRUPOFAMILIAR_ID);
                if ($grupoFamiliarExport) {
                    $nroGrupoFamiliar = $grupoFamiliarExport->GRUPFAMI_NUEVOFOLIO;
                } else {
                    $nroGrupoFamiliar = '';
                }
            } else {
                if($error){
                    $this->collectError('No se puede consultar el export.',
                        Constante::ERR_NEGOCIO_ERROR_NO_SE_ENCONTRA_GRUPO_FAMILIAR_VIGENTE_Y_ACTIVO);
                }
            }
        }
        return $nroGrupoFamiliar;
    }
}
