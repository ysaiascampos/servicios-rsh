<?php

namespace App\Clases;
use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Models\Sii;
use App\Sequence;
use Illuminate\Support\Facades\DB;
use App\Models\PersonasExport;

class ActualizacionIngresoCapital extends ActualizacionIngreso
{
    public $tipoSolicitud = Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL;
    public $nombreSolicitud = 'Solicitud de Actualización de ingreso de capital';

    public function editarSolicitud()
    {
        $fuenteCapital = $this->obtenerFuente();
        if (!in_array($fuenteCapital,$this->fuentes['solicitudes'][Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL]['fuentes'])) {
            throw new ExcepcionReglaNegocio('No se encontro fuente de ingreso valida para este tramite',Constante::ERR_NEGOCIO_FUENTE_NO_VALIDA);
        }
        $this->verificarFirmaSimple();
        $this->guardarSolicitud([]);
        $this->generarPdfFirmaSimple();
    }
    protected function guardarSolicitud($data)
    {
        DB::beginTransaction();
        try {
            $nuevoFolio = $this->obtenerNuevoFolio();
            $datoNuevo = $nuevoFolio;
            $datoActual = $nuevoFolio;

            $this->obtenerRegistroSolicitud($datoActual, $datoNuevo, $nuevoFolio);

            $this->solicitud->ID_SUB_TIPO_SOLICITUD = Constante::SUB_TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL_1_INGRESO_DE_CAPITAL;
            if(is_null($this->solicitud->ID_FUENTE_ACTUAL)){
                $this->solicitud->ID_FUENTE_ACTUAL = 20; // debería ser Constante::FUENTE_200_INGRESO_DE_CAPITAL, pero 20 está de tiempos preteritos
                $this->solicitud->RUN_INTEGRANTE = $this->titular->PER_RUN;
            }

            $this->solicitud->AVISO_CORREO = Constante::AVISO_CORREO_1;
            $this->solicitud->save();

            $registroSii = $this->obtenerRegistroSII();
            $registroSii->SII_F22_ID_I = Sequence::nextValue('seq_rsh_ingreso');
            $registroSii->IDSOLICITUD = $this->solicitud->ID_SOLICITUD;
            $registroSii->save();

            DB::commit();
            $this->configurarCargaInformacionDeTramite();
        } catch (Throwable $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    protected function cargarDataTramite()
    {
        $this->ingresos = Sii::where('idsolicitud', $this->solicitud->ID_SOLICITUD)
            ->select('SII_F22_ID_I', 'KAPITAL_SUM_COMPL')
            ->first();

        if ($this->ingresos) {
            $this->ingreso['capital'] = $this->ingresos->KAPITAL_SUM_COMPL;
        } else {
            throw new ExcepcionConsistencia('Error administrativo, no se encontró la información del sii para poder realizar este trámite.',Constante::ERR_CONSISTENCIA_FUENTE_NO_EXISTE);
        }
    }
    protected function obtenerRegistroSII()
    {
        if (isset($this->ingresos)) { // PUT
            $nuevaFilaCapital = $this->ingresos->first();
        } else { // POST
            $filaCapital = Sii::where('rut', $this->titular->PER_RUN)
                ->where('vigente', Constante::VIGENTE_1_VIGENTE)
                ->where('calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO)
                ->first();

            if (!($filaCapital)) {
                throw new ExcepcionConsistencia('Error administrativo, no se encontró la información.',Constante::ERR_CONSISTENCIA_REGISTRO_NO_VIGENTE);
            }

            $nuevaFilaCapital = new Sii();
            $nuevaFilaCapital->RUT = $this->titular->PER_RUN;
            $nuevaFilaCapital->ANNO_COMERCIAL = $filaCapital->ANNO_COMERCIAL;
            $nuevaFilaCapital->ANNIO_TRIBUTARIO = $filaCapital->ANNIO_TRIBUTARIO;
            $nuevaFilaCapital->ING_ALTOS_FLAG = $filaCapital->ING_ALTOS_FLAG;
            $nuevaFilaCapital->KAPITAL_FLAG = '1';
            $nuevaFilaCapital->DJ_1887_FLAG = $filaCapital->DJ_1887_FLAG;
            $nuevaFilaCapital->F22_161_FLAG = $filaCapital->F22_161_FLAG;
            $nuevaFilaCapital->F22_110_FLAG = $filaCapital->F22_110_FLAG;
            $nuevaFilaCapital->DJ_1879_FLAG = $filaCapital->DJ_1879_FLAG;
            $nuevaFilaCapital->DJ_1812_PENS_FLAG = $filaCapital->DJ_1812_PENS_FLAG;
            $nuevaFilaCapital->KAPITAL_SUM = $filaCapital->KAPITAL_SUM;
            $nuevaFilaCapital->PENSION_DJ1812_SUM = $filaCapital->PENSION_DJ1812_SUM;
            $nuevaFilaCapital->ING_INDEPENDIENTE_SUM = $filaCapital->ING_INDEPENDIENTE_SUM;
            $nuevaFilaCapital->ING_DEPENDIENTE_SUM = $filaCapital->ING_DEPENDIENTE_SUM;
            $nuevaFilaCapital->MES_1_COMPL_DJ1812 = $filaCapital->MES_1_COMPL_DJ1812;
            $nuevaFilaCapital->MES_2_COMPL_DJ1812 = $filaCapital->MES_2_COMPL_DJ1812;
            $nuevaFilaCapital->MES_3_COMPL_DJ1812 = $filaCapital->MES_3_COMPL_DJ1812;
            $nuevaFilaCapital->MES_4_COMPL_DJ1812 = $filaCapital->MES_4_COMPL_DJ1812;
            $nuevaFilaCapital->MES_5_COMPL_DJ1812 = $filaCapital->MES_5_COMPL_DJ1812;
            $nuevaFilaCapital->MES_6_COMPL_DJ1812 = $filaCapital->MES_6_COMPL_DJ1812;
            $nuevaFilaCapital->MES_7_COMPL_DJ1812 = $filaCapital->MES_7_COMPL_DJ1812;
            $nuevaFilaCapital->MES_8_COMPL_DJ1812 = $filaCapital->MES_8_COMPL_DJ1812;
            $nuevaFilaCapital->MES_9_COMPL_DJ1812 = $filaCapital->MES_9_COMPL_DJ1812;
            $nuevaFilaCapital->MES_10_COMPL_DJ1812 = $filaCapital->MES_10_COMPL_DJ1812;
            $nuevaFilaCapital->MES_11_COMPL_DJ1812 = $filaCapital->MES_11_COMPL_DJ1812;
            $nuevaFilaCapital->MES_12_COMPL_DJ1812 = $filaCapital->MES_12_COMPL_DJ1812;
            $nuevaFilaCapital->MES_1_COMPL_INDEP = $filaCapital->MES_1_COMPL_INDEP;
            $nuevaFilaCapital->MES_2_COMPL_INDEP = $filaCapital->MES_2_COMPL_INDEP;
            $nuevaFilaCapital->MES_3_COMPL_INDEP = $filaCapital->MES_3_COMPL_INDEP;
            $nuevaFilaCapital->MES_4_COMPL_INDEP = $filaCapital->MES_4_COMPL_INDEP;
            $nuevaFilaCapital->MES_5_COMPL_INDEP = $filaCapital->MES_5_COMPL_INDEP;
            $nuevaFilaCapital->MES_6_COMPL_INDEP = $filaCapital->MES_6_COMPL_INDEP;
            $nuevaFilaCapital->MES_7_COMPL_INDEP = $filaCapital->MES_7_COMPL_INDEP;
            $nuevaFilaCapital->MES_8_COMPL_INDEP = $filaCapital->MES_8_COMPL_INDEP;
            $nuevaFilaCapital->MES_9_COMPL_INDEP = $filaCapital->MES_9_COMPL_INDEP;
            $nuevaFilaCapital->MES_10_COMPL_INDEP = $filaCapital->MES_10_COMPL_INDEP;
            $nuevaFilaCapital->MES_11_COMPL_INDEP = $filaCapital->MES_11_COMPL_INDEP;
            $nuevaFilaCapital->MES_12_COMPL_INDEP = $filaCapital->MES_12_COMPL_INDEP;
            $nuevaFilaCapital->MES_1_COMPL_DEP = $filaCapital->MES_1_COMPL_DEP;
            $nuevaFilaCapital->MES_2_COMPL_DEP = $filaCapital->MES_2_COMPL_DEP;
            $nuevaFilaCapital->MES_3_COMPL_DEP = $filaCapital->MES_3_COMPL_DEP;
            $nuevaFilaCapital->MES_4_COMPL_DEP = $filaCapital->MES_4_COMPL_DEP;
            $nuevaFilaCapital->MES_5_COMPL_DEP = $filaCapital->MES_5_COMPL_DEP;
            $nuevaFilaCapital->MES_6_COMPL_DEP = $filaCapital->MES_6_COMPL_DEP;
            $nuevaFilaCapital->MES_7_COMPL_DEP = $filaCapital->MES_7_COMPL_DEP;
            $nuevaFilaCapital->MES_8_COMPL_DEP = $filaCapital->MES_8_COMPL_DEP;
            $nuevaFilaCapital->MES_9_COMPL_DEP = $filaCapital->MES_9_COMPL_DEP;
            $nuevaFilaCapital->MES_10_COMPL_DEP = $filaCapital->MES_10_COMPL_DEP;
            $nuevaFilaCapital->MES_11_COMPL_DEP = $filaCapital->MES_11_COMPL_DEP;
            $nuevaFilaCapital->MES_12_COMPL_DEP = $filaCapital->MES_12_COMPL_DEP;
            $nuevaFilaCapital->VIGENTE = Constante::VIGENTE_2_PENDIENTE;
            $nuevaFilaCapital->FECHAEXPIRACION = $filaCapital->MES_12_COMPL_DEP;
            $nuevaFilaCapital->CALCULOACTIVO = $filaCapital->CALCULOACTIVO;
            $nuevaFilaCapital->FUENTE = Constante::FUENTE_INGRESO_100_SOLICITADO_POR_SISTEMA_WEB;
            $nuevaFilaCapital->FECHAFUENTE = $filaCapital->FECHAFUENTE;
        }

        $nuevaFilaCapital->KAPITAL_SUM_COMPL = $this->input->capital;

        return $nuevaFilaCapital;
    }

    protected function obtenerFuente()
    {
        $fuente = PersonasExport::where('per_run', $this->titular->PER_RUN)
            ->select('calc_ingreso_capital')
            ->first();
        if (is_null($fuente)) {
            throw new ExcepcionConsistencia('La solicitud no registra la fuente.',Constante::ERR_CONSISTENCIA_FUENTE_NO_EXISTE);
        }
        return $fuente->CALC_INGRESO_CAPITAL > 0 ? 200 : 0;
    }

    protected function obtenerNombreTipoFuente($indice)
    {
        return 'capital';
    }
}
