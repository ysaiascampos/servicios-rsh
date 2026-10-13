<?php


namespace App\Servicios\ReglasCuidadores;


use App\Clases\DatosDeContacto;
use App\Constantes\Constante;
use App\Http\ResponseMappers\PersonaCuidadoresMapper;
use App\Utiles;
use Closure;
use Exception;

class ValidarGruposFamiliaresRsh
{
    private $folioDependiente;
    private $nuevoFolioDependiente;
    private $folioCuidado;
    private $nuevoFolioCuidado;
    private $mapear;
    private $consultaCuidado;

    public function __construct()
    {
        $this->mapear = new PersonaCuidadoresMapper();
        $this->consultaCuidado = new ConsultaCuidadoresCuidado();
    }

    public function handle($request, Closure $next)
    {
        $validar = true;
        $folioDependiente = null;

        try {

            $dependiente = $request[Constante::DATOS_DEPENDIENTE];
            $cuidador = $request[Constante::DOTOS_CUIDADOR];

            //TODO: Corresponde a todos los datos del dependiente y su lista de cuidadores
            if (isset($dependiente)) {
                // datos general del dependiente
                $result = self::validarGrupoFamiliar($dependiente['run'],
                    $dependiente); // TODO: saca el folio del dependiente
                $request[Constante::DATOS_DEPENDIENTE] = $result;
                $this->folioDependiente = $result['folio'];
                $this->nuevoFolioDependiente = $result ['nuevofolio'];

                // TODO: saca el folio de todos sus cuidadores
                $nuevaLista = [];
                foreach ($dependiente[Constante::DEPENDIENTE_LISTA_CUIDADOR] as $listado) {
                    $conversion = (array)$listado;
                    $result_folio = self::validarGrupoFamiliar($conversion['RUN_CUIDADOR'], $conversion);
                    $result_folio['run_falso'] = Utiles::ponePuntosAlRun($conversion['RUN_CUIDADOR']);
                    //Saca si son del mismo grupo familiar
                    $folioDependiente = $this->nuevoFolioDependiente != null ? $this->nuevoFolioDependiente : $this->folioDependiente;
                    $foliocuidador = $result_folio['nuevofolio'] != null ? $result_folio['nuevofolio'] : $result_folio['folio'];
                    $result_folio['mismo_grupofamiliar'] = $folioDependiente == $foliocuidador ? true : false;

                    $nuevaLista[] = self::mapearDatos($conversion['RUN_CUIDADOR'], $result_folio);
                }
                $request[Constante::DATOS_DEPENDIENTE][Constante::DEPENDIENTE_LISTA_CUIDADOR] = $nuevaLista;
            }

            // TODO: Corresponde al acuidador y a todo su lista de quien esta cuidando
            if (isset($cuidador)) {
                $result = self::validarGrupoFamiliar($cuidador['run'], $cuidador);
                $request[Constante::DOTOS_CUIDADOR] = $result;
                $this->folioCuidado = $result['folio'];
                $this->nuevoFolioCuidado = $result ['nuevofolio'];

                $nuevaLista = [];
                foreach ($cuidador[Constante::CUIDADOR_LISTA_DEPENDIENTE] as $listado) {
                    $conversion = (array)$listado;
                    $result_folio = self::validarGrupoFamiliar($conversion['RUN_DEPENDIENTE'], $conversion);

                    $result_folio['run_falso'] = Utiles::ponePuntosAlRun($conversion['RUN_DEPENDIENTE']);

                    //Saca si son del mismo grupo familiar
                    $foliocuidador = $this->nuevoFolioCuidado != null ? $this->nuevoFolioCuidado : $this->folioCuidado;
                    $folioDependiente = $result_folio['nuevofolio'] != null ? $result_folio['nuevofolio'] : $result_folio['folio'];
                    $result_folio['mismo_grupofamiliar'] = $folioDependiente == $foliocuidador ? true : false;
                    $nuevaLista[] = self::mapearDatos($conversion['RUN_DEPENDIENTE'], $result_folio);
                }
                $request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_DEPENDIENTE] = $nuevaLista;

            }


            if (isset($cuidador['run']) && isset($dependiente['run'])) {
                $folioDependiente = $request[Constante::DATOS_DEPENDIENTE]['nuevofolio'] != null ? $request[Constante::DATOS_DEPENDIENTE]['nuevofolio'] : $request[Constante::DATOS_DEPENDIENTE]['folio'];
                $foliocuidador = $request[Constante::DOTOS_CUIDADOR]['nuevofolio'] != null ? $request[Constante::DOTOS_CUIDADOR]['nuevofolio'] : $request[Constante::DOTOS_CUIDADOR]['folio'];
                $request['mismo_grupofamiliar'] = $folioDependiente == $foliocuidador ? true : false;
            }

        } catch (Exception $e) {
            throw new Exception('Tenemos problemas en el grupo familiar');
        }

        return $next($request);
    }


    private function validarGrupoFamiliar($run, $datos)
    {

        $resultado = $this->consultaCuidado->traerFolio($run);
        $datos['folio'] = isset($resultado[0]->FOLIO) ? $resultado[0]->FOLIO : null;
        $datos['nuevofolio'] = isset($resultado[0]->NUEVOFOLIO) ? $resultado[0]->NUEVOFOLIO : null;
        return $datos;

    }

    private function mapearDatos($rut, $result_folio)
    {
        $Datosdependiente = DatosDeContacto::obtenerDatosContacto($rut);
        $Datosdependiente = $this->mapear->map($Datosdependiente);
        $normalizar = $this->consultaCuidado->normalizarInfo($result_folio);
        $result_folio = array_merge($normalizar, $Datosdependiente);
        $resultado = $this->mapear->mapCuidadorDependiente($result_folio);
        return $resultado;
    }


}