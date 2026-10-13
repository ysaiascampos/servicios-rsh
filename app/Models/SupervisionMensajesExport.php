<?php

namespace App\Models;

use App\Constantes\Constante;
use App\Http\ResponseMappers\GrupoFamiliarAsignacion;
use App\Utiles;
use DateTime;
use DateTimeZone;
use Eloquent;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\SupervisionMensajesExport
 *
 * @property int $SV_ID_MENSAJE
 * @property string|null $SV_DESC_MENSAJE
 * @property string|null $SV_MENSAJE
 * @property string|null $SV_MENSAJE_VENCIDO
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionMensajesExport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionMensajesExport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionMensajesExport query()
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionMensajesExport whereSVDESCMENSAJE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionMensajesExport whereSVIDMENSAJE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionMensajesExport whereSVMENSAJE($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SupervisionMensajesExport whereSVMENSAJEVENCIDO($value)
 * @mixin Eloquent
 */
class SupervisionMensajesExport extends Eloquent
{
    protected $connection = 'oracle_export';
    protected $table = 'supervision_mensajes';
    protected $primaryKey = 'SV_ID_MENSAJE';
    public $timestamps = false;

    public static function getMensajes($runIntegrante, $grupoFamiliarExport,$integrantesExport)
    {
        $nuevoFolio = $grupoFamiliarExport->GRUPFAMI_NUEVOFOLIO;
        $integrantes = $integrantesExport;
        try {
            $zonaHorariaChileContinental = new DateTimeZone('America/Santiago');
            $fechaActual = new DateTime("now", $zonaHorariaChileContinental);
            $query = self::join('supervision_rsh', 'supervision_rsh.sv_id_mensaje', '=',
                'supervision_mensajes.sv_id_mensaje')
                ->where(function ($query) use ($runIntegrante, $nuevoFolio) {
                    $query->where(function ($query2) use ($runIntegrante) {
                            $query2->where('supervision_rsh.sv_destinatario_mens',2)
                                ->where('supervision_rsh.sv_run', $runIntegrante);
                        })
                        ->orwhere(function ($query2) use ($nuevoFolio) {
                            $query2->where('supervision_rsh.sv_destinatario_mens',3)
                                ->where('supervision_rsh.sv_folio', '=', $nuevoFolio);
                        })->orwhere(function ($query2) use ($nuevoFolio) {
                            $query2->where('supervision_rsh.sv_menor',1)
                                ->where('supervision_rsh.sv_folio', '=', $nuevoFolio);
                        });
                })
                ->where('supervision_rsh.sv_vigente', Constante::VIGENTE_1_VIGENTE);

            if(env('MENSAJE_BLOQUEO_FECHA')){
                $query
                    ->where('supervision_rsh.sv_fec_ult_dia_mens', '>=', $fechaActual->format('Ymd'));
            }
            $elemento = $query->get();

        } catch (Exception $ex) {
            log::error($ex->getMessage());
            Log::info('Exception en buscar mensajes de supervisión: run '. $runIntegrante . ' - Nuevo Folio [' . $nuevoFolio . ']');
            $elemento = null;
        }
        if($elemento->count()>0){
            $runes = array();
            foreach ($elemento as $key => $item){
                $runMensaje = (int)$item->SV_RUN;
                if((int)$item->SV_MENOR == 1 &&
                    (int)$item->SV_RUN_MENOR>0 &&
                    !in_array((int)$item->SV_RUN_MENOR,$runes)){
                    $gfasignacionfamiliar=GrupoFamiliar::findVigenteAprobadoByRunCiudadano($item->SV_RUN,false);
                    $gfasignacionmenor=GrupoFamiliar::findVigenteAprobadoByRunCiudadano($item->SV_RUN_MENOR,false);
                    if($gfasignacionfamiliar && $gfasignacionmenor && $gfasignacionfamiliar->GRUPFAMI_NUEVOFOLIO == $gfasignacionmenor->GRUPFAMI_NUEVOFOLIO){
                        $runes[]=(int)$item->SV_RUN_MENOR;
                    }
                    else{
                        $elemento[$key] = null;
                    }
                }
            }
            $elemento = $elemento->filter(function ($value, $key){
                return $value != null;
            });
            if($elemento->count() == 0){
                $mensaje = "XYZ";
                $elemento = null;
                return $elemento;
            }

            // Valida si el integrante del mensaje corresponde al grupo hogar
            $mensaje = "";
            foreach ($integrantes as $integrante){
                if ($integrante->PER_RUN == $runMensaje){
                    $mensaje = "El integrante es del grupo hogar";

                }
            }
            if (empty($mensaje)) {
                $mensaje = "El integrante no esta dentro del grupo hogar";
                $elemento = null;
                return $elemento;
            }

            $personas = array();
            foreach ($runes as $item){
                $persona = Utiles::getPersona($item);
                if(!is_int($persona)){
                    $personas[$item]['SV_RUN_MENOR'] = $persona->PER_RUN;
                    $personas[$item]['SV_RUN_COMPLETO_MENOR'] = Utiles::ponePuntosAlRun($persona->PER_RUN,
                        $persona->PER_DV);
                    $nombre = $persona->PER_NOMBRES . " " . $persona->PER_APELLIDOPATERNO . " "
                        .$persona->PER_APELLIDOMATERNO;
                    $personas[$item]['SV_NOMBRE_MENOR'] = $nombre;
                }
            }
            $calle = Utiles::obtenerCalleExport($grupoFamiliarExport);
            $direccion = "Calle no encontrada" != $calle ? $calle:"";
            $direccion .= isset($grupoFamiliarExport->GRUPFAMI_NUMDOMICILIO)?" " .
                $grupoFamiliarExport->GRUPFAMI_NUMDOMICILIO:"";
            $comuna = LocacionComuna::findByComunaIne($grupoFamiliarExport->GRUPFAMI_COMUNAINE)[0];
            $direccion .= isset($comuna->DESCRIPCION)?", " .
                $comuna->DESCRIPCION:"";
            $region = ($comuna) ? LocacionRegion::getRegionPorComuna($comuna->CODIGO) : null;
            $direccion .= isset($region->REG_DESCRIPCION)?", " .
                $region->REG_DESCRIPCION:"";
            foreach ($elemento as $item) {
                if((int)$item->SV_MENOR == 1 && isset($personas[$item->SV_RUN_MENOR])){
                    $runMenor = $personas[$item->SV_RUN_MENOR]['SV_RUN_COMPLETO_MENOR'];
                    $item->SV_MENSAJE = str_replace("SV_RUN_MENOR", $runMenor, $item->SV_MENSAJE);
                    $item->SV_MENSAJE_CORREO = str_replace("SV_RUN_MENOR", $runMenor, $item->SV_MENSAJE_CORREO);
                    $nombre = $personas[$item->SV_RUN_MENOR]['SV_NOMBRE_MENOR'];
                    $item->SV_MENSAJE = str_replace("SV_NOMBRE_MENOR", $nombre , $item->SV_MENSAJE);
                    $item->SV_MENSAJE_CORREO = str_replace("SV_NOMBRE_MENOR", $nombre, $item->SV_MENSAJE_CORREO);
                    $item->SV_RUN_MENOR=$runMenor;
                }
                $item->SV_MENSAJE = str_replace("DIRECCION_RSH_CARTOLA", $direccion , $item->SV_MENSAJE);
                $item->SV_MENSAJE_CORREO = str_replace("DIRECCION_RSH_CARTOLA", $direccion, $item->SV_MENSAJE_CORREO);
                if(!empty($item->SV_PERIODO_RSH)){
                    $formato=self::formato_periodorsh($item->SV_PERIODO_RSH);
                    if($formato===0){
                        $formato='';
                    }
                    $item->SV_MENSAJE=str_replace("SV_PERIODO_RSH", $formato , $item->SV_MENSAJE);
                }
            }
        }
        return $elemento;
    }

    public static function formato_periodorsh($periodo_rsh){
        if(!empty($periodo_rsh)){
            $anoperiodo=substr($periodo_rsh,0,4);
            $mes=substr($periodo_rsh,4,5);
            switch($mes){
                case 01:
                    $mesperiodo=Constante::MES01;
                    break;
                case 02:
                    $mesperiodo=Constante::MES02;
                    break;
                case 03:
                    $mesperiodo=Constante::MES03;
                    break;
                case 04:
                    $mesperiodo=Constante::MES04;
                    break;
                case 05:
                    $mesperiodo=Constante::MES05;
                    break;
                case 06:
                    $mesperiodo=Constante::MES06;
                    break;
                case 07:
                    $mesperiodo=Constante::MES07;
                    break;
                case 8:
                    $mesperiodo=Constante::MES08;
                    break;
                case 9:
                    $mesperiodo=Constante::MES09;
                    break;
                case 10:
                    $mesperiodo=Constante::MES10;
                    break;
                case 11:
                    $mesperiodo=Constante::MES11;
                    break;
                case 12:
                    $mesperiodo=Constante::MES12;
                    break;
                default:
                    break;
            }
            $periodo=$mesperiodo.'  de  '.$anoperiodo;
            return $periodo;
        }
        else
            return 0;
    }
}
