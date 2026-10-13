<!doctype html>
<html lang="es_cl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Testeo Cartola Hogar - Ministerio de Desarrollo Social y Familia">
    <title>Cartola Hogar- Registro Social de Hogares</title>
    <link href="{{ asset('assets/cartola/css/main.css') }}?{{ time() }}" rel="stylesheet">

    <?php $formatFontTtf = env('FORMAT_FONT_TTF');?>
    @if($formatFontTtf)
        <style>
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Regular.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Regular.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Black.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-BlackItalic.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Bold.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-BoldItalic.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Italic.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Light.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-LightItalic.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Medium.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-MediumItalic.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Thin.ttf')}}") format("ttf");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-ThinItalic.ttf')}}") format("ttf");
            }
            body {font-family: "Roboto", sans-serif; font-size: 11px !important;}
        </style>
    @else
        <style>
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Regular.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Regular.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Black.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-BlackItalic.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Bold.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-BoldItalic.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Italic.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Light.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-LightItalic.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Medium.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-MediumItalic.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-Thin.ttf')}}");
            }
            @font-face {
                font-family: 'Roboto';
                src: url("{{asset('assets/cartola/fontcartola/Roboto-ThinItalic.ttf')}}");
            }
            body {font-family: "Roboto", sans-serif; font-size: 11px !important;}
        </style>
    @endif
</head>

<body>
<?php
use App\Utiles;
use App\Constantes\Constante;
?>
    <!-- CARTOLA -->

<div id="cartola" style="font-family: 'Roboto', sans-serif; margin-left: auto; margin-right: auto; break-after:page">
    <!-- Cabecera -->
    <div style="padding-bottom: 20px;">
        <div  style=" margin-right: auto; margin-left: auto;">
            <table width="100%" border="0" cellpadding="0">
                <tr>
                    <td width="50%">
                        <span style="font-size: 2.5em; font-weight: bold;">Cartola Hogar</span><br>
                        <span style="font-size: 1.2em;letter-spacing: 1px; font-weight: bold; text-transform: uppercase;">{{Utiles::capitalizaTexto($personaExport['nombres'] . " " . $personaExport['apellido-paterno'] . " " . $personaExport['apellido-materno'])}}</span><br>
                        <span style="font-size: 1em; letter-spacing: 1px; color: black;">
                            {{ Utiles::formatearDireccion($grupoFamiliarExport) }}
                        </span>
                    </td>
                    <td width="35%" style="text-align: right;margin-right: 5em">
                        <span style="font-size: 1.2em;font-weight: bold;">Número de Folio: #{{$grupoFamiliaraAsignacion['folio-nuevo']}} &nbsp;</span><br>
                        <span style="font-size: 1em;letter-spacing: 1px">Última actualización de información:&nbsp; <strong>@if($grupoFamiliarExport['fecha-cierre']!= null){{ date('d/m/Y', strtotime($grupoFamiliarExport['fecha-cierre']))}}@endif</strong> &nbsp;</span><br>
                        <span style="font-size: 1em;letter-spacing: 1px">Fecha de consulta:&nbsp; <strong>{{ date('d/m/Y')}}</strong> &nbsp;</span>
                    </td>
                    <td width="15%">
                        <img src="{{asset('assets/cartola/imgcartola/logo-midesof-rsh.jpg')}}" style="max-width: 150px;">
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <!-- Tramo -->
    <div id="graficoCSE">
        <div id="tramo-tramo" style=" margin-right: auto; margin-left: auto;">

            <table width="100%" border="0" cellpadding="0">
                <tr>
                    <td width="100%" style="vertical-align: bottom;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                            <tr>
                                <td width="60%">
                                    <span style="font-size: 2.3em; font-weight: bold; line-height: 1.3em; margin-bottom: 15px; display: block;">¿Cuál es el tramo de mi hogar?</span>
                                </td>
                                <td width="40%" style="background-color: #292929; padding: 10px; text-align: center; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                    <span style="font-size: 3em; font-weight: bold; color: #fff">@if ($grupoFamiliarExport['calc-quintil-validacion-2'] == '40')
                                            0 a 40%
                                        @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '50')
                                            41 a 50%
                                        @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '60')
                                            51 a 60%
                                        @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '70')
                                            61 a 70%
                                        @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '80')
                                            71 a 80%
                                        @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '90')
                                            81 a 90%
                                        @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '100')
                                            91 a 100%
                                        @endif</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="tramo-hogar" style="vertical-align: bottom;width: 100%">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #ebebeb;">
                            <tr>
                                <td width="60%" style="padding: 5px; border-left: 1px solid #fff ;text-align: right;max-width: 100%; background-color: #ebebeb;  margin-right: auto; margin-left: auto;">
                                    <span style="font-size: 1em;letter-spacing: 1px; margin-right: 10px">TRAMO </span>
                                </td>
                                <td width="40%">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #ebebeb;">
                                        <tr>
                                            <td id="tramo00400" @if ($grupoFamiliarExport['calc-quintil-validacion-2'] == '40') class="activo" @endif style="padding: 5px; border-left: 1px solid #fff ;text-align: center;max-width: 100%; "> <span style="font-size: 1em;">40</span> </td>
                                            <td id="#tramo4150" @if ($grupoFamiliarExport['calc-quintil-validacion-2'] == '50') class="activo" @endif style="padding: 5px; border-left: 1px solid #fff ;text-align: center;max-width: 100%; "> <span style="font-size: 1em;">50</span> </td>
                                            <td id="#tramo5160" @if ($grupoFamiliarExport['calc-quintil-validacion-2'] == '60') class="activo" @endif style="padding: 5px; border-left: 1px solid #fff ;text-align: center;max-width: 100%; "> <span style="font-size: 1em;">60</span> </td>
                                            <td id="#tramo6170" @if ($grupoFamiliarExport['calc-quintil-validacion-2'] == '70') class="activo" @endif style="padding: 5px; border-left: 1px solid #fff ; text-align: center;max-width: 100%; "> <span style="font-size: 1em;">70</span> </td>
                                            <td id="#tramo7180" @if ($grupoFamiliarExport['calc-quintil-validacion-2'] == '80') class="activo" @endif style="padding: 5px; border-left: 1px solid #fff ; text-align: center;max-width: 100%; "> <span style="font-size: 1em;">80</span> </td>
                                            <td id="#tramo8190" @if ($grupoFamiliarExport['calc-quintil-validacion-2'] == '90') class="activo" @endif style="padding: 5px; border-left: 1px solid #fff ; text-align: center;max-width: 100%; "> <span style="font-size: 1em;">90</span> </td>
                                            <td id="#tramo91100" @if ($grupoFamiliarExport['calc-quintil-validacion-2'] == '100') class="activo" @endif style="padding: 5px; border-left: 1px solid #fff ; text-align: center;max-width: 100%; "> <span style="font-size: 1em;">100</span> </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style=" margin-right: auto; margin-left: auto; background-color: #292929; text-align: center; padding: 15px 0">
                        <span style="font-size: 1.3em; color: #fff">Según los tramos de vulnerabilidad socioeconómica, tu hogar se ubica en el @if($formatFontTtf) @else&nbsp;&nbsp;@endif<strong>Tramo {{$grupoFamiliarExport['calc-quintil-validacion-2']}} </strong> @if ($grupoFamiliarExport['calc-quintil-validacion-2'] == '40')
                                (0% al 40%).
                            @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '50')
                                (41% al 50%).
                            @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '60')
                                (51% al 60%).
                            @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '70')
                                (61% al 70%).
                            @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '80')
                                (71% al 80%)
                            @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '90')
                                (81% al 90%).
                            @elseif ($grupoFamiliarExport['calc-quintil-validacion-2'] == '100')
                                (91% al 100%).
                            @endif</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Información -->
    <div  style=" margin-right: auto; margin-left: auto; ">
        <table width="100%" border="0" cellpadding="0">
            <tr>
                <td style="width: 50%; padding: 10px; vertical-align: top;" >
                    <span style="font-size: 2.3em; font-weight: bold; line-height: 1.3em;display: block;">¿Qué información se considera para el tramo de mi hogar?</span>
                    <span style="font-size: 1.3em; margin: 20px 0px; display: block">El tramo de tu hogar se define utilizando @if($formatFontTtf) @else&nbsp;&nbsp;@endif<strong>información autorizada, entregada por personas del hogar y ajustada con datos almacenados por las instituciones del Estado.</strong></span>
                    <span style="font-size: 1.3em; margin: 20px 0px; display: block">El cálculo del tramo considera principalmente tres factores:</span>

                    <span style="font-size: 1.6em; font-weight: 400;  margin-bottom: 10px; display: block;"><strong>1. Total de personas en tu hogar con:</strong></span>
                    <table width="100%" border="0" cellpadding="0">
                        <tr>
                            <td style="padding: 10px 0px; text-align: center;max-width: 100%; ">
                                <span style="font-size: 1em;letter-spacing: 1px">DISCAPACIDAD</span> <br>
                                <span style="font-size: 1.8em; margin-top: 30px; display: block;"><strong>@if(isset($grupoFamiliarExport['n-tipo-in-dicap'])) {{$grupoFamiliarExport['n-tipo-in-dicap']}} @endif</strong></span>
                            </td>
                            <td style="padding: 10px 0px; border-left: 1px solid #ccc ; text-align: center;max-width: 100%; ">
                                <span style="font-size: 1em;letter-spacing: 1px">DEPENDENCIA</span> <br>
                                <span style="font-size: 1.8em; margin-top: 30px; display: block;"><strong>@if(isset($grupoFamiliarExport['n-tipo-in-dep'])) {{$grupoFamiliarExport['n-tipo-in-dep']}} @endif</strong></span>
                            </td>
                            <td style="padding: 10px 0px; border-left: 1px solid #ccc ;text-align: center;max-width: 100%; ">
                                <span style="font-size: 1em;letter-spacing: 1px">INVALIDEZ</span> <br>
                                <span style="font-size: 1.8em; margin-top: 30px; display: block;"><strong>@if(isset($grupoFamiliarExport['n-tipo-in-inval'])) {{$grupoFamiliarExport['n-tipo-in-inval']}} @endif</strong></span>
                            </td>
                            <td style="padding: 10px 0px; border-left: 1px solid #ccc; text-align: center;max-width: 100%; ">
                                <span style="font-size: 1em;letter-spacing: 1px">NECESIDADES<br>EDUCATIVAS<br>ESPECIALES</span> <br>
                                <span style="font-size: 1.8em; margin-top: 10px; display: block;"><strong>@if(isset($grupoFamiliarExport['n-tipo-in-ed-esp'])) {{$grupoFamiliarExport['n-tipo-in-ed-esp']}} @endif</strong></span>
                            </td>
                        </tr>
                    </table>
                </td>

                <td style="width: 50%; padding: 5px; border-left: 1px dashed #ccc;  margin-bottom: 0px; vertical-align: top;" >
                    <table style=" margin-bottom: 5px; border-bottom: 1px dashed #ccc; width: 100%;">
                        <tr>
                            <td style="padding: 8px 0px; ">
                                <span style="font-size: 1.6em; font-weight: 400; margin-left: 10px; margin-bottom: 8px; display: block;"><strong>2. Personas que conforman el hogar:</strong></span>
                            </td>
                            <td style="padding: 8px 0px; text-align: center; width: 20%; ">
                                <span style="font-size: 1em;letter-spacing: 1px">TOTAL</span> <br>
                                <span style="font-size: 1.8em; margin-top: 10px; display: block;"><strong>@if(isset($grupoFamiliaraAsignacion['total'])) {{$grupoFamiliaraAsignacion['total']}} @else 0 @endif</strong></span>
                            </td>
                            <td style="padding: 8px 0px; border-left: 1px solid #ccc ;text-align: center; width: 20%; ">
                                <span style="font-size: 1em;letter-spacing: 1px">-18 AÑOS</span> <br>
                                <span style="font-size: 1.8em; margin-top: 10px; display: block;"><strong>@if(isset($grupoFamiliaraAsignacion['menores'])) {{$grupoFamiliaraAsignacion['menores']}} @else 0 @endif</strong></span>
                            </td>
                            <td style="padding: 8px 0px; border-left: 1px solid #ccc;text-align: center; width: 20%; ">
                                <span style="font-size: 1em;letter-spacing: 1px">+60 AÑOS</span> <br>
                                <span style="font-size: 1.8em; margin-top: 10px; display: block;"><strong>@if(isset($grupoFamiliaraAsignacion['adultos-mayores'])) {{$grupoFamiliaraAsignacion['adultos-mayores']}} @else 0 @endif</strong></span>
                            </td>
                        </tr>
                    </table>

                    <table style=" border-bottom: 1px dashed #ccc; width: 100%;">
                        <tr>
                            <td style="padding: 5px 0px; ">

                                <span style="font-size: 1.6em; font-weight: 400; margin-left: 10px; margin-bottom: 10px; display: block;"><strong>3. Ingresos de las personas que conforman el hogar:</strong></span>
                                <table style="width: 100%; margin-top: 10px;letter-spacing: 1px">
                                    <tbody>
                                    <tr>
                                        <td style="padding: 8px 10px ; border-bottom: 1px solid #ccc;"> Trabajo</td>
                                        <td style="padding: 8px 10px ; border-bottom: 1px solid #ccc;">@if ($grupoFamiliarExport['calc-tramo-laboral-min'] > 0)
                                                Entre ${{number_format($grupoFamiliarExport['calc-tramo-laboral-min'],0,",",".")}} y ${{number_format($grupoFamiliarExport['calc-tramo-laboral-max'],0,",",".")}}
                                            @else
                                                No tiene
                                            @endif</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 10px ; border-bottom: 1px solid #ccc;"> Pensión y/o Jubilación</td>
                                        <td style="padding: 8px 10px ; border-bottom: 1px solid #ccc;">@if ($grupoFamiliarExport['calc-tramo-pension-min'] > 0)
                                                Entre ${{number_format($grupoFamiliarExport['calc-tramo-pension-min'],0,",",".")}} y ${{number_format($grupoFamiliarExport['calc-tramo-pension-max'],0,",",".")}}
                                            @else
                                                No tiene
                                            @endif</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 10px ; border-bottom: 1px solid #ccc;">Capital (ej. inversiones)</td>
                                        <td style="padding: 8px 10px ; border-bottom: 1px solid #ccc;">@if ($grupoFamiliarExport['calc-tramo-capital-min'] > 0)
                                                Entre ${{number_format($grupoFamiliarExport['calc-tramo-capital-min'],0,",",".")}} y ${{number_format($grupoFamiliarExport['calc-tramo-capital-max'],0,",",".")}}
                                            @else
                                                No tiene
                                            @endif</td>
                                    </tr>
                                    @if (isset($grupoFamiliarExport['rmin-y-penalim-recibe-hogar']) && $grupoFamiliarExport['rmin-y-penalim-recibe-hogar']>0)
                                        <tr>
                                            <td style="padding: 8px 10px ; border-bottom: 1px solid #ccc;"> Recibe pensión de alimentos</td>
                                            <td style="padding: 8px 10px ; border-bottom: 1px solid #ccc;">Entre ${{number_format($grupoFamiliarExport['rmin-y-penalim-recibe-hogar'],0,",",".")}} y ${{number_format($grupoFamiliarExport['rmax-y-penalim-recibe-hogar'],0,",",".")}}</td>
                                        </tr>
                                    @endif
                                    @if (isset($grupoFamiliarExport['rmin-y-penalim-paga-hogar']) && $grupoFamiliarExport['rmin-y-penalim-paga-hogar']>0)
                                        <tr>
                                            <td style="padding: 8px 10px ; border-bottom: 3px solid #333;">Paga pensión de alimentos</td>
                                            <td style="padding: 8px 10px ; border-bottom: 3px solid #333;">Entre ${{number_format($grupoFamiliarExport['rmin-y-penalim-paga-hogar'],0,",",".")}} y ${{number_format($grupoFamiliarExport['rmax-y-penalim-paga-hogar'],0,",",".")}}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td style="padding: 8px 10px ;letter-spacing: 1px"><strong>TOTAL INGRESOS HOGAR</strong></td>
                                        <td style="padding: 8px 10px ;"><strong>@if ($grupoFamiliarExport['calc-tramo-def-min'] == 0)
                                                    No tiene
                                                    {{--                                                Menor que <strong>${{number_format($grupoFamiliarExport['calc-tramo-defl-max'],0,",",".")}}</strong>--}}
                                                @else
                                                    Entre <strong>${{number_format($grupoFamiliarExport['calc-tramo-def-min'],0,",",".")}} y ${{number_format($grupoFamiliarExport['calc-tramo-defl-max'],0,",",".")}}</strong>
                                                @endif</strong></td>
                                    </tfoot>
                                </table>
                                <span style="font-size: 1em;letter-spacing: 1px; font-weight: 400;margin-left: 10px;"><em>Ingresos observados en los últimos 12 meses disponibles.</em></span>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

    </div>
</div>

<!-- Otros factores -->
<div style=" margin-right: auto; margin-left: auto; ">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="background-color: #292929; padding: 10px; border-top-left-radius: 10px; border-top-right-radius: 10px; vertical-align: middle;">
                <span style="font-size: 1.5em; color: #fff;"><strong>¿Qué otros factores influyen el tramo de mi hogar?</strong> </span>
                <span style="font-size: 1.2em; font-weight: 400; color: #fff;float: right;width:290px"><em>Estos factores pueden subir el tramo de tu hogar.</em></span>
            </td>
        </tr>
    </table>

    <table  style="font-size: 1em; font-weight: 400; background: transparent; width: 100%; margin-bottom: 1em; color: #212529; vertical-align: top; border-color: #ccc;">
        <tbody>
        <tr>
            <td style="background-color: #E9EAEC; padding: 5px;">
                <img @if($grupoFamiliarExport['calc-propiedades-costosas'] == 1) src="{{asset('assets/cartola/imgcartola/ico-check.jpg')}}" @else src="{{asset('assets/cartola/imgcartola/ico-nocheck.jpg')}}" @endif width="15px" style="margin-right: 10px;">
                <span style="letter-spacing: 1px;"> Bienes raíces de alto o muy alto valor </span>
            </td>
            <td style="background-color: #E9EAEC; padding: 5px;">
                <img @if($grupoFamiliarExport['calc-isaprecarga-costoso'] == 1) src="{{asset('assets/cartola/imgcartola/ico-check.jpg')}}" @else src="{{asset('assets/cartola/imgcartola/ico-nocheck.jpg')}}" @endif width="15px" style="margin-right: 10px;">
                <span style="letter-spacing: 1px;"> Plan de salud de alto o muy alto costo</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">
                <img @if($grupoFamiliarExport['calc-vehiculos-costosos'] == 1) src="{{asset('assets/cartola/imgcartola/ico-check.jpg')}}" @else src="{{asset('assets/cartola/imgcartola/ico-nocheck.jpg')}}" @endif width="15px" style="margin-right: 10px;">
                <span style="letter-spacing: 1px;"> Vehículos de alto o muy alto valor</span>
            </td>
            <td style="padding: 5px;">
                <img @if($grupoFamiliarExport['medio-padre-no-present'] == 1) src="{{asset('assets/cartola/imgcartola/ico-check.jpg')}}" @else src="{{asset('assets/cartola/imgcartola/ico-nocheck.jpg')}}" @endif width="15px" style="margin-right: 10px;">
                <span style="letter-spacing: 1px;"> Altos o muy altos ingresos del padre o madre fuera del hogar</span>
            </td>
        </tr>
        <tr>
            <td style="background-color: #E9EAEC; padding: 5px;">
                <img @if($grupoFamiliarExport['calc-colegio-costoso'] == 1) src="{{asset('assets/cartola/imgcartola/ico-check.jpg')}}" @else src="{{asset('assets/cartola/imgcartola/ico-nocheck.jpg')}}" @endif width="15px" style="margin-right: 10px;">
                <span style="letter-spacing: 1px;"> Establecimiento educacional de alto costo</span>
            </td>
            <td style="background-color: #E9EAEC; padding: 5px;"></td>
        </tr>
        </tbody>
    </table>
</div>

<!-- Quienes -->
<div style=" margin-right: auto; margin-left: auto; margin-bottom: 10px;display:block;overflow: auto;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td style="background-color: #292929; padding: 10px; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <span style="font-size: 1.5em; color: #fff;"><strong>¿Quiénes conforman mi hogar?</strong></span>
                <span style="font-size: 1.5em; color: #fff;float: right;">Total de integrantes: <strong>{{count($integrantesExport)}} @if(count($integrantesExport) == 1) persona @else personas @endif</strong></span>
            </td>
        </tr>
    </table>
    <table id="hogar" style="font-size: 1em; font-weight: 400;background: transparent; width: 100%; margin-bottom: 1em; color: #212529; vertical-align: top; border-color: #ccc; text-align: left;">
        <tr>
            <th style=" border-bottom: 1px solid #333;"><strong>RUT</strong></th>
            <th style=" border-bottom: 1px solid #333;"><strong>Integrantes</strong></th>
            <th style=" border-bottom: 1px solid #333;"><strong>Parentesco</strong></th>
            <th align="center" style="text-align:center; border-bottom: 1px solid #333;"><strong>-18 años</strong></th>
            <th align="center" style="text-align:center; border-bottom: 1px solid #333;"><strong>+60 años</strong></th>
            <th align="center" style="text-align:center; border-bottom: 1px solid #333;"><strong>Persona<br>cuidadora</strong></th>
        </tr>
        @foreach($integrantesExport as $integrante)
            <tr>
                <td  style="">{{substr(Utiles::ponePuntosAlRun($integrante['run'], $integrante['dv']), 0, -5)."XXX-X"}}</td>
                <td  style="">{{Utiles::capitalizaTexto($integrante['nombres'] . " " . $integrante['apellido-paterno'] . " " . $integrante['apellido-materno'])}}</td>
                <td style=""><?php
                                 $dato = "";
                                 $info = $integrante['fic-parentesco'];
                                 if(isset($info)){
                                     $partes = explode('.', $info, 2);
                                     $dato = isset($partes[1]) ? trim($partes[1]) : $info;
                                 }else{
                                     $dato = "";
                                 }
                                 ?>
                    {{ $dato }}
                </td>
                <td align="center"  style="text-align:center;"><img @if($integrante['edad']< Constante::EDAD_18_MAYOR_DE_EDAD) src="{{asset('assets/cartola/imgcartola/ico-check.jpg')}}" @else src="{{asset('assets/cartola/imgcartola/ico-nocheck.jpg')}}" @endif width="15px"></td>
                <td align="center"  style="text-align:center;"><img @if($integrante['edad']>= Constante::EDAD_60_ADULTO_MAYOR) src="{{asset('assets/cartola/imgcartola/ico-check.jpg')}}" @else src="{{asset('assets/cartola/imgcartola/ico-nocheck.jpg')}}" @endif width="15px"></td>
                <td align="center"  style="text-align:center;"><img @if($integrante['cuidador'] > 0) src="{{asset('assets/cartola/imgcartola/ico-check.jpg')}}" @else src="{{asset('assets/cartola/imgcartola/ico-nocheck.jpg')}}" @endif width="15px"></td>
            </tr>
        @endforeach
    </table>
    <span style="font-size: 1em;letter-spacing: 1px; font-weight: 400;"><em>Información reportada por los integrantes del hogar.</em></span>

</div>
@if(count($grupoFamiliarExport['beneficios-poten-dps']) > 0)
    <!-- BENEFICIOS -->
    <table width="100%" cellpadding="0" cellspacing="0" style="page-break-inside: avoid; margin-bottom: 20px;">
        <tr>
            <td width="60%" valign="top" style="padding-right: 12px;">
                <div style="background-color: #ebebeb; border-radius: 10px; padding: 25px;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="86%" valign="top">
                                <div style="font-family: Roboto, Arial, sans-serif; font-size: 20px; font-weight: bold; line-height: 1.3; margin-bottom: 14px;">
                                    Beneficios que corresponden a tu hogar
                                </div>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="13" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; color: #0F5AC4; padding-right: 6px; padding-bottom: 8px;">&bull;</td>
                                        <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; padding-bottom: 8px;">
                                            <a href="https://www.ventanillaunicasocial.gob.cl/ficha/96/subsidio-discapacidad-menores" target="_blank"
                                               style="font-family: Roboto, Arial, sans-serif; color: #0F5AC4; font-weight: 500; letter-spacing: 0.2px;
                                               text-decoration: underline;">Subsidio de Discapacidad para menores de 18 a&ntilde;os&nbsp;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="13" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; color: #0F5AC4; padding-right: 6px; padding-bottom: 8px;">&bull;</td>
                                        <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; padding-bottom: 8px;">
                                            <a href="https://www.ventanillaunicasocial.gob.cl/ficha/97/subsidio-familiar" target="_blank"
                                               style="font-family: Roboto, Arial, sans-serif; color: #0F5AC4; font-weight: 500; letter-spacing: 0.2px;
                                               text-decoration: underline;">Subsidio Familiar (SUF)</a>
                                        </td>
                                    </tr>
                                </table>

                                <table cellpadding="0" cellspacing="0" style="width: 190px; margin-top: 14px; margin-left: 18px; background-color: #ffffff; border: 1px solid #0F5AC4;">
                                    <tr>
                                        <td valign="middle" style="padding: 8px 6px 8px 18px; font-family: Roboto, Arial, sans-serif; font-size: 1.3em; white-space: nowrap;">
                                            <a href="https://www.ventanillaunicasocial.gob.cl/ciudadano/beneficios-y-tramites/beneficios-potenciales" target="_blank"
                                               style="color: #0F5AC4; font-weight: 400; text-decoration: none;">Consulta tus beneficios</a>
                                        </td>
                                        <td width="24" valign="middle" style="padding-right: 14px; font-family: Roboto, Arial, sans-serif; font-size: 1.3em; color: #0F5AC4;">&gt;</td>
                                    </tr>
                                </table>

                            </td>
                            <td style="width: 20%; text-align: center; border-left: 1px solid #989898; padding-left: 22px;">
                                <div id="qr" align="center">
                                    <img src="{{ asset('assets/cartola/imgcartola/qr-code-a.jpg') }}" width="100" height="100" style="margin-bottom: 10px;">
                                    <div align="center" style="font-family: Roboto, Arial, sans-serif; font-size: 11px; line-height: 1.3;">
                                        Escanea el QR y conoce todos los beneficios
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td width="40%" valign="top" style="padding: 22px 0 0 0;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="30" valign="middle" style="padding: 0 8px 0 0; line-height: 0;">
                            <img src="{{ asset('assets/cartola/imgcartola/ico-help.png') }}" width="24" height="24">
                        </td>
                        <td valign="middle" style="font-family: Roboto, Arial, sans-serif; font-size: 20px; font-weight: bold; line-height: 1.1;">
                            Consultas y ayuda
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" style="padding: 9px 0 0 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="12" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35; padding-bottom: 5px;">&bull;</td>
                                    <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35; padding-bottom: 5px;">
                                        Ingresa al sitio web desde este enlace:<br>
                                        <a href="https://www.ventanillaunicasocial.gob.cl" target="_blank" style="color: #0F5AC4; font-weight: 500; text-decoration: underline; white-space: nowrap; letter-spacing: 0.3px;">www.ventanillaunicasocial.gob.cl</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="12" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35; padding-bottom: 5px;">&bull;</td>
                                    <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35; padding-bottom: 5px;">
                                        Ac&eacute;rcate a tu <strong>municipio</strong> o visita las sucursales de <strong>ChileAtiende</strong>.
                                    </td>
                                </tr>
                                <tr>
                                    <td width="12" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35;">&bull;</td>
                                    <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35;">
                                        Llama al <a href="tel:800104777" style="color: #0F5AC4; font-weight: 500; text-decoration: underline;">800-104-777</a> o <a href="tel:800719002" style="color: #0F5AC4; font-weight: 500; text-decoration: underline;">800-719-002</a>, <span style="white-space: nowrap;">(opci&oacute;n 1).</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@else
    <!-- BENEFICIOS -->
    <table width="100%" cellpadding="0" cellspacing="0" style="page-break-inside: avoid; margin-bottom: 20px;">
        <tr>
            <td width="60%" valign="top" style="padding-right: 12px;">
                <div style="background-color: #ebebeb; border-radius: 10px; padding: 25px;">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="78%" valign="top" style="padding-right: 12px;">
                                <div style="font-family: Roboto, Arial, sans-serif; font-size: 20px; font-weight: bold; line-height: 1.3; margin-bottom: 14px;">
                                    Conoce beneficios y realiza tr&aacute;mites
                                </div>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="16" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; padding-bottom: 5px;">&bull;</td>
                                        <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; font-weight: 500; padding-bottom: 5px;">Consulta beneficios recibidos.</td>
                                    </tr>
                                    <tr>
                                        <td width="16" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; padding-bottom: 5px;">&bull;</td>
                                        <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; font-weight: 500; padding-bottom: 5px;">Actualiza tu Registro Social de Hogares.</td>
                                    </tr>
                                    <tr>
                                        <td width="16" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; padding-bottom: 5px;">&bull;</td>
                                        <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; font-weight: 500; padding-bottom: 5px;">Accede a tus tr&aacute;mites en l&iacute;nea.</td>
                                    </tr>
                                    <tr>
                                        <td width="16" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; padding-bottom: 5px;">&bull;</td>
                                        <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; font-weight: 500; padding-bottom: 5px;">Revisa apoyos y prestaciones del Estado.</td>
                                    </tr>
                                </table>
                                <table cellpadding="0" cellspacing="0" style="width: 260px; margin-top: 14px; margin-left: 18px; background-color: #ffffff; border: 1px solid #0F5AC4;">
                                    <tr>
                                        <td valign="middle" style="padding: 8px 6px 8px 18px; font-family: Roboto, Arial, sans-serif; font-size: 13px; white-space: nowrap;">
                                            <a href="https://www.ventanillaunicasocial.gob.cl" target="_blank" style="color: #0F5AC4; font-weight: 400; text-decoration: none;">Ingresa en Ventanilla &Uacute;nica Social</a>
                                        </td>
                                        <td width="24" valign="middle" style="padding-right: 14px; font-family: Roboto, Arial, sans-serif; font-size: 15px; color: #0F5AC4;">&gt;</td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 20%; text-align: center; border-left: 1px solid #989898; padding-left: 22px;">
                                <div id="qr" align="center">
                                    <img src="{{ asset('assets/cartola/imgcartola/qr-code-b.jpg') }}" width="100" height="100" style="margin-bottom: 10px;">
                                    <div align="center" style="font-family: Roboto, Arial, sans-serif; font-size: 11px; line-height: 1.3;">
                                        Escanea el QR y conoce todos los beneficios
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td width="40%" valign="top" style="padding: 22px 0 0 0;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="30" valign="middle" style="padding: 0 8px 0 0; line-height: 0;">
                            <img src="{{ asset('assets/cartola/imgcartola/ico-help.png') }}" width="24" height="24">
                        </td>
                        <td valign="middle" style="font-family: Roboto, Arial, sans-serif; font-size: 20px; font-weight: bold; line-height: 1.1;">
                            Consultas y ayuda
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" style="padding: 9px 0 0 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="12" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35; padding-bottom: 5px;">&bull;</td>
                                    <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35; padding-bottom: 5px;">
                                        Ingresa al sitio web desde este enlace:<br>
                                        <a href="https://www.ventanillaunicasocial.gob.cl" target="_blank" style="color: #0F5AC4; font-weight: 500; white-space: nowrap; letter-spacing: 0.3px;">www.ventanillaunicasocial.gob.cl</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="12" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35; padding-bottom: 5px;">&bull;</td>
                                    <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35; padding-bottom: 5px;">
                                        Ac&eacute;rcate a tu <strong>municipio</strong> o visita las sucursales de <strong>ChileAtiende</strong>.
                                    </td>
                                </tr>
                                <tr>
                                    <td width="12" valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35;">&bull;</td>
                                    <td valign="top" style="font-family: Roboto, Arial, sans-serif; font-size: 1.3em; line-height: 1.35;">
                                        Llama al <a href="tel:800104777" style="color: #0F5AC4; font-weight: 500; text-decoration: underline;">800-104-777</a> o <a href="tel:800719002" style="color: #0F5AC4; font-weight: 500; text-decoration: underline;">800-719-002</a>, <span style="white-space: nowrap;">(opci&oacute;n 1).</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endif

<!-- FIN CARTOLA -->
@if($entendiendo)
    <div id="entendiendo" style="height: 50%;">@if(count($grupoFamiliarExport['beneficios-poten-dps']) > 0)<img src="{{asset('assets/cartola/imgcartola/entendiendo-A.png')}}" style="width:100%; height: auto;">@else<img src="{{asset('assets/cartola/imgcartola/entendiendo-B.png')}}" style="width:100%; height: auto;">@endif</div>
@endif

</body>
</html>
