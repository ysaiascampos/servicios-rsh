<?php


namespace App\Clases;


use App\Constantes\Constante;
use Illuminate\Support\Facades\File;
use PDF;
use View;
use Response;

class FormularioSolicitud
{
    private $data;
    private $options;


    public function __construct($portal, Tramite $tramite)
    {
        $this->data = [
            'tramite' => $tramite,
            'portal' => $portal
        ];

        $this->options = [
            'orientation'   => 'portrait',
            'encoding'      => 'UTF-8',
            'page-size'     => 'letter',
            'margin-top'    => '40mm',
            'margin-right'  => '15mm',
            'margin-bottom' => '20mm',
            'margin-left'   => '15mm',
            'image-quality'=>'98',
            'footer-right' => 'Hoja [page] de [toPage]'
        ];

        switch ($tramite->tipoSolicitud) {
            case Constante::TIPO_SOLICITUD_1_INGRESO_AL_REGISTRO:
                $this->data['vista'] = 'sol01_ingreso';
                break;
            case Constante::TIPO_SOLICITUD_2_CAMBIO_DE_DOMICILIO:

                break;
            case Constante::TIPO_SOLICITUD_3_INCORPORACION_DE_NUEVO_INTEGRANTE:
                $this->data['vista'] = 'sol03_incorporacion';
                break;
            case Constante::TIPO_SOLICITUD_4_DESVINCULACION_DE_INTEGRANTE:
                $this->data['vista'] = 'sol04_desvinculacion';
                break;
            case Constante::TIPO_SOLICITUD_5_ACTUALIZACION_DE_MODULO_SALUD:
                $this->data['vista'] = 'sol05_act_modulo_salud';
                break;
            case Constante::TIPO_SOLICITUD_6_ACTUALIZACION_DE_MODULO_EDUCACION:
                $this->data['vista'] = 'sol06_act_modulo_educacion';
                break;
            case Constante::TIPO_SOLICITUD_7_ACTUALIZACION_DE_MODULO_VIVIENDA:
                $this->data['vista'] = 'sol07_act_modulo_vivienda';
                break;
            case Constante::TIPO_SOLICITUD_14_ACTUALIZACION_OCUPACION_DE_INGRESOS:
                $this->data['vista'] = 'sol14_act_modulo_ocupacion';
                break;
            case Constante::TIPO_SOLICITUD_17_COMPLEMENTO_COLEGIO_PARTICULAR_PAGADO:
            case Constante::TIPO_SOLICITUD_18_RECTIFICACION_INGRESO:
            case Constante::TIPO_SOLICITUD_20_COMPLEMENTO_DE_SALUD:
            case Constante::TIPO_SOLICITUD_21_RECTIFICACION_DE_MODIFICACION_DE_COLEGIO:
            case Constante::TIPO_SOLICITUD_22_RECTIFICACION_DE_ELIMINACION_DE_COLEGIO:
            case Constante::TIPO_SOLICITUD_23_RECTIFICACION_DE_MODIFICACION_DE_VEHICULO:
            case Constante::TIPO_SOLICITUD_24_RECTIFICACION_DE_ELIMINACION_DE_VEHICULO:
            case Constante::TIPO_SOLICITUD_25_RECTIFICACION_DE_ELIMINACION_VIVIENDA:
            case Constante::TIPO_SOLICITUD_26_RECTIFICACION_DE_MODIFICACION_DE_ISAPRE:
            case Constante::TIPO_SOLICITUD_27_RECTIFICACION_DE_ELIMINACION_DE_ISAPRE:
            case Constante::TIPO_SOLICITUD_30_SOLICITUD_DE_INGRESO_PARA_INSTITUCIONALIZADOS:
                $this->data['vista'] = 'sol30_insitucionalizados';
                break;
            case Constante::TIPO_SOLICITUD_50_COMPLEMENTO_POR_PENSION_DE_ALIMENTOS:
                $this->data['vista'] = 'sol50_pension_alimentos';
                break;
            case Constante::TIPO_SOLICITUD_51_COMPLEMENTO_DE_COTIZACION_SALUD:
                $this->data['vista'] = 'sol51_comp_salud';
                break;
            case Constante::TIPO_SOLICITUD_52_COMPLEMENTO_POR_PAGO_DE_ARANCEL_DE_ESTABLECIMIENTO_EDUCACIONAL:
                $this->data['vista'] = 'sol52_comp_establecimiento';
                break;
            case Constante::TIPO_SOLICITUD_53_COMPLEMENTO_POR_PROPIEDAD_COMPARTIDA_DE_BIEN_RAIZ:
                $this->data['vista'] = 'sol53_comp_raiz';
                break;
            case Constante::TIPO_SOLICITUD_54_COMPLEMENTO_POR_COMPENSACION_ECONOMICA:
                $this->data['vista'] = 'sol54_comp_economica';
                break;
            case Constante::TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES:
                $this->data['vista'] = 'sol55_cuidadores';
                break;
            case Constante::TIPO_SOLICITUD_81_COMPLEMENTO_DE_SOCIEDAD_SIN_LUCRO:
                $this->data['vista'] = 'sol81_comp_raiz_sin_lucro';
                break;
            case Constante::TIPO_SOLICITUD_60_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE:
                $this->data['vista'] = 'sol60_dependiente';
                break;
            case Constante::TIPO_SOLICITUD_61_ACTUALIZACION_INGRESO_TRABAJADOR_INDEPENDIENTE:
                $this->data['vista'] = 'sol61_independiente';
                break;
            case Constante::TIPO_SOLICITUD_62_ACTUALIZACION_INGRESO_TRABAJADOR_DEPENDIENTE_E_INDEPENDIENTE:
                $this->data['vista'] = 'sol62_dependiente_independiente';
                break;
            case Constante::TIPO_SOLICITUD_63_ACTUALIZACION_DE_INGRESOS_MONETARIOS_POR_PENSION_Y_O_JUBILACION:
                $this->data['vista'] = 'sol63_pension_jubilacion';
                break;
            case Constante::TIPO_SOLICITUD_64_ACTUALIZACION_DE_INGRESOS_DE_CAPITAL:
                $this->data['vista'] = 'sol64_capital';
                break;
            case Constante::TIPO_SOLICITUD_65_ACTUALIZACION_DE_COTIZACIONES_DE_SALUD:
                $this->data['vista'] = 'sol65_act_cotizacion_salud';
                break;
            case Constante::TIPO_SOLICITUD_66_ACTUALIZACION_DE_MATRICULA_EN_ESTABLECIMIENTO_EDUCACIONAL:
                $this->data['vista'] = 'sol66_act_establecimiento';
                break;
            case Constante::TIPO_SOLICITUD_67_ACTUALIZACION_DE_PROPIEDAD_DE_BIEN_RAIZ:
                $this->data['vista'] = 'sol67_act_raiz';
                break;
            case Constante::TIPO_SOLICITUD_68_ACTUALIZACION_DE_PROPIEDAD_DE_AUTOMOVIL:
                $this->data['vista'] = 'sol68_act_automovil';
                break;
            case Constante::TIPO_SOLICITUD_69_ACTUALIZACION_DE_INFORMACION_DE_EDUCACION_SUPERIOR:
                $this->data['vista'] = 'sol69_act_educacion';
                break;
            case Constante::TIPO_SOLICITUD_79_ACTUALIZACION_DE_PARTICIPACION_JURIDICA:
                $this->data['vista'] = 'sol79_act_juridico';
                break;
            case Constante::TIPO_SOLICITUD_100_CAMBIO_JEFE_DE_HOGAR:
                $this->data['vista'] = 'sol100_jefe_hogar';
                break;
            case Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR:
                $this->data['vista'] = 'sol101_desvinculacion_jefe';
                break;
            case Constante::TIPO_SOLICITUD_103_SOLICITUD_DE_SUPERVISION:
                $this->data['vista'] = 'sol103_supervision';
                break;
            case Constante::TIPO_SOLICITUD_202_CAMBIO_DE_DOMICILIO_Y_VIVIENDA:
                $this->data['vista'] = 'sol202_act_domicilio';
                break;
            case Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA:
                $this->data['vista'] = 'sol206_ingreso_nna';
                break;
            default:
                $this->data['vista'] = 'error';
                break;
        }
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function descargarFormulario()
    {
        $nombre = 'formulario_' . $this->data['tramite']->tipoSolicitud . '_' . date('Y-m-d-His') . '.pdf';
        $vista = 'formularios.layout_formulario';

        // las opciones se generan en el constructor
        $this->options['header-html'] = view()->make('formularios.header-encabezado-pdf',$this->data)->render();
        $this->options['footer-html'] = view()->make('formularios.footer-pie-pagina',$this->data)->render();

        $pdf = PDF::loadView($vista, $this->data)
            ->setOptions($this->options);
        return $pdf->download($nombre);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public static function descargarFormularioError($tramite)
    {
        $nombre = 'formulario_error_' . date('Y-m-d-His') . '.pdf';
        $vista = 'formularios.layout_formulario';
        $pdf = PDF::loadView($vista, [
            'vista' => 'error',
            'tramite' => $tramite,
            'mensaje' => Constante::MENSAJE_ERROR_FORMULARIO_SOLICITUD
        ]);

        return $pdf->download($nombre);
    }

    /**
     * @return string
     */
    public function guardarFormularioEnStorage()
    {
        $nombre = 'formulario_' . $this->data['tramite']->tipoSolicitud . '_' . date('Y-m-d-His') . '.pdf';
        $vista = 'formularios.layout_formulario';

        // las opciones se generan en el constructor
        $this->options['header-html'] = view()->make('formularios.header-encabezado-pdf',$this->data)->render();
        $this->options['footer-html'] = view()->make('formularios.footer-pie-pagina',$this->data)->render();

        $pdf = PDF::loadView($vista, $this->data)
            ->setOptions($this->options);
        $rutapdf = storage_path() . DIRECTORY_SEPARATOR . $nombre;
        File::put($rutapdf, $pdf->output());
        return $rutapdf;
    }

    /*
     * @return pdf*/
    public function guardarFormularioEnStoragePdf($dato)
    {
        $nombre = $dato['documento'];
        $destinationPath = $dato['vista'];
        //$destinationPath = storage_path() . $dato['vista'];
        $vista = 'formularios.layout_formulario';
        $destinationPath = str_replace('\\','',$destinationPath);
        $destinationPath = str_replace('/',DIRECTORY_SEPARATOR,$destinationPath);
        $destinationPath = storage_path().$destinationPath;

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        // las opciones se generan en el constructor
        $this->options['header-html'] = view()->make('formularios.header-encabezado-pdf',$this->data)->render();
        $this->options['footer-html'] = view()->make('formularios.footer-pie-pagina',$this->data)->render();

        $pdf = PDF::loadView($vista, $this->data)
            ->setOptions($this->options);

        $rutapdf = $destinationPath . $nombre;
        $crearActualizar = 2;
        if (file_exists($rutapdf)) {
            $crearActualizar = 1;
        }
        if (File::put($rutapdf, $pdf->output())) {
            // El archivo se guardó correctamente
            return Response::json([
                'estado' => 'ok',
                'actualizar-crear' => $crearActualizar,
                'documento' => $rutapdf
            ]);
        } else {

            \Log::error('No se pudo generar el archivo '.$rutapdf.' Id-solicitud:' . $dato['idSolicitud']);
            // Error al guardar el archivo
            return Response::json([
                'estado' => 'error',
                'mensaje' => 'No se pudo guardar el archivo PDF de firma simple archivo '.$rutapdf.' Id-solicitud:' . $dato['idSolicitud']
            ]);
        }
    }
}
