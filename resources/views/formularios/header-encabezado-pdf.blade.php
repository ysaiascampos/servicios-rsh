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

        .custom-title-size {
            font-size: 1.25rem;
            font-weight: bold;
            color: black;
            /* Código de color para negro */
        }

        .custom-subtitle {
            font-size: 0.875rem;
            /* Tamaño de fuente personalizado para los subtítulos */
            font-weight: normal;
            /* Asegura que el texto esté en negrita */
            color: black;
            /* Color gris oscuro */
        }
    </style>
</head>

<body>
    <header class="row no-gutters justify-content-between">
        <img id="logo" src="{{ asset('assets/img/logo.jpg') }}" alt="logo mdsf" width="117px" height="100px"
            class="p-1 m-1">
        <div class="header-title text-center p-1 m-1">
            @if (isset($tramite))
                <h5 class="custom-title-size">
                    @if($tramite->solicitud->ID_TIPO_SOLICITUD == 50)
                        @php
                            $complementoOriginal = optional($tramite->ingresos->first())->C_TCOMPLEMENTO_ORIGINAL;
                            $subtipos = [
                                        1 => 'Solicitud de Complemento por Pago <br>de Pensión de Alimentos',
                                        2 => 'Solicitud de Complemento por Recepción <br>de Pensión de Alimentos',

                                        3 => $complementoOriginal == 1  ? 'Complemento por cese de pago <br>de pensión de alimentos' :
                                        ($complementoOriginal == 2   ? 'Complemento por cese de recepción <br>de pensión de alimentos' :
                                        'Complemento por cese <br>de pensión de alimentos'),

                                        4 => $complementoOriginal == 1 ? 'Complemento por variación de pago <br>de pensión de alimentos':
                                        ($complementoOriginal == 2 ? 'Complemento por variación de recepción <br>de pensión de alimentos':
                                        'Complemento por variación <br>de pensión de alimentos'),
                            ];
                        @endphp
                        @if(isset($subtipos[$tramite->solicitud->ID_SUB_TIPO_SOLICITUD]))
                            {!! $subtipos[$tramite->solicitud->ID_SUB_TIPO_SOLICITUD] !!}
                        @endif
                    @else
                        {{ $tramite->tituloFormulario }}<br>
                        {{ $tramite->nombreSolicitud }}
                    @endif
                </h5>
            @endif
            @if (isset($tramite->solicitud->MIGRANTE) &&
                    $tramite->solicitud->MIGRANTE == App\Constantes\Constante::SOLICITUD_PERFIL_CIUDADANO_NNA_MIGRANTE)
                <h5 class="custom-title-size">cuya Persona Responsable es Extranjera</h5>
                <h6 class="custom-subtitle">Registro Social de Hogares</h6>
            @elseif (
                $tramite->solicitud->ID_TIPO_SOLICITUD ==
                    App\Constantes\Constante::TIPO_SOLICITUD_101_DESVINCULACION_DEL_JEFE_DE_HOGAR)
                <h6 class="custom-subtitle">(Realizado por otra persona integrante del hogar)</h6>
            @else
                <h6 class="custom-subtitle">Registro Social de Hogares</h6>
            @endif
        </div>
        @if (isset($tramite))
            <div class="header-info border rounded p-1 m-1" style="display: inline-block;">
                <ul class="list-unstyled m-0">
                    <li class="mb-2">Nº solicitud: <span>{{ $tramite->solicitud->ID_PADRE_SOLICITUD }}</span></li>
                    @if(($tramite->solicitud->ID_ESTADO == 5 && $tramite->solicitud->VIGENTE == 1) &&
                        ($tramite->nombreSolicitud == 'Solicitud de Ingreso al Registro Social de Hogares' ||
                         $tramite->nombreSolicitud == 'Solicitud de Ingreso al Registro Social de Hogares de NNA' ||
                         $tramite->nombreSolicitud == 'de Personas Institucionalizadas'))
                        <li class="mb-2">Nº registro: <span>{{ $tramite->numeroRegistro }}</span></li>
                    @elseif($tramite->nombreSolicitud == 'Solicitud de Ingreso al Registro Social de Hogares' ||
                          $tramite->nombreSolicitud == 'Solicitud de Ingreso al Registro Social de Hogares de NNA' ||
                          $tramite->nombreSolicitud == 'de Personas Institucionalizadas')
                    <!-- No registra número de registro -->
                    @else
                        <li class="mb-2">Nº registro: <span>{{ $tramite->numeroRegistro }}</span></li>
                    @endif
                    <li>Fecha:
                        <span>{{ \Carbon\Carbon::parse($tramite->solicitud->FECHA_CREACION)->format('d-m-Y H:i:s') }}</span>
                    </li>
                </ul>
            </div>
        @endif

    </header>
</body>

</html>
