<!doctype html>
<html lang="es_cl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Testeo Cartola Hogar - Ministerio de Desarrollo Social y Familia">
    <title>Cartola Hogar- Registro Sociald e Hogares</title>

    <style type="text/css">
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-Regular.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-Regular.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-Black.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-BlackItalic.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-Bold.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-BoldItalic.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-Italic.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-Light.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-LightItalic.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-Medium.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-MediumItalic.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-Thin.ttf')}}") format("ttf");
        }
        @font-face {
            font-family: Roboto;
            src: url("{{asset('assets/fontcartola/Roboto-ThinItalic.ttf')}}") format("ttf");
        }

        body {font-family: 'Roboto', sans-serif; font-size: 11px !important}
        #graficoCSE .activo {
            background-color: #292929;
            color: #fff;
        }
        #graficoCSE img {
            display: none;
        }
        #graficoCSE .activo img {
            max-width: 70px;
            display: block;
            margin: -35px auto 5px auto;
        }
        @media (max-width: 739px) {
            #tramo0040 {width: 20% !important }
            #tramo4150, #tramo5160, #tramo6170, #tramo7180, #tramo8190, #tramo91100 {width: 10% !important }
            #tramo-tramo {display: block !important;}
            .tramo-pregunta, .tramo-hogar { width: 100% !important}
            .tramo-titulo { width: 20% !important}
            .tramo-numero { width: 80% !important}
            table#hogar thead {
                display: none;
            }
            table#hogar td {
                /*display: block;*/
                text-align: left;
                /*font-size: 13px;*/
                border-bottom: 1px dotted #ccc;
                font-weight: bold;
            }
            table#hogar.no-style td {
                display: table-cell !important;

            }
            table#hogar td:before {
                content: attr(data-label);
                text-transform: uppercase;
                font-weight: normal;
                margin-right: 10px;
                margin-bottom: 10px
            }
            table#hogar tr {
                border-bottom: 10px solid #575756;
            }
        }

    </style>
</head>

<body>
<?php
use App\Utiles;
use App\Constantes\Constante;
?>
<!-- CARTOLA -->

<div id="cartola" style="padding-top:30px;font-family: 'Roboto', sans-serif; margin-left: auto; margin-right: auto; break-after:page">

    <!-- Cabecera -->
    <div style="padding-bottom: 20px">
        <div  style=" margin-right: auto; margin-left: auto;">
            <table width="100%" border="0" cellpadding="0">
                <tr>
                    <td width="50%">
                        <span style="font-size: 2.5em; font-weight: bold;">Cartola Hogar</span><br>
                        <span style="font-size: 1.2em;letter-spacing: 1px; font-weight: bold; text-transform: uppercase;"></span><br>
                        <span style="font-size: 1em;letter-spacing: 1px"></span>
                    </td>
                    <td width="35%" style="text-align: right;">
                        <span style="font-size: 1.2em;font-weight: bold;">Número de Folio: #</span><br>
                        <span style="font-size: 1em;letter-spacing: 1px">Última actualización de información: <b></b></span><br>
                        <span style="font-size: 1em;letter-spacing: 1px">Fecha de consulta: <b>{{ date('d/m/Y')}}</b></span>
                    </td>
                    <td width="15%">
                        <img src="{{asset('assets/imgcartola/logo-midesof-rsh.jpg')}}" style="max-width: 150px;">
                    </td>
                </tr>
            </table>
        </div>

    </div>
    <div style="padding-bottom: 20px">
        <div  style=" margin-right: auto; margin-left: auto;">
            {{isset($mensaje)?$mensaje:"La cartola no se pudo cargar por favor intentelo más tarde"}}
        </div>
    </div>
</div>
</body>
</html>