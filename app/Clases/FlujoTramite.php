<?php

namespace App\Clases;

use App\Constantes\Constante;
use App\Exceptions\ExcepcionEjecucion;
use App\Exceptions\ExcepcionReglaNegocio;
use App\Exceptions\ExcepcionValidacion;
use App\Models\Solicitud;

abstract class FlujoTramite
{
    /**
     * Claves utilizadas en el array de flujo
     */
    const METODO = 'metodo';
    const ESTADO = 'estado';
    const CONDICION_CANAL = 'condicion.canal';
    const CONDICION_ORIGEN_REGISTRO = 'condicion.origenRegistro';

    /**
     * @var array[][] Flujo para la solicitud
     */
    protected $flujo;

    /**
     * @var Solicitud
     */
    public $solicitud;

    protected $canal;
    protected $origenRegistro;
    protected $accion;

    public abstract function editarSolicitud();

    public abstract function enDigitacion();

    public abstract function pendienteDocumentos();

    public abstract function pendienteResolver();

    public abstract function devolverGestion();

    public abstract function aprobar();

    public abstract function desistir();

    public abstract function rechazar();

    public function seguirFlujo()
    {
        $estadoActual = Constante::ESTADO_SOLICITUD_FICTICIO_CREACION_SOLICITUD;
        if (!empty($this->solicitud)) {
            $estadoActual = $this->solicitud->ID_ESTADO;
        }

        if (empty($this->accion)) {
            throw ExcepcionValidacion::new('Debe especificar una acción')
                ->setOrigen('accion');
        }

        $estaEnEstadoTramitable = array_key_exists($estadoActual, $this->flujo);
        if (!$estaEnEstadoTramitable) {
            throw new ExcepcionReglaNegocio('La solicitud no se encuentra en estado tramitable',
                Constante::ERR_NEGOCIO_SOLICITUD_NO_TRAMITABLE);
        }

        $accionCorrespondeAEstado = array_key_exists($this->accion, $this->flujo[$estadoActual]);
        if (!$accionCorrespondeAEstado) {
            throw ExcepcionValidacion::new('Acción no permitida')
                ->setOrigen('accion');
        }

        $tieneCondicionParaCanal = array_key_exists(self::CONDICION_CANAL, $this->flujo[$estadoActual][$this->accion]);
        if ($tieneCondicionParaCanal) {
            $canalCumpleCondicion = in_array(
                Constante::$CANAL_VALIDO_PARA_ARRAY_FLUJO[$this->canal],
                $this->flujo[$estadoActual][$this->accion][self::CONDICION_CANAL]
            );
            if (!$canalCumpleCondicion) {
                throw ExcepcionValidacion::new('Canal no permitido')
                    ->setOrigen('canal');
            }
        }

        $tieneCondicionParaOrigenRegistro = array_key_exists(
            self::CONDICION_ORIGEN_REGISTRO,
            $this->flujo[$estadoActual][$this->accion]
        );
        if ($tieneCondicionParaOrigenRegistro) {
            $origenRegistroCumpleCondicion = in_array(
                strtolower($this->origenRegistro),
                $this->flujo[$estadoActual][$this->accion][self::CONDICION_ORIGEN_REGISTRO]
            );
            if (!$origenRegistroCumpleCondicion) {
                throw ExcepcionValidacion::new('Origen de registro no permitido')
                    ->setOrigen('origen-registro');
            }
        }

        $metodoParaSeguirFlujo = $this->flujo[$estadoActual][$this->accion][self::METODO];
        if (method_exists($this, $metodoParaSeguirFlujo)) {
            $this->$metodoParaSeguirFlujo();
        } else {
            throw ExcepcionEjecucion::new('El método ' . get_class($this) . '::' . $metodoParaSeguirFlujo . '() no está definido')
                ->setContexto(['estadoActual' => $estadoActual, 'accion' => $this->accion]);
        }
        return $this;
    }

}
