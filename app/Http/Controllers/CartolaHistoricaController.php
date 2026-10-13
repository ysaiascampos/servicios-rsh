<?php

namespace App\Http\Controllers;

use App\Clases\DatosCartolaHistorica;
use App\Models\LocacionComuna;
use App\Models\LogCartolaHistorica;
use App\Models\Usuario;
use App\Models\UsuarioComuna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;
use App\Sequence;
use App\Constantes\Constante;

class CartolaHistoricaController extends Controller
{
    public function descargarComposicionHistorica(Request $request)
    {
        try {

            $run            = $request->input('RUN');
            $dv             = $request->input('DV');
            $usuario        = $request->input('USUARIO');
            $inicio         = $request->input('INICIO');
            $termino        = $request->input('TERMINO');
            $nombreCompleto = $request->input('NOMBRE_COMPLETO');
            $tipoCartola = $request->input('CARTOLA');

            $runFormateado     = number_format((int)$run, 0, '', '.');
            $runCompleto       = $runFormateado . '-' . $dv;
            $periodoConsultado = $inicio . ' - ' . $termino;
            $fechaExtraccion   = now()->format('d/m/Y');
            $horaExtraccion  = now()->format('H:i:s');

            $datosCartola = new DatosCartolaHistorica();
            $paginas = $datosCartola->obtenerPaginas($run, $dv, $usuario, $inicio, $termino);

            if (empty($paginas)) {
                return response()->json(['error'    => false, 'sin_data' => true,
                    'mensaje'  => 'No se encontraron datos para el período ' . $inicio . ' - ' . $termino . '.'
                ], 200);
            }

            $nomusuario = Usuario::where( 'RUT_USUARIO', $usuario)->first();
            $nombreUsuario = null;

            if ($nomusuario) {
                $nombreUsuario = mb_strtoupper(
                    trim($nomusuario->NOMBRES_USUARIO . ' ' . $nomusuario->APAT_USUARIO . ' ' . $nomusuario->AMAT_USUARIO),
                    'UTF-8'
                );
            }
            
            $oUsuario = Usuario::where('RUT_USUARIO', $usuario)->first();
            $comuna = UsuarioComuna::where('RUT_USUARIO', $usuario)->value('COM_ID');
            $comunaUsuario = LocacionComuna::getNombreComuna($comuna);

            switch ($oUsuario->ID_PERFIL) {
                case Constante::PERFIL_2_ADMINISTRADOR_NACIONAL:
                case Constante::PERFIL_3_CONSULTA_NACIONAL:
                case Constante::PERFIL_6_COORDINADOR_NACIONAL:
                case Constante::PERFIL_18_NIVEL_CENTRAL:
                    $descripcionPerfil = "MINISTERIO DE DESARROLLO SOCIAL Y FAMILIA";
                    break;
                case Constante::PERFIL_14_CHILE_ATIENDE:
                case Constante::PERFIL_25_CHILE_ATIENDE_EJECUTOR:
                    $descripcionPerfil = "CHILE ATIENDE";
                    break;
                case Constante::PERFIL_29_FUNFA:
                    $descripcionPerfil = "FUNDACIÓN DE LA FAMILIA";
                    break;
                default:
                    $descripcionPerfil = "MUNICIPALIDAD DE "  . $comunaUsuario;
                    break;
            }

            $this->insertLogCartolaHistorica(
                $comuna,
                $run,
                $tipoCartola,
                $inicio,
                $termino,
                $usuario,
                $nombreUsuario,
                $nomusuario->ID_PERFIL
            );

            $pdf = PDF::loadView('cartola-historica.CartolaHistorica', [
                'paginas'           => $paginas,
                'nombreCompleto'    => $nombreCompleto,
                'runCompleto'       => $runCompleto,
                'periodoConsultado' => $periodoConsultado,
                'fechaExtraccion'   => $fechaExtraccion,
                'horaExtraccion'   => $horaExtraccion,
                'nombreUsuario'     => $nombreUsuario,
                'comunaUsuario'     => $descripcionPerfil,
            ]);

            $pdf->setOption('enable-local-file-access', true);

            return response()->json([
                'error'      => false,
                'pdf_base64' => base64_encode($pdf->output())
            ], 200);

        } catch (\Exception $e) {

            Log::error(
                'ERROR PDF COMPLEJO: ' .
                $e->getMessage() .
                ' en línea ' .
                $e->getLine()
            );

            return response()->json([
                'error'   => true,
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    private function insertLogCartolaHistorica($comuna, $run, $cartola, $inicio, $termino, $usuario, $nombreUsuario, $perfil)
    {
        try {
            $log = new LogCartolaHistorica();
            $log->LOG_ID = Sequence::nextValue('SEQ_LOG_CARTOLA');
            $log->USUARIO_RUN = $usuario;
            $log->USUARIO_NOMBRE = $nombreUsuario;
            $log->USUARIO_PERFIL = $perfil;
            $log->COMUNA_ID = $comuna;
            $log->CIUDADANO_RUN = $run;
            $log->MODALIDAD = $cartola == 1 ? 'POR PERIODO' : 'COMPLETA';
            $log->ANIO_INICIO = $cartola == 1 ? $inicio : null;
            $log->ANIO_TERMINO = $cartola == 1 ? $termino : null;
            $log->save();
        } catch (\Exception $eLog) {
            Log::warning('No se pudo registrar log de cartola: ' . $eLog->getMessage());
        }
    }
}
