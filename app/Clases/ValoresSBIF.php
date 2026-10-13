<?php
namespace App\Clases;

use App\Constantes\Constante;
use App\Models\ValorUf;
use App\Sequence;
use Exception;

class ValoresSBIF
{
    public static function obtenerUfWS()
    {
//        UtilesController::limpiaCacheYLlena();
        $monolog = new \Monolog\Logger('batch-log');
        $monolog->pushHandler(new \Monolog\Handler\StreamHandler(storage_path() . '/logs/batch-log.log'), \Monolog\Logger::INFO);
        $zonaHorariaChileContinental = new DateTimeZone('America/Santiago');
        $dateNow = new DateTime("now", $zonaHorariaChileContinental);
        $monolog->info('Inicia Batch Obtener UF: ' . $dateNow->format('Y-m-d H:i:s'));
        $secret = (isset($_POST['secret'])) ? $_POST['secret'] : 0;
        if (strcmp($secret, 'WrS6AuHbSN7x9zBNX33h') == 0) {
            try {
                $busquedaValoresExistentes = ValorUf::where('fecha', Date('Y-m-d'))
                    ->first();
                if (count($busquedaValoresExistentes) == 0) {
                    $llamadaServicio = self::llamadaWSSBIF();
                    if (($llamadaServicio == -1 || $llamadaServicio == -2) &&
                        (is_numeric($llamadaServicio))
                    ) {
                        $monolog->error('Fin Batch obtenerUfWS ERROR: ' . $dateNow->format('Y-m-d H:i:s'));
                        return "ERROR DE PROCESO!!!";
                    }
                    $busquedaValoresExistentes = ValorUf::where('fecha', Date('Y-m-d'))
                        ->first();
                    $msj = 'Se Ingreso el valor $' . $busquedaValoresExistentes->valor_uf_dia . ' para la fecha: ' . Date('Y-m-d');
                    $monolog->info($msj);
                    return ($msj);
                } else {
                    $monolog->info('Ya Existe el Valor $' . $busquedaValoresExistentes->valor_uf_dia . ' para la fecha: ' . $busquedaValoresExistentes->fecha);
                    return ('Ya Existe el Valor $' . $busquedaValoresExistentes->valor_uf_dia . ' para la fecha: ' . $busquedaValoresExistentes->fecha);
                }
            } catch (Exception $ex) {
                $monolog->error('Excepcion: ' . $ex->getMessage());
                $monolog->error('Stacktrace: ' . $ex->getTraceAsString());
                $monolog->error('Fin Batch obtenerUfWS ERROR: ' . $dateNow->format('Y-m-d H:i:s'));
                return "ERROR DE PROCESO!!!";
            }
        }
        $dateNow = new DateTime("now", $zonaHorariaChileContinental);
        $monolog->error('Fin Batch obtenerUfWS NO AUTORIZADO: ' . $dateNow->format('Y-m-d H:i:s'));
        return "ACCESO NO AUTORIZADO!!!";
    }

    public static function llamadaWSSBIF()
    {
        try {
            $apiKeySbif = '?apikey=' . env('APIKEY_SBIF') . '&formato=json';
            $urlUf = Constante::UF_URL_1 . date('Y') . '/' . date('m') . Constante::UF_URL_2 . date('d') . $apiKeySbif;
            $client = new GuzzleHttp\Client();
            $res = $client->get($urlUf);
            if ($res->getStatusCode() == Constante::STATUS_200_SUCCESS_OK) {
                $cuerpo = $res->getBody();
                $ufValorConComa = Utiles::remplazaComaPorPunto(json_decode($cuerpo, true)['UFs'][0]['Valor']);
                $fecha = json_decode($cuerpo, true)['UFs'][0]['Fecha'];
                $guardaDatos = self::escribirBD($fecha, $ufValorConComa);
                if ($guardaDatos == 1) {
                    return ($ufValorConComa);
                } else {
                    // problema al guardar
                    return (-2);
                }
            } else {
                // problema con SBIF
                return (Constante::COMUNICACION_ERROR);
            }
        } catch (Exception $ex) {
            return ($ex);
        }
    }

    public static function escribirBD($fecha, $valorUF)
    {
        $actualizarUF = new ValorUf();
        $pid = Sequence::nextValue('seq_valor_uf');
        $actualizarUF->id = $pid;
        $actualizarUF->fecha = Date('Y-m-d', strtotime($fecha));
        $actualizarUF->valor_uf_dia = $valorUF;
        if ($actualizarUF->save()) {
            return (1);
        } else {
            // problema al guardar el registro
            return (-1);
        }
    }

    /**
     * Devuelve el valor de UF del dia, sin embargo en caso que no hayan valores en BD
     * se hace una llamada al servicio si este no responde en 2 oportunidades, se devuelve el
     * ultimo valor de UF que tenia la BD.
     */
    public static function devuelveUf()
    {
        $cont = 0;
        $busquedaValoresExistentes = ValorUf::where('fecha', Date('Y-m-d'))
            ->first();
        while (empty($busquedaValoresExistentes)) {
            //$b = self::llamadaWSSBIF();
            $cont = $cont + 1;
            $busquedaValoresExistentes = ValorUf::where('fecha', Date('Y-m-d'))
                ->first();
            if ($cont >= 2) {
                $busquedaValoresExistentes = ValorUf::OrderBy('id', 'desc')
                    ->first();
            }
        }
        return ($busquedaValoresExistentes->VALOR_UF_DIA);
    }
}
