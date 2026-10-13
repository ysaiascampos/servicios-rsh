<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">
                    SOLICITO LA DESVINCULACIÓN DE LA/S SIGUIENTE/S PERSONA/S DE MI REGISTRO SOCIAL DE HOGARES QUIEN/ES
                    AUTORIZA/N ESTA SOLICITUD:
                </h6>
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th scope="bg-custom">Nº</th>
                            <th scope="bg-custom">RUN</th>
                            <th scope="bg-custom">Nombres</th>
                            <th scope="bg-custom">Primer Apellido</th>
                            <th scope="bg-custom">Segundo Apellido</th>
                            <th scope="bg-custom" class="text-center">Edad</th>
                            @if ($tramite->titular->PER_EDAD >= 18)
                                <th class="bg-custom" scope="col">Firma mayor de 18 años</th>
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
                                <td class="text-center">{{ App\Utiles::capitalizaTexto($persona->PER_EDAD) }}</td>
                                @if ($tramite->titular->PER_EDAD >= 18)
                                    <td>
                                        @if (
                                            $tramite->solicitud->FIRMA_SIMPLE &&
                                                $portal == 'ciudadano' &&
                                                $persona->PER_EDAD >= \App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD)
                                            Firmado Digitalmente
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <section>
        <div class="p-1 m-1">
            <p class="mb-0"> IMPORTANTE<br>
                Si la desvinculación corresponde a un Niño, Niña y/o Adolescente podrá realizarse solo si va acompañada
                de su padre, madre o persona adulta que tiene
                su cuidado personal a menos que presente una Resolución judicial que entregue el cuidado personal a otra
                persona adulta responsable.</p><br>
            <p class="mb-0"> Si el solicitante es Jefe de Hogar solo se requiere su firma, en el caso que otra persona
                integrante del hogar requiere desvincular a otro integrante del
                mismo hogar requerirá la firma de la Jefatura de hogar</p>
        </div>
    </section>
    <!-- end Form according to request  -->
</main><!-- end Main -->
