<?php

namespace App\Http\ResponseMappers;

use App\Clases\DatosGlobales;

class DatosGlobalesMapper extends ResponseMapper
{

    public function __construct(DatosGlobales $datosGlobales)
    {

        if ($datosGlobales->cargarDatosContacto) {
            $this->campos['datos-de-contacto'] = new PersonaMapper($datosGlobales->ciudadano);
        }
        
        $this->campos['adulto-responsable'] = $datosGlobales->datosContactoAdultoResponsable;
    
        if ($datosGlobales->cargarGrupoFamiliarAsignacion) {
            $this->campos['grupo-familiar-asignacion'] = new GrupoFamiliarAsignacion($datosGlobales->grupoFamiliarAsignacion,
                $datosGlobales->integrantesAsignacion, $datosGlobales->preguntasYRespuestas);
        }
        if ($datosGlobales->cargarGrupoFamiliarExport) {
            $this->campos['grupo-familiar-export'] = new GrupoFamiliarExport($datosGlobales->grupoFamiliarExport,
                $datosGlobales->integrantesExport, $datosGlobales->preguntasYRespuestas);
        }
        if ($datosGlobales->cargarSolicitudes) {
            $this->campos['solicitudes'] = self::mapSolicitudes($datosGlobales->solicitudes);
        }
        if ($datosGlobales->cargarSolicitudesIncorporarDesvincular) {
            $this->campos['solicitudes-run-incorporar-desvincular'] = self::mapSolicitudesIncorporarDesvincular($datosGlobales->solicitudesIncorporarDesvincular);
        }
        if ($datosGlobales->cargarBeneficios) {
            $this->campos['beneficios'] = new BeneficiosMapper($datosGlobales->integrantesExport,
                $datosGlobales->integrantesAsignacion, $datosGlobales->ciudadano);
        }
        if ($datosGlobales->cargarSolicitudes) {
            $this->campos['solicitudes-tramitables'] = $datosGlobales->solicitudesTramitables;
        }
        if ($datosGlobales->cargarSupervision) {
            $this->campos['supervision'] = self::mapMensajesSupervision($datosGlobales->mensajesSupervision);
        } else {
            $this->campos['supervision'] = [
                'mensaje-individual' => [],
                'mensaje-grupal' => []
            ];
        }
        if ($datosGlobales->cargarModalAviso) {
            $this->campos['modal-aviso'] = self::mapMensajeAviso($datosGlobales->modalAviso);
        }

        if ($datosGlobales->cargarSolicitudesValidacionTercero) {
            $this->campos['solicitudes-validacion-tercero'] = self::mapValidacionTercero($datosGlobales->solicitudesValidacionTercero);
        }
    }

    private function mapSolicitudes($solicitudes)
    {
        $map = [];
        if (!empty($solicitudes)) {
            foreach ($solicitudes as $solicitud) {
                $map[] = new SolicitudMapper($solicitud);
            }
        }
        return $map;
    }

    private function mapSolicitudesIncorporarDesvincular($solicitudes)
    {
        $map = [];
        foreach ($solicitudes as $tipoSolicitud => $data) {
            $map[$tipoSolicitud] = [
                'puede-realizar-solicitud' => $data['puede-realizar-solicitud'],
                'mensaje' => $data['mensaje']
            ];
        }
        return $map;
    }

    private function mapMensajesSupervision($mensajesSupervision)
    {
        $supervision = [
            'mensaje-individual' => [],
            'mensaje-grupal' => []
        ];
        if (!empty($mensajesSupervision)) {
            foreach ($mensajesSupervision as $mensaje) {
                if ($mensaje->SV_RUN != 0
                    && $mensaje->SV_DESTINATARIO_MENS == 2
                    && (int)$mensaje->SV_MENOR < 1) {
                    $supervision['mensaje-individual'][$mensaje->SV_RUN][] = [
                        'run' => $mensaje->SV_RUN,
                        'run_menor' => $mensaje->SV_RUN_MENOR,
                        'id-mensaje' => $mensaje->SV_ID_MENSAJE,
                        'desc-mensaje' => $mensaje->SV_DESC_MENSAJE,
                        'mensaje' => $mensaje->SV_MENSAJE,
                        'folio' => $mensaje->SV_FOLIO,
                        'tipo-hallazgo' => $mensaje->SV_TIPO_HALLAZGO_ID,
                        'oficio-informe' => $mensaje->SV_OFICIO_INFORME,
                        'fecha-carga' => $mensaje->SV_FECHA_CARGA,
                        'plazo-dias' => $mensaje->SV_PLAZO_DIAS,
                        'gravedad' => $mensaje->SV_GRAVEDAD,
                        'destinatario' => $mensaje->SV_DESTINATARIO_MENS,
                        'plataforma' => $mensaje->SV_PLATAFORMA_MENS,
                        'flag-notificacion' => $mensaje->SV_FLAG_NOTIFICACION,
                        'tipo-sol-1' => $mensaje->SV_TIPO_SOL_RELEVANTE_1,
                        'tipo-sol-2' => $mensaje->SV_TIPO_SOL_RELEVANTE_2,
                        'tipo-sol-3' => $mensaje->SV_TIPO_SOL_RELEVANTE_3
                    ];
                } else {
                    $supervision['mensaje-grupal'][$mensaje->SV_FOLIO][] = [
                        'run' => $mensaje->SV_RUN,
                        'run_menor' => $mensaje->SV_RUN_MENOR,
                        'id-mensaje' => $mensaje->SV_ID_MENSAJE,
                        'desc-mensaje' => $mensaje->SV_DESC_MENSAJE,
                        'mensaje' => $mensaje->SV_MENSAJE,
                        'folio' => $mensaje->SV_FOLIO,
                        'tipo-hallazgo' => $mensaje->SV_TIPO_HALLAZGO_ID,
                        'oficio-informe' => $mensaje->SV_OFICIO_INFORME,
                        'fecha-carga' => $mensaje->SV_FECHA_CARGA,
                        'plazo-dias' => $mensaje->SV_PLAZO_DIAS,
                        'gravedad' => $mensaje->SV_GRAVEDAD,
                        'destinatario' => $mensaje->SV_DESTINATARIO_MENS,
                        'plataforma' => $mensaje->SV_PLATAFORMA_MENS,
                        'flag-notificacion' => $mensaje->SV_FLAG_NOTIFICACION,
                        'tipo-sol-1' => $mensaje->SV_TIPO_SOL_RELEVANTE_1,
                        'tipo-sol-2' => $mensaje->SV_TIPO_SOL_RELEVANTE_2,
                        'tipo-sol-3' => $mensaje->SV_TIPO_SOL_RELEVANTE_3
                    ];
                }
            }
        }
        return $supervision;
    }

    private function mapMensajeAviso($mensajeAviso)
    {
        $map = [];
        if (!empty($mensajeAviso)){
            foreach ($mensajeAviso as  $data) {
                $map[] = [
                    'id' => $data->ID,
                    'titulo' => $data->TITULO,
                    'mensaje' =>$data->MENSAJE
                ];
            }
        }

        return $map;
    }

    private function mapValidacionTercero($solicitudes)
    {
        $map = [];
        if (!empty($solicitudes)) {
            foreach ($solicitudes as $solicitud) {
                $map[] = new ValidaTerceroMapper($solicitud);
            }
        }
        return $map;
    }
}
