<?php


namespace App\Clases;


use App\Constantes\Constante;
use App\Models\Programas;
use App\Utiles;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class DatosDeBeneficios
{
    public static function obtenerBeneficiosIntegrantesExport($integrantesExport, $origenRegistro){
        $beneficios = [];
        if ($integrantesExport) {
            foreach ($integrantesExport as $integrante) {
                if ($origenRegistro != Constante::ORIGEN_REGISTRO_1_PLATAFORMA_MUNICIPAL) {
                    //usado para consumir servicio RIS
                    //$getBeneficiosOtorgados = self::getBeneficiosOtorgados($integrante->per_run);
                    //Se usa para buscar en la tabla local (Vista Materializada)
                    $getBeneficiosOtorgados = self::getBeneficiosOtorgadosDbLocal($integrante->PER_RUN);
                    $getBeneficiosPotenciales = self::getBeneficiosPotenciales($integrante);
//                    $beneficios[$integrante->PER_RUN] = [
//                        'nombres' => $integrante->PER_NOMBRES,
//                        'apellido-paterno' => $integrante->PER_APELLIDOPATERNO,
//                        'apellido-materno' => $integrante->PER_APELLIDOMATERNO,
//                        'cantidad-otorgados' => count($getBeneficiosOtorgados),
//                        'otorgados' => $getBeneficiosOtorgados,
//                        'cantidad-potenciales' => count($getBeneficiosPotenciales),
//                        'potenciales' => $getBeneficiosPotenciales
//                    ];
                    $beneficios[] = [
                        'integrante' => $integrante,
                        'otorgados' => $getBeneficiosOtorgados,
                        'potenciales' => $getBeneficiosPotenciales
                    ];
                }
            }
//            $runesIntegrantes = Arr::pluck($export->integrantesGrupoFamiliar, 'per_run');
//            $export->supervision = self::getMensajesSupervision($runesIntegrantes, 42072718);
//            $export->supervision = self::getMensajesSupervision($runesIntegrantes,
//                $export->grupoFamiliar->grupfami_nuevofolio);
        } else {
            $integrantesExport->beneficios = null;
        }
        return $beneficios;
    }

    private static function getBeneficiosOtorgadosDbLocal($run)
    {
        $url = null;
        $dataBeneficio = null;
        $salida = [];

//        Información otorgada por Miguel el dia 20/07/2020 al final del día
//
//        -- B.FECHA_RECEPCION >= 20190101 and B.RUT <= 5999999
//        SELECT * FROM RIS_PROD.VWM_R_BENEFICIOS_ENTREG_1_59;
//
//        -- FECHA_RECEPCION >= 20190101 and RUT > 5999999 and RUT <= 9999999
//        SELECT * FROM RIS_PROD.VWM_R_BENEFICIOS_ENTREG_59_99;
//
//        -- FECHA_RECEPCION >= 20190101 and RUT > 9999999 and RUT <= 16999999
//        SELECT * FROM RIS_PROD.VWM_R_BENEFICIOS_ENTREG_99_16;
//
//        -- FECHA_RECEPCION >= 20190101 and RUT > 16999999
//        SELECT * FROM RIS_PROD.VWM_R_BENEFICIOS_ENTREG_16;  ---  ESTA CON DATOS EN DESARROLLO

        try {
            $tablaFuente = 'RIS_PROD.VWM_R_BENEFICIOS_ENTREG_16';
            if ($run <= 5999999) {
                $tablaFuente = 'RIS_PROD.VWM_R_BENEFICIOS_ENTREG_1_59';
            } elseif ($run <= 9999999) {
                $tablaFuente = 'RIS_PROD.VWM_R_BENEFICIOS_ENTREG_59_99';
            } elseif ($run <= 16999999) {
                $tablaFuente = 'RIS_PROD.VWM_R_BENEFICIOS_ENTREG_99_16';
            }

            $dataBeneficio = DB::table($tablaFuente)
                ->where('rut', $run)
                ->select('id_programa', 'nombre as nombre_programa', 'descripcion as explicacion_programa', 'monto',
                    'fecha_recepcion', 'ejecutor', 'id_tipo_prestacion')
                ->get();
            if ($dataBeneficio) {
                $id_programas = [];
                $beneficios_programas = [];
                foreach ($dataBeneficio as $beneficio) {
                    $arrayBeneficio = [
                        'nombre-beneficio' => $beneficio->NOMBRE_PROGRAMA,
                        'monto' => $beneficio->MONTO,
                        'fecha' => Utiles::formatFechaSeparado($beneficio->FECHA_RECEPCION)
                    ];

                    if ($beneficio->ID_PROGRAMA && !in_array($beneficio->ID_PROGRAMA, $id_programas)) {
                        $id_programas[$beneficio->ID_PROGRAMA] = $beneficio->ID_PROGRAMA;
                        $arrayBeneficio['id-programa'] = $beneficio->ID_PROGRAMA;
                        $arrayBeneficio['descripcion'] = $beneficio->EXPLICACION_PROGRAMA;
                        $arrayBeneficio['nombre-institucion'] = $beneficio->EJECUTOR;
                        if ($beneficio->ID_TIPO_PRESTACION != 1) {
                            $arrayBeneficio['monto'] = null;
                        }
                        $beneficios_programas[$beneficio->ID_PROGRAMA] = $arrayBeneficio;
                    }
                    $beneficios_programas[$beneficio->ID_PROGRAMA]['historico'][] = [
                        'monto' => $beneficio->MONTO,
                        'fecha' => Utiles::formatFechaSeparado($beneficio->FECHA_RECEPCION)
                    ];
                    if (isset($beneficio->ID_PROGRAMA) &&
                        in_array($beneficio->ID_PROGRAMA, $id_programas) &&
                        $beneficios_programas[$beneficio->ID_PROGRAMA]['fecha'] < Utiles::formatFechaSeparado($beneficio->FECHA_RECEPCION)
                    ) {
                        $beneficios_programas[$beneficio->ID_PROGRAMA]['monto'] = $beneficio->MONTO;
                        $beneficios_programas[$beneficio->ID_PROGRAMA]['fecha'] = Utiles::formatFechaSeparado($beneficio->FECHA_RECEPCION);
                    }

                }
                foreach ($beneficios_programas as $beneficio) {

                    $salida[] = $beneficio;
                }
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            $salida = [];
        }
        return $salida;
    }

    private static function getBeneficiosPotenciales($per)
    {
        $salida = [];

        $rut = $per->PER_RUN;
        $folio = 1;
        $mensaje = '';
        $error = '';
        $cursor = array();

        $rut = trim($rut);
        $folio = trim($folio);

        $listadoPotencial = DB::connection('oracle_export')
            ->getPdo()
            ->prepare("CALL PKG_BENEF_POTENCIALES.BENEFICIOS_POTENCIALES(
                        :I_RUT,
                        :I_FOLIO_RSH,
                        :O_MSG,
                        :O_ERR,
                        :O_CURSOR)");

        $listadoPotencial->bindParam('I_RUT', $rut);
        $listadoPotencial->bindParam('I_FOLIO_RSH', $folio);
        $listadoPotencial->bindParam('O_MSG', $mensaje, PDO::PARAM_STR, 3000);
        $listadoPotencial->bindParam('O_ERR', $error, PDO::PARAM_INT);
        $listadoPotencial->bindParam('O_CURSOR', $cursor, PDO::PARAM_STMT);
        $listadoPotencial->execute();

        oci_execute($cursor, OCI_DEFAULT);

        while (($row = oci_fetch_array($cursor, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            $arrayBeneficio = [
                'periodo' => Utiles::formatFechaAnioMes($row['PERIODO']),
                'nombre-beneficio' => $row['DESC_LARGA'],
                'descripcion' => $row['EXPLICACION'],
                'requisitos' => $row['REQUISITOS'],
                'link' => null,
                'nombre-institucion' => null
            ];
            if (isset($row['PROGRAMA_RIS'])) {
                $arrayBeneficio['id-programa'] = $row['PROGRAMA_RIS'];
                $programa = Programas::where('id', $row['PROGRAMA_RIS'])->first();
                if ($programa) {
                    $arrayBeneficio['nombre-institucion'] = $programa->EJECUTOR;
                }
            }
            $salida[] = $arrayBeneficio;
        }
        return $salida;
    }
}
