<!-- Footer -->
<footer class="mb-1">
    <div class="py-2 px-3 rounded text-justify bg-light mb-2">
        Se recuerda que mantener actualizada esta información es de su responsabilidad. Por tanto, si la información incorporada en este documento es  distinta  a  su  situación  actual,  debe  ingresar  a  la  página  web  www.registrosocial.gob.cl  o  dirigirse  a  su  Municipalidad  para  pedir  su  actualización.
    </div>
    <div class="card sign-form">
        <div class="card-body">
            <!-- Contact -->
            <h5 class="text-center mb-3">Se reciben consultas del Registro Social de Hogares en:</h5>
            <div class="row flex-nowrap justify-content-between px-3">
                <div>
                    <span class="fas fa-desktop"></span>
                    <span><strong>registrosocial.gob.cl</strong></span>
                </div>
                <div>
                    <span class="fas fa-phone-alt"></span>
                    <span><strong>800 104 777</strong> </span>
                </div>
                <div>
                    <span class="fas fa-home"></span>
                    <span><strong>Oficinas Chile Atiende</strong></span>
                </div>
                <div>
                    <span class="fas fa-building"></span>
                    <span><strong>Municipalidad</strong></span>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end Footer -->
<div class="row no-gutters">
    <div class="col-5"><img id="logo-footer" src="{{asset('assets/img/mds-logo-compacto.png')}}" alt="mds-logo-compacto" width="120px"></div>
    <div class="col-7"><small class="text-secondary">Esta cartola fue impresa el {{date(" j ")}} de {{ App\Utiles::obtenerMes(date("m")) }} a las {{date(" H:i")}} hrs. @if(isset($comunaUsuario) && is_object($comunaUsuario)) Desde {{$descripcionPerfil}}@endif</small></div>
</div>
