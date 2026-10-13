<?php

namespace App\Constantes;
/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
abstract class ConstanteCampos extends ConstanteCanalOrigen
{
    /**
     * Campo BNFGRUPFAMI_INSTITUCIONALIZADO de BENEFICIARIOS_GRUPOSFAMILIARES y
     * Campo GRUPFAMI_INSTITUCIONALIZADO de GRUPOS_FAMILIARES
     */
    const INSTITUCIONALIZADO_0_NO_INSTITUCIONALIZADO = 0;
    const INSTITUCIONALIZADO_1_INSTITUCIONALIZADO = 1;
    const INSTITUCIONALIZADO_88_INSTITUCIONALIZADO = 88;

    /**
     * Campo DEP_O_INDEP de tablas
     * ASIGNACION.EVER_SUPER_SALUD_COTIZANTES y ASIGNACION.EVER_ACTUALIZACION_ING
     */
    const DEP_O_INDEP_0_OTRO = 0;
    const DEP_O_INDEP_1_DEPENDIENTE = 1;
    const DEP_O_INDEP_2_INDEPENDIENTE = 2;

    /**
     * Aviso de correo
     * Campo AVISO_CORREO de tabla ASIGNACION.AB_SOLICITUD
     */
    const AVISO_CORREO_0 = 0;
    const AVISO_CORREO_1 = 1; // Correo enviado
    const AVISO_CORREO_2 = 2; // DEJA MARCA QUE SE ENVIÓ CORREO
    const AVISO_CORREO_3 = 3; // DEJA MARCA QUE SE ENVIÓ CORREO

    /**
     * Tipos de Visita no efectiva que finalizan la orden de inmediato
     * Campo ID_PREGUNTA de tabla ASIGNACION.VISITA_SUPERVISOR
     */
    const VISITA_130_HOGAR_VISITADO_NO_VIVE_EN_DOMICILIO = 130;
    const VISITA_131_DOMICILIO_VIVIENDA_INEXISTENTE = 131;
    const VISITA_132_ENTREVISTA_FRUSTRADA = 132;

    /**
     * Tipos de Visita no efectiva que finalizan la orden de inmediato
     * Campo ESTADO de ASIGNACION.ORDEN_TRABAJO_SUPERVISOR
     */
    const ORDEN_TRABAJO_1_ACTIVA = 1;
    const ORDEN_TRABAJO_2_FINALIZADA = 2;

    /**
     * Campo DOC_ELIMINADO de tabla ASIGNACION.AB_SOLICITUD_ADJUNTOS
     */
    const SOLICITUD_ADJUNTOS_ELIMINADO_0_DOCUMENTO_NO_ELIMINADO = 0;
    const SOLICITUD_ADJUNTOS_ELIMINADO_1_DOCUMENTO_ELIMINADO = 1;

    /**
     * Campo ID_ESTADO_MOVER de tabla ASIGNACION.AB_SOLICITUD_ADJUNTOS
     */
    const SOLICITUD_ADJUNTOS_ESTADO_MOVER_1 = 1;

}
