<?php


namespace App\Servicios\ReglasCuidadores;


use App\Constantes\Constante;
use Closure;

class ValidarIgualCuidadorCuidadoRsh
{

    private $consultaCuidado;

    public function __construct()
    {
        $this->consultaCuidado = new ConsultaCuidadoresCuidado();
    }

    public function handle($request, Closure $next)
    {
        // Se compara la lista de los cuidadores y la lista de dependiente, si hay uno que coincida de agrega a la lista
        $request['tramite_diada'] = true; // se ingresa por defecto el valor si puede hacer tramite

        $request['cuidadores_dependiente'] = array(); // inicialización del array vacio

        $lista_cuidadores = isset($request[Constante::DATOS_DEPENDIENTE][Constante::DEPENDIENTE_LISTA_CUIDADOR])
            ? $request[Constante::DATOS_DEPENDIENTE][Constante::DEPENDIENTE_LISTA_CUIDADOR]
            : array();
        $lista_dependiente = isset($request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_DEPENDIENTE])
            ? $request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_DEPENDIENTE]
            : array();

        if (!empty($lista_cuidadores) && !empty($lista_dependiente)) {
            // Usar array_filter para encontrar coincidencias en run_cuidador y run_dependiente
            $resultado_combinado = array_map(function ($dependiente) use ($lista_cuidadores) {
                // Filtrar para encontrar el cuidador que coincida con run_cuidador y run_dependiente
                $cuidador = array_filter($lista_cuidadores, function ($cuidador) use ($dependiente) {
                    return $cuidador['run_cuidador'] === $dependiente['run_cuidador'] &&
                        $cuidador['run_dependiente'] === $dependiente['run_dependiente'];
                });

                // Si encontramos un cuidador coincidente, extraer los campos que nos interesan
                if (!empty($cuidador)) {
                    return $dependiente;
                }
                return null; // Si no hay coincidencia, retornamos null para que no se agregue al resultado
            }, $lista_dependiente);

            // Eliminar los valores nulos del resultado
            $resultado_combinado = array_filter($resultado_combinado);
            // sacos los indices para dejarlo normalizado
            $resultado_combinado = array_values($resultado_combinado);

            // primer filtro: saber si ya hay una solicitud hecha anteiormente en sistema de cuidador y cuidado, esto sirve solamente
            // cuando se pregunta al mismo tienmpo por cuidador y cuidado
            if(count($resultado_combinado) > 0){
               if( isset($request["motivos_bloqueo"])){
                    $mensaje = Constante::$bloqueosGloblalesCuidadores[9];
                    if (!in_array($mensaje,$request["motivos_bloqueo"])) {
                        $request["motivos_bloqueo"] = $mensaje;
                    }
                    $request['tramite_diada'] = false; // se ingresa por defecto el valor si puede hacer tramite
                }


               if(!$resultado_combinado[0]['solicitud_en_tramite']){
                   #Agregar mensaje a casa dato del bloque cuidadores

                   $resultadoCuidadores = $this->consultaCuidado->bloqueosDeadasRepetidas($lista_cuidadores,$resultado_combinado);
                   $request[Constante::DATOS_DEPENDIENTE][Constante::DEPENDIENTE_LISTA_CUIDADOR] = $resultadoCuidadores;

                   $resultadoDependiente = $this->consultaCuidado->bloqueosDeadasRepetidas($lista_dependiente,$resultado_combinado);
                   $request[Constante::DOTOS_CUIDADOR][Constante::CUIDADOR_LISTA_DEPENDIENTE] = $resultadoDependiente;



               }

            }

            $request['cuidadores_dependiente'] = $resultado_combinado;

        }
        return $next($request);
    }

}
