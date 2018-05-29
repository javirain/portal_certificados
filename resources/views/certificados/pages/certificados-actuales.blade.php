@extends('certificados.layout.layout-cert')

@section('content')
    <div id="page-certificados-actuales">
    <div id="datos_certificado" class="left">
        @include('certificados.includes.certificados-menu')
    </div>
    <div class="contenido-right">
        <form id="certificados-gratis-emitidos-form" method="post" action="?page=certificados-gratis-emitidos">
            <div id="listado-certificados-gratis" class="lista-cert">
                <div class="titulo"><span>CERTIFICADOS ACTUALES</span></div>
                <div class="listado">
                    <div class="item-cert"><!---------------------------->
                        <div class="tipo-cert">CERTIFICADO DE ALUMNO REGULAR</div>
                        <div class="subtitulo-cert">INGENIERÍA DE EJECUCIÓN EN COMPUTACIÓN E INFORMÁTICA 2013-I</div>
                        <a href="#"><div class="botn-dow"></div></a>
                    </div><!-------------------------------------------->
                    <div class="item-cert"><!---------------------------->
                        <div class="tipo-cert">CERTIFICADO DE ALUMNO REGULAR</div>
                        <div class="subtitulo-cert">INGENIERÍA DE EJECUCIÓN EN COMPUTACIÓN E INFORMÁTICA 2013-I</div>
                        <a href="#"><div class="botn-dow"></div></a>
                    </div><!-------------------------------------------->
                    <div class="item-cert"><!---------------------------->
                        <div class="tipo-cert">CERTIFICADO DE ALUMNO REGULAR</div>
                        <div class="subtitulo-cert">INGENIERÍA DE EJECUCIÓN EN COMPUTACIÓN E INFORMÁTICA 2013-I</div>
                        <a href="#"><div class="botn-dow"></div></a>
                    </div><!-------------------------------------------->
                    <div class="item-cert"><!---------------------------->
                        <div class="tipo-cert">CERTIFICADO DE ALUMNO REGULAR</div>
                        <div class="subtitulo-cert">INGENIERÍA DE EJECUCIÓN EN COMPUTACIÓN E INFORMÁTICA 2013-I</div>
                        <a href="#"><div class="botn-dow"></div></a>
                    </div><!-------------------------------------------->
                </div><!---------------------listado-------------------->
                <div class="btn-emitir" style="padding:0 10px">descargar todos</div>
            </div>
        </form>
    </div>
</div>
@endsection()