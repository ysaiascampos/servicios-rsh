<!doctype html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/bootstrap-4.5.2/css/bootstrap.min.css') }}?{{ time() }}" rel="stylesheet">
    <link href="{{ asset('assets/css/forms.css') }}?{{ time() }}" rel="stylesheet">
    <link href="{{ asset('assets/css/wkhtmltopdf.css') }}?{{ time() }}" rel="stylesheet">
    <link href="{{ asset('assets/fontawesome-5.15.1/css/all.min.css') }}?{{ time() }}" rel="stylesheet">
    <title>MDSF Solicitud</title>
</head>

<body>

    <!-- Main Wrapper -->
    <div class="wrapper">
        @if (isset($qrcode))
            <div class="text-right">
                <img src="data:image/png;base64, {!! $qrcode !!}" width="150px">
            </div>
        @endif
    </div><!-- end Main Wrapper -->
</body>

</html>
