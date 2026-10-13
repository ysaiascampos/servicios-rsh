<?php
namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionValidacion;
use App\Models\Fuente;
use App\Models\GrupoFamiliarExport;
use App\Models\LocacionComuna;
use App\Models\LocacionLocalidad;
use App\Models\LocacionRegion;
use App\Models\Parentesco;
use App\Models\Personas;
use App\Models\PersonasExport;
use App\Models\TramoQuintil;
use App\Utiles;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;
use App\Clases\DebugTrait;
use PDF;
use stdClass;
use DB;
use App\Models\Usuario;
use App\Models\UsuarioComuna;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Sequence;



class DatosDeCartola
{
    private $runCiudadano;
    private $vista;
    private $ip;
    private $usuario;
    private $runUsuario;
    private $descripcionPerfil;
    private $data;
    private $canal;

    public $ciudadano;
    public $ciudadanoExport;
    public $cargarGrupoFamiliarExport;

    private $listadoParentescos;
    private $tramosQuintil;
    private $comunaUsuario;
    private $validacionCartola;
    public $qrcode;
    private $idLog;

    public function crearCartola($canal, $portal, $runCiudadano, $ip = null, $runUsuario = null, $validacionCartola=false)
    {
        $this->runCiudadano = $runCiudadano;
        //$this->portal = $portal;
        $this->ip = $ip;
        $this->runUsuario = $runUsuario;
        $this->validacionCartola = $validacionCartola;

         $canalTexto = strtolower(trim($canal));
         $this->descripcionPerfil = '';

             switch ($canalTexto) {
        case 'municipal':
            $this->canal = Constante::CANAL_1_MUNICIPAL;
            break;
        case 'clave_unica':
            $this->canal = Constante::CANAL_2_CLAVE_UNICA;
            $this->descripcionPerfil = "Clave Unica";
            break;
        case 'run_ciudadano':
            $this->canal = Constante::CANAL_3_RUN_CIUDADANO;
            $this->descripcionPerfil = "Run Ciudadano";
            break;
        case 'chile_atiende_ejecutor':
            $this->canal = Constante::CANAL_4_CHILE_ATIENDE_EJECUTOR;
            $this->descripcionPerfil = "Chile Atiende Ejecutor";
            break;
        case 'supervision':
            $this->canal = Constante::CANAL_5_SUPERVISION;
            $this->descripcionPerfil = "Supervision";
            break;
        case 'funfa':
            $this->canal = Constante::CANAL_6_FUNFA;
            $this->descripcionPerfil = "FUNFA";
            break;
        case 'totem':
            $this->canal = Constante::CANAL_7_TOTEM;
            $this->descripcionPerfil = "Totem";
            break;
        default:

            $this->canal = Constante::CANAL_1_MUNICIPAL;
            break;
    }

        $this->cacheCartola($runCiudadano);
        $this->qrcode = $this->generarQR();
        DB::connection('oracle_export')
            ->insert("INSERT INTO AB_LOG_CARTOLA (RUN_CONSULTADO, USUARIO,FECHA, CANAL, IP, ID_LOG)
                                  VALUES (?,?,?,?,?,?)", array($this->runCiudadano, $this->runUsuario, date('Y-m-d H:i:s'), $this->canal, $this->ip, $this->idLog));
        $this->listadoParentescos = Parentesco::listParentescos();
        $this->tramosQuintil = TramoQuintil::findAll();

    if ($canalTexto == 'municipal') {
        $oResults = Usuario::where('RUT_USUARIO',$this->runUsuario)->get();
        if($oResults->count()>0){
            $this->usuario = $oResults->first();
        }
        $oResults = UsuarioComuna::select('COM_ID')
            ->where('RUT_USUARIO',$this->runUsuario)->get();
        if($oResults->count()>0){
            $comuna = $oResults->first()->COM_ID;
            $this->comunaUsuario = LocacionComuna::findByComunaIne($comuna)[0];
            switch ($this->usuario->ID_PERFIL){
                case Constante::PERFIL_2_ADMINISTRADOR_NACIONAL:
                case Constante::PERFIL_3_CONSULTA_NACIONAL:
                case Constante::PERFIL_6_COORDINADOR_NACIONAL:
                case Constante::PERFIL_18_NIVEL_CENTRAL:
                    $this->descripcionPerfil="el Ministerio de Desarrollo Social y Familia";
                    break;
                case Constante::PERFIL_14_CHILE_ATIENDE:
                case Constante::PERFIL_25_CHILE_ATIENDE_EJECUTOR:
                    $this->descripcionPerfil="Chile Atiende";
                    break;
                case Constante::PERFIL_29_FUNFA:
                    $this->descripcionPerfil="la Fundación de la Familia";
                    break;
                default:
                    $this->descripcionPerfil="la I.Municipalidad de ".Utiles::capitalizaTexto($this->comunaUsuario->DESCRIPCION);
                    break;
            }
        }
    }

        $this->cargarDataCartola();

        return $this->generarPdf();
    }
    private function cargarDataCartola()
    {
        $this->vista = 'formularios.cartola-rsh';
        $this->ciudadanoExport = $this->obtenerCiudadanoExport($this->runCiudadano);
        $this->grupoFamiliarExport = $this->obtenerGrupoFamiliarExport();
        $this->integrantesExport = $this->obtenerIntegrantesGrupoFamiliarExport();

        $this->data = ['canal' => $this->canal,
            'titulo' => 'Cartola Hogar',
            'ciudadanoExport' => $this->ciudadanoExport,
            'grupoFamiliarExport' => $this->grupoFamiliarExport,
            'integrantesExport' => $this->integrantesExport,
            'usuario' => $this->usuario,
            'descripcionPerfil' => $this->descripcionPerfil,
            'comunaUsuario' => $this->comunaUsuario,
            'qrcode' => $this->qrcode
            ];
    }

    public function errorCartola()
    {
        $this->vista = 'formularios.cartola-rsh';
        if(isset($this->data)){
            $this->data = ['titulo' => 'Cartola Hogar',
                           'error' => true,
                           'mensaje' => "Cartola no válida"
                           ];
        }else{
            $this->data = array_merge($this->data,['titulo' => 'Cartola Hogar',
                'error' => true]);
        }
        return $this->generarPdf();
    }
    private function generarPdf()
    {
        $nombre = 'cartola_' . date('Y-m-d-His') . '.pdf';
        $pdf = PDF::loadView($this->vista, $this->data);
        return $pdf->download($nombre);
    }

    private function obtenerCiudadanoExport($runCiudadano)
    {
        return PersonasExport::findByRunCiudadano($runCiudadano);
    }
    private function obtenerGrupoFamiliarExport()
    {
        $grupoFamiliar = null;
        if ($this->ciudadanoExport) {
            $grupoFamiliar = GrupoFamiliarExport::findByIdGrupoFamiliar($this->ciudadanoExport->PER_FIC_GRUPOFAMILIAR_ID);
            if ($grupoFamiliar) {
                //DebugTrait::debug('Antes de Linea \$grupoFamiliar->nombreAH: ' . $this->runCiudadano);
                $grupoFamiliar->nombreAH = $grupoFamiliar->obtenerNombreAgrupacionHabitacional();

                //DebugTrait::debug('Antes de Linea \$grupoFamiliar->nombreUV : ' . $this->runCiudadano);
                $grupoFamiliar->nombreUV = $grupoFamiliar->obtenerNombreUnidadVecinal();

                $comuna = LocacionComuna::findByComunaIne($grupoFamiliar->GRUPFAMI_COMUNAINE)[0];
                $grupoFamiliar->comuna = $comuna;
                $grupoFamiliar->region = ($comuna) ? LocacionRegion::getRegionPorComuna($comuna->CODIGO) : null;
                $grupoFamiliar->localidad = ($comuna) ?
                    LocacionLocalidad::getLocalidad($comuna->CODIGO, $grupoFamiliar->GRUPFAMI_LOCALIDAD) : null;

                //DebugTrait::debug('Antes de Linea \$grupoFamiliar->calle: ' . $this->runCiudadano);
                $grupoFamiliar->calle = ($comuna) ? Utiles::obtenerCalleExport($grupoFamiliar) : null;
                //DebugTrait::debug('Fin de Linea \$grupoFamiliar->calle: ' . $this->runCiudadano);

                $grupoFamiliar->estadoGrupo = $grupoFamiliar->obtenerEstadoGrupoFamiliar();

                $grupoFamiliar->rangoIngresos = null;
                $grupoFamiliar->ingresoLaboralGrupoFamiliar = Utiles::traeTramoIngreso(
                    $grupoFamiliar->CALC_TRAMO_LABORAL_MIN,
                    $grupoFamiliar->CALC_TRAMO_LABORAL_MAX
                );
                $grupoFamiliar->ingresoJubilacionGrupoFamiliar = Utiles::traeTramoIngreso(
                    $grupoFamiliar->CALC_TRAMO_PENSION_MIN,
                    $grupoFamiliar->CALC_TRAMO_PENSION_MAX
                );
                $grupoFamiliar->ingresoOtrosGrupoFamiliar = Utiles::traeTramoIngreso(
                    $grupoFamiliar->CALC_TRAMO_CAPITAL_MIN,
                    $grupoFamiliar->CALC_TRAMO_CAPITAL_MAX
                );
                $grupoFamiliar->ingresoTotalGrupoFamiliar = Utiles::traeTramoIngreso(
                    $grupoFamiliar->CALC_TRAMO_DEF_MIN,
                    $grupoFamiliar->CALC_TRAMO_DEFL_MAX
                );

                $grupoFamiliar->datosComplementarios = self::obtenerDatosComplementarios($grupoFamiliar);

                $grupoFamiliar->tramo = null;
                if (!empty($this->tramosQuintil) && !empty($grupoFamiliar)) {
                    $msjTramo = Fuente::mensajeTramo();
                    $grupoFamiliar->tramo = new stdClass();
                    $grupoFamiliar->tramo->numero = $grupoFamiliar->CALC_QUINTIL_VALIDACION2;
                    $grupoFamiliar->tramo->nombre = $this->tramosQuintil[$grupoFamiliar->CALC_QUINTIL_VALIDACION2];
                    $grupoFamiliar->tramo->mensaje = $msjTramo;
                }

                $grupoFamiliar->cantidadIntegrantes = 0;
                $grupoFamiliar->cantidadConDiscapacidad = 0;
                $grupoFamiliar->cantidadAdultoMayor = 0;
                $grupoFamiliar->cantidadMenoresDeEdad = 0;
                $grupoFamiliar->fechaValidacion = ($grupoFamiliar) ? $this->obtenerDatosFechaValidacion($grupoFamiliar) : null;
            }
        }
        return $grupoFamiliar;
    }

    private function obtenerIntegrantesGrupoFamiliarExport()
    {
        $integrantes = null;
        if (!empty($this->grupoFamiliarExport)) {

            $integrantes = PersonasExport::findByIdGrupoFamiliarExport($this->grupoFamiliarExport->GRUPFAMI_ID);

            foreach ($integrantes as $integrante) {
                $integrante->parentescoId = $integrante->PER_FIC_PARENTESCOID;
                $integrante->parentescoNombre = isset($this->listadoParentescos[$integrante->PER_FIC_PARENTESCOID])?
                    $this->listadoParentescos[$integrante->PER_FIC_PARENTESCOID]:
                    $this->listadoParentescos[Constante::PARENTESCO_99_POR_DEFINIR];
                $integrante->informante = $integrante->PER_FIC_INFORMANTE;
                $integrante->situacion_unico_per_id = PersonasExport::situacionUnicoPerID($integrante->PER_RUN);
                $integrante->situacion_unico_folio = PersonasExport::situacionUnicoFolio($integrante->PER_RUN);
                $integrante->tiene_rsh = Personas::tieneRSH($integrante->PER_ID);
                $integrante->ingresoLaboral = Utiles::traeTramoIngreso($integrante->CALC_TRAMO_LABORAL_MIN,
                    $integrante->CALC_TRAMO_LABORAL_MAX);
                $integrante->ingresoJubilacion = Utiles::traeTramoIngreso($integrante->CALC_TRAMO_PENSION_MIN,
                    $integrante->CALC_TRAMO_PENSION_MAX);
                $integrante->ingresoOtros = Utiles::traeTramoIngreso($integrante->CALC_TRAMO_CAPITAL_MIN,
                    $integrante->CALC_TRAMO_CAPITAL_MAX);
                $integrante->ingresoTotal = Utiles::traeTramoIngreso($integrante->CALC_TRAMO_DEF_MIN,
                    $integrante->CALC_TRAMO_DEFL_MAX);

                $this->grupoFamiliarExport->cantidadIntegrantes++;

                if ($integrante->PER_EDAD >= Constante::EDAD_60_ADULTO_MAYOR) {
                    $this->grupoFamiliarExport->cantidadAdultoMayor++;
                }
                if ($integrante->PER_EDAD < Constante::EDAD_18_MAYOR_DE_EDAD) {
                    $this->grupoFamiliarExport->cantidadMenoresDeEdad++;
                }

                $integrante->detalleIngreso = PersonasExport::detalleIngresoExport($integrante);
                $integrante->datosComplementarios = self::obtenerDatosComplementariosIntegrante($integrante);

                //ToDo: revisar si corresponde solicitudes en tramite para export
                //$integrante->solicitudesEnTramite = $this->datosDeSolicitud->getSolicitudDatosGlobales($integrante->PER_RUN);

                $integrante->mediosActivos = new stdClass();
                $integrante->mediosActivos->colegioAltoCosto = (int)$integrante->CALC_COLEGIO_COSTOSO == 1;
                $integrante->mediosActivos->cargaIsapreAltoCosto = (int)$integrante->CALC_ISAPRECARGA_COSTOSO == 1;
                $integrante->mediosActivos->vehiculoAltoCostoTerrestre = (int)$integrante->CALC_VEHICULOS_COSTOSOS == 1;
                $integrante->mediosActivos->vehiculoAltoCostoMaritimo = (int)$integrante->DNAVES_IND_MUY_ALTO_VALOR == 1;
                $integrante->mediosActivos->bienRaizAltoCosto = (int)$integrante->CALC_PROPIEDADES_COSTOSAS == 1;
            }
        }
        return $integrantes;
    }

    private function obtenerDatosComplementariosIntegrante($integrante)
    {
        $datosComplementarios = [];
        if (is_null($integrante)) {
            return [];
        }
        $medio_padre_no_presente = false;
        $medio_padre = null;
        if (isset($integrante->MEDIO_PADRE_NO_PRESENT)) {
            $medio_padre = $integrante->MEDIO_PADRE_NO_PRESENT;
            if ($integrante->MEDIO_PADRE_NO_PRESENT != 1) {
                $medio_padre_no_presente = true;
            }
        } elseif (isset($integrante->MEDIO_PADRE_NO_PRESEN_AMB)) {
            $medio_padre = $integrante->MEDIO_PADRE_NO_PRESEN_AMB;
            if ($integrante->MEDIO_PADRE_NO_PRESEN_AMB != 1) {
                $medio_padre_no_presente = true;
            }
        }

        if (($integrante->CALC_VEHICULOS_COSTOSOS != 1 &&
            $integrante->DNAVES_IND_MUY_ALTO_VALOR != 1 &&
                $integrante->CALC_ISAPRECARGA_COSTOSO != 1 &&
                $integrante->CALC_PROPIEDADES_COSTOSAS != 1 &&
                $integrante->CALC_COLEGIO_COSTOSO != 1
            ) && ($medio_padre_no_presente == true)
        ) {
            $datosComplementarios[] = (object)[
                'item' => 'No hay datos complementarios que influyan en la calificación del hogar.',
                'info' => null
            ];
        } else {
            if ($integrante->DNAVES_IND_MUY_ALTO_VALOR == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Tenencia de embarcaciones marítimas de muy alto valor en el hogar.',
                    'info' => 'La información de tenencia y propiedad de la o las embarcaciones marítimas es obtenida de
                    los registros de la Dirección General de Territorio Marítimo y de Marina Mercante (DIRECTEMAR).'
                ];
            }

            if ($integrante->CALC_VEHICULOS_COSTOSOS == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor de la suma de los avalúos fiscales de los vehículos terrestre del hogar.',
                    'info' => 'La Información de tenencia y propiedad del o los vehículos terrestre es obtenida del Registro Civil,
                    y la información de avalúo fiscal del vehículo es obtenida del Servicio de Impuestos Internos(SII).'
                ];
            }

            if ($integrante->CALC_ISAPRECARGA_COSTOSO == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor del Plan de Salud de uno o más integrantes del hogar .',
                    'info' => 'Información obtenida de la base de datos respectiva proporcionada por la Superintendencia de Salud.'
                ];
            }

            if ($integrante->CALC_PROPIEDADES_COSTOSAS == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor de la suma de los avalúos fiscales de las propiedades(bienes raíces) a nombre de los integrantes del hogar.',
                    'info' => 'Información extraída de la base de datos de propiedades del Servicio de Impuestos Internos(SII).'
                ];
            }

            if ($integrante->CALC_COLEGIO_COSTOSO == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Presencia en el hogar de alumnos matriculados en colegios con mensualidad de alto costo.',
                    'info' => 'Información obtenida desde la base de datos de matrículas, proporcionada por el Ministerio de Educación.'
                ];
            }

            if ($medio_padre == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Ingresos del padre o madre no presente en el hogar.',
                    'info' => 'Información obtenida de la base de datos del Servicio de Registro Civil e Identificación (SRCeI) e ingresos totales obtenidos de alguna(s) de las siguientes fuentes de ingresos: Administradora de Fondos de Cesantía (AFC), Superintendencia de Pensiones (SP), Superintendencia de Salud (SS), Servicio de Impuestos Internos (SII) y/o Registro Social de Hogares (RSH).'
                ];
            }
        }
        return $datosComplementarios;
    }

    private function obtenerDatosComplementarios($grupoFamiliar)
    {
        $datosComplementarios = [];
        if (is_null($grupoFamiliar)) {
            return [];
        }
        $medio_padre_no_presente = false;
        $medio_padre = null;
        if (isset($grupoFamiliar->MEDIO_PADRE_NO_PRESENT)) {
            $medio_padre = $grupoFamiliar->MEDIO_PADRE_NO_PRESENT;
            if ($grupoFamiliar->MEDIO_PADRE_NO_PRESENT != 1) {
                $medio_padre_no_presente = true;
            }
        } elseif (isset($grupoFamiliar->MEDIO_PADRE_NO_PRESEN_AMB)) {
            $medio_padre = $grupoFamiliar->MEDIO_PADRE_NO_PRESEN_AMB;
            if ($grupoFamiliar->MEDIO_PADRE_NO_PRESEN_AMB != 1) {
                $medio_padre_no_presente = true;
            }
        }

        if (($grupoFamiliar->CALC_VEHICULOS_COSTOSOS != 1 &&
                $grupoFamiliar->DNAVES_MUYALTO != 1 &&
                $grupoFamiliar->CALC_ISAPRECARGA_COSTOSO != 1 &&
                $grupoFamiliar->CALC_PROPIEDADES_COSTOSAS != 1 &&
                $grupoFamiliar->CALC_COLEGIO_COSTOSO != 1
            ) && ($medio_padre_no_presente == true)
        ) {
            $datosComplementarios[] = (object)[
                'item' => 'No hay datos complementarios que influyan en la calificación del hogar.',
                'info' => null
            ];
        } else {
            if ($grupoFamiliar->DNAVES_MUYALTO == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Tenencia de embarcaciones marítimas de muy alto valor en el hogar.',
                    'info' => 'La información de tenencia y propiedad de la o las embarcaciones marítimas es obtenida de
                    los registros de la Dirección General de Territorio Marítimo y de Marina Mercante (DIRECTEMAR).'
                ];
            }

            if ($grupoFamiliar->CALC_VEHICULOS_COSTOSOS == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor de la suma de los avalúos fiscales de los vehículos terrestre del hogar.',
                    'info' => 'La Información de tenencia y propiedad del o los vehículos terrestre es obtenida del Registro Civil,
                    y la información de avalúo fiscal del vehículo terrestre es obtenida del Servicio de Impuestos Internos(SII).'
                ];
            }

            if ($grupoFamiliar->CALC_ISAPRECARGA_COSTOSO == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor del Plan de Salud de uno o más integrantes del hogar .',
                    'info' => 'Información obtenida de la base de datos respectiva proporcionada por la Superintendencia de Salud.'
                ];
            }

            if ($grupoFamiliar->CALC_PROPIEDADES_COSTOSAS == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Valor de la suma de los avalúos fiscales de las propiedades(bienes raíces) a nombre de los integrantes del hogar.',
                    'info' => 'Información extraída de la base de datos de propiedades del Servicio de Impuestos Internos(SII).'
                ];
            }

            if ($grupoFamiliar->CALC_COLEGIO_COSTOSO == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Presencia en el hogar de alumnos matriculados en colegios con mensualidad de alto costo.',
                    'info' => 'Información obtenida desde la base de datos de matrículas, proporcionada por el Ministerio de Educación.'
                ];
            }

            if ($medio_padre == 1) {
                $datosComplementarios[] = (object)[
                    'item' => 'Ingresos del padre o madre no presente en el hogar.',
                    'info' => 'Información obtenida de la base de datos del Servicio de Registro Civil e Identificación (SRCeI) e ingresos totales obtenidos de alguna(s) de las siguientes fuentes de ingresos: Administradora de Fondos de Cesantía (AFC), Superintendencia de Pensiones (SP), Superintendencia de Salud (SS), Servicio de Impuestos Internos (SII) y/o Registro Social de Hogares (RSH).'
                ];
            }
        }
        return $datosComplementarios;
    }

    private function obtenerDatosFechaValidacion($grupoExport)
    {
        $fechaValidacion_aux = null;
        if ($grupoExport) {
            $fecha = substr($grupoExport->GRUPFAMI_FECHA_ENCUESTA, 0, 4) . "-" .
                substr($grupoExport->GRUPFAMI_FECHA_ENCUESTA, 4, 2) . "-" .
                substr($grupoExport->GRUPFAMI_FECHA_ENCUESTA, 6, 2);

            $fechaValidacion_aux = $fecha; // aca se cambia por la fecha ingeso.
            if (date($grupoExport->GRUPFAMI_FECHA_VALIDACION_CIUD) >= date($fechaValidacion_aux)) {
                $fechaValidacion_aux = $grupoExport->GRUPFAMI_FECHA_VALIDACION_CIUD;
            }
        } else {
            $fechaValidacion_aux = date("Y/m/d");
        }
        return $fechaValidacion_aux;
    }
    public static function cacheCartola($runCiudadano){
        $key = 'Cartola-' . $runCiudadano;
        $minutos = 150;
        $horas = $minutos/60;
        $numeroCartolas = 30;
        if (Cache::has($key)) {
            $valorKey = Cache::get($key);
            if ($valorKey > $numeroCartolas) {
                throw ExcepcionValidacion::new("Ha superado el limite de consultas por el mismo RUN ".$runCiudadano."
                , espere ".$horas." horas para volver a consultar.")
                    ->setTitular($runCiudadano);
            } elseif ($valorKey < $numeroCartolas) {
                Cache::increment($key);
            } else {
                Cache::put($key, 99, now()->addMinutes($minutos));
            }
        } else {
            Cache::put($key, 1, now()->addMinutes($minutos));
        }
    }
    private function generarQR()
    {
        $this->idLog = (int)Sequence::nextValue('LOG_CARTOLA_ID');
        $fechaHora = date('Y-m-d H:i:s');
        $runSolicitante = $this->runCiudadano;
        if(isset($this->runUsuario)){
            $runSolicitante = $this->runUsuario;
        }
        $hashMd5 = $this->validacionCartola;
        if(!$this->validacionCartola){
            $hashMd5 = base64_encode($this->idLog.'&'.$fechaHora.'&'.$this->runCiudadano.'&'.$runSolicitante.'&'.$this->canal.'&'.$this->ip);
        }

        //CODIGO QR
        //TODO CAMBIAR RUTA DINAMICA POR RSHMUNICIPAL
        $qrcode = base64_encode(QrCode::format('png')->size(100)->generate(asset('/api/validacartola').'/'.$hashMd5));

        return $qrcode;
    }

    public function validacionCartolaQr($hash)
    {
        if (!isset($hash) && !$hash) {
            //TODOC CREAR PANTALLA ERROR 404
            Log::error("Ha ocurrido un problema en el hash al obtener cartola del Ciudadano.");
            return $this->errorCartola();
        }
        $error = false;
        try {
            $hashDecode =base64_decode($hash);
            $arrayHash= explode('&', $hashDecode);
            $logCartola = DB::connection('oracle_export')
                ->select("SELECT * FROM AB_LOG_CARTOLA WHERE ID_LOG =".$arrayHash[0]);
            if($arrayHash[4] =='ciudadano'){
                $this->canal = Constante::CANAL_2_CLAVE_UNICA;
            } else {
                $this->canal = Constante::CANAL_1_MUNICIPAL;
            }
            $novalida=false;
            if(isset($logCartola[0])
                && isset($arrayHash[1])
                && $arrayHash[2] == $logCartola[0]->RUN_CONSULTADO
                && $arrayHash[3] == $logCartola[0]->USUARIO
                && $this->canal == $logCartola[0]->CANAL
                && $arrayHash[5] == $logCartola[0]->IP){
                //TODO AGREGAR VALIDACION FECHA GENERADA SEA IGUAL AL MES DE LA FECHA ACTUALL
                if (date('m') === date('m', strtotime($arrayHash[1]))) {
                    $result = $this->crearCartola($arrayHash[4],
                        $logCartola[0]->RUN_CONSULTADO,
                        $logCartola[0]->IP,
                        $logCartola[0]->USUARIO,
                        $hash);
                }else{
                    $novalida = true;
                }

            }else{
                //TODO AGREGAR VISTA CON MENSAJE DE CARTOLA NO VALIDA
                $novalida = true;
            }
            if($novalida){
                $this->data['mensaje'] = "Cartola no válida";
                $result = $this->errorCartola();
            }
        } catch (\Throwable $ex) {
            $error = true;
        }
        if($error){
            Log::error("Ha ocurrido un problema al obtener cartola del Ciudadano. Detalles del error  -> ". $ex);
            $result = $this->errorCartola();
        }
        return $result;

    }

}
