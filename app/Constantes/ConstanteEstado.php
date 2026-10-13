<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
abstract class ConstanteEstado extends ConstanteExcepcion
{
    /**
     * Tabla ASIGNACION.AB_ESTADO_USUARIO
     * Ocupado en Campo ESTADO de tabla AB_SOLICITUD_DOCUMENTOS, AB_PARENTESCO, AB_PARENTESCO_SEXO, AB_PUEBLO_INDIGENA, AB_RESPUESTA_PREGUNTA, AB_RESPUESTA
     * Campo ID_ESTADO de AB_USUARIO
     */
    const ESTADO_0_INACTIVO = 0; // fila no activa
    const ESTADO_1_ACTIVO = 1;

    /**
     * Tabla ORDEN_TRABAJO_SUPERVISOR
     * Campo Estado se ocupa para las ordenes de trabajo asignadas a supervisores y mantener un control de las mismas
     * Las ordenes de trabajo se vinculan con los grupos familiares basados en folio
     */
    const ESTADO_ORDENT_1_ACTIVA = 1;
    const ESTADO_ORDENT_2_FINALIZADA = 2;

    /**
     * Tabla AB_ESTADO_SOLICITUD
     * Ocupado en campo ID_ESTADO de tabla AB_SOLICITUD
     */
    const ESTADO_SOLICITUD_FICTICIO_0 = 0; // ocupado en FormularioDomicilioViviendaController.php y ConfirmarController.php
    const ESTADO_SOLICITUD_FICTICIO_CREACION_SOLICITUD = -1;    // estado que no pertenece a tabla AB_ESTADO_SOLICITUD
    const ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS = 1; // también llamado 'PERDIENTE' o 'PENDIENTE POR ASIGNAR'
    const ESTADO_SOLICITUD_2_AGENDADO = 2;
    const ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL = 3;          // en FamiliaController@ingresar es llamado 'DIGITACION'
    const ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL = 4; // Ahora llamado 'Pendiente por Resolver Ejecutor' // también llamado 'POR APROBAR EC' o 'PARA APROBACIÓN EC'
    const ESTADO_SOLICITUD_5_APROBADO = 5;                      // ESTADO FINALIZADO
    const ESTADO_SOLICITUD_6_EN_ESPERA_CIUDADANO = 6;
    const ESTADO_SOLICITUD_7_RECHAZADA = 7;                     // ESTADO FINALIZADO // también llamado 'ANULA SOLICITUD' en HojaRutaController.php
    const ESTADO_SOLICITUD_8_POR_APROBAR_SIT = 8;
    const ESTADO_SOLICITUD_9_DESISTIDA = 9;                     // ESTADO FINALIZADO
    const ESTADO_SOLICITUD_10_EN_NIVEL_CENTRAL = 10;
    const ESTADO_SOLICITUD_11_EN_DIGITACION = 11;               // también llamado 'BORRADOR' en algunas clases // en RshGestionarController llamado DERIVADO_A_LA_FUENTE
    const ESTADO_SOLICITUD_12_POR_APROBAR_UGIT = 12;            // Unidad de Gestión de Información Territorial
    const ESTADO_SOLICITUD_14_INACTIVO_POR_DEFUNCION = 14;
    const ESTADO_SOLICITUD_15_EN_RUTA = 15;
    const ESTADO_SOLICITUD_16_SUPERVISION = 16;
    const ESTADO_SOLICITUD_17_CONFIRMADO = 17;
    const ESTADO_SOLICITUD_18_EN_ESPERA_APROBACION_CIUDADANO = 18;
    const ESTADO_SOLICITUD_19_BORRADOR_PENDIENTE_FIRMA_DIGITAL = 19;

    public static $ESTADO_SOLICITUD_5_7_9_FINALIZADOS = [
        self::ESTADO_SOLICITUD_5_APROBADO,
        self::ESTADO_SOLICITUD_7_RECHAZADA,
        self::ESTADO_SOLICITUD_9_DESISTIDA
    ];

    public static $ESTADO_SOLICITUD_7_9_RECHAZADOS_DESISTIDOS = [
        self::ESTADO_SOLICITUD_7_RECHAZADA,
        self::ESTADO_SOLICITUD_9_DESISTIDA
    ];

    public static $ESTODOS_DE_SOLICITUD_TRAMITE = [
        self::ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS,
        self::ESTADO_SOLICITUD_2_AGENDADO,
        self::ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL,
        self::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL,
        self::ESTADO_SOLICITUD_5_APROBADO,
        self::ESTADO_SOLICITUD_7_RECHAZADA,
        self::ESTADO_SOLICITUD_9_DESISTIDA,
        self::ESTADO_SOLICITUD_11_EN_DIGITACION
    ];
    /**
     * Todos menos los finalizados.
     * DEPRECATED, Se debería usar:
     *      ->whereNotIn('id_estado', Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS)
     *      !in_array($s['id_estado'], Constante::$ESTADO_SOLICITUD_5_7_9_FINALIZADOS))
     */
    public static $ESTADO_SOLICITUD_NO_FINALIZADOS = [
        self::ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS,
        self::ESTADO_SOLICITUD_2_AGENDADO,
        self::ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL,
        self::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL,
        self::ESTADO_SOLICITUD_6_EN_ESPERA_CIUDADANO,
        self::ESTADO_SOLICITUD_8_POR_APROBAR_SIT,
        self::ESTADO_SOLICITUD_10_EN_NIVEL_CENTRAL,
        self::ESTADO_SOLICITUD_11_EN_DIGITACION,
        self::ESTADO_SOLICITUD_12_POR_APROBAR_UGIT,
        self::ESTADO_SOLICITUD_14_INACTIVO_POR_DEFUNCION,
        self::ESTADO_SOLICITUD_15_EN_RUTA,
        self::ESTADO_SOLICITUD_16_SUPERVISION,
        self::ESTADO_SOLICITUD_18_EN_ESPERA_APROBACION_CIUDADANO,
        self::ESTADO_SOLICITUD_19_BORRADOR_PENDIENTE_FIRMA_DIGITAL
    ];

    /**
     * id_estado < Constante::ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL
     * id_estado == Constante::ESTADO_SOLICITUD_15_EN_RUTA
     * id_estado == Constante::ESTADO_SOLICITUD_11_EN_DIGITACION
     */
    public static $ESTADO_SOLICITUD_POR_AGENDAR_1_2_3_11_Y_15 = [
        self::ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS,
        self::ESTADO_SOLICITUD_2_AGENDADO,
        self::ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL,
        self::ESTADO_SOLICITUD_11_EN_DIGITACION,
        self::ESTADO_SOLICITUD_15_EN_RUTA,
    ];

    /**
     * ACCIONES QUE SE ENVÍAN A LA API, EN LA VARIABLES ACCION. Y QUE DERIVA LOS TRAMITES A ESTADOS_SOLICITUD, VIGENTE O SUB_ESTADOS_SOLICITUD ESPECÏFICOS
     */
    const ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3 = 'borrador';                          // llevar a ESTADO_SOLICITUD_11_EN_DIGITACION, VIGENTE_3_SOLICITUD_BORRADOR
    const ACCION_AGENDAR_VA_ESTADO_11_SUBESTADO_14 = 'agendar';                         // llevar a ESTADO_SOLICITUD_11_EN_DIGITACION, SUB_ESTADO_SOLICITUD_14_POR_AGENDAR
    const ACCION_AGENDAR_ETAPA1_VA_ESTADO_11_SUBESTADO_14 = 'etapa1';                   // llevar a ESTADO_SOLICITUD_11_EN_DIGITACION, SUB_ESTADO_SOLICITUD_14_POR_AGENDAR, Lo mismo que la acción AGENDAR pero valida ciertas entradas. Pertenece al paso1 del trámite 1 del municipal antiguo
    const ACCION_DOCUMENTOS_VA_ESTADO_1 = 'documentos';                                 // llevar a ESTADO_SOLICITUD_1_PENDIENTE_ENTREGAR_DOCUMENTOS
    const ACCION_AGENDADO_VA_ESTADO_2 = 'agendado';                                     // llevar a ESTADO_SOLICITUD_2_AGENDADO
    const ACCION_GESTION_VA_ESTADO_3 = 'gestion';                                       // llevar a ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL
    const ACCION_GESTION_DOCUMENTOS_COMPLETOS_VA_ESTADO_3 = 'documentossupervision';    // llevar a ESTADO_SOLICITUD_3_EN_GESTION_MUNICIPAL, esto solo para marcar solicitud como documentos completos desde perfil supervisor
    const ACCION_RESOLVER_VA_ESTADO_4 = 'resolver';                                     // llevar a ESTADO_SOLICITUD_4_PENDIENTE_RESOLVER_ENCARGADO_COMUNAL
    const ACCION_APROBAR_VA_ESTADO_5 = 'aprobar';                                       // llevar a ESTADO_SOLICITUD_5_APROBADO
    const ACCION_RECHAZAR_VA_ESTADO_7 = 'rechazar';                                     // llevar a ESTADO_SOLICITUD_7_RECHAZADA
    const ACCION_DESISTIR_VA_ESTADO_9 = 'desistir';                                     // llevar a ESTADO_SOLICITUD_9_DESISTIDA
    const ACCION_DESISTIR_SUPERVISION_VA_ESTADO_9 = 'desistirsupervision';              // llevar a ESTADO_SOLICITUD_9_DESISTIDA Y VIGENTE 2
    const ACCION_CONFIRMAR_VA_ESTADO_17 = 'confirmar';                                  // llevar a ESTADO_SOLICITUD_17_CONFIRMADO
    const ACCION_BORRADOR_PENDIENTE_FIRMA_DIGITAL_19 = 'pendientefirmadigital';         // llevar a ESTADO_SOLICITUD_19_PENDIENTE_FIRMA_DIGITAL, VIGENTE_3_SOLICITUD_BORRADOR

    public static $ACCION_VALIDA_PARA_INPUT = [
        self::ACCION_BORRADOR_VA_ESTADO_11_VIGENTE_3,
        self::ACCION_AGENDAR_VA_ESTADO_11_SUBESTADO_14,
        self::ACCION_AGENDAR_ETAPA1_VA_ESTADO_11_SUBESTADO_14,
        self::ACCION_DOCUMENTOS_VA_ESTADO_1,
        self::ACCION_AGENDADO_VA_ESTADO_2,
        self::ACCION_GESTION_VA_ESTADO_3,
        self::ACCION_GESTION_DOCUMENTOS_COMPLETOS_VA_ESTADO_3,
        self::ACCION_RESOLVER_VA_ESTADO_4,
        self::ACCION_APROBAR_VA_ESTADO_5,
        self::ACCION_RECHAZAR_VA_ESTADO_7,
        self::ACCION_DESISTIR_VA_ESTADO_9,
        self::ACCION_DESISTIR_SUPERVISION_VA_ESTADO_9,
        self::ACCION_CONFIRMAR_VA_ESTADO_17,
        self::ACCION_BORRADOR_PENDIENTE_FIRMA_DIGITAL_19
    ];

    /**
     * STRING QUE SE ENVÏAN A LA API, EN LA VARIABLES BORRADOR
     */
    const BORRADOR_NULL = null;
    const BORRADOR_BORRADOR = 'borrador';

    /**
     * Mesajería que se muestra al Plataforma Ciudadano de acuerdo a su ESTADO_SOLICITUD
     */
    const MENSAJE_BORRADOR = 'Borrador';      // $idEstado == Constante::ESTADO_SOLICITUD_11_EN_DIGITACION && $idSubEstado != Constante::ESTADO_SOLICITUD_14_INACTIVO_POR_DEFUNCION
    const MENSAJE_APROBADO = 'Aprobado';      // $idEstado == Constante::ESTADO_SOLICITUD_5_APROBADO
    const MENSAJE_RECHAZADA = 'Rechazada';    // $idEstado == Constante::ESTADO_SOLICITUD_7_RECHAZADA
    const MENSAJE_DESISTIDA = 'Desistida';    // $idEstado == Constante::ESTADO_SOLICITUD_9_DESISTIDA
    const MENSAJE_CONFIRMADO = 'Confirmado';  // $idEstado == Constante::ESTADO_SOLICITUD_17_CONFIRMADO
    const MENSAJE_EN_REVISION = 'En trámite'; // mensaje por defecto, en otro caso
    const MENSAJE_PENDIENTE_COMPLETAR = 'Pendiente Completar'; // mensaje por defecto, estado 18 y 11 solicitud supervision
    const MENSAJE_PENDIENTE_FIRMA_DIGITAL = 'Pendiente por Validar';  // $idEstado == Constante::ESTADO_SOLICITUD_19_PENDIENTE_FIRMA_DIGITAL

    const ACCION_VISTA_GUARDAR_CONTINUAR_TARDE = 1;
    const ACCION_VISTA_CONTINUAR = 2;
    const ACCION_VISTA_VOLVER = 3;

    /**
     * Tabla AB_SUB_ESTADO_SOLICITUD
     * Ocupado en campo ID_SUB_ESTADO de tabla AB_SOLICITUD
     */
    const SUB_ESTADO_SOLICITUD_1_ERROR_DE_SOLICITUD = 1;
    const SUB_ESTADO_SOLICITUD_2_ESPERANDO_TRAMO = 2;
    const SUB_ESTADO_SOLICITUD_3_CALLE_PENDIENTE = 3;
    const SUB_ESTADO_SOLICITUD_4_CUMPLE_MAXIMO_NUMERO_DE_VISITAS = 4;
    const SUB_ESTADO_SOLICITUD_5_ESPERANDO_RESPUESTA_MDS = 5;
    const SUB_ESTADO_SOLICITUD_6_EN_ESPERA_DEL_CIUDADANO = 6;
    const SUB_ESTADO_SOLICITUD_7_DESDE_NIVEL_CENTRAL = 7;
    const SUB_ESTADO_SOLICITUD_9_IDENTIDAD_ACREDITADA = 9;
    const SUB_ESTADO_SOLICITUD_10_AGRUPACION_HABITACIONAL = 10;
    const SUB_ESTADO_SOLICITUD_11_CIUDAD_O_LOCALIDAD = 11;
    const SUB_ESTADO_SOLICITUD_12_CALLE_VIA_CAMINO = 12;
    const SUB_ESTADO_SOLICITUD_13_OBSERVADAS_POR_NIVEL_CENTRAL = 13;
    const SUB_ESTADO_SOLICITUD_14_POR_AGENDAR = 14;                  // subestado 14 es cuando se terminó de digitar y ya está listo para la acción agendar

    /**
     * Tabla AB_ESTADO_GRUPO_FAMILIAR campo ID
     * Ocupado en campo GRUPFAMI_ESTADO de tabla GRUPOS_FAMILIARES
     */
    const ESTADO_GRUPO_FAMILIAR_1_SOLICITADO = 1;
    const ESTADO_GRUPO_FAMILIAR_2_DIGITACION = 2;
    const ESTADO_GRUPO_FAMILIAR_3_ESPERANDO_PUNTAJE = 3;
    const ESTADO_GRUPO_FAMILIAR_4_SUSPENDIDO = 4; // en FamiliaController ($accion == 2) ocupa 4_EN_REVISION
    const ESTADO_GRUPO_FAMILIAR_5_PROVISORIO = 5;
    const ESTADO_GRUPO_FAMILIAR_6_ANULADO = 6;
    const ESTADO_GRUPO_FAMILIAR_7_APROBADO = 7;   // esperando puntaje
    const ESTADO_GRUPO_FAMILIAR_14_INACTIVO_POR_DEFUNCION = 14;

    public static $ESTADO_GRUPO_FAMILIAR_3_5_7 = [
        self::ESTADO_GRUPO_FAMILIAR_3_ESPERANDO_PUNTAJE,
        self::ESTADO_GRUPO_FAMILIAR_5_PROVISORIO,
        self::ESTADO_GRUPO_FAMILIAR_7_APROBADO
    ];

    /**
     * Tabla AB_ESTADO_NC (ESTADO NIVEL CENTRAL)
     * Ocupado en campo ID_ESTADO_NC de tabla AB_SOLICITUD
     *   DF = División Focalización
     *   DIS = Dirección de Información Social
     *   DPS = División Política Social
     */
    const ESTADO_NC_1_EN_REVISION_DF = 1;
    const ESTADO_NC_2_ANALIZANDO_PERTINENCIA_DF = 2;
    const ESTADO_NC_3_ADMISIBLE_DF = 3;
    const ESTADO_NC_4_INADMISIBLE_DF = 4;
    const ESTADO_NC_5_EN_REVISION_DIS = 5;
    const ESTADO_NC_6_ANALIZANDO_PERTINENCIA_DIS = 6;
    const ESTADO_NC_7_ADMISIBLE_DIS = 7;
    const ESTADO_NC_8_INADMISIBLE_DIS = 8;
    const ESTADO_NC_9_EN_REVISION_DPS = 9;
    const ESTADO_NC_10_ANALIZANDO_PERTINENCIA_DPS = 10;
    const ESTADO_NC_11_ADMISIBLE_DPS = 11;
    const ESTADO_NC_12_INADMISIBLE_DPS = 12;
    const ESTADO_NC_13_POR_NIVEL_CENTRAL = 13;
    const ESTADO_NC_14_OFICIAR_A_LA_FUENTE = 14;

    /**
     * Campo ID_ESTADO_SUPERVISION de tabla AB_ESTADO_SUPERVISION
     * Ocupado en campo ESTADO_SUP de tabla AB_SUPERVISION
     */
    const ESTADO_SUPERVISION_1_SUPERVISION = 1;
    const ESTADO_SUPERVISION_2_ADMISIBLE = 2;
    const ESTADO_SUPERVISION_3_INADMISIBLE_FALTAN_ANTECEDENTES = 3;
    const ESTADO_SUPERVISION_4_INADMISIBLE_SIN_COMPETENCIAS = 4;
    const ESTADO_SUPERVISION_5_INADMISIBLE_SOLICITUD_NO_PERTINENTE = 5;
    const ESTADO_SUPERVISION_6_CERRADO_ADMISIBLE = 6;

    /**
     * Tabla ORDEN_TRABAJO_SUPERVISOR
     * Campo Codigo_resultado se ocupa para las ordenes de trabajo asignadas a supervisores, este estado indica que sucedio con la orden
     * una vez fue cerrada o finalizada
     */
    const CODIGO_RESULTADO_ORDENT_1_VIVIENDA_INEXSITENTE = 1;
    const CODIGO_RESULTADO_ORDENT_2_DOMICILIO_INEXISTENTE = 2;
    const CODIGO_RESULTADO_ORDENT_3_VERIFICACION_DE_RESICENDIA = 3;
    const CODIGO_RESULTADO_ORDENT_4_SIN_MORADORES = 4;
    const CODIGO_RESULTADO_ORDENT_5_ENTREVISTA_FRUSTRADA = 5;
    const CODIGO_RESULTADO_ORDENT_6_ENTREVISTA_EFECTIVA_CON_AUTOTIZACION_DOC_COMPLETOS = 6;
    const CODIGO_RESULTADO_ORDENT_7_ENTREVISTA_EFECTIVA_CON_AUTOTIZACION_DOC_PENDIENTE = 7;
    const CODIGO_RESULTADO_ORDENT_8_ENTREVISTA_EFECTIVA_SIN_AUTOTIZACION = 8;


    /**
     * Tabla AB_ESTADO_GRUPO_FAMILIAR
     * Estado de Folios del grupo Familiar
     */
    const ESTADO_FOLIO_SOLICITADO = 'Solicitado';
    const ESTADO_FOLIO_DIGITACION = 'Digitacion';
    const ESTADO_FOLIO_ESPERANDO_PUNTAJE = 'Esperando Puntaje';
    const ESTADO_FOLIO_PROVISORIO = 'Provisorio';
    const ESTADO_FOLIO_SUSPENDIDO = 'Suspendido';
    const ESTADO_FOLIO_ANULADO = 'Anulado';
    const ESTADO_FOLIO_APROBADO = 'Aprobado';
    const ESTADO_FOLIO_INACTIVO_X_DEFUNCION = 'Inactivo por Defuncion';
    const ESTADO_FOLIO_SIN_INFORMACION = 'Sin Informacion';

    /**
     * Tabla nomina_aprobacion
     * Estado de nomina aprobacion automatica
     */
    const ESTADO_NOMINA_APROBACION_1_GESTIONABLE = 1;
    const ESTADO_NOMINA_APROBACION_5_APROBADA = 5;
    const ESTADO_NOMINA_APROBACION_7_FALLA_VALIDACION_O_SERVICIO = 7;
    const ESTADO_NOMINA_APROBACION_15_YA_APROBADA = 15;
    const ESTADO_NOMINA_APROBACION_17_YA_RECHAZADA = 17;
    const ESTADO_NOMINA_APROBACION_19_YA_DESISTIDA = 19;


    /**
     * Tabla valida_solicitud_tercero
     * Estado de validaciones
     */
    const VALIDACION_TERCERO_ESTADO_0_PENDIENTE = 0;
    const VALIDACION_TERCERO_ESTADO_1_APROBADO = 1;
    const VALIDACION_TERCERO_ESTADO_2_RECHAZADO = 2;
    const VALIDACION_TERCERO_ESTADO_3_DESISTIDO = 3;

    public static $VALIDACION_TERCERO_ESTADO_1_2_3_FINALIZADOS = [
        self::VALIDACION_TERCERO_ESTADO_1_APROBADO,
        self::VALIDACION_TERCERO_ESTADO_2_RECHAZADO,
        self::VALIDACION_TERCERO_ESTADO_3_DESISTIDO
    ];

    public static $VALIDACION_TERCERO_ESTADO_2_3_RECHAZADOS_DESISTIDOS = [
        self::VALIDACION_TERCERO_ESTADO_2_RECHAZADO,
        self::VALIDACION_TERCERO_ESTADO_3_DESISTIDO
    ];

    public static $VALIDACION_TERCERO_ESTADO_NO_FINALIZADOS = [
        self::VALIDACION_TERCERO_ESTADO_0_PENDIENTE
    ];
}
