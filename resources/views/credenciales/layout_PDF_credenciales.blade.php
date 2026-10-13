<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{asset('assets/bootstrap-4.5.2/css/bootstrap.min.css')}}?{{time()}}" rel="stylesheet">
    <link href="{{asset('assets/css/forms.css')}}?{{time()}}" rel="stylesheet">
    <link href="{{asset('assets/css/wkhtmltopdf.css')}}?{{time()}}" rel="stylesheet">
    <link href="{{asset('assets/fontawesome-5.15.1/css/all.min.css')}}?{{time()}}" rel="stylesheet">
    <title>MDSF Credecial</title>
    <link href="https://fonts.cdnfonts.com/css/museo-slab-500" rel="stylesheet">
    <style>
        .alturacredencial {
            height: 465px;
            width: 400px !important;
            flex: 0 0 25% !important;
        }
        .clasefamiliamuseoslab {
            font-family: 'Museo Slab 500' !important;
        }
        .borderprepicado {
            border-style: dashed !important;
            border-width: 2px !important;
            padding-right: 0px !important;
            padding-left: 0px !important;
        }
        .textocentrocolorblanco {
            text-align: center;
            color: white;
        }
    </style>
</head>
<body topmargin="0">
    <div class="row">
        <div class="col-2 alturacredencial"></div>
        <div class="col-4 alturacredencial borderprepicado" style="background-image: url('{{ asset('certificados/cuidadoras/img/fondocredencial_v2024.png') }}'); width: 100%; background-repeat: no-repeat;background-size: cover;">
            <br><br><br>
            <h4 style="margin-top: 0px;margin-bottom:0px;" class="clasefamiliamuseoslab textocentrocolorblanco"><b>Credencial</b></h4>
            <h4 style="margin-top: 0px;" class="clasefamiliamuseoslab textocentrocolorblanco"><b>Persona Cuidadora</b></h4>
            <br>
            @if(isset($imagenqr))
                <center>
                    <div style="margin-top: 10px;background-color:white;width:170px;height:165px;">
                        <img src="data:image/png;base64, {!! $imagenqr !!}" width="150" height="145" style="margin-top:10px;">
                    </div>
                </center>
            @endif
            <h4 style="margin-top: 10px;margin-bottom:0px;" class="clasefamiliamuseoslab textocentrocolorblanco">{{ $nombreapellido }}</h4>
            <h4 style="margin-top: 0px;" class="clasefamiliamuseoslab textocentrocolorblanco">{{ $rut }}</h4>
            <br>
            <h5 style="margin-top: 5px;margin-bottom:0px;" class="clasefamiliamuseoslab textocentrocolorblanco">Ejerce labor de persona cuidadora</h5>
        </div>
        <div class="col-4 alturacredencial borderprepicado">
            <img src="{{asset('certificados/cuidadoras/img/credencialB_V2024.png')}}" alt="" style="width:100%;height:100%">
        </div>
        <div class="col-2 alturacredencial"></div>
    </div>
</body>
</html>
