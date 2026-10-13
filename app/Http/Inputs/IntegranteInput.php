<?php


namespace App\Http\Inputs;


use App\Constantes\Constante;
use App\Exceptions\ExcepcionConsistencia;
use App\Exceptions\ExcepcionValidacion;
use App\Models\Personas;
use App\Utiles;

abstract class IntegranteInput extends ServiceInput
{
    public $id;
    public $edad;
    public $estadocivil;

    public function __construct(array $arrayInput, $defaultValue = null)
    {
        parent::__construct($arrayInput, $defaultValue);
        $this->setInfoIntegrante();
    }

    /**
     * Trae la info del integrante desde tabla Persona
     * (edad, estado civil, run, per_id)
     */
    public function setInfoIntegrante()
    {
//        $persona = Personas::select(['per_id', 'per_run', 'per_fechanacimiento', 'per_estadocivil'])
//            ->where('per_vigente', Constante::VIGENTE_1_VIGENTE)
//            ->where('per_calculoactivo', Constante::CALCULO_ACTIVO_1_APROBADO);
//        if (!is_null($this->id)) {
//            $persona = $persona->where('per_id', $this->attributes['id']);
//        } elseif (!is_null($this->runTitular)) {
//            $persona = $persona->where('per_run', $this->attributes['run-titular']);
//        } else {
//            throw ExcepcionValidacion::new('No se ha especificado el run del titular')
//                ->setOrigen('run-titular');
//        }
//
//        $persona = $persona->first();
        $persona = Utiles::getPersona($this->attributes['run-titular']);
        if (empty($persona) || is_numeric($persona)) {
            throw new ExcepcionConsistencia(
                'El integrante ' . $this->id . ' no existe en la base datos',
                Constante::ERR_CONSISTENCIA_REGISTRO_NO_EXISTE
            );
        }
        $this->runTitular = $persona->PER_RUN;
        $this->id = $persona->PER_ID;
        $this->edad = Utiles::getEdad($persona->PER_FECHANACIMIENTO);
        $this->estadocivil = $persona->PER_ESTADOCIVIL;
    }
}
