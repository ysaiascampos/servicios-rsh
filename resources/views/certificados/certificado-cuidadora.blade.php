<!doctype html>
<html lang="es">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Personas Cuidadoras. Registro Social de Hogares.</title>

    <link rel="stylesheet" href="{{asset('certificados/cuidadoras/css/styles.css')}}">

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400"/>
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Slab:300,400"/>

    @section('style')
    @show

</head>

<body>

<!-- Main Wrapper -->
<div class="container-fluid p-3 bg_certificado">
    <div class="bg_certificado_body">
        @include('certificados.cuidadoras.certificado-header')
        @if(isset($error))
            @include('certificados.cuidadoras.certificado-error')
        @else
            @include('certificados.cuidadoras.certificado-body')
        @endif
        @include('certificados.cuidadoras.certificado-footer')
    </div>
</div>
<!-- end Main Wrapper -->
</body>
</html>

