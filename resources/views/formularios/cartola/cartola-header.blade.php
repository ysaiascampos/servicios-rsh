<!-- Header -->
<header class="row no-gutters justify-content-between">
    <img id="logo" src="{{asset('assets/img/logo.jpg')}}" alt="logo mdsf" width="117px" height="100px">
    <div class="header-title text-center mt-4">
        <h5 class="card-title cartola-title">
            {{$titulo}}
        </h5>
        <h5 class="card-title cartola-title">Registro Social de Hogares</h5>
    </div>
    <div class="header-info border rounded row no-gutters justify-content-between">
        <ul class="header-info__list pr-2">
            <li>Nº registro: <span>{{isset($grupoFamiliarExport['GRUPFAMI_NUEVOFOLIO'])?$grupoFamiliarExport['GRUPFAMI_NUEVOFOLIO']:''}}</span></li>
            <li>Fecha Confirmación<br>Ciudadano: <span>{{isset($grupoFamiliarExport['fechaValidacion'])?$grupoFamiliarExport['fechaValidacion']:''}}</span></li>
        </ul>
        @if(isset($qrcode))
            <div class="text-right">
                <img src="data:image/png;base64, {!! $qrcode !!}" width="70px">
            </div>
        @endif
    </div>
</header>
<!-- end Header -->
