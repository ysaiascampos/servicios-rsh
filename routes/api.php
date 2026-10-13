<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/auth-api-rest/token', 'AuthApiController@getToken');

Route::middleware(['token', 'request.log'])->group(function () {

    Route::post('/datos-globales', 'DatosGlobalesController@show');
    Route::get('/fuentes', 'FuentesController@show');
    Route::get('/preguntas-ficha/{modulo}/{run?}', 'PreguntasFichaController@show');
    Route::get('/datos-de-contacto/{runCiudadano}', 'DatosContactoController@show');
    Route::post('/detalle-gfamiliar/{runCiudadano}', 'DetalleGrupoFamiliarController@show');
    Route::post('/verificar-nroserie', 'NumeroSerieController@validarNumeroSerie');
    Route::get('/aprobacion-automatica', 'AprobacionAutomaticaController@procesarSolicitudAprobacionAutomatica');

    //Rutas para generar el token de validación de correo
    Route::group(['prefix' => '/email-token'], function () {
        Route::post('/generar', 'ValidaMailController@solicitaToken');
        Route::post('/validar', 'ValidaMailController@validaToken');
        Route::post('/reenviar', 'ValidaMailController@reenviaToken');
        Route::post('/confirmar', 'ValidaMailController@confirmaContacto');
    });

    Route::group(['prefix' => '/validacion-tercero'], function () {
        Route::post('', 'ValidaTerceroController@guardar');
        Route::put('', 'ValidaTerceroController@updateValidacion');

        Route::get('/lista/{runCiudadano}', 'ValidaTerceroController@showListaSolicitudes');
        Route::get('/solicitud/{runCiudadano}/{idSolicitud}', 'ValidaTerceroController@show');
        Route::get('/solicitudes/{idSolicitud}', 'ValidaTerceroController@showSolicitudes');
        Route::put('/solicitud/{idSolicitud}', 'ValidaTerceroController@update');

        Route::get('/valida-menores/{idSolicitud}', 'ValidaTerceroController@validaMenoresidSolicitud');
        Route::get('/valida-menores/run/{runCiudadano}', 'ValidaTerceroController@validaMenoresRun');
    });

    Route::group(['prefix' => '/parentescos'], function () {
        Route::get('/lista', 'ParentescosController@showListaParentesco');
        Route::get('/{runCiudadano}', 'ParentescosController@show');
    });

    Route::group(['prefix' => '/educacion'], function () {
        Route::group(['prefix' => '/superior'], function () {
            // api/educacion/superior/motivo
            Route::get('/motivo', 'EducacionController@EducacionSuperiorMotivo');
            // api/educacion/superior/tipo-institucion
            Route::get('/tipo-institucion', 'EducacionController@EducacionSuperiorTipo');
            // api/educacion/superior/institucion/
            Route::get('/institucion/{tipoInstitucion}', 'EducacionController@EducacionSuperiorInstitucion');
            // api/educacion/superior/institucion
            Route::get('/institucion', 'EducacionController@EducacionSuperiorInstitucionTodas');
        });
        Route::group(['prefix' => '/colegios'], function () {
//            Route::get('/institucion/{comunaInstitucion}', 'EducacionController@getColegio');
            // api/educacion/colegio/institucion
            Route::get('/institucion', 'EducacionController@EducacionColegiosInstitucion');
        });
    });

    Route::group(['prefix' => '/locacion'], function () {
        Route::get('/calle/{codigoComuna}', 'LocacionController@showCalle');
        Route::get('/region-comuna-localidad-uv/{compress?}', 'LocacionController@showRegionComunaLocalidadUv');
        Route::get('/region-comuna-localidad-uv-ah/{compress?}', 'LocacionController@showRegionComunaLocalidadUvAh');
        Route::get('/region-comuna-localidad-uv-ah-ine/{codigoComuna}',
            'LocacionController@showRegionComunaLocalidadUvAhPorComunaIne');
    });

    Route::group(['prefix' => '/tramite/', 'middleware' => ['token.claveUnica']], function () {
        //Route::get('', 'TramiteControllert@showInfo');
        Route::post('', 'TramiteController@store');
        Route::get('/{idSolicitud}', 'TramiteController@show');
        Route::put('/{idSolicitud}', 'TramiteController@update');
        Route::delete('/{idSolicitud}', 'TramiteController@delete');
    });

    Route::group(['prefix' => '/documentos-adjuntos'], function () {
        Route::get('/requeridos/{tipoSolicitud}/{idSubTipoSolicitud?}',
            'AdjuntosController@listarDocumentosRequeridos');

        Route::group(['prefix' => '/{idSolicitud}'], function () {
            Route::get('/', 'AdjuntosController@show');
            Route::get('/zip', 'AdjuntosController@zip');
            Route::post('/{idTipoDocumento}/{idDocumento?}', 'AdjuntosController@store');
            Route::get('/{idTipoDocumento}/{idDocumento?}', 'AdjuntosController@download');
            Route::delete('/{idTipoDocumento}/{idDocumento?}', 'AdjuntosController@delete');
        });
    });
    Route::get('/documentos-requeridos-extras/{idSolicitud}',
        'AdjuntosController@requeridosExtrasShow');
    //para desarrollo de bladescartola-rsh-sin-entendiendo
    Route::get('/descargar-formulario/{portal}/{idSolicitud}', 'FormularioController@descargarFormulario');
    //Route::get('/cartola-rsh/{portal}/{runCiudadano}', 'CartolaController@show');
    Route::get('/cartola-rsh/{canal}/{runCiudadano}', 'Cartola2024Controller@show');
    Route::get('/cartola-rsh-sin-entendiendo/{canal}/{runCiudadano}/{formato?}', 'Cartola2024Controller@showSinEntendiendo');
    Route::post('/codigo-cartola-emitida', 'GenerarCodigoCartolaController@show');
    //Descargar Excel de Bandeja
    Route::get('/descargar-excel/{portal}/{idCom}', 'GeneracionExcelController@descargarExcel');
    //Generacion Job Manual
    Route::get('/generar-excel-job/{idCom}', 'GeneracionExcelController@jobExcel');
    //descarga pdf en el storage tanto emitido por municipal como ciudadano
    Route::post('/descargarGuardar-formulario', 'FormularioController@guardarFormularioEnStoragePdf');

    Route::group(['prefix' => '/qr-certificado'], function () {
        Route::get('/cuidadora/{portal}/{runCiudadano}','GeneracionCertificadoCuidadoraController@show');
        Route::get('/validacartola/{hashMd5}', 'GeneracionCertificadoCuidadoraController@validar');
        Route::get('/cuidadora-status-totem/{codigoinstitucion}/{runCiudadano}','GeneracionCertificadoCuidadoraTotemController@statuscuidadora');
        Route::get('/cuidadora-totem/{portal}/{runCiudadano}','GeneracionCertificadoCuidadoraTotemController@show');
        Route::get('/cuidadora-totem-base64/{portal}/{runCiudadano}','GeneracionCertificadoCuidadoraTotemController@showBase64');
        Route::get('/cuidadora/credencialimage/{portal}/{runCiudadano}','GeneracionCertificadoCuidadoraController@showCredencialImage');
    });

    Route::group(['prefix' => '/qr'], function () {
        Route::post('/validar', 'QrController@validarCuidadorQR');
        Route::post('/validar-run-n-serie', 'QrController@validarCuidadorRunNSerie');
        Route::get('/pdf/{runCiudadano}', 'QrController@generarPdfQR');
        Route::get('/imagen/{runCiudadano}', 'QrController@generarImagenQR');
        Route::get('/datos-cuidador/{runCiudadano}','QrController@ObtenerDatos');
        Route::get('/generarqrmasivo','QrController@GenerarQrMasivo');
        Route::get('/descargaqrzip/{y}/{m?}/{d?}','QrController@DescargarQrZip');
        Route::get('/instituciones_vigentes', 'QrController@consultarinstitucionesvigentes');
        Route::get('/validar_codigo_institucion/{idinstitucion}/{codigo}', 'QrController@validarCodigoInstitucion');

    });
    Route::get('/nacionalidades', 'NacionalidadController@consultar');
    Route::get('/parentescos', 'ParentescosNNAController@obtenerParentescos');
    Route::post('/guardar-formulario', 'RshEspecialAdultoController@store');
    Route::get('/representantes/{idSolicitud}', 'RepresentanteController@obtenerRepresentantes');
    Route::get('/representantes-run/{runCiudadano}', 'RepresentanteController@obtenerRepresentantesRun');
    Route::post('/guardar-consulta-nna', 'RegistroConsultaNnaController@store');
    Route::put('/representantes/{id}', 'RshEspecialAdultoController@update');

    Route::get('/trazabilidad-ciudadano/{runCiudadano}', 'TrazabilidadController@trazabilidadFolio');

    Route::group(['prefix' => '/credencial'], function () {
        Route::get('/generacionmasiva','CredencialesController@GenerarCredencialesMasivo');
        Route::get('/generarpdfrunCiudadano/{runCiudadano}','CredencialesController@generarCredencialPDFCuidadoraQR');
        Route::get('/generarjpgrunCiudadano/{runCiudadano}','CredencialesController@generarCredencialJPGCuidadoraQR');
        Route::get('/emailmasivonegativos','CredencialesController@EnvioEmailMasivosNegativos');
        Route::get('/emailmasivopositivos','CredencialesController@EnvioEmailMasivosPositivos');
        Route::get('/descargacredencialeszip/{y}/{m?}/{d?}','CredencialesController@DescargarCredecialesZip');
    });

    Route::group(['prefix' => '/encuesta-cartola'], function () {
        Route::post('/guardar-encuesta', 'EncuestaCartolaController@store');
        Route::get('/encuestas/{runCiudadano}', 'EncuestaCartolaController@show');
        Route::put('/actualizar-encuesta/{run}', 'EncuestaCartolaController@update');
    });

    Route::get('/companias-luz', 'CompaniasLuzController@show');
    Route::get('/companias-agua', 'CompaniasAguaController@show');
    Route::post('/cartola-historica', 'CartolaHistoricaController@descargarComposicionHistorica');
    Route::group(['prefix' => '/datos-cuidadores'], function () {
        Route::post('/', 'CuidadoresController@show');
    });

    Route::group(['prefix' => '/duplicidad-domicilio'], function () {
        Route::post('/', 'DuplicidadDomicilioController@show');
    });

    Route::group(['prefix' => '/descarga-dj'], function () {
        Route::get('/{documento}', 'DocumentosDjController@show');
    });
    Route::post('/runificador-rest', 'ApiRunificadorController@show');

    Route::post('/urlinfosolicitud', 'SolicitudUrlController@infoSolicitud');

});
Route::get('/validacartola/{hashMd5}', 'CartolaController@validar');


