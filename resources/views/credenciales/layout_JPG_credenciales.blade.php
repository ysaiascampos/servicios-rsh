<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MDSF Credecial</title>
    <link href="https://fonts.cdnfonts.com/css/museo-slab-500" rel="stylesheet">
    <style>
        .clasefamiliamuseoslab {
            font-family: 'Museo Slab 500' !important;
        }
    </style>
</head>
<body style="background-image: url('{{ asset('certificados/cuidadoras/img/fondocredencial_v2024.png') }}'); background-size: 100%;" topmargin="0">
    <div class="wrapper">
        <div class="row" style="text-align: center;color: white;">
            <div class="col-12">
                <br><br><br>
                <h2 style="margin-top: 0px;margin-bottom:0px;" class="clasefamiliamuseoslab"><b>Credencial</b></h2>
                <h2 style="margin-top: 0px;" class="clasefamiliamuseoslab"><b>Persona Cuidadora</b></h2>
                <br>
                @if(isset($imagenqr))
                    <center>
                        <div style="margin-top: 10px;background-color:white;width:240px;height:260px;">
                            <img src="data:image/png;base64, {!! $imagenqr !!}" width="210" height="220" style="margin-top:20px;">
                        </div>
                    </center>
                @endif
                <h2 style="margin-top: 10px;margin-bottom:0px;" class="clasefamiliamuseoslab">{{ $nombreapellido }}</h2>
                <h2 style="margin-top: 0px;" class="clasefamiliamuseoslab">{{ $rut }}</h2>
                <br>
                <h3 style="margin-top: 5px;margin-bottom:0px;" class="clasefamiliamuseoslab">Ejerce labor de persona cuidadora</h3>
            </div>
        </div>
    </div>
</body>
</html>
