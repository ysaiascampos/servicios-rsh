<?php

namespace App\Constantes;

/**
 * Class Constante
 * EJ: Constante::STATUS_200_SUCCESS_OK;
 *
 * Para cambiar nombre se debe presionar SHIFT+F6 sobre la constante
 *    así el cambio se refleja en todas los archivos donde se llama
 */
abstract class ConstanteStatusCodeHttp extends ConstanteTipoSolicitud
{
    const STATUS_100_INFO_CONTINUE = 100; // Esta respuesta provisional indica que todo hasta ahora está bien y que el cliente debe continuar con la solicitud o ignorarla si ya está terminada.
    const STATUS_101_INFO_SWITCHING_PROTOCOL = 101; // Este código se envía en respuesta a un encabezado de solicitud Upgrade por el cliente e indica que el servidor acepta el cambio de protocolo propuesto por el agente de usuario.
    const STATUS_102_INFO_PROCESSING = 102; // (WebDAV) Este código indica que el servidor ha recibido la solicitud y aún se encuentra procesandola; por lo que no hay respuesta disponible.
    const STATUS_103_INFO_CHECKPOINT = 103; // Se va a reanudar una petición POST o PUT que fue abortada previamente.
    // 2xx: Peticiones correctas
    const STATUS_200_SUCCESS_OK = 200; // La solicitud ha tenido éxito. El significado de un éxito varía dependiendo del método HTTP: GET: El recurso se ha obtenido y se transmite en el cuerpo del mensaje. HEAD: Los encabezados de entidad están en el cuerpo del mensaje. PUT o POST: El recurso que describe el resultado de la acción se transmite en el cuerpo del mensaje. TRACE: El cuerpo del mensaje contiene el mensaje de solicitud recibido por el servidor.
    const STATUS_201_SUCCESS_CREATED = 201; // La solicitud ha tenido éxito y se ha creado un nuevo recurso como resultado de ello. Ésta es típicamente la respuesta enviada después de una petición PUT.
    const STATUS_202_SUCCESS_ACCEPTED = 202; // La solicitud se ha recibido; pero aún no se ha actuado. Es una petición "Sin compromiso"; lo que significa que no hay manera en HTTP que permita enviar una respuesta asíncrona que indique el resultado del procesamiento de la solicitud. Está pensado para los casos en que otro proceso o servidor maneja la solicitud; o para el procesamiento por lotes.
    const STATUS_203_SUCCESS_NON_AUTHORITATIVE_INFORMATION = 203; // La petición se ha completado con éxito; pero su contenido no se ha obtenido de la fuente originalmente solicitada; sino que se recoge de una copia local o de un tercero. Excepto esta condición; se debe preferir una respuesta de 200 OK en lugar de esta respuesta.
    const STATUS_204_SUCCESS_NO_CONTENT = 204; // La petición se ha completado con éxito pero su respuesta no tiene ningún contenido; aunque los encabezados pueden ser útiles. El agente de usuario puede actualizar sus encabezados en caché para este recurso con los nuevos valores.
    const STATUS_205_SUCCESS_RESET_CONTENT = 205; // La petición se ha completado con éxito; pero su respuesta no tiene contenidos y además; el agente de usuario tiene que inicializar la página desde la que se realizó la petición; este código es útil por ejemplo para páginas con formularios cuyo contenido debe borrarse después de que el usuario lo envíe.
    const STATUS_206_SUCCESS_PARTIAL_CONTENT = 206; // La petición servirá parcialmente el contenido solicitado. Esta característica es utilizada por herramientas de descarga como wget para continuar la transferencia de descargas anteriormente interrumpidas; o para dividir una descarga y procesar las partes simultáneamente.
    const STATUS_207_SUCCESS_MULTI_STATUS = 207; // (WebDAV) Una respuesta Multi-Estado transmite información sobre varios recursos en situaciones en las que varios códigos de estado podrían ser apropiados. El cuerpo de la petición es un mensaje XML.
    const STATUS_208_SUCCESS_ALREADY_REPORTED = 208; // (WebDAV) El listado de elementos DAV ya se notificó previamente; por lo que no se van a volver a listar.
    const STATUS_226_SUCCESS_IM_USED = 226; // (HTTP Delta encoding) El servidor ha cumplido una petición GET para el recurso y la respuesta es una representación del resultado de una o más manipulaciones de instancia aplicadas a la instancia actual.
    // 3xx: Redirecciones
    const STATUS_301_REDIRECTION_MOVED = 301; // The data requested has been assigned a new URI; the change is permanent. (N.B. this is an optimisation; which must; pragmatically; be included in this definition. Browsers with link editing capabiliy should automatically relink to the new reference; where possible)
    const STATUS_302_REDIRECTION_FOUND = 302; // The data requested actually resides under a different URL; however; the redirection may be altered on occasion (when making links to these kinds of document; the browser should default to using the Udi of the redirection document; but have the option of linking to the final document) as for "Forward".
    const STATUS_303_REDIRECTION_METHOD = 303; // Note: This status code is to be specified in more detail. For the moment it is for discussion only. Like the found response; this suggests that the client go try another network address. In this case; a different method may be used too; rather than GET. The body-section contains the parameters to be used for the method. This allows a document to be a pointer to a complex query operation. The body may be preceded by the following additional fields as listed.
    const STATUS_304_REDIRECTION_NOT_MODIFIED = 304; // If the client has done a conditional GET and access is allowed; but the document has not been modified since the date and time specified in If-Modified-Since field; the server responds with a 304 status code and does not send the document body to the client. Response headers are as if the client had sent a HEAD request; but limited to only those headers which make sense in this context. This means only headers that are relevant to cache managers and which may have changed independently of the document's Last-Modified date. Examples include Date ; Server and Expires. The purpose of this feature is to allow efficient updates of local cache information (including relevant metainformation) without requiring the overhead of multiple HTTP requests (e.g. a HEAD followed by a GET) and minimizing the transmittal of information already known by the requesting client (usually a caching proxy).
    // 4xx: Errores del cliente
    const STATUS_400_ERROR_BAD_REQUEST = 400; // El servidor no procesará la solicitud; porque no puede; o no debe; debido a algo que es percibido como un error del cliente (ej: solicitud malformada; sintaxis errónea; etc). La solicitud contiene sintaxis errónea y no debería repetirse.
    const STATUS_401_ERROR_UNAUTHORIZED = 401; // Similar al 403 Forbidden; pero específicamente para su uso cuando la autentificación es posible pero ha fallado o aún no ha sido provista. Vea autenticación HTTP básica y Digest access authentication.
    const STATUS_402_ERROR_PAYMENT_REQUIRED = 402; // La intención original era que este código pudiese ser usado como parte de alguna forma o esquema de Dinero electrónico o micropagos; pero eso no sucedió; y este código nunca se utilizó.
    const STATUS_403_ERROR_FORBIDDEN = 403; // La solicitud fue legal; pero el servidor rehúsa responderla dado que el cliente no tiene los privilegios para hacerla. En contraste a una respuesta 401 No autorizado; la autenticación no haría la diferencia.
    const STATUS_404_ERROR_NOT_FOUND = 404; // Recurso no encontrado. Se utiliza cuando el servidor web no encuentra la página o recurso solicitado.
    const STATUS_405_ERROR_METHOD_NOT_ALLOWED = 405; // Una petición fue hecha a una URI utilizando un método de solicitud no soportado por dicha URI; por ejemplo; cuando se utiliza GET en un formulario que requiere que los datos sean presentados vía POST; o utilizando PUT en un recurso de solo lectura.
    const STATUS_406_ERROR_NOT_ACCEPTABLE = 406; // El servidor no es capaz de devolver los datos en ninguno de los formatos aceptados por el cliente; indicados por éste en la cabecera "Accept" de la petición.
    const STATUS_407_ERROR_PROXY_AUTHENTICATION_REQUIRED = 407;
    const STATUS_408_ERROR_REQUEST_TIMEOUT = 408; // El cliente falló al continuar la petición - excepto durante la ejecución de videos Adobe Flash cuando solo significa que el usuario cerró la ventana de video o se movió a otro. ref
    const STATUS_409_ERROR_CONFLICT = 409; // Indica que la solicitud no pudo ser procesada debido a un conflicto con el estado actual del recurso que esta identifica.
    const STATUS_410_ERROR_GONE = 410; // Indica que el recurso solicitado ya no está disponible y no lo estará de nuevo. Debería ser utilizado cuando un recurso ha sido quitado de forma permanente. Si un cliente recibe este código no debería volver a solicitar el recurso en el futuro. Por ejemplo un buscador lo eliminará de sus índices y lo hará más rápidamente que utilizando un código 404.
    const STATUS_411_ERROR_LENGTH_REQUIRED = 411; // El servidor rechaza la petición del navegador porque no incluye la cabecera Content-Length adecuada.2​
    const STATUS_412_ERROR_PRECONDITION_FAILED = 412; // El servidor no es capaz de cumplir con algunas de las condiciones impuestas por el navegador en su petición.2​
    const STATUS_413_ERROR_REQUEST_ENTITY_TOO_LARGE = 413; // La petición del navegador es demasiado grande y por ese motivo el servidor no la procesa2​
    const STATUS_414_ERROR_REQUEST_URI_TOO_LONG = 414; // La URI de la petición del navegador es demasiado grande y por ese motivo el servidor no la procesa (esta condición se produce en muy raras ocasiones y casi siempre porque el navegador envía como GET una petición que debería ser POST).2​
    const STATUS_415_ERROR_UNSUPPORTED_MEDIA_TYPE = 415; // La petición del navegador tiene un formato que no entiende el servidor y por eso no se procesa.2​
    const STATUS_416_ERROR_REQUESTED_RANGE_NOT_SATISFIABLE = 416; // El cliente ha preguntado por una parte de un archivo; pero el servidor no puede proporcionar esa parte; por ejemplo; si el cliente preguntó por una parte de un archivo que está más allá de los límites del fin del archivo.
    const STATUS_417_ERROR_EXPECTATION_FAILED = 417; // EXCEPCION EN SOLICITUD DE INGRESO AL REGISTRO // La petición del navegador no se procesa porque el servidor no es capaz de cumplir con los requerimientos de la cabecera Expect de la petición.2​
    const STATUS_418_ERROR_I_AM_A_TEAPOT = 418; // "Soy una tetera". Este código fue definido en 1998 como una inocentada; en el Protocolo de Transmisión de Hipertexto de Cafeteras (RFC-2324). No se espera que los servidores web implementen realmente este código de error; pero es posible encontrar sitios que devuelvan este código HTTP.
    const STATUS_422_ERROR_UNPROCESSABLE_ENTITY = 422; // (WebDAV - RFC 4918) La solicitud está bien formada pero fue imposible seguirla debido a errores semánticos.
    const STATUS_423_ERROR_LOCKED = 423; // (WebDAV - RFC 4918) El recurso al que se está teniendo acceso está bloqueado.
    const STATUS_424_ERROR_FAILED_DEPENDENCY = 424; // (WebDAV) (RFC 4918) La solicitud falló debido a una falla en la solicitud previa.
    const STATUS_425_ERROR_UNASSIGNED = 425; // Definido en los drafts de WebDav Advanced Collections; pero no está presente en "Web Distributed Authoring and Versioning (WebDAV) Ordered Collections Protocol" (RFC 3648).
    const STATUS_426_ERROR_UPGRADE_REQUIRED = 426; // (RFC 7231) El cliente debería cambiarse a TLS/1.0.
    const STATUS_428_ERROR_PRECONDITION_REQUIRED = 428; // EXCEPCION EN SOLICITUD DE DOMICILIO Y VIVIENDA // El servidor requiere que la petición del navegador sea condicional (este tipo de peticiones evitan los problemas producidos al modificar con PUT un recurso que ha sido modificado por otra parte).
    const STATUS_429_ERROR_TOO_MANY_REQUESTS = 429; // Hay muchas conexiones desde esta dirección de internet.
    const STATUS_431_ERROR_REQUEST_HEADER_FIELDS_TOO_LARGE = 431; // El servidor no puede procesar la petición porque una de las cabeceras de la petición es demasiado grande. Este error también se produce cuando la suma del tamaño de todas las peticiones es demasiado grande.
    const STATUS_449_ERROR_EXCEPCION_INTERNA = 449; //
    const STATUS_451_ERROR_UNAVAILABLE_FOR_LEGAL_REASONS = 451; // El contenido ha sido eliminado como consecuencia de una orden judicial o sentencia emitida por un tribunal.
    // 5xx: Errores de servidor
    const STATUS_500_ERROR_INTERNAL_ERROR = 500; // The server encountered an unexpected condition which prevented it from fulfilling the request.
    const STATUS_501_ERROR_NOT_IMPLEMENTED = 501; // The server does not support the facility required.
    const STATUS_502_ERROR_SERVICE_TEMPORARILY_OVERLOADED = 502; // (TO BE DISCUSSED) The server cannot process the request due to a high load (whether HTTP servicing or other requests). The implication is that this is a temporary condition which maybe alleviated at other times.
    const STATUS_503_ERROR_GATEWAY_TIMEOUT = 503; // (TO BE DISCUSSED) This is equivalent to Internal Error 500; but in the case of a server which is in turn accessing some other service; this indicates that the respose from the other service did not return within a time that the gateway was prepared to wait. As from the point of view of the clientand the HTTP transaction the other service is hidden within the server; this maybe treated identically to Internal error 500; but has more diagnostic value.

    // De nuestra plataforma
    const STATUS_9182_ERROR_DOCUMENTOS_ADJUNTOS = 9182;

}
