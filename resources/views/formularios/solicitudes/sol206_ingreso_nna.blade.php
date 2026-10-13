<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">INFORMACIÓN DEL DOMICILIO
                    DEL NIÑO, NIÑA O
                    ADOLESCENTE:</h6>
                <table class="table table-bordered table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="bg-custom">Calle:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->CALLE }}</td>
                            <td class="bg-custom">Número:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_NUMDOMICILIO }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Depto:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_DPTO }}</td>
                            <td class="bg-custom">Block:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_BLOCK }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Casa:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_CASA }}</td>
                            <td class="bg-custom">Comuna:</td>
                            <td>
                                @if (!is_null($tramite->grupoFamiliarSolicitudActual->COMUNA))
                                    {{ $tramite->grupoFamiliarSolicitudActual->COMUNA->DESCRIPCION }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Agrupación habitacional:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_AGRUPACION_HABITACIONAL }}</th>
                            <td class="bg-custom">Unidad vecinal:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_UNIDAD_VECINAL }}</th>
                        </tr>
                        <tr>
                            <td class="bg-custom">Km / Sitio:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_SITIO }}</th>
                            <td class="bg-custom">Ciudad / Localidad:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->LOCALIDAD->NOMBRE }}</th>
                        </tr>
                        <tr>
                            <td class="bg-custom">Empresa/Cooperativa eléctrica:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_COMPANIA_LUZ }}</td>
                            <td class="bg-custom">Empresa/Cooperativa de agua:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_COMPANIA_AGUA }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">N° Cliente de Luz:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_NUMCLIENTELUZ }}</td>
                            <td class="bg-custom">N° Cliente de Agua:</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_NUMCLIENTEAGUA }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Referencia:</td>
                            <td colspan="3" class="border-bottom-0">
                                {{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_REFERENCIA }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                @if ($tramite->solicitud->MIGRANTE == 1)
                    <h5 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">INFORMACIÓN DE LAS
                        PERSONAS QUE CONFORMAN EL
                        HOGAR:</h5>
                @else
                    <h5 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">RESIDENCIA:</h5>
                @endif
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="bg-custom" scope="col">N°</th>
                            <th class="bg-custom" scope="col">RUN</th>
                            <th class="bg-custom" scope="col">Nombres</th>
                            <th class="bg-custom" scope="col">Primer Apellido</th>
                            <th class="bg-custom" scope="col">Segundo Apellido</th>
                            <th class="bg-custom" scope="col">Edad</th>
                            @php
                                $contador_parentesco = 0;
                            @endphp
                            @foreach ($tramite->beneficiariosSolicitudActual as $key => $beneficiario)
                                @if ($beneficiario->NOMBRE_PARENTESCO != null || $beneficiario->NOMBRE_PARENTESCO != '')
                                    @php
                                        $contador_parentesco = $contador_parentesco + 1;
                                    @endphp
                                @endif
                            @endforeach
                            @if ($contador_parentesco != 0)
                                <th class="bg-custom" scope="col">Parentesco</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tramite->beneficiariosSolicitudActual as $key => $beneficiario)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ App\Utiles::ponePuntosAlRun($beneficiario->PER_RUN, $beneficiario->PER_DV) }}
                                </td>
                                <td>{{ App\Utiles::capitalizaTexto($beneficiario->PER_NOMBRES) }} </td>
                                <td> {{ App\Utiles::capitalizaTexto($beneficiario->PER_APELLIDOPATERNO) }} </td>
                                <td> {{ App\Utiles::capitalizaTexto($beneficiario->PER_APELLIDOMATERNO) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($beneficiario->PER_EDAD) }}</td>
                                @if ($contador_parentesco != 0)
                                    @if ($beneficiario->NOMBRE_PARENTESCO != null || $beneficiario->NOMBRE_PARENTESCO != '')
                                        <td>{{ App\Utiles::capitalizaTexto($beneficiario->NOMBRE_PARENTESCO) }}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- end Form according to request  -->
</main><!-- end Main -->
