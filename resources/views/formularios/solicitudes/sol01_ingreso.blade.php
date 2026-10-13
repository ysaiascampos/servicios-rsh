<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">SOLICITO EL INGRESO DE LA/S
                    SIGUIENTE/S
                    PERSONA/S,
                    QUIEN/ES DECLARA/N PERTENECER AL HOGAR Y AUTORIZAN ESTA SOLICITUD:</h6>
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th class="bg-custom" scope="col">Nº</th>
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
                            <th class="bg-custom" scope="col">Firma mayores de 18 años</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tramite->beneficiariosSolicitudActual as $key => $beneficiario)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ App\Utiles::ponePuntosAlRun($beneficiario->PER_RUN, $beneficiario->PER_DV) }}
                                </td>
                                <td>{{ App\Utiles::capitalizaTexto($beneficiario->PER_NOMBRES) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($beneficiario->PER_APELLIDOPATERNO) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($beneficiario->PER_APELLIDOMATERNO) }}</td>
                                <td>{{ App\Utiles::capitalizaTexto($beneficiario->PER_EDAD) }}</td>
                                @if ($contador_parentesco != 0)
                                    @if ($beneficiario->NOMBRE_PARENTESCO != null || $beneficiario->NOMBRE_PARENTESCO != '')
                                        <td>{{ App\Utiles::capitalizaTexto($beneficiario->NOMBRE_PARENTESCO) }}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                @endif
                                <th>
                                    @if (
                                        $tramite->solicitud->FIRMA_SIMPLE &&
                                            $portal == 'ciudadano' &&
                                            $beneficiario->PER_EDAD >= \App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD)
                                        Firmado Digitalmente
                                        @elseif(
                                        $tramite->solicitud->FIRMA_SIMPLE &&
                                            $portal == 'ciudadano' &&
                                            $beneficiario->PER_EDAD < \App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD)
                                        No Aplica
                                    @endif
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @if ($tramite->solicitud->ID_TIPO_SOLICITUD != App\Constantes\Constante::TIPO_SOLICITUD_206_INGRESO_AL_REGISTRO_NNA)
        <section>
            <div class="card p-1 m-1">
                <div class="card-body p-1 m-1">
                    <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">DECLARO, BAJO MI
                        RESPONSABILIDAD, QUE LA FIRMA
                        DE LOS INTEGRANTES NO ES POSIBLE OBTENERLA POR:</h6>
                    <div class="d-flex flex-column">
                        <div class="p-0">
                            <div class="form-row">
                                <h6 class="form-label">Nombre Completo:</h6>
                                <div class="form-line"></div>
                            </div>
                            <div class="form-row">
                                <h6 class="form-label">Motivo:</h6>
                                <div class="form-line"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
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
                            <td>
                                @if($tramite->grupoFamiliarSolicitudActual->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_COMPANIA_LUZ }}</td>
                            @endif
                            <td class="bg-custom">Empresa/Cooperativa de agua:</td>
                            <td>
                                @if($tramite->grupoFamiliarSolicitudActual->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarSolicitudActual->NOMBRE_COMPANIA_AGUA }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="bg-custom">N° Cliente de Luz:</td>
                            <td>
                                @if($tramite->grupoFamiliarSolicitudActual->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_NUMCLIENTELUZ }}
                                @endif
                            </td>
                            <td class="bg-custom">N° Cliente de Agua:</td>
                            <td>
                                @if($tramite->grupoFamiliarSolicitudActual->GRUPFAMI_TIPOVIVIENDA < 8 )
                                    {{ $tramite->grupoFamiliarSolicitudActual->GRUPFAMI_NUMCLIENTEAGUA }}
                                @endif
                            </td>
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
    <!-- end Form according to request  -->
</main><!-- end Main -->
