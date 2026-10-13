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
<div style="padding-bottom: 30px">
<span style="font-size: 1em;letter-spacing: 1px; font-weight: 400">Esta cartola fue impresa el {{date(" j ")}} de {{ App\Utiles::obtenerMesMin(date("m")) }} a las {{date(" H:i")}} hrs.
    @if(isset($descripcionPerfil) && $descripcionPerfil != "") {{$descripcionPerfil}} @endif @if(isset($codigoValidacion) && $codigoValidacion != "") Código {{ number_format($codigoValidacion, 0, '', '-') }} @endif</span>
</div>
</body>
</html>
