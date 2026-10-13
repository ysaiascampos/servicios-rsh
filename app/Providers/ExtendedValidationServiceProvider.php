<?php

namespace App\Providers;

use App\Utiles;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as Validador;
use Illuminate\Support\ServiceProvider;

class ExtendedValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        Validator::extend('run', function ($attribute, $value, $parameters, $validator) {
            return Utiles::validaRUN($value);
        });

        // validaciones estendidas para clases input.
        Validator::extend('object', function ($attribute, $value, $parameters, $validator) {
            if (empty($value)) {
                return true;
            }
            if (is_numeric($attribute)) {
                $parameters = ExtendedValidationServiceProvider::obtenerParametroCorrecto($validator, $attribute,
                    'object');
            }
            $class = $parameters[0];
            if (is_array($value)) {
                foreach ($value as $object) {
                    if (!($object instanceof $class)) {
                        return false;
                    }
                }
                return true;
            } else {
                return $value instanceof $class;
            }
        });

        Validator::extend('uppercase', function ($attribute, $value, $parameters, $validator) {
            return !(bool)preg_match('/[a-z]/', $value);  // a diferencia de ctype_upper() si permite espacios y números
        });

        Validator::extend('lowercase', function ($attribute, $value, $parameters, $validator) {
            return !(bool)preg_match('/[A-Z]/', $value);  // a diferencia de ctype_lower() si permite espacios y números
        });


    }

    /**
     * Corrige el error producido debido a que Laravel reordena los campos numericamente cuando estos campos tienen
     * nombres (claves) numericos
     * @param Validador $validador
     * @param string $atributo
     * @param string $regla
     * @return array
     */
    public static function obtenerParametroCorrecto(Validador $validador, string $atributo, string $regla): array
    {
        $parametros = [];
        $reglasOriginales = null;
        $nombrePropiedadReglasOriginales = 'initialRules';
        $validadorComoArray = (array)$validador;
        $largoNombrePropiedad = strlen($nombrePropiedadReglasOriginales);
        foreach ($validadorComoArray as $nombrePropiedad => $valorPropiedad) {
            if (substr($nombrePropiedad, -$largoNombrePropiedad) === $nombrePropiedadReglasOriginales) {
                $reglasOriginales = $valorPropiedad;
                break;
            }
        }
        $definicion = $reglasOriginales[$atributo];
        $listadoReglas = explode('|', $definicion);
        foreach ($listadoReglas as $reglaActual) {
            if (strpos($reglaActual, $regla) !== false) {
                $elementosRegla = explode(':', $reglaActual);
                $parametros = explode(',', $elementosRegla[1]);
                break;
            }
        }
        return $parametros;
    }
}
