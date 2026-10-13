<?php

namespace App\Clases;

use Exception;
use App\Constantes\Constante;
use Illuminate\Support\Facades\Cache;
use App\Models\FuenteExport;

class DatosDeFuentes
{
    public static function getListaFuentes()
    {
        $filePath =storage_path("../app/Clases/DatosDeFuentes.php");
        if(!file_exists($filePath)){
            $cacheKey = 'servicio-fuentes-ingresos';
        }else{
            $version = filemtime($filePath);
            $cacheKey = 'servicio-fuentes-ingresos-'.$version;
        }
        $listaFuentes = [];
        try {
            if (Cache::has($cacheKey)) {
                $listaFuentes = Cache::get($cacheKey);
                if(!(isset($listaFuentes['tipos-fuentes'])
                    && count($listaFuentes['tipos-fuentes'])>0
                    && isset($listaFuentes['solicitudes']))){
                    Cache::forget($cacheKey);
                    $listaFuentes = [];
                }
            }
            if (!Cache::has($cacheKey)) {
                $fuentes = FuenteExport::getFuentes();
                if($fuentes->count()){
                    $listaFuentes = ['tipos-fuentes' => null,
                        'solicitudes' =>[
                            60=>['fuentes'=>[]],
                            61=>['fuentes'=>[]],
                            62=>['fuentes'=>[]],
                            63=>['fuentes'=>[]],
                            64=>['fuentes'=>[]],
                            70=>['fuentes'=>[]],
                            71=>['fuentes'=>[]],
                            72=>['fuentes'=>[]],
                            77=>['fuentes'=>[]],
                            78=>['fuentes'=>[]]]
                    ];
                    foreach ($fuentes as $fuente) {
                        $idFuente = (string) $fuente->FUE_ID;
                        $tipoFuente = ['id-fuente' => $idFuente,
                            'descripcion' => (string)$fuente->FUE_DESCRIPCION,
                            'descripcion-larga' => $fuente->FUE_DESCRIPCION_LARGA,
                            'descripcion-municipal-larga' => $fuente->FUE_DESCRIPCION_LARGA,
                            'sub-fuentes' => null
                        ];

                        if($fuente->TIPO_SOLICITUD_62 == "1"
                            && $fuente->TIPO_SOLICITUD_60 == "1"
                            && $fuente->TIPO_SOLICITUD_61 == "1") {
                            $listaFuentes['solicitudes'][Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE]['fuentes'][] = $idFuente;
                            $tipoFuente['sub-fuentes']['dependiente'] = [
                                'id-fuente' => (string)$fuente->FUE_DEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_DEPENDIENTE,
                                'obligatorio' => '1',
                                'descripcion-municipal' => 'Ingresos como trabajador dependiente'
                            ];
                            $tipoFuente['sub-fuentes']['independiente'] = [
                                'id-fuente' => (string)$fuente->FUE_INDEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_INDEPENDIENTE,
                                'obligatorio' => '1',
                                'descripcion-municipal' => 'Ingresos como trabajador independiente'
                            ];
                        }else if($fuente->TIPO_SOLICITUD_60 == "1") {
                            $listaFuentes['solicitudes'][Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE]['fuentes'][] = $idFuente;
                            $tipoFuente['sub-fuentes']['dependiente'] = [
                                'id-fuente' => (string)$fuente->FUE_DEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_DEPENDIENTE,
                                'obligatorio' => '1',
                                'descripcion-municipal' => 'Ingresos como trabajador dependiente'
                            ];
                            $tipoFuente['sub-fuentes']['independiente'] = [
                                'id-fuente' => (string)$fuente->FUE_INDEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_INDEPENDIENTE,
                                'obligatorio' => '0',
                                'descripcion-municipal' => 'Ingresos como trabajador independiente'
                            ];

                        }else if($fuente->TIPO_SOLICITUD_61 == "1") {
                            $listaFuentes['solicitudes'][Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE]['fuentes'][] = $idFuente;
                            $tipoFuente['sub-fuentes']['independiente'] = [
                                'id-fuente' => (string)$fuente->FUE_INDEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_INDEPENDIENTE,
                                'obligatorio' => '1',
                                'descripcion-municipal' => 'Ingresos como trabajador independiente'
                            ];
                            $tipoFuente['sub-fuentes']['dependiente'] = [
                                'id-fuente' => (string)$fuente->FUE_DEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_DEPENDIENTE,
                                'obligatorio' => '0',
                                'descripcion-municipal' => 'Ingresos como trabajador dependiente'
                            ];

                        }
                        if($fuente->TIPO_SOLICITUD_63 == "1") {
                            $listaFuentes['solicitudes'][Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION]['fuentes'][] = $idFuente;
                            if((int)$fuente->FUE_PENS_1 > 0){
                                $tipoFuente['sub-fuentes']['pension'] = [
                                    'id-fuente' => (string)$fuente->FUE_PENS_1,
                                    'nro-meses' => $fuente->N_MESES_INDEPENDIENTE,
                                    'obligatorio' => '1',
                                    'descripcion-municipal' => 'Ingresos por pensión y/o jubilación'
                                ];
                            }
                            if((int)$fuente->FUE_PENS_2 > 0) {
                                $tipoFuente['sub-fuentes']['sii'] = [
                                    'id-fuente' => (string)$fuente->FUE_PENS_2,
                                    'nro-meses' => $fuente->N_MESES_DEPENDIENTE,
                                    'obligatorio' => '1',
                                    'descripcion-municipal' => 'Ingresos por Servicio de Impuestos Internos'
                                ];
                            }
                        }
                        if($fuente->TIPO_SOLICITUD_64 == "1") {
                            $listaFuentes['solicitudes'][Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL]['fuentes'][] = $idFuente;
                        }
                        if($fuente->TIPO_SOLICITUD_78 == "1") {
                            $listaFuentes['solicitudes'][Constante::TIPO_SOLICITUD_78_RECTIFICACION_INGRESO_DEPENDIENTE_E_INDEPENDIENTE]['fuentes'][] = $idFuente;
                            $tipoFuente['sub-fuentes']['dependiente'] = [
                                'id-fuente' => (string)$fuente->FUE_DEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_DEPENDIENTE,
                                'obligatorio' => '1',
                                'descripcion-municipal' => 'Ingresos como trabajador dependiente'
                            ];
                            $tipoFuente['sub-fuentes']['independiente'] = [
                                'id-fuente' => (string)$fuente->FUE_INDEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_INDEPENDIENTE,
                                'obligatorio' => '1',
                                'descripcion-municipal' => 'Ingresos como trabajador independiente'
                            ];
                        }
                        if ($fuente->TIPO_SOLICITUD_70 == "1") {
                            $listaFuentes['solicitudes'][Constante::TIPO_SOLICITUD_70_RECTIFICACION_INGRESO_TRABAJADOR_DEPENDIENTE]['fuentes'][] = $idFuente;
                            $tipoFuente['sub-fuentes']['dependiente'] = [
                                'id-fuente' => (string)$fuente->FUE_DEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_DEPENDIENTE,
                                'obligatorio' => '1',
                                'descripcion-municipal' => 'Ingresos como trabajador dependiente'
                            ];
                            $tipoFuente['sub-fuentes']['independiente'] = [
                                'id-fuente' => (string)$fuente->FUE_INDEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_INDEPENDIENTE,
                                'obligatorio' => '0',
                                'descripcion-municipal' => 'Ingresos como trabajador independiente'
                            ];

                        }
                        if ($fuente->TIPO_SOLICITUD_71 == "1") {
                            $listaFuentes['solicitudes'][Constante::TIPO_SOLICITUD_71_RECTIFICACION_INGRESO_TRABAJADOR_INDEPENDIENTE]['fuentes'][] = $idFuente;
                            $tipoFuente['sub-fuentes']['independiente'] = [
                                'id-fuente' => (string)$fuente->FUE_INDEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_INDEPENDIENTE,
                                'obligatorio' => '1',
                                'descripcion-municipal' => 'Ingresos como trabajador independiente'
                            ];
                            $tipoFuente['sub-fuentes']['dependiente'] = [
                                'id-fuente' => (string)$fuente->FUE_DEPENDIENTE,
                                'nro-meses' => $fuente->N_MESES_DEPENDIENTE,
                                'obligatorio' => '0',
                                'descripcion-municipal' => 'Ingresos como trabajador dependiente'
                            ];

                        }
                        if ($fuente->TIPO_SOLICITUD_77 == "1") {
                            $listaFuentes['solicitudes'][Constante::TIPO_SOLICITUD_77_RECTIFICACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION]['fuentes'][] = $idFuente;
                            if((int)$fuente->FUE_PENS_1 > 0){
                                $tipoFuente['sub-fuentes']['pension'] = [
                                    'id-fuente' => (string)$fuente->FUE_PENS_1,
                                    'nro-meses' => $fuente->N_MESES_INDEPENDIENTE,
                                    'obligatorio' => '1',
                                    'descripcion-municipal' => 'Ingresos por pensión y/o jubilación'
                                ];
                            }
                            if((int)$fuente->FUE_PENS_2 > 0) {
                                $tipoFuente['sub-fuentes']['sii'] = [
                                    'id-fuente' => (string)$fuente->FUE_PENS_2,
                                    'nro-meses' => $fuente->N_MESES_DEPENDIENTE,
                                    'obligatorio' => '1',
                                    'descripcion-municipal' => 'Ingresos por Servicio de Impuestos Internos'
                                ];
                            }
                        }
                        if ($fuente->TIPO_SOLICITUD_72 == "1") {
                            $listaFuentes['solicitudes'][Constante::TIPO_SOLICITUD_72_RECTIFICACION_DE_INGRESOS_DE_CAPITAL]['fuentes'][] = $idFuente;
                        }
                        $listaFuentes['tipos-fuentes'][$idFuente] = $tipoFuente;
                    }
                    Cache::put($cacheKey, $listaFuentes, now()->addMinutes(Constante::CACHE_API_MINUTOS));
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $listaFuentes;
    }

}
