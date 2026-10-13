<?php

namespace App\Http\Inputs;

use App\Constantes\Constante;

class ValidacionTerceroTipoSolicitudInput extends ServiceInput
{
    protected function define()
    {
        //$this->removerCamposNull = true;

        $this->campos = [
            Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO =>
                'object:' . ValidacionTerceroTipoSolicitudBodyInput::class,
            Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO =>
                'object:' . ValidacionTerceroTipoSolicitudBodyInput::class,
            Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE =>
                'object:' . ValidacionTerceroTipoSolicitudBodyInput::class,
            Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE =>
                'object:' . ValidacionTerceroTipoSolicitudBodyInput::class,
            Constante::TIPO_SOLICITUD_5_ACTUALIZACION_DE_MODULO_SALUD =>
                'object:' . ValidacionTerceroTipoSolicitudBodyInput::class,
            Constante::TIPO_SOLICITUD_6_ACTUALIZACION_DE_MODULO_EDUCACION =>
                'object:' . ValidacionTerceroTipoSolicitudBodyInput::class,
            Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA =>
                'object:' . ValidacionTerceroTipoSolicitudBodyInput::class,
            Constante::TIPO_SOLICITUD_14_ACTUALIZACION_OCUPACION_DE_INGRESOS =>
                'object:' . ValidacionTerceroTipoSolicitudBodyInput::class,
            Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR =>
                'object:' . ValidacionTerceroTipoSolicitudBodyInput::class,
        ];
    }

    public function getTipo()
    {
        // considera solo una solicitud por consumo
        return key(array_filter($this->attributes));
    }
}
