<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">
                    EN REPRESENTACIÓN DE MI HOGAR, SOLICITO LA DESVINCULACIÓN DE LA JEFATURA DE HOGAR INDIVIDUALIZADA A
                    CONTINUACIÓN:
                </h6>
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="bg-custom" scope="col">Nº</th>
                            <th class="bg-custom" scope="col">RUN</th>
                            <th class="bg-custom" scope="col">Nombres</th>
                            <th class="bg-custom" scope="col">Primer Apellido</th>
                            <th class="bg-custom" scope="col">Segundo Apellido</th>
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
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @if ($tramite->solicitud->FIRMA_SIMPLE != 1 || empty($tramite->solicitud->TOKEN_ACCESO) || $portal == 'municipal')
        <section>
            <div class="card p-1 m-1">
                <div class="card-body p-1 m-1">
                    <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">
                        LAS SIGUIENTES PERSONAS INTEGRANTES DEL HOGAR 18 AÑOS O MÁS AUTORIZAN LA SOLICITUD DE
                        DESVINCULACIÓN DE LA JEFATURA DE HOGAR:
                    </h6>
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th class="bg-custom" scope="col">Nº</th>
                                <th class="bg-custom" scope="col">RUN</th>
                                <th class="bg-custom" scope="col">Nombres</th>
                                <th class="bg-custom" scope="col">Primer Apellido</th>
                                <th class="bg-custom" scope="col">Segundo Apellido</th>
                                <th scope="bg-custom" class="text-center">Edad</th>
                                <th class="bg-custom" scope="col">Firma mayores de 18 años</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tramite->beneficiariosVigente as $n => $integrante)
                                @if ($integrante->PER_RUN != $tramite->titular->PER_RUN)
                                    <tr>
                                        <td>{{ $n + 1 }}</td>
                                        <td>{{ App\Utiles::ponePuntosAlRun($integrante->PER_RUN, $integrante->PER_DV) }}
                                        </td>
                                        <td>{{ $integrante->PER_NOMBRES }}</td>
                                        <td>{{ $integrante->PER_APELLIDOPATERNO }}</td>
                                        <td>{{ $integrante->PER_APELLIDOMATERNO }}</td>
                                        <td class="text-center">
                                            {{ App\Utiles::capitalizaTexto($integrante->PER_EDAD) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section>
            <div class="p-1 m-1">
                <p class="mb-0">Este formulario se utiliza exclusivamente cuando otra persona integrante desvincula a
                    la Jefatura de Hogar.</p>
            </div>
        </section>
    @endif
    <!-- end Form according to request  -->
</main><!-- end Main -->
