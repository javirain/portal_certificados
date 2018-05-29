@extends('certificados.layout.layout-cert')

@section('content')
<div id="page-certificados-actuales">
    <div id="datos_certificado" class="left">
        @include('certificados.includes.inicio-dialog-form')
        @include('certificados.includes.inicio-cambio-clave')
        @include('certificados.includes.inicio-datos-usuario')

        <?php
            // TODO
        $query = "select CEF_NOMBRE_CERTIFICADO, CEF_DESCRIPCION_CERTIFICADO, CEF_FECHA_CERTIFICADO, CEF_CODIGO_VERIFICACION, CEF_URL, CEF_FECHA_VIGENCIA
		from Portal_procesos..SESION_USUARIO_PORTAL sup
		inner join Portal_procesos..CERTIFICADO_EMITIDO_FEA cef on
			cef.PWE_CORRELATIVO = sup.PWE_CORRELATIVO
			and cef.SUP_CORRELATIVO = sup.SUP_CORRELATIVO
		where sup.PWE_CORRELATIVO = 2
			and sup.USU_LOGIN = ".session('id_usuario')."
			and cef.CEF_FECHA_CERTIFICADO <= GETDATE()
			and cef.CEF_FECHA_VIGENCIA >= GETDATE()
			and len(CEF.CEF_URL) > 0
		order by CEF_FECHA_VIGENCIA desc";
        $vigentes = query($query);
        //dd($vigentes);
            $vigentes = [];
        $c1 = new stdClass();
        $c1->CEF_NOMBRE_CERTIFICADO = 'Certificado Falso 1';
        $c1->CEF_DESCRIPCION_CERTIFICADO = 'Este es un certificadode prueba porque la conexion no funciona.';
        $c1->CEF_FECHA_CERTIFICADO = '2017/12/01';
        $c1->CEF_CODIGO_VERIFICACION = '123123123';
        $c1->CEF_URL = 'http://asdasd.com';
        $c1->CEF_FECHA_VIGENCIA = '2017/12/01';
        array_push($vigentes, $c1);
        $c2 = new stdClass();
        $c2->CEF_NOMBRE_CERTIFICADO = 'Certificado Falso 2';
        $c2->CEF_DESCRIPCION_CERTIFICADO = 'Este es un certificadode prueba porque la conexion no funciona.';
        $c2->CEF_FECHA_CERTIFICADO = '2016/12/01';
        $c2->CEF_CODIGO_VERIFICACION = '123123123';
        $c2->CEF_URL = 'http://asdasd.com';
        $c2->CEF_FECHA_VIGENCIA = '2016/12/01';
        array_push($vigentes, $c2);
        ?>
    </div>
    <div class="contenido-right">
        <form id="certificados-gratis-emitidos-form" method="post" action="?page=certificados-gratis-emitidos">
            <div id="listado-certificados-gratis" class="lista-cert">
                <div class="titulo"><span>CERTIFICADOS VIGENTES</span></div>
                <div class="listado">
                    @foreach ($vigentes as $vigente)
                        <div class="item-cert"><!---------------------------->
                            <div class="tipo-cert"><a href="{{ $HtmlView . $vigente->CEF_URL }}" target="_blank">{{ $vigente->CEF_NOMBRE_CERTIFICADO }}</a><span class="fecha-emitido">válido hasta: {{ date("d/m/Y",strtotime($vigente->CEF_FECHA_VIGENCIA)) }}</span></div>
                            <div class="subtitulo-cert">{{ $vigente->CEF_DESCRIPCION_CERTIFICADO }}</div>
                            <a href="pages/PDF/mostrar-pdf.php?pdf={{ $vigente->CEF_CODIGO_VERIFICACION }}"><div class="botn-dow"></div></a>
                        </div><!-------------------------------------------->
                    @endforeach
                </div><!---------------------listado-------------------->
                <a href="pages/PDF/download-vig.php?rut={{ session('id_usuario') }}"<div class="btn-emitir" style="padding:0 10px">descargar todos</div></a>
            </div>
        </form>
    </div>
</div>
@endsection()