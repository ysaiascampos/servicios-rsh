<?php


return [
    'DB_CONNECTION' => 'oracle',
    'DB_HOST' => '192.168.31.160',
    //'DB_HOST' => '192.168.0.171,192.168.0.173',
    'DB_PORT' => '1521',
    'DB_DATABASE' => 'oradesa',
    //'DB_DATABASE' => 'dbdwh',
    'DB_USERNAME' => 'ASIGNACION',
    'DB_PASSWORD' => 'PROV.,2016',
  //'DB_PASSWORD' => 'RtAwp36Dg',
    'DB_CHARSET' => 'UTF8',

    'DB_EXPORT_CONNECTION' => 'oracle',
    'DB_EXPORT_HOST' => '192.168.31.160',
    'DB_EXPORT_PORT' => '1521',
    'DB_EXPORT_DATABASE' => 'oradesa',
    'DB_EXPORT_USERNAME' => 'ASIGNACION_EXPORT',
    'DB_EXPORT_PASSWORD' => 'PROV.,2016',
     'DB_EXPORT_CHARSET' => 'UTF8',

    'wsrunificador' => 'http://wsrunificador01.mds.cl/run.asmx?wsdl',
    'aid' => 'V3aEwWABki0apwYCZZ2RSQ==',
    'sso' => 'http://sso.mideplan.cl',
    'ambienteDebug' => true,
    'sso_ws_url' => 'http://ws.mds.cl/ssoapi/ssoapi-v1.0.asmx?wsdl',
    'sso_rol_defecto' => '216', // 218
    'sso_id_app' => '139', //'141',
    'sso_nombre' => 'usuario',
    'migracionSSO' => false,

    'PERMITE_INGRESO_POR_CLAVE_UNICA' => false,
    'CLAVE_UNICA_TOKEN_FALSO' => '94355a1824e3492298ccfbc7c96f04e4',
    'CLAVE_UNICA_URI' => 'http://localhost/acceso/callback',
    'CLAVE_UNICA_AUTH_ENDPOINT' => 'https://www.claveunica.gob.cl/openid/authorize',
    'CLAVE_UNICA_TOKEN_ENDPOINT' => 'https://www.claveunica.gob.cl/openid/token',
    'CLAVE_UNICA_USER_INFO_ENDPOINT' => 'https://www.claveunica.gob.cl/openid/userinfo',
    'CLAVE_UNICA_CLIENT_SECRET' => '953eb1acd59b4ac2ac5e26ebd0b20a36',
    'CLAVE_UNICA_CLIENT_ID' => '893897f408a94198b5b2867352590d1f',
    'CLAVE_UNICA_METHOD' => 'client_secret_post',

    'API_KEY_GOOGLE' => '123',

    'SIT_URL_GENERAR_TOKEN' => 'http://rshmunicipal.mideplan.cl/api/auth-api-rest/token',
    'SIT_MAP_DOMAIN' => 'http://sitmds.mideplan.cl:8080',
    'SIT_MAP_URL' => '/sit-war/ol-vis-map.html',
    'SIT_MAP_ID'=>'110',
    'SIT_MAP_USER_ID'=>'9',

    //'API_REST_HOST' => 'http://192.168.31.27/',
    //'API_REST_BASE_URL' => '/rsh-proyecto-arc/public/api',

    'API_REST_HOST' => 'http://rshmunicipal.mideplan.cl/',
    'API_REST_BASE_URL' => '/api',

// 	Habilita la solicitud de confirmar en la comunas detalldas en COMUNAS_CONFIRMAR
	'HABILITA_CONFIRMAR' => true,
	//esta lista de comunas debe ser separada por ,
	'COMUNAS_CONFIRMAR' => '',
	'DIAS_CONFIRMAR' => '30',
	 //regla utilizada para limitar la muestra para boton 'confirmar'
	'REGLA_INGRESO_REGISTRO' => '0',

	'REGIONES_PILOTO_CHILE_ATIENDE' => '6,8',
	'LOG_SERVICE_INPUT' => false,
//	VIA_TOKEN se utiliza para omitir el login via plataforma SSO y utiliza el metodo LOGIN del SOAP de SSO
	'VIA_TOKEN_SSO' => false,
//	CONSUMIR_WS_RUNIFICADOR consulta si debe realizarse un consumo SOAP al servicio de Runificador
	'CONSUMIR_WS_RUNIFICADOR' => false,
// 	Rango_Edad_confirmar establece regla de condiciones cuando los integrantes del grupo de familiar aparece la solicitud confirmar
	'RANGO_EDAD_CONFIRMAR' => '10,99',
//	ACTIVAR_DEPURACION evalua los tiempos de ejecucion de cada parametro de datos globales basado en los RUNES_DEBUG
	'ACTIVAR_DEPURACION_DATOS_GLOBALES' => false,
 	'RUNES_DEBUG' => '',

        // Utilizado para cambiar la version de SOAP de default a 1.2
        'VERSION_1.2_SOAP' => false,
	// Activa el tiempo de 3 segundos de envio y 10 espera de respuesta
	'ACTIVAR_TIMEOUT_SOAP' => true,
	// Activa la cache del SOAP definida en php.ini sea en el memoria o disco
	'ACTIVAR_CACHE_SOAP' => true,

	/*  ClaveSistema RIS */
//	'CLAVE_SISTEMA_RIS_DESA' => '0gMQU9kjKf2LGY3lRDTM',
	'CLAVE_SISTEMA_RIS_DESA' => '0gMQU9kjKf2LGY3lRDTM',
	'CLAVE_SISTEMA_RIS_QA' => 'bRTQ2ETyfckdgGA0ouI8',
	'CLAVE_SISTEMA_RIS_PROD' => 'bRTQ2ETyfckdgGA0ouI8',
	'ADD_DOCUMENTOS_MULTIPLES' => true,
	'token_debug' => '77VbDTLpXorIU7img5kMAa3jyjDzixSs',
//perfiles para activar modal nueva persona manual
    //para activar a todos los perfiles dejar en vacio '', valor default es '8,12,17,25,26'
    'PERFIL_NUEVA_PERSONA' => '8,12,17,25,26',

//	'CERTIFICADO_NACIMIENTO_WS_URL' =>
//	'http://ms-info-certificado-nacimiento-pisee.mds.cl/api/v1/certificado-nacimiento',

	'HABILITAR_VIVIENDA_INGRESO_REGISTRO' => true,
	'FECHA_APLICACION_MONTO_MENSUAL' => '20200403',
	'APIKEY_SBIF' => 'a6802a17db51a4591fa6701a53d063fe88de6dcc',
	'TEMPORAL_PARENTESCO' => true,
	'LOGIN_RUN_TOKEN_FALSO' => '000000a1824e3492298ccfbc7c933333e4',

    'CLAVE_PUBLICA_RECAPTCHA'=>'6LdqTP0UAAAAANU7t1-sn445jvh-lX8I7-foEwAy',
    'CLAVE_PRIVADA_RECAPTCHA'=>'6LdqTP0UAAAAAEQUAWEliGUB_LOpQHbJiqhpMrXi',
    'ACTIVACION_RECAPTCHA'=>false,
    'RUN_RIS_HEALTHCHECK' => '10307950-0',
    'RUN_DG_HEALTHCHECK' => '10307950',
    'ACTIVAR_LOG_SERVICIOS' => true,
    'RUTAPROBADOR' => 11111111
];