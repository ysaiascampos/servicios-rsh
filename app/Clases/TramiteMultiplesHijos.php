<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Models\BeneficiarioGrupoFamiliar;
use App\Models\GrupoFamiliar;
use App\Models\Personas;
use App\Models\PuebloIndigena;
use App\Utiles;

abstract class TramiteMultiplesHijos extends Tramite
{
    public $beneficiariosSolicitudActual;
    public $beneficiariosVigente;
    public $folio;

    protected $flujo = [
        Constante::ESTADO_SOLICITUD_FICTICIO_CREACION_SOLICITUD => [
            Constante::ACCION_DOCUMENTOS_VA_ESTADO_1 => [
                self::METODO => 'pendienteDocumentos',
                self::ESTADO => Constante::ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS,
                self::CONDICION_CANAL => [
                    Constante::CANAL_1_MUNICIPAL,
                    Constante::CANAL_4_CHILE_ATIENDE_EJECUTOR
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL
                ]
            ],
            Constante::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3 => [
                self::METODO => 'enDigitacion',
                self::ESTADO => Constante::ESTADO_SOLICITUD_11_EN_DIGITACION,
                self::CONDICION_CANAL => [
                    Constante::CANAL_2_CLAVE_UNICA,
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA
                ]
            ]
        ],
        Constante::ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL => [
            Constante::ACCION_GESTION_VA_ESTADO_3 => [
                self::METODO => 'editarSolicitud',
                self::ESTADO => Constante::ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL,
                self::CONDICION_CANAL => [
                    Constante::CANAL_1_MUNICIPAL,
                    Constante::CANAL_4_CHILE_ATIENDE_EJECUTOR,
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL
                ]
            ],
            Constante::ACCION_RESOLVER_VA_ESTADO_4 => [
                self::METODO => 'pendienteResolver',
                self::ESTADO => Constante::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL,
                self::CONDICION_CANAL => [
                    Constante::CANAL_1_MUNICIPAL,
                    Constante::CANAL_4_CHILE_ATIENDE_EJECUTOR,
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL
                ]
            ],
            Constante::ACCION_RECHAZAR_VA_ESTADO_7 => [
                self::METODO => 'rechazar',
                self::ESTADO => Constante::ESTADO_SOLICITUD_7_RECHAZADA,
                self::CONDICION_CANAL => [
                    Constante::CANAL_1_MUNICIPAL,
                    Constante::CANAL_4_CHILE_ATIENDE_EJECUTOR,
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL
                ]
            ],
        ],
        Constante::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL => [
            Constante::ACCION_GESTION_VA_ESTADO_3 => [
                self::METODO => 'devolverGestion',
                self::ESTADO => Constante::ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL,
                self::CONDICION_CANAL => [
                    Constante::CANAL_1_MUNICIPAL,
                    Constante::CANAL_4_CHILE_ATIENDE_EJECUTOR,
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL
                ]
            ],
            Constante::ACCION_RESOLVER_VA_ESTADO_4 => [
                self::METODO => 'editarSolicitud',
                self::ESTADO => Constante::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL,
                self::CONDICION_CANAL => [
                    Constante::CANAL_1_MUNICIPAL,
                    Constante::CANAL_4_CHILE_ATIENDE_EJECUTOR,
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL
                ]
            ],
            Constante::ACCION_APROBAR_VA_ESTADO_5 => [
                self::METODO => 'aprobar',
                self::ESTADO => Constante::ESTADO_SOLICITUD_5_APROBADO,
                self::CONDICION_CANAL => [
                    Constante::CANAL_1_MUNICIPAL,
                    Constante::CANAL_4_CHILE_ATIENDE_EJECUTOR,
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL
                ]
            ],
            Constante::ACCION_RECHAZAR_VA_ESTADO_7 => [
                self::METODO => 'rechazar',
                self::ESTADO => Constante::ESTADO_SOLICITUD_7_RECHAZADA,
                self::CONDICION_CANAL => [
                    Constante::CANAL_1_MUNICIPAL,
                    Constante::CANAL_4_CHILE_ATIENDE_EJECUTOR,
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL
                ]
            ],
        ],
        Constante::ESTADO_SOLICITUD_9_DESISTIDA => [
            Constante::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3 => [
                self::METODO => 'enDigitacion',
                self::ESTADO => Constante::ESTADO_SOLICITUD_11_EN_DIGITACION,
                self::CONDICION_CANAL => [
                    Constante::CANAL_2_CLAVE_UNICA
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA
                ]
            ]
        ],
        Constante::ESTADO_SOLICITUD_11_EN_DIGITACION => [
            Constante::ACCION_RESOLVER_VA_ESTADO_4 => [
                self::METODO => 'pendienteResolver',
                self::ESTADO => Constante::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL,
                self::CONDICION_CANAL => [
                    Constante::CANAL_2_CLAVE_UNICA
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA
                ]
            ],
            Constante::ACCION_RECHAZAR_VA_ESTADO_7 => [
                self::METODO => 'rechazar',
                self::ESTADO => Constante::ESTADO_SOLICITUD_9_DESISTIDA,
                self::CONDICION_CANAL => [
                    Constante::CANAL_2_CLAVE_UNICA
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA
                ]
            ],
            Constante::ACCION_DESISTIR_VA_ESTADO_9 => [
                self::METODO => 'desistir',
                self::ESTADO => Constante::ESTADO_SOLICITUD_9_DESISTIDA,
                self::CONDICION_CANAL => [
                    Constante::CANAL_2_CLAVE_UNICA
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA
                ]
            ],
            Constante::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3 => [
                self::METODO => 'enDigitacion',
                self::ESTADO => Constante::ESTADO_SOLICITUD_11_EN_DIGITACION,
                self::CONDICION_CANAL => [
                    Constante::CANAL_2_CLAVE_UNICA
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA
                ]
            ],
        ],
        Constante::ESTADO_SOLICITUD_19_BORRADOR_PENDIENTE_FIRMA_DIGITAL => [
            Constante::ACCION_APROBAR_VA_ESTADO_5 => [
                self::METODO => 'aprobar',
                self::ESTADO => Constante::ESTADO_SOLICITUD_5_APROBADO,
                self::CONDICION_CANAL => [
                    Constante::CANAL_2_CLAVE_UNICA
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA
                ]
            ],
            Constante::ACCION_DESISTIR_VA_ESTADO_9 => [
                self::METODO => 'desistir',
                self::ESTADO => Constante::ESTADO_SOLICITUD_9_DESISTIDA,
                self::CONDICION_CANAL => [
                    Constante::CANAL_2_CLAVE_UNICA
                ],
                self::CONDICION_ORIGEN_REGISTRO => [
                    Constante::ORIGEN_REGISTRO_2_PLATAFORMA_CIUDADANA
                ]
            ],
        ]
    ];

    public function cargarDataGrupoFamiliarVigente()
    {
        $this->folio = $this->solicitud->DATO_ACTUAL;
        $this->beneficiariosVigente = BeneficiarioGrupoFamiliar::getBeneficiariosGrupoFamiliarActivo($this->folio);
        foreach ($this->beneficiariosVigente as $integrante) {
            $per_id = $integrante->PER_ID;
            $querypuebloOriginario = PuebloIndigena::getPuebloIndigenaByIdPersona($per_id);

            $integrante->puebloOriginario = !empty($querypuebloOriginario) ? substr($querypuebloOriginario->NOMBRE, 3) :
                null;
            $integrante->nacionalidad = isset($integrante->PER_NACIONALIDAD) ?
                ($integrante->PER_NACIONALIDAD == Constante::NACIONALIDAD_1_CHILENA ? "Chilena" : "Extranjera") :
                null;
        }

        $this->grupoFamiliarVigente = GrupoFamiliar::findByNuevoFolio($this->folio);
        if (!$this->grupoFamiliarVigente) {
            $this->grupoFamiliarVigente = GrupoFamiliar::findByNuevoFolio($this->folio,
                ['estado' => Constante::ESTADO_GRUPO_FAMILIAR_5_PROVISORIO]);
        }
    }

    protected function obtenerPersonasAsociadas()
    {
        foreach ($this->beneficiariosSolicitudActual as $beneficiario) {
            if (Utiles::getEdad($beneficiario->PER_FECHANACIMIENTO) > Constante::EDAD_18_MAYOR_DE_EDAD &&
                $this->solicitante->PER_RUN != $beneficiario->PER_RUN) {
                $this->agregarFirmante($beneficiario);
            }
            $this->agregarPersonasAsociadas($beneficiario);
        }
    }

    protected function consultarTitular()
    {
        $this->titular = Personas::find($this->solicitud->DATO_NUEVO);
    }
}
