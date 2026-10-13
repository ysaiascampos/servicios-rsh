<?php

namespace App\Models;

use App\Constantes\Constante;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

/**
 * App\Models\Ficha
 *
 * @property int $FICHA_ID
 * @property int $FICHA_PERSONA
 * @property string|null $FICHA_TUTORLEGAL
 * @property int|null $FICHA_PUEBLOINDIGENA
 * @property int|null $FICHA_NUMPAREJA
 * @property int|null $FICHA_INGRESOANUAL
 * @property int|null $FICHA_INGRESOJUBILACION
 * @property int|null $FICHA_INGRESOOTROS
 * @property int|null $FICHA_FECHAAPLICACION
 * @property int|null $FICHA_FECHAREVISION
 * @property int|null $FICHA_PENSIONADMIN
 * @property int|null $FICHA_DIFICVISUAL
 * @property int|null $FICHA_DIFICAUDITIVA
 * @property int|null $FICHA_DIFICHABLA
 * @property int|null $FICHA_DIFICFISICA
 * @property int|null $FICHA_DIFICMENTAL
 * @property int|null $FICHA_DIFICPSIQUICA
 * @property int|null $FICHA_CALLESINAYUDA
 * @property int|null $FICHA_MEDICOSINAYUDA
 * @property int|null $FICHA_ASEOSINAYUDA
 * @property int|null $FICHA_DESPLAZAMIENTOENCASA
 * @property int|null $FICHA_CTRLESFINTERES
 * @property int|null $FICHA_ASISTEESTABEDUCACIONAL
 * @property int|null $FICHA_CURSO
 * @property int|null $FICHA_TIPOEDUCACION
 * @property int|null $FICHA_TRABAJA
 * @property int|null $FICHA_OCUPACIONACTUAL
 * @property int|null $FICHA_PUNTAJE
 * @property int|null $FICHA_FECHAFUENTE
 * @property int|null $FICHA_FECHAACTUALIZACION
 * @property int|null $FICHA_VIGENTE
 * @property string|null $FICHA_FECHAEXPIRACION
 * @property int|null $FICHA_IDSOLICITUD
 * @property int|null $FICHA_CALCULOACTIVO
 * @property int $FICHA_FUENTE
 * @property int|null $FICHA_NOASISTEMOTIVO
 * @property int|null $FICHA_HORASTRABAJO
 * @property int|null $FICHA_BUSCOTRABAJO
 * @property int|null $FICHA_NOBUSCOTRABAJOMOTIVO
 * @property int|null $FICHA_CONDICIONDISCAPACIDAD
 * @property int|null $FICHA_CODIGOSRAMA
 * @property int|null $FICHA_TEMPORALIDADPERMANENCIA
 * @property int|null $FICHA_CODIGOCONTRATO
 * @property int|null $FICHA_RELACIONCONTRACTUAL
 * @property int|null $FICHA_INSTITUCIONALIZADO
 * @property int|null $FICHA_FCORREC_MAN
 * @property string|null $FICHA_DESC_CORREC
 * @method static Builder|Ficha newModelQuery()
 * @method static Builder|Ficha newQuery()
 * @method static Builder|Ficha query()
 * @method static Builder|Ficha whereFICHAASEOSINAYUDA($value)
 * @method static Builder|Ficha whereFICHAASISTEESTABEDUCACIONAL($value)
 * @method static Builder|Ficha whereFICHABUSCOTRABAJO($value)
 * @method static Builder|Ficha whereFICHACALCULOACTIVO($value)
 * @method static Builder|Ficha whereFICHACALLESINAYUDA($value)
 * @method static Builder|Ficha whereFICHACODIGOCONTRATO($value)
 * @method static Builder|Ficha whereFICHACODIGOSRAMA($value)
 * @method static Builder|Ficha whereFICHACONDICIONDISCAPACIDAD($value)
 * @method static Builder|Ficha whereFICHACTRLESFINTERES($value)
 * @method static Builder|Ficha whereFICHACURSO($value)
 * @method static Builder|Ficha whereFICHADESCCORREC($value)
 * @method static Builder|Ficha whereFICHADESPLAZAMIENTOENCASA($value)
 * @method static Builder|Ficha whereFICHADIFICAUDITIVA($value)
 * @method static Builder|Ficha whereFICHADIFICFISICA($value)
 * @method static Builder|Ficha whereFICHADIFICHABLA($value)
 * @method static Builder|Ficha whereFICHADIFICMENTAL($value)
 * @method static Builder|Ficha whereFICHADIFICPSIQUICA($value)
 * @method static Builder|Ficha whereFICHADIFICVISUAL($value)
 * @method static Builder|Ficha whereFICHAFCORRECMAN($value)
 * @method static Builder|Ficha whereFICHAFECHAACTUALIZACION($value)
 * @method static Builder|Ficha whereFICHAFECHAAPLICACION($value)
 * @method static Builder|Ficha whereFICHAFECHAEXPIRACION($value)
 * @method static Builder|Ficha whereFICHAFECHAFUENTE($value)
 * @method static Builder|Ficha whereFICHAFECHAREVISION($value)
 * @method static Builder|Ficha whereFICHAFUENTE($value)
 * @method static Builder|Ficha whereFICHAHORASTRABAJO($value)
 * @method static Builder|Ficha whereFICHAID($value)
 * @method static Builder|Ficha whereFICHAIDSOLICITUD($value)
 * @method static Builder|Ficha whereFICHAINGRESOANUAL($value)
 * @method static Builder|Ficha whereFICHAINGRESOJUBILACION($value)
 * @method static Builder|Ficha whereFICHAINGRESOOTROS($value)
 * @method static Builder|Ficha whereFICHAINSTITUCIONALIZADO($value)
 * @method static Builder|Ficha whereFICHAMEDICOSINAYUDA($value)
 * @method static Builder|Ficha whereFICHANOASISTEMOTIVO($value)
 * @method static Builder|Ficha whereFICHANOBUSCOTRABAJOMOTIVO($value)
 * @method static Builder|Ficha whereFICHANUMPAREJA($value)
 * @method static Builder|Ficha whereFICHAOCUPACIONACTUAL($value)
 * @method static Builder|Ficha whereFICHAPENSIONADMIN($value)
 * @method static Builder|Ficha whereFICHAPERSONA($value)
 * @method static Builder|Ficha whereFICHAPUEBLOINDIGENA($value)
 * @method static Builder|Ficha whereFICHAPUNTAJE($value)
 * @method static Builder|Ficha whereFICHARELACIONCONTRACTUAL($value)
 * @method static Builder|Ficha whereFICHATEMPORALIDADPERMANENCIA($value)
 * @method static Builder|Ficha whereFICHATIPOEDUCACION($value)
 * @method static Builder|Ficha whereFICHATRABAJA($value)
 * @method static Builder|Ficha whereFICHATUTORLEGAL($value)
 * @method static Builder|Ficha whereFICHAVIGENTE($value)
 * @mixin Eloquent
 */
class Ficha extends Eloquent
{
    protected $table = 'fichas';
    protected $primaryKey = 'FICHA_ID';
    public $timestamps = false;

    /**
     * @param $idPersona
     * @return Ficha|null
     * @throws Throwable
     */
    public static function findByIdPersona($idPersona): ?Ficha
    {
        try {
            $datosDeFichas = self::where('ficha_persona', $idPersona)
                ->where('ficha_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('ficha_vigente', Constante::VIGENTE_1_VIGENTE)
                ->first();
        } catch (Throwable $ex) {
            throw $ex;
        }
        return $datosDeFichas;
    }

    public static function findByIdSolicitud($idSolicitud)
    {
        return self::where('ficha_idsolicitud', $idSolicitud)
            ->first();
    }

    public static function findByIdPersonaIdSolicitud($idPersona, $idSolicitud)
    {
        return self::where('ficha_persona', $idPersona)
            ->where('ficha_idsolicitud', $idSolicitud)
            ->first();
    }

    public static function findDetallePersonaByIdSolicitudMontoMensual($idPersona, $idSolicitud, $tieneMontoMensual = false)
    {
        if ($tieneMontoMensual) {
            $salida = self::join('personas', 'ficha_persona', '=', 'per_id')
                ->join('ficha_monto_mensual', 'per_id', '=', 'mensual_id_persona')
                ->where('ficha_persona', $idPersona)
                ->where('ficha_idsolicitud', $idSolicitud)
                ->where('mensual_idsolicitud', $idSolicitud)
                ->first();
        } else {
            if(is_null($idSolicitud)){
                $salida = self::where('ficha_persona', $idPersona)
                    ->first();
            }
            else{
                $salida = self::where('ficha_persona', $idPersona)
                    ->first();
                if(!is_null($salida['FICHA_IDSOLICITUD'])){
                    $salida = self::where('ficha_persona', $idPersona)
                        ->where('ficha_idsolicitud', $idSolicitud)
                        ->first();
                }

            }

        }
        return $salida;
    }

    public static function findDetallePersonaByIdSolicitud($idSolicitud, $tieneMontoMensual = false)
    {
        if ($tieneMontoMensual) {
            $salida = self::join('personas', 'ficha_persona', '=', 'per_id')
                ->join('ficha_monto_mensual', 'per_id', '=', 'mensual_id_persona')
                ->where('ficha_idsolicitud', $idSolicitud)
                ->where('mensual_idsolicitud', $idSolicitud)
                ->first();
        } else {
            $salida = self::join('personas', 'ficha_persona', '=', 'per_id')
                ->where('ficha_idsolicitud', $idSolicitud)
                ->first();
        }
        return $salida;
    }

    public static function findDetallePersonaByIdPersona($idPersona, $tieneMontoMensual = false)
    {
        if ($tieneMontoMensual) {
            $salida = self::join('personas', 'ficha_persona', '=', 'per_id')
                ->join('ficha_monto_mensual', 'fichas.ficha_persona', '=', 'ficha_monto_mensual.mensual_id_persona')
                ->where('ficha_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('ficha_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('ficha_persona', $idPersona)
                ->first();
        } else {
            $salida = self::join('personas', 'ficha_persona', '=', 'per_id')
                ->where('ficha_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->where('ficha_vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('ficha_persona', $idPersona)
                ->first();
        }
        return $salida;
    }
}
