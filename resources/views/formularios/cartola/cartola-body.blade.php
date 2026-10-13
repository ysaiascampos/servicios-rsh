<?php use App\Utiles;?>
<section>
    <div class="py-2 px-3 text-justify rounded bg-light mb-2">
        {{isset($grupoFamiliarExport['tramo'])?$grupoFamiliarExport['tramo']->mensaje:''}}
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row no-gutters justify-content-between">
                <div class="user-info col-6">
                    <h5 class="card-title mb-3">{{Utiles::capitalizaTexto($ciudadanoExport->PER_NOMBRES . " " . $ciudadanoExport->PER_APELLIDOPATERNO . " " . $ciudadanoExport->PER_APELLIDOMATERNO)}}</h5>
                    <div class="row">
                        <h6 class="col-3">RUN:</h6>
                        <h6>{{Utiles::ponePuntosAlRun($ciudadanoExport->PER_RUN, $ciudadanoExport->PER_DV)}}</h6>
                    </div>
                    <div class="row">
                        <h6 class="col-3">Dirección del Hogar:</h6>
                        <h6 class="col-9 pl-0 font-weight-normal">@if(isset($grupoFamiliarExport['calle']))
                                <strong>{{Utiles::capitalizaTexto($grupoFamiliarExport['calle'])}}</strong>,
                            @endif
                            @if(isset($grupoFamiliarExport['GRUPFAMI_NUMDOMICILIO']))
                                Nro domicilio: <strong>{{$grupoFamiliarExport['GRUPFAMI_NUMDOMICILIO']}}</strong>,
                            @endif
                            @if(isset($grupoFamiliarExport['GRUPFAMI_DPTO']))
                                Dpto: <strong>{{$grupoFamiliarExport['GRUPFAMI_DPTO']}}</strong>,
                            @endif
                            @if(isset($grupoFamiliarExport['GRUPFAMI_BLOCK']))
                                Block: <strong>{{$grupoFamiliarExport['GRUPFAMI_BLOCK']}}</strong>,
                            @endif
                            @if(isset($grupoFamiliarExport['GRUPFAMI_CASA']))
                                Casa: <strong>{{$grupoFamiliarExport['GRUPFAMI_CASA']}}</strong>,
                            @endif
                            @if(isset($grupoFamiliarExport['GRUPFAMI_SITIO']))
                                Sitio: <strong>{{$grupoFamiliarExport['GRUPFAMI_SITIO']}}</strong>,
                            @endif
                            @if(isset($grupoFamiliarExport['comuna']['DESCRIPCION']))
                                <strong>{{Utiles::capitalizaTexto($grupoFamiliarExport['comuna']['DESCRIPCION'])}}</strong>.
                            @endif
                        </h6>
                    </div>
                    <div class="row">
                        <h6 class="col-3">Agrupación Habitacional:</h6>
                        @if(isset($grupoFamiliarExport['nombreAH']))
                            <h6>
                                {{Utiles::capitalizaPrimeraPalabra($grupoFamiliarExport['nombreAH'])}}
                            </h6>
                        @else
                            <h6>No hay registro</h6>
                        @endif
                    </div>
                </div>

                <div class="user-info col-6">
                    <h5 class="card-title mb-3 text-center">Calificación Socioeconómica</h5>
                    <div class="calif-economica">
                        <hr class="my-1">
                        <div class="row justify-content-between calif-economica__list">
                            <span class="{{$grupoFamiliarExport['tramo']->numero == 40?'selected':''}}">40%</span>
                            <span class="{{$grupoFamiliarExport['tramo']->numero == 50?'selected':''}}">50%</span>
                            <span class="{{$grupoFamiliarExport['tramo']->numero == 60?'selected':''}}">60%</span>
                            <span class="{{$grupoFamiliarExport['tramo']->numero == 70?'selected':''}}">70%</span>
                            <span class="{{$grupoFamiliarExport['tramo']->numero == 80?'selected':''}}">80%</span>
                            <span class="{{$grupoFamiliarExport['tramo']->numero == 90?'selected':''}}">90%</span>
                            <span class="{{$grupoFamiliarExport['tramo']->numero == 100?'selected':''}}">100%</span>
                        </div>
                        <hr class="my-1">
                        <div class="row justify-content-between px-3 mb-1">
                            <div class="text-center calif-economica__info">
                          <span>-Ingresos<br>
                             +Vulnerabilidad
                            </span>
                            </div>
                            <div class="text-center calif-economica__info">
                          <span>+Ingresos<br>
                            -Vulnerabilidad
                          </span>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2">
                        <?php
                        $contador = strlen($grupoFamiliarExport['tramo']->nombre);
                        if ($contador > 96){
                            $variableTexto1 = substr($grupoFamiliarExport['tramo']->nombre, 0, 35);
                            $variableTexto2 = substr($grupoFamiliarExport['tramo']->nombre, 35, 40);
                            $variableTexto3 = substr($grupoFamiliarExport['tramo']->nombre, -32, 32);
                        }else{
                            $variableTexto1 = substr($grupoFamiliarExport['tramo']->nombre, 0, 31);
                            $variableTexto2 = substr($grupoFamiliarExport['tramo']->nombre, 31, 32);
                            $variableTexto3 = substr($grupoFamiliarExport['tramo']->nombre, -32, 32);
                        }
                        ?>
                        <p class="text-center">{{$variableTexto1}}<strong>{{$variableTexto2}}</strong>{{$variableTexto3}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main -->
<main>
    <div class="py-2 px-3 rounded text-center bg-light mb-2">
        La Calificación Socioeconómica del hogar se calculó en base a los datos que se detallan a continuación:
    </div>
    <!-- Form according to request  -->
    <section>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Integrantes del Hogar</h5>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">RUN</th>
                        <th scope="col">Nombre Completo</th>
                        <th scope="col">Parentesco</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($integrantesExport as $int)
                        <tr>
                            <td>{{substr(Utiles::ponePuntosAlRun($int->PER_RUN, $int->PER_DV), 0, -5)."XXX-X"}}</td>
                            <td>{{Utiles::capitalizaTexto($int->PER_NOMBRES . " " . $int->PER_APELLIDOPATERNO . " " . $int->PER_APELLIDOMATERNO)}}</td>
                            <td>{{substr($int->PER_FIC_PARENTESCO, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div>
                    <h6 class="card-title">Características integrantes del hogar</h6>
                    <div class="row justify-content-between">
                        <div class="px-3">
                            <span>Menores de 18 años:</span>
                            <p class="text-secondary mb-0">{{$grupoFamiliarExport['cantidadMenoresDeEdad']}}
                                personas</p>
                        </div>
                        <div class="px-3">
                            <span>De 60 años o más:</span>
                            <p class="text-secondary mb-0">{{$grupoFamiliarExport['cantidadAdultoMayor']}} personas</p>
                        </div>
                        <div class="px-3">
                            <span>Dependencia o discapacidad:</span>
                            <p class="text-secondary mb-0">{{$grupoFamiliarExport['cantidadConDiscapacidad']}}
                                personas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="card">
            <div class="card-body pb-1">
                <div class="row no-gutters justify-content-between">
                    <div class="user-info col-6">
                        <h5 class="card-title mb-3">Ingresos del Hogar</h5>
                        <div class="row">
                            <h6 class="col-5">Ingresos del trabajo:</h6>
                            <h6>{{$grupoFamiliarExport['ingresoLaboralGrupoFamiliar']}}</h6>
                        </div>
                        <div class="row">
                            <h6 class="col-5">Ingresos de pensiones:</h6>
                            <h6>{{$grupoFamiliarExport['ingresoJubilacionGrupoFamiliar']}}</h6>
                        </div>
                        <div class="row">
                            <h6 class="col-5">Ingresos de capital:</h6>
                            <h6>{{$grupoFamiliarExport['ingresoOtrosGrupoFamiliar']}}</h6>
                        </div>
                        <small class="text-muted">En base a información disponible de los últimos 12 meses.</small>
                    </div>

                    <div class="user-info w-50 mt-5">
                        <div class="py-2 px-3 border rounded text-center bg-light">
                            <h5>Total Ingresos del hogar</h5>
                            <h5 class="text-dark font-weight-bold">{{$grupoFamiliarExport['ingresoTotalGrupoFamiliar']}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="card">
            <div class="card-body pb-1">
                <div class="p-0">
                    <h5 class="card-title mb-3">Datos Complementarios</h5>
                    @if(count($grupoFamiliarExport['datosComplementarios'])>0)
                        @foreach($grupoFamiliarExport['datosComplementarios'] as $datosComplementarios)
                            <p class="mb-2">{{$datosComplementarios->item}}</p>
                            <h5 class="text-secondary">{{$datosComplementarios->info}}</h5>
                        @endforeach
                    @else
                        <p class="mb-2">No hay datos complementarios que influyan en la calificación del hogar.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- end Form according to request  -->
</main><!-- end Main -->
