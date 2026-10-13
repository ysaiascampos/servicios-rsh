<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
abstract class ConstanteError extends ConstanteEstado
{
    /**
     * MENSAJES DE ERROR
     */
    const MENSAJE_ERROR_CONSUMO_SERVICIO_REST = 'Error al consumir servicio rest. Verifique que la información proporcionada al servicio este vigente en la plataforma.';
    const MENSAJE_ERROR_NO_GRUPO_FAMILIAR_VIGENTE = 'El ciudadano no posee registro vigente de su grupo familiar en la plataforma'; // DatosBeneficiarios.php
    const MENSAJE_ERROR_NO_GRUPO_FAMILIAR_VIGENTE2 = 'El ciudadano no posee un grupo familiar activo en la plataforma'; // DatosDomicilioVivienda.php
    const MENSAJE_ERROR_NO_DATO_CONTACTO = 'Ciudadano no posee registros en el Registro Civil'; // 'Ciudadano no posee registros en la plataforma';
    const MENSAJE_ERROR_FALLA_REGISTRO_CIVIL = 'No es posible consultar la información para el ciudadano, debido a un error desde el SRCeI. Contactar con la Mesa de Ayuda.'; // 'Error en registro civil';
    const MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA = 'La solicitud no se encuentra vigente o no esta disponible'; // DatosDeSolicitud.php
    const MENSAJE_ERROR_SOLICITUD_NO_ENCONTRADA2 = 'Solicitud no encontrada'; // DatosTipoDeSolicitud.php
    const MENSAJE_ERROR_YA_EXISTE_SOLICITUD_TIPO_1 = 'El ciudadano ya tiene una solicitud de cambio de domicilio y vivienda en trámite';
    const MENSAJE_ERROR_YA_EXISTE_SOLICITUD_MISMO_TIPO = 'Ya existe una solicitud en trámite.';
    const MENSAJE_ERROR_SOLICITUD_SIN_VISTA = 'No existe vista para este tipo de solicitud';
    const MENSAJE_RUN_NO_CORRESPONDE_AL_DE_SOLICITUD = 'RUN no corresponde al de la solicitud';
    const MENSAJE_ERROR_MSG_NO_DATOS_SOLICITUD = 'No se encuentran datos asociados a esta solicitud';
    const MENSAJE_ERROR_MSG_NO_DATOS_GRUPO_FAMILIAR = 'No se encuentran datos asociados a este grupo familiar';
    const MENSAJE_ERROR_MSG_NO_DATOS_CONTACTO = 'El ciudadano no registra informacion en la plataforma.';
    const MENSAJE_ERROR_FALLA_DATOS_CONTACTO = 'Error al guardar la información de contacto';
    const MENSAJE_FOLIO_SIN_ORDEN_TRABAJO = 'Error no existe Orden de Trabajo para este Folio';
    const MENSAJE_ERROR_GUARDADO_DATOS_SOLICITUD = 'Error no se logro guardar parte de la solicitud (%parte%)';
    const MENSAJE_ERROR_MENOR_GRUPO_FAMILIAR_VIGENTE = 'Si se realiza esta solicitud quedarán menores sin RSH';
    const MENSAJE_CANAL_NO_EXISTE = "El canal proporcionado no existe en la base de datos.";



    /**
     * MENSAJES GENERALES
     */
    const MENSAJE_SOLICITUD_SUCCESS = "La solicitud fue editada";
    const MENSAJE_SOLICITUD_CREATED = "La solicitud fue creada";
    const MENSAJE_DOCUMENTO_AUTOFIRMADO = 'No se requiere adjuntar este documento puesto que el acceso con clave única equivale a firma electrónica';

    /**
     * Mensaje de uso de datos en cartolas
     * --------------------------------------------------------------------------
     * Mensaje que indica la fecha de los datos utilizados para calcular el tramo
     */
    const FechaDatosCartola = 'Esta cartola ha sido construida con información actualizada y aprobada por el municipio hasta el día 21 de octubre de 2016 a las 12:30 horas. Los cambios de información aprobados con posterioridad a esa fecha se verán reflejados en tu cartola el primer día hábil de diciembre.';

    /**
     * Antiguos
     */
    const mensajeAlertaCiudadano = 'Su grupo familiar se encuentra en estado En Revisión. Debe actualizar la información de su registro.';
    const mesajeCiudadanoIngresaClaveRun = 'Recuerde que al acceder con Clave RUN; debe acreditar su identidad en la municipalidad correspondiente a su domicilio para continuar con la gestión de su solicitud. Solo el ingreso con ClaveÚnica del Registro Civil; permite finalizar su trámite en línea.';

    /**
     * CONSTANTES DE ERROR DE SOAP
     */
    const MENSAJE_ERROR_SOAP_COMUNICACION_RUNIFICADOR = 'Error de comunicación con Registro Civil (runificador)';
    const MENSAJE_SOAP_RUNIFICADOR_RUN_NO_VALIDO = 'El RUN ingresado no es valido';

    /**
     * Codigos COMUNICACION con SERVICIOS o URLs
     */
    const COMUNICACION_OK = 1;     //  NO ERROR
    const COMUNICACION_ERROR = -1;
    const COMUNICACION_OK_TEXTO = 'OK';
    const COMUNICACION_ERROR_TEXTO = 'NO OK';


    /**
     * Mensajes Generales sobre solicitudes-tramitables
     */
    const MENSAJE_NO_CUMPLE_REGLA_MUNICIPAL = 'Ciudadano no cuenta con registros administrativos que permitan realizar esta solicitud.';
    const MENSAJE_NO_CUMPLE_REGLA_CIUDADANO = 'No cuenta con datos complementarios que influyan en la Calificación Socioecónomica y que permitan realizar este tramite.';

    const MENSAJE_SOLICITUD_JURIDICA_PROPIA_EN_TRAMITE_ACTUALIZACION = 'Se mantiene en trámite una solicitud de actualización de participación en esta sociedad o empresa.';
    const MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_PROPIA_EN_TRAMITE_ACTUALIZACION = 'Se mantiene en trámite una solicitud de actualización de bien raíz que es patrimonio de esta empresa o sociedad.';
    const MENSAJE_SOLICITUD_JURIDICA_TERRESTRE_PROPIA_EN_TRAMITE_ACTUALIZACION = 'Se mantiene en trámite una solicitud de actualización de vehículo terrestre que es patrimonio de esta empresa o sociedad';
    const MENSAJE_SOLICITUD_JURIDICA_MARINA_PROPIA_EN_TRAMITE_ACTUALIZACION = 'Se mantiene en trámite una solicitud de actualización de vehículo marítimo que es patrimonio de esta empresa o sociedad';

    const MENSAJE_SOLICITUD_JURIDICA_SOCIEDAD_EN_TRAMITE_ACTUALIZACION = 'Se mantiene en trámite una solicitud de actualización de participación en esta sociedad o empresa, generada por un integrante de la sociedad.';
    const MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_SOCIEDAD_EN_TRAMITE_ACTUALIZACION = 'Se mantiene en trámite una solicitud de actualización de bien raíz que es patrimonio de esta empresa o sociedad, generada por un integrante de la sociedad.';
    const MENSAJE_SOLICITUD_JURIDICA_TERRESTRE_SOCIEDAD_EN_TRAMITE_ACTUALIZACION = 'Se mantiene en trámite una solicitud de actualización de vehículo terrestre que es patrimonio de esta empresa o sociedad, generada por un integrante de la sociedad.';
    const MENSAJE_SOLICITUD_JURIDICA_MARINA_SOCIEDAD_EN_TRAMITE_ACTUALIZACION = 'Se mantiene en trámite una solicitud de actualización de vehículo marítimo que es patrimonio de esta empresa o sociedad, generada por un integrante de la sociedad.';

    const MENSAJE_SOLICITUD_JURIDICA_PROPIA_EN_TRAMITE_RECTIFICACION = 'Se mantiene en trámite una solicitud de rectificación de participación en esta sociedad o empresa.';
    const MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_PROPIA_EN_TRAMITE_RECTIFICACION = 'Se mantiene en trámite una solicitud de rectificación de bien raíz que es patrimonio de esta empresa o sociedad.';
    const MENSAJE_SOLICITUD_JURIDICA_TERRESTRE_PROPIA_EN_TRAMITE_RECTIFICACION = 'Se mantiene en trámite una solicitud de rectificación de vehículo terrestre que es patrimonio de esta empresa o sociedad';
    const MENSAJE_SOLICITUD_JURIDICA_MARINA_PROPIA_EN_TRAMITE_RECTIFICACION = 'Se mantiene en trámite una solicitud de rectificación de vehículo marítimo que es patrimonio de esta empresa o sociedad';

    const MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_PROPIA_EN_TRAMITE_SIN_LUCRO = 'Se mantiene en trámite una solicitud de complemento para sociedades sin fines de lucro que es patrimonio de esta empresa o sociedad.';
    const MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_SOCIEDAD_EN_TRAMITE_SIN_LUCRO= 'Se mantiene en trámite una solicitud de complemento para sociedades sin fines de lucro que es patrimonio de esta empresa o sociedad, generada por un integrante de la sociedad';

    const MENSAJE_SOLICITUD_JURIDICA_SOCIEDAD_EN_TRAMITE_RECTIFICACION = 'Se mantiene en trámite una solicitud de rectificación de participación en esta sociedad o empresa, generada por un integrante de la sociedad.';
    const MENSAJE_SOLICITUD_JURIDICA_PROPIEDAD_SOCIEDAD_EN_TRAMITE_RECTIFICACION = 'Se mantiene en trámite una solicitud de rectificación de bien raíz que es patrimonio de esta empresa o sociedad, generada por un integrante de la sociedad.';
    const MENSAJE_SOLICITUD_JURIDICA_TERRESTRE_SOCIEDAD_EN_TRAMITE_RECTIFICACION = 'Se mantiene en trámite una solicitud de rectificación de vehículo terrestre que es patrimonio de esta empresa o sociedad, generada por un integrante de la sociedad.';
    const MENSAJE_SOLICITUD_JURIDICA_MARINA_SOCIEDAD_EN_TRAMITE_RECTIFICACION= 'Se mantiene en trámite una solicitud de rectificación de vehículo marítimo que es patrimonio de esta empresa o sociedad, generada por un integrante de la sociedad.';

    const MENSAJE_ERROR_FORMULARIO_SOLICITUD = 'Ha ocurrido un problema al obtener formulario de la solicitud.';
}
