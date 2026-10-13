<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/bootstrap-4.5.2/css/bootstrap.min.css') }}?{{ time() }}" rel="stylesheet">
    <link href="{{ asset('assets/css/forms.css') }}?{{ time() }}" rel="stylesheet">
    <link href="{{ asset('assets/css/wkhtmltopdf.css') }}?{{ time() }}" rel="stylesheet">
    <link href="{{ asset('assets/fontawesome-5.15.1/css/all.min.css') }}?{{ time() }}" rel="stylesheet">
    <style>
        body {
            padding-bottom: 4mm;
            background-color: transparent;
        }
    </style>
</head>

<body>
    <div class="footer d-inline-block" style="width: 500px;">
        <img id="logo-footer" src="{{ asset('assets/img/mds-logo-compacto.png') }}" alt="mds-logo-compacto"
            width="200px" class="p-1 m-1">
        <p class="mx-1 pl-1">Origen: <span class="font-weight-bold">{{ \App\Utiles::evaluarOrigen($tramite->solicitud) }}</span></p>
    </div>
</body>

</html>
