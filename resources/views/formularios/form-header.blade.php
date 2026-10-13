<!-- Header -->
<style>
    .table-responsive {
        border: 1px solid #8A8A8A;
        /* Ajusta el color según sea necesario */
        margin-top: -1px;
        /* Para compensar el borde superior que falta */
    }

    .table-rounded {
        border: 1px solid #ccc;
        /* Borde de 1px de grosor y color gris */
        border-radius: 5px;
        /* Ajusta el valor según sea necesario */
        overflow: hidden;
        /* Asegúrate de que el contenido no sobresalga de los bordes redondeados */
    }

    .color-FE6565 {
        background-color: #FE6565;
        color: white;
        /* Esto es opcional, puedes ajustar el color del texto según sea necesario */
        text-align: left;
        /* Opcional: centrar el texto */
        padding: 10px;
        /* Opcional: ajustar el relleno */
        font-weight: bold;
    }

    .color-006FB3 {
        background-color: #006FB3;
        color: white;
        /* Esto es opcional, puedes ajustar el color del texto según sea necesario */
        text-align: center;
        /* Opcional: centrar el texto */
        padding: 10px;
        /* Opcional: ajustar el relleno */
    }

    .padding-recuadro {
        padding-top: 0.8rem;
        padding-bottom: 0.1rem;
        margin-bottom: 0rem;
    }

    .title-box {
        display: inline-block;
        border: 1px solid #ccc;
        /* Borde de 1px de grosor y color gris */
        border-radius: 5px;
        /* Bordes redondeados */
        padding: 10px;
        /* Espacio interior de 10px */
    }


    .titulo-cuadro {
        color: #006FB3;
        /* Código de color para azul */
    }

    .bg-custom {
        color: black;
        font-weight: bold;
        /* Para asegurar que el texto sea legible en el fondo oscuro */
    }

    .text-dark-gray {
        color: #4A4A4A;
        font-weight: bold;
        /* Código de color para un gris oscuro */
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

    .form-row {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .form-label {
        flex: 0 0 auto;
        margin-right: 10px;
    }

    .form-line {
        flex: 1 1 auto;
        border-bottom: 1px solid #6c757d;
        margin-bottom: 0.5rem;
    }

    .form-line-2 {
        border: none;
        border-bottom: 1px solid #6c757d;
    }
</style>

@if (isset($tramite))
    <section>

        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                @if (isset($tramite->solicitud->MIGRANTE) &&
                        $tramite->solicitud->MIGRANTE == App\Constantes\Constante::SOLICITUD_PERFIL_CIUDADANO_NNA_MIGRANTE)
                    <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">INFORMACIÓN DE
                        CONTACTO DE LA
                        PERSONA
                        ADULTA
                        RESPONSABLE:</h6>
                @else
                    <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">INFORMACIÓN DE
                        CONTACTO DE LA PERSONA
                        SOLICITANTE:</h6>
                @endif
                <table class="table table-bordered table-sm mb-0 mt-0">
                    <tbody>
                        <tr>
                            <td class="bg-custom">Nombre completo:</td>
                            <td colspan="5">
                                {{ App\Utiles::capitalizaTexto(
                                    $tramite->solicitante->PER_NOMBRES .
                                        ' ' .
                                        $tramite->solicitante->PER_APELLIDOPATERNO .
                                        ' ' .
                                        $tramite->solicitante->PER_APELLIDOMATERNO,
                                ) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Correo electrónico:</td>
                            <td colspan="3">{{ $tramite->solicitante->PER_EMAIL }}</td>
                            <td class="bg-custom">Teléfono móvil:</td>
                            <td>{{ isset($tramite->solicitante->PER_CELULAR) ? $tramite->solicitante->PER_CELULAR : '' }}</td>

                        </tr>
                        <tr>
                            <td class="bg-custom">
                                @if (isset($tramite->solicitud->MIGRANTE) &&
                                        $tramite->solicitud->MIGRANTE == App\Constantes\Constante::SOLICITUD_PERFIL_CIUDADANO_NNA_MIGRANTE)
                                    DNI o pasaporte:
                                @else
                                    RUN:
                                @endif
                            </td>
                            @if (isset($tramite->solicitud->MIGRANTE) &&
                                    $tramite->solicitud->MIGRANTE == App\Constantes\Constante::SOLICITUD_PERFIL_CIUDADANO_NNA_MIGRANTE)
                                <td>
                                    @if (isset($tramite->solicitud->MIGRANTE) &&
                                            $tramite->solicitud->MIGRANTE == App\Constantes\Constante::SOLICITUD_PERFIL_CIUDADANO_NNA_MIGRANTE)
                                        {{ $tramite->solicitante->DNI }}
                                    @else
                                        {{ App\Utiles::ponePuntosAlRun($tramite->solicitante->PER_RUN, $tramite->solicitante->PER_DV) }}
                                    @endif
                                </td>
                            @elseif ($tramite->nombreSolicitud == 'Ingreso al Registro Social de Hogares')
                                <td colspan="5">
                                    @if (isset($tramite->solicitud->MIGRANTE) &&
                                            $tramite->solicitud->MIGRANTE == App\Constantes\Constante::SOLICITUD_PERFIL_CIUDADANO_NNA_MIGRANTE)
                                        {{ $tramite->solicitante->DNI }}
                                    @else
                                        {{ App\Utiles::ponePuntosAlRun($tramite->solicitante->PER_RUN, $tramite->solicitante->PER_DV) }}
                                    @endif
                                </td>
                            @else
                                <td colspan="3">
                                    @if (isset($tramite->solicitud->MIGRANTE) &&
                                            $tramite->solicitud->MIGRANTE == App\Constantes\Constante::SOLICITUD_PERFIL_CIUDADANO_NNA_MIGRANTE)
                                        {{ $tramite->solicitante->DNI }}
                                    @else
                                        {{ App\Utiles::ponePuntosAlRun($tramite->solicitante->PER_RUN, $tramite->solicitante->PER_DV) }}
                                    @endif
                                </td>
                            @endif
                            @if (isset($tramite->solicitud->MIGRANTE) &&
                                    $tramite->solicitud->MIGRANTE == App\Constantes\Constante::SOLICITUD_PERFIL_CIUDADANO_NNA_MIGRANTE)
                                @if ($tramite->solicitante->PER_NACIONALIDAD_MIGRANTE != 7)
                                    <td class="bg-custom">Nacionalidad:</td>
                                    <td>{{ $tramite->solicitante->PER_NOMBRE_NACIONALIDAD }}</td>
                                @else
                                    <td class="bg-custom">Nacionalidad:</td>
                                    <td>{{ $tramite->solicitante->PER_NACIONALIDAD_MIGRANTE_MANUAL }}</td>
                                @endif
                            @endif
                            <td class="bg-custom">Teléfono fijo:</td>
                            <td>{{ $tramite->solicitante->PER_TELEFONO }}</td>
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>
    </div>
</section>
@endif
