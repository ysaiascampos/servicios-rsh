<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <div class="row no-gutters justify-content-between">
                    <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">DATOS DEL DIRECTOR DEL
                        CENTRO:</h6>
                    <h6 class="card-title mt-0.5 mb-0.5">Fecha de Resolución: <span
                            class="bg-light p-1 m-1 border rounded">{{ \Carbon\Carbon::parse($tramite->institucion->FECHA_RESOLUCION)->format('d-m-Y') }}</span>
                    </h6>
                </div>
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="bg-custom" scope="col">Primer Apellido</th>
                            <th class="bg-custom" scope="col">Segundo Apellido</th>
                            <th class="bg-custom" scope="col">Nombres</th>
                            <th class="bg-custom" scope="col">RUN</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="bg-custom" scope="col">Primer Apellido</th>
                            <td>{{ $tramite->institucion->PATERNO_DIRECTOR_CENTRO }}</td>
                            <th class="bg-custom" scope="col">Segundo Apellido</th>
                            <td>{{ $tramite->institucion->MATERNO_DIRECTOR_CENTRO }}</td>
                        </tr>
                        <tr>
                            <th class="bg-custom" scope="col">Nombres</th>
                            <td>{{ $tramite->institucion->NOMBRE_DIRECTOR_CENTRO }}</td>
                            <th class="bg-custom" scope="col">RUN</th>
                            <td>{{ App\Utiles::ponePuntosAlRun($tramite->institucion->RUN_DIRECTOR_CENTRO, $tramite->institucion->DV_DIRECTOR_CENTRO) }}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-custom" scope="col">Correo Electrónico</th>
                            <td>{{ $tramite->institucion->EMAIL_DIRECTOR_CENTRO }}</td>
                            <th class="bg-custom" scope="col">Teléfono Intitución</th>
                            <td>{{ $tramite->institucion->TELEFONO }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">RESIDENCIA:
                </h6>
                <table class="table table-bordered table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="bg-custom">Calle</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->CALLE }}</td>
                            <td class="bg-custom">Número</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_NUMDOMICILIO }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Depto</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_DPTO }}</td>
                            <td class="bg-custom">Block</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_BLOCK }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Casa</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_CASA }}</td>
                            <td class="bg-custom">Comuna</td>
                            <td>
                                @if (!is_null($tramite->grupoFamiliarSolicitudActual->COMUNA))
                                    {{ $tramite->grupoFamiliarSolicitudActual->COMUNA->DESCRIPCION }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Agrupación habitacional</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_AGRUPACION_HABITACIONAL }}</td>
                            <td class="bg-custom">Unidad vecinal</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_UNIDAD_VECINAL }}</td>
                        </tr>
                        <tr>
                            <td class="bg-custom">Km / Sitio</td>
                            <td>{{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_SITIO }}</td>
                            <td class="bg-custom">Ciudad / Localidad</td>
                            <td>
                                @if (isset($tramite->grupoFamiliarSolicitudActual->LOCALIDAD->NOMBRE))
                                    {{ $tramite->grupoFamiliarSolicitudActual->LOCALIDAD->NOMBRE }}
                                @endif
                            </td>
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
                            <td class="bg-custom">Referencia</td>
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
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">SOLICITO INGRESAR A LA
                    SIGUIENTE PERSONA AL REGISTRO SOCIAL DE HOGARES:</h6>
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="bg-custom" scope="col">Nº</th>
                            <th class="bg-custom" scope="col">RUN</th>
                            <th class="bg-custom" scope="col">Nombres</th>
                            <th class="bg-custom" scope="col">Primer Apellido</th>
                            <th class="bg-custom" scope="col">Segundo Apellido</th>
                            <th class="bg-custom" scope="col">Edad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tramite->personasAsociadas as $key => $personas)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ App\Utiles::ponePuntosAlRun($personas->PER_RUN, $personas->PER_DV) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($personas->PER_NOMBRES) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($personas->PER_APELLIDOPATERNO) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($personas->PER_APELLIDOMATERNO) }}</td>
                                <td>{{ $personas->PER_EDAD }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- end Form according to request  -->
</main><!-- end Main -->
