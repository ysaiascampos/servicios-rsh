@php
    $ocultarFirmaCuidador = false;
    $ocultarFirmaCuidado = false;

    $extraerRun = function($data) {
        if (empty($data)) {
            return null;
        }
        if (is_numeric($data)) {
            return intval($data);
        }
        if (is_object($data)) {
            if (isset($data->PER_RUN)) return intval($data->PER_RUN);
            if (isset($data->per_run)) return intval($data->per_run);
            if (isset($data->RUN_CIUDADANO)) return intval($data->RUN_CIUDADANO);
            if (isset($data->run_ciudadano)) return intval($data->run_ciudadano);
            if (isset($data->RUT_CUIDADOR)) return intval($data->RUT_CUIDADOR);
            if (isset($data->rut_cuidador)) return intval($data->rut_cuidador);
            if (isset($data->RUT_CUIDADO)) return intval($data->RUT_CUIDADO);
            if (isset($data->rut_cuidado)) return intval($data->rut_cuidado);
        }
        if (is_array($data)) {
            if (isset($data['PER_RUN'])) return intval($data['PER_RUN']);
            if (isset($data['per_run'])) return intval($data['per_run']);
            if (isset($data['RUN_CIUDADANO'])) return intval($data['RUN_CIUDADANO']);
            if (isset($data['run_ciudadano'])) return intval($data['run_ciudadano']);
            if (isset($data['RUT_CUIDADOR'])) return intval($data['RUT_CUIDADOR']);
            if (isset($data['rut_cuidador'])) return intval($data['rut_cuidador']);
            if (isset($data['RUT_CUIDADO'])) return intval($data['RUT_CUIDADO']);
            if (isset($data['rut_cuidado'])) return intval($data['rut_cuidado']);
        }
        return null;
    };

    $extraerGrupo = function($data) {
        if (empty($data)) return null;
        if (is_object($data)) {
            if (isset($data->GRUPOFAMILIAR)) return $data->GRUPOFAMILIAR;
            if (isset($data->grupofamiliar)) return $data->grupofamiliar;
            if (isset($data->GRUPFAMI_NUEVOFOLIO)) return $data->GRUPFAMI_NUEVOFOLIO;
        }
        if (is_array($data)) {
            if (isset($data['GRUPOFAMILIAR'])) return $data['GRUPOFAMILIAR'];
            if (isset($data['grupofamiliar'])) return $data['grupofamiliar'];
            if (isset($data['GRUPFAMI_NUEVOFOLIO'])) return $data['GRUPFAMI_NUEVOFOLIO'];
        }
        return null;
    };

    $subTipoCuidadores = isset($tramite->solicitud->ID_SUB_TIPO_SOLICITUD) ? $tramite->solicitud->ID_SUB_TIPO_SOLICITUD : null;
    if (empty($subTipoCuidadores) && isset($tramite->subTipo)) {
        $subTipoCuidadores = $tramite->subTipo;
    }
    if (empty($subTipoCuidadores) && isset($tramite->registroCuidador[0]->C_TCOMPLEMENTO_CUIDADORES)) {
        $subTipoCuidadores = $tramite->registroCuidador[0]->C_TCOMPLEMENTO_CUIDADORES;
    }

    $esCese = ($subTipoCuidadores == \App\Constantes\Constante::SUB_TIPO_SOLICITUD_55_COMPLEMENTO_CUIDADORES_3_CESE
        || $subTipoCuidadores == 3
        || $subTipoCuidadores === '3'
        || (is_string($subTipoCuidadores) && strtolower($subTipoCuidadores) === 'cese')
        || (isset($tramite->nombreSolicitud) && stripos($tramite->nombreSolicitud, 'cese') !== false)
        || (isset($tramite->tituloCuidado) && stripos($tramite->tituloCuidado, 'deja de recibir') !== false)
        || (isset($tramite->tituloCuidador) && stripos($tramite->tituloCuidador, 'deja de ser') !== false));

    if ($esCese) {
        $cuidadorRun = $extraerRun(isset($tramite->cuidador) ? $tramite->cuidador : (isset($tramite->registroCuidador[0]->RUT_CUIDADOR) ? $tramite->registroCuidador[0]->RUT_CUIDADOR : null));
        $cuidadoRun = $extraerRun(isset($tramite->cuidado) ? $tramite->cuidado : (isset($tramite->registroCuidador[0]->RUT_CUIDADO) ? $tramite->registroCuidador[0]->RUT_CUIDADO : null));
        $solicitanteRun = $extraerRun(isset($tramite->solicitante) ? $tramite->solicitante : (isset($tramite->solicitud->RUN_CIUDADANO) ? $tramite->solicitud->RUN_CIUDADANO : null));

        $grupoCuidador = $extraerGrupo(isset($tramite->cuidador) ? $tramite->cuidador : null);
        $grupoCuidado = $extraerGrupo(isset($tramite->cuidado) ? $tramite->cuidado : null);
        $grupoSolicitante = $extraerGrupo(isset($tramite->solicitante) ? $tramite->solicitante : null);

        $mismoHogarCuidadorCuidado = false;
        if ($cuidadorRun && $cuidadoRun) {
            if ($cuidadorRun == $cuidadoRun) {
                $mismoHogarCuidadorCuidado = true;
            } elseif (!empty($grupoCuidador) && !empty($grupoCuidado)) {
                $mismoHogarCuidadorCuidado = ($grupoCuidador == $grupoCuidado);
            } else {
                $checkHogar = \App\Utiles::verificaRunesTienenMismoGrupoFamiliar($cuidadorRun, $cuidadoRun);
                if ($checkHogar && isset($checkHogar->ESTADO) && $checkHogar->ESTADO == '1') {
                    $mismoHogarCuidadorCuidado = true;
                }
            }
        }

        $solicitanteEsCuidadorOHogarCuidador = false;
        if ($solicitanteRun && $cuidadorRun) {
            if ($solicitanteRun == $cuidadorRun) {
                $solicitanteEsCuidadorOHogarCuidador = true;
            } elseif (!empty($grupoSolicitante) && !empty($grupoCuidador)) {
                $solicitanteEsCuidadorOHogarCuidador = ($grupoSolicitante == $grupoCuidador);
            } else {
                $checkHogarCuidador = \App\Utiles::verificaRunesTienenMismoGrupoFamiliar($solicitanteRun, $cuidadorRun);
                if ($checkHogarCuidador && isset($checkHogarCuidador->ESTADO) && $checkHogarCuidador->ESTADO == '1') {
                    $solicitanteEsCuidadorOHogarCuidador = true;
                }
            }
        }

        $solicitanteEsCuidadoOHogarCuidado = false;
        if ($solicitanteRun && $cuidadoRun) {
            if ($solicitanteRun == $cuidadoRun) {
                $solicitanteEsCuidadoOHogarCuidado = true;
            } elseif (!empty($grupoSolicitante) && !empty($grupoCuidado)) {
                $solicitanteEsCuidadoOHogarCuidado = ($grupoSolicitante == $grupoCuidado);
            } else {
                $checkHogarCuidado = \App\Utiles::verificaRunesTienenMismoGrupoFamiliar($solicitanteRun, $cuidadoRun);
                if ($checkHogarCuidado && isset($checkHogarCuidado->ESTADO) && $checkHogarCuidado->ESTADO == '1') {
                    $solicitanteEsCuidadoOHogarCuidado = true;
                }
            }
        }

        // Regla Cese (Subtipo 3):
        if ($solicitanteEsCuidadorOHogarCuidador) {
            // Cuando la solicitante es la persona cuidadora:
            // 1. La firma de la persona cuidadora DEBE APARECER visualmente (tanto para mismo hogar como distinto grupo hogar)
            $ocultarFirmaCuidador = false;

            // 2. La firma de la persona que deja de recibir cuidados SE OCULTA visualmente
            $ocultarFirmaCuidado = true;
        }

        // Regla Cese: Si el solicitante es la persona cuidada (o su hogar) y el cuidador es de distinto hogar -> Ocultar firma en la tabla de la persona cuidadora (deja de ser cuidadora)
        if ($solicitanteEsCuidadoOHogarCuidado && !$mismoHogarCuidadorCuidado) {
            $ocultarFirmaCuidador = true;
        }
    }

    // Regla global: Menores de 18 años nunca firman
    $edadCuidado = null;
    if (isset($tramite->cuidado->PER_EDAD)) {
        $edadCuidado = intval($tramite->cuidado->PER_EDAD);
    } elseif (isset($tramite->cuidado['PER_EDAD'])) {
        $edadCuidado = intval($tramite->cuidado['PER_EDAD']);
    }
    if ($edadCuidado !== null && $edadCuidado < \App\Constantes\Constante::EDAD_18_MAYOR_DE_EDAD) {
        $ocultarFirmaCuidado = true;
    }
@endphp

<!-- Main -->
<main>
    <!-- Form according to request  -->
    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">
                    {{ $tramite->tituloCuidador }}
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
                            @if (!$ocultarFirmaCuidador)
                                <th class="bg-custom" scope="col">Firma mayor de 18 años</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ 1 }}</td>
                            <td>{{ App\Utiles::ponePuntosAlRun($tramite->cuidador->PER_RUN, $tramite->cuidador->PER_DV) }}
                            </td>
                            <td>{{ App\Utiles::capitalizaTexto($tramite->cuidador->PER_NOMBRES) }}</td>
                            <td>{{ App\Utiles::capitalizaTexto($tramite->cuidador->PER_APELLIDOPATERNO) }}</td>
                            <td>{{ App\Utiles::capitalizaTexto($tramite->cuidador->PER_APELLIDOMATERNO) }}</td>
                            <td class="text-center">{{ App\Utiles::capitalizaTexto($tramite->cuidador->PER_EDAD) }}</td>
                            @if (!$ocultarFirmaCuidador)
                                @if (
                                    ($portal == 'ciudadano' ) && ($tramite->cuidador['PER_RUN'] == $tramite->solicitante['PER_RUN']))
                                    <td><strong>Firmado Digitalmente</strong></td>
                                @else
                                    <td></td>
                                @endif
                            @endif
                        </tr>
                        <tr>
                            @if ($ocultarFirmaCuidador)
                                <td colspan="2" class="bg-custom text-center" scope="col">Rol:</td>
                                <td colspan="2" class="text-center">Principal:
                                    @if ($tramite->registroCuidador[0]->PRINCIPAL == 1)
                                        __X__
                                    @else
                                        _____
                                    @endif
                                </td>
                                <td colspan="2" class="text-center">Secundario:
                                    @if ($tramite->registroCuidador[0]->PRINCIPAL == 0)
                                        __X__
                                    @else
                                        _____
                                    @endif
                                </td>
                            @else
                                <td colspan="3" class="bg-custom text-center" scope="col">Rol:</td>
                                <td colspan="2" class="text-center">Principal:
                                    @if ($tramite->registroCuidador[0]->PRINCIPAL == 1)
                                        __X__
                                    @else
                                        _____
                                    @endif
                                </td>
                                <td colspan="2" class="text-center">Secundario:
                                    @if ($tramite->registroCuidador[0]->PRINCIPAL == 0)
                                        __X__
                                    @else
                                        _____
                                    @endif
                                </td>
                            @endif
                        </tr>
                    </tbody>
                </table>


            </div>
        </div>
    </section>



    <section>
        <div class="card p-1 m-1">
            <div class="card-body p-1 m-1">
                <h6 class="text-left font-weight-bold text-black mt-0.5 mb-0.5 title-box">
                    {{ $tramite->tituloCuidado }}
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
                            @if (!$ocultarFirmaCuidado)
                                <th scope="bg-custom">Firma mayores de 18 años</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ 1 }}</td>
                            <td>{{ App\Utiles::ponePuntosAlRun($tramite->cuidado->PER_RUN, $tramite->cuidado->PER_DV) }}
                            </td>
                            <td>{{ App\Utiles::capitalizaTexto($tramite->cuidado->PER_NOMBRES) }}</td>
                            <td>{{ App\Utiles::capitalizaTexto($tramite->cuidado->PER_APELLIDOPATERNO) }}</td>
                            <td>{{ App\Utiles::capitalizaTexto($tramite->cuidado->PER_APELLIDOMATERNO) }}</td>
                            <td class="text-center">{{ App\Utiles::capitalizaTexto($tramite->cuidado->PER_EDAD) }}</td>
                            @if (!$ocultarFirmaCuidado)
                                @if (
                                   ($portal == 'ciudadano' ) && ($tramite->cuidado['PER_RUN'] == $tramite->solicitante['PER_RUN']))
                                    <td><strong> Firmado Digitalmente </strong></td>
                                @else
                                    <td></td>
                                @endif
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- end Form according to request  -->
</main><!-- end Main -->
