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
                        <span style="font-size: 1em;letter-spacing: 1px;color: black;">@if($grupoFamiliaraAsignacion['calle']!=null)
                                {{Utiles::capitalizaTexto($grupoFamiliaraAsignacion['calle'])}},
                            @endif
                            @if($grupoFamiliaraAsignacion['grupfami-num-domicilio']!=null)
                                #{{$grupoFamiliaraAsignacion['grupfami-num-domicilio']}},
                            @endif
                            @if($grupoFamiliaraAsignacion['grupfami-dpto']!=null)
                                Dpto: {{$grupoFamiliaraAsignacion['grupfami-dpto']}},
                            @endif
                            @if($grupoFamiliaraAsignacion['grupfami-block']!=null)
                                Block: {{$grupoFamiliaraAsignacion['grupfami-block']}},
                            @endif
                            @if($grupoFamiliaraAsignacion['grupfami-casa']!=null)
                                Casa: {{$grupoFamiliaraAsignacion['grupfami-casa']}},
                            @endif
                            @if($grupoFamiliaraAsignacion['grupfami-sitio']!=null)
                                Sitio: {{$grupoFamiliaraAsignacion['grupfami-sitio']}},
                            @endif
                            @if($grupoFamiliaraAsignacion['comuna']!=null) {{Utiles::capitalizaTexto($grupoFamiliaraAsignacion['comuna'])}}
                            @endif</span>
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
                                                    Menor que <strong>${{number_format($grupoFamiliarExport['calc-tramo-defl-max'],0,",",".")}}</strong>
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
    <!-- Beneficios -->
    <div style="margin-right: auto; margin-left: auto; display:block;overflow: auto;">
        <table width="100%" style="height: 120px" border="0" cellpadding="0">
            <tr>
                <td style="width: 70%;">
                    <table width="100%" border="0" cellpadding="0">
                        <tr>
                            <td style="padding: 15px; border-radius: 10px; background-color: #ebebeb;">
                                <table width="100%" border="0" cellpadding="0">
                                    <tr>
                                        <td style="width: 20%;display: initial">
                                            <span style="font-size: 2.3em; font-weight: bold;line-height: 1.3em;width:10px;">¿A qué beneficios puedo acceder?</span>
                                        </td>
                                        <td style="width: 50%; margin-right: 10px" >
                                            <span style="font-size: 1em;letter-spacing: 1px; font-weight: 400; display: block;margin-bottom: 10px;">Según la información disponible a la fecha de última actualización de tu Cartola Hogar, son los siguientes:</span>


                                            <?php $i = 65; ?>
                                            @foreach($grupoFamiliarExport['beneficios-poten-dps'] as $beneficiosPotenDps)
                                                <?php $letter = chr($i++); ?>
                                                <span style="font-size: 1.3em; font-weight: 400; display: block;margin-bottom: 10px;margin-right: 5px"><strong>{{$letter}}.</strong> {{$beneficiosPotenDps['nombre-prog']}}</span>
                                            @endforeach
                                            <hr>
                                            <span style="font-size: 1em;letter-spacing: 1px; font-weight: 400;"><em>Ingresa al sitio web de Red de Protección Social <span style="text-decoration: underline;">www.reddeproteccion.cl</span> acércate a tu municipio o escanea el QR para conocer más sobre estos beneficios.</em></span>
                                        </td>
                                        <td id="qr" style="width: 20%;">
                                            <img src="{{asset('assets/cartola/imgcartola/qr-code-t2a.jpg')}}" style="width: 100%; height: auto; ">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 30%;display: initial">
                    <div style="padding-bottom: 0px;padding-left: 20px;padding-right: 20px">
                        <span style="font-size: 1.5em; font-weight: 400; display: block;"><strong>¿Tienes consultas sobre tu Registro Social de Hogares?</strong></span><br>
                        <span style="font-size: 1em;letter-spacing: 1px; font-weight: 400;"><em> Para actualizar, complementar o corregir la información de tu RSH, ingresa a <span style="text-decoration: underline;">www.registrosocial.gob.cl</span>, acércate a tu municipio o a las sucursales de Chile Atiende. Si tienes dudas comunícate al 800-104-777 o 800-719-002, opción 1.</em></span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
@else
    <!-- Beneficios -->
    <div style=" margin-right: auto; margin-left: auto;display:block;overflow: auto;">
        <table width="100%" border="0" cellpadding="0">
            <tr>
                <td style="width: 70%;">
                    <table width="100%" border="0" cellpadding="0">
                        <tr>
                            <td style="padding: 15px; border-radius: 10px; background-color: #ebebeb;">
                                <table width="100%" border="0" cellpadding="0">
                                    <tr>
                                        <td style="width: 80%;" > <span style="font-size: 2.3em; font-weight: bold;line-height: 1.3em;">Conoce los apoyos del Estado</span>
                                            <br><br>
                                            <span style="font-size: 1em;letter-spacing: 1px; font-weight: 400;"><em>Para tener más información sobre los beneficios puedes ingresar al sitio web de Red de Protección Social, acercarte a tu municipio o escanear el código QR.</em></span><br>
                                            <span style="font-size: 1.3em; font-weight: 400; text-decoration: underline;">www.reddeproteccion.cl</span>
                                        </td>
                                        <td id="qr" style="width: 20%;">
                                            <img src="{{asset('assets/cartola/imgcartola/qr-code-t2b.jpg')}}" style="width: 100%; height: auto;">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 30%">
                    <div style="padding: 20px">
                        <span style="font-size: 1.5em; font-weight: 400; display: block;"><strong>¿Tienes consultas sobre tu Registro Social de Hogares?</strong></span><br>
                        <span style="font-size: 1em;letter-spacing: 1px; font-weight: 400;"><em> Para actualizar, complementar o corregir la información de tu RSH, ingresa a <span style="text-decoration: underline;">www.registrosocial.gob.cl</span>, acércate a tu municipio o a las sucursales de Chile Atiende. Si tienes dudas comunícate al 800-104-777 o 800-719-002, opción 1.</em></span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
@endif
<div style="padding-bottom: 30px">
<span style="font-size: 1em;letter-spacing: 1px; font-weight: 400">Esta cartola fue impresa el {{date(" j ")}} de {{ App\Utiles::obtenerMesMin(date("m")) }} a las {{date(" H:i")}} hrs.
    @if(isset($descripcionPerfil) && $descripcionPerfil != "") {{$descripcionPerfil}} @endif</span>
</div>
<!-- FIN CARTOLA -->
@if($entendiendo)
<div id="entendiendo" class="page_break" style="height: 50%;">@if(count($grupoFamiliarExport['beneficios-poten-dps']) > 0)<img src="{{asset('assets/cartola/imgcartola/entendiendo_A.png')}}" style="width:100%; height: auto;">@else<img src="{{asset('assets/cartola/imgcartola/entendiendo_B.png')}}" style="width:100%; height: auto;">@endif</div>
@endif

</body>
</html>
