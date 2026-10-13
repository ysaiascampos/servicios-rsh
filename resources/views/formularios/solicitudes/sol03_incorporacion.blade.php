<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                @if ($tramite->solicitud->MIGRANTE == 1)
                    <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">SOLICITO LA
                        INCORPORACIÓN DE LA/S SIGUIENTE/S PERSONA/S AL REGISTRO SOCIAL DE HOGARES, DE QUIENES SOY
                        RESPONSABLE:</h6>
                @else
                    <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">SOLICITO LA
                        INCORPORACIÓN DE LA/S SIGUIENTE/S PERSONA/S A MI REGISTRO SOCIAL DE HOGARES, QUIEN/ES AUTORIZA/N
                        ESTA SOLICITUD:</h6>
                @endif

                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="bg-custom" scope="col">Nº</th>
                            <th class="bg-custom" scope="col">RUN</th>
                            <th class="bg-custom" scope="col">Nombres</th>
                            <th class="bg-custom" scope="col">Primer Apellido</th>
                            <th class="bg-custom" scope="col">Segundo Apellido</th>
                            <th class="bg-custom" scope="col">Edad</th>
                            <th class="bg-custom" scope="col">Parentesco</th>
                            <th class="bg-custom" scope="col">Pueblo indígena</th>

                            @php
                                $mayoresDeEdad = false;
                            @endphp

                            @foreach ($tramite->personasAsociadas as $key => $persona)
                                @if ($persona->PER_EDAD >= \App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD)
                                    @php
                                        $mayoresDeEdad = true;
                                    @endphp
                                @endif
                            @endforeach
                            @if ($tramite->solicitud->MIGRANTE != 1)
                                <th class="bg-custom" scope="col">Firma mayores de 18 años</th>
                            @endif

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tramite->personasAsociadas as $key => $persona)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ App\Utiles::ponePuntosAlRun($persona->PER_RUN, $persona->PER_DV) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($persona->PER_NOMBRES) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($persona->PER_APELLIDOPATERNO) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($persona->PER_APELLIDOMATERNO) }}</td>
                                <td>{{ $persona->PER_EDAD }}</td>
                                <td>{{ $persona->NOMBRE_PARENTESCO }}</td>
                                <td>{{ $persona->NOMBRE_PUEBLO_INDIGENA }}</td>
                                @if (
                                    $tramite->solicitud->FIRMA_SIMPLE &&
                                        $portal == 'ciudadano' &&
                                        $persona->PER_EDAD >= \App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD)
                                    <td>Firmado Digitalmente</td>
                                @elseif ($persona->PER_EDAD >= \App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD && $tramite->solicitud->MIGRANTE != 1)
                                    <td></td>
                                @elseif ($tramite->solicitud->MIGRANTE != 1)
                                    <td class="text-center">No Aplica</td>
                                @endif
                        @endforeach
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <section>
        @if ($tramite->solicitud->MIGRANTE != 1)
            <p class="p-1 m-1 text-justify">La incorporación del Niño, Niña y/o Adolescente solo debe ser realizada
                por la madre, el padre, la
                persona adulta que tiene su cuidado personal o
                la Jefatura de hogar.</p>
        @else
            <p class="p-1 m-1 text-justify">La incorporación del Niño, Niña y/o Adolescente solo debe ser realizada por
                la madre, el padre o por la persona adulta que tiene su cuidado personal.</p>
        @endif
    </section>
    <section>
        @if ($tramite->solicitud->MIGRANTE != 1)
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">DECLARO, BAJO MI
                    RESPONSABILIDAD, QUE LA FIRMA
                    DE LOS INTEGRANTES NO ES POSIBLE OBTENERLA POR:</h6>
                <div class="container no-gutters">
                    <div class="row no-gutters">

                            <div class="col-6">
                                <h6 class="">Nombre Completo:</h6>
                                <span class="form-line-2"></span>
                            </div>
                            <div class="col-6">
                                <h6 class="">Motivo:</h6>
                                <span class="form-line-2"></span>
                            </div>
                        </div>

                </div>
            </div>
        </div>
        @endif
    </section>
    <!-- end Form according to request  -->
</main><!-- end Main -->
