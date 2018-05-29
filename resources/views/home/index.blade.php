<?php
/**
 * NOTA: Tengo que sacar el CSS y el JS de acá, que ordinariors. Also hay clases CSS sin usar... ¿por que eres así?
 *
 * @var $acceso_gratis
 * @var $acceso_pago
 * @var $client_certificado
 * @var $PW_ID
 */
?>
@include('layouts.header')
<style>
    .social-icon{width:23px;height:23px;float:left;margin:0 5px 0 0}
    #face{ background:url({{ asset('images/fb.png') }}) }
    #twit{ background:url({{ asset('images/tt.png') }}) }
    #yout{ background:url({{ asset('images/yb.png') }}) }
    #link{ background:url({{ asset('images/in.png') }}) }
    #flick{ background:url({{ asset('images/fk.png') }}) }

    #mesa-ayuda #telefono{
        float:left;
        width:210px;
        margin: 15px 0 0 35px;
        color: white;
        font-size: 20px;
        font-weight: bold;
        font-family: arial, sans-serif;
    }
    #dialog .listado-certificado{
        background: none;
        margin:10px 0 0 0
    }
    #dialog .listado-certificado ul li{
        color: #20437D;
        margin: 0 0 5px 0
    }
    #dialog-verifica .titulo {
        background: none repeat scroll 0 0 #E98F00;
        border: 1px solid #C47902;
        color: #FFFFFF;
        height: 23px;
        margin: 0;
        width: 320px;
    }
    #dialog-verifica .titulo span{
        padding: 0 0 0 10px;
    }

    .item-cert {
        background: none repeat scroll 0 0 #EDEDED;
        float: left;
        margin: 5px 0;
        padding: 0 0 10px;
        width: 322px;
    }
    .lista-cert .listado .item-cert label {
        float: left;
        margin: 10px 0 0 15px;
        width: 300px;
    }
    .lista-cert .listado .item-cert label .tipo-cert {
        font-size: 16px;
        line-height: 18px;
        margin: 0 0 1px;
    }
    .lista-cert .listado .item-cert label .tipo-cert span {
        color: #E98F00;
        float: right;
        font-weight: bold;
    }
    .tipo-cert {
        float: left;
        font-size: 16px;
        line-height: 21px;
        margin: 10px 0 0 10px;
        width: 310px;
    }
    .fecha-emitido {
        color: #E98F00;
        float: right;
        font-size: 11px;
        margin: 0 10px 0 0;
    }
    .subtitulo-cert {
        float: left;
        font-size: 11px;
        line-height: 14px;
        margin: 2px 0 0 10px;
        width: 300px;
    }

    #form-verifica .inputwrapper{
        margin:30px 0 0 0
    }
    .inputwrapper .boton_verificar {
        background: #DD9300;
        font-size: 11px;
        line-height: 25px;
        margin: 20px auto 0;
        padding: 5px 20px;
        width: 130px;
        cursor:pointer;
        color:white;
    }

    .inputwrapper .boton_verificar:hover {
        background: #FF9D00;
        text-decoration:none;
    }
</style>
<body>
    <div class="mainwrapper">
        @include('layouts.header_body')
        <div id="sombra-wrapper"></div>
        <div class="wrapper">
            <div class="maincontent">
                <div class="maincontentinner">
                    <div class="row-fluid">
                        @if ($acceso_gratis == 1 || $acceso_pago == 1)
                        <div id="dashboard-right" class="span4 login_cert">
                            <div class="widgetbox" id="login_cert">
                                <div class="headtitle">
                                    <h4 class="">ACCESO A CERTIFICADOS</h4>
                                </div>
                                <form id="login-portal" action="{{ url('login') }}" method="post" >
                                    {{ csrf_field() }}
                                    @if ($errors->any())
                                        <div class="inputwrapper login-alert" style="display: inline">
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-error">{{ $error }}</div>
                                        @endforeach
                                        </div>
                                    @endif
                                    <div class="inputwrapper ">
                                        <input type="text" name="username" id="username" placeholder="R.U.T" />
                                    </div>
                                    <div class="inputwrapper">
                                        <input type="password" name="password" id="password" placeholder="Clave" />
                                    </div>
                                    <div class="inputwrapper">
                                        <button id="boton-ingresar" name="submit">Ingresar</button>
                                    </div>
                                    <div class="inputwrapper" id="olvide-pass">
                                        <a id="olvide-password" href="#" >Olvid&eacute; mi contrase&ntilde;a.</a>
                                    </div>
                                </form>
                                <div id="dialog-recupera" title="Recuperar Contraseña">
                                    <div id="form-recupera" style="margin:30px 0 0 0">
                                        <div class="inputwrapper bounceIn">
                                            <input style="border:1px #A9A9A9 solid;width:300px;margin:0 0 10px 0" type="text" name="rut_recupera" id="rut_recupera" placeholder="Ingrese RUT" class="validate[required] input-small hasDatepicker" />
                                        </div>
                                        <div class="inputwrapper bounceIn">
                                            <input class="validate[required]" style="border:1px #A9A9A9 solid;width:300px;margin:0 0 10px 0" type="email required" name="mail_recupera" id="mail_recupera" placeholder="Ingrese Correo Electrónico" />
                                        </div>
                                        <div class="inputwrapper bounceIn" style="height:55px">
                                            <input class="validate[required] datepicker" style="border:1px #A9A9A9 solid;width:130px;margin:0 0 10px 0;float:left" type="date" name="fecha_nac_recupera" id="fecha_nac_recupera" placeholder="Fecha Naciemieto" />
                                            <input class="validate[required]" style="border:1px #A9A9A9 solid;width:130px;margin:0 0 10px 0;float:right" type="text" name="ingreso_recupera" id="ingreso_recupera" placeholder="Año de Ingreso" />
                                        </div>
                                        <div class="inputwrapper  bounceIn" >
                                            <button id="btn-olvidar-password" style="background:#16335F;border:1px solid grey;width:300px">Recuperar Contraseña</button>
                                        </div>
                                    </div>
                                    <div id="texto-recupera" style="margin:30px 0 0 0;display:none"></div>
                                </div>
                            </div><!--widgetcontent-->
                        </div>
                        @endif
                        <div id="dashboard-left" class="span8 slide_cert">
                            <div class="widgetcontent nopadding">
                                <div class="slider-wrapper theme-light">
                                    <div id="slider" class="nivoSlider">

                                        <!-- TODO ajustar webservice -->
                                        @foreach ($client_certificado->call('getImagenesSlide', array('pw_id' => $PW_ID,'hpw_id'=>1,'shp_id'=>1)) as $slide_img)
                                            <img title='<b><h4>{{ $slide_img['titulo'] }}</h4></b><span>{{ $slide_img['detalle'] }}</span>' src='{{ asset('images/slide/' . substr($slide_img['ruta'], strrpos($slide_img['ruta'], '/'))) }}'/>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="wrapper">
            <div class="row-fluid">
                <div id="dashboard-right" class="span4 mesa-ayuda">
                    <div id="mesa-ayuda">
                        <div id="telefono">(54-41) 311 1218</div><!-------REEMPLAZAR AQUÍ EL NÚMERO DE TELÉFONO POR CONSULTA A BD---------->
                    </div>
                </div>
                <div id="dashboard-right" class="span4 listado-certificado">
                    <div id="titulo-listado"></div>
                    <ul>
                        @foreach ($vitrinas as $vitrina)
                            <li>{{ $vitrina['descripcion'] }} @if ($vitrina['nuevo'] == 1)<span>(Nuevo)</span>@endif</li>
                        @endforeach
                    </ul>

                    <div id="ver_mas_vitrina">ver más</div>

                    <div id="dialog" title="Certificados Disponibles">
                        <div class="listado-certificado">
                            <ul>
                                @foreach($vitrinas as $vitrina):
                                <li>{{ $vitrina['descripcion'] }}
                                    @if($vitrina['nuevo'] == 1) <span>(Nuevo)</span>@endif
                                    <span class="precio">
                                        @if ($vitrina['precio'] == 0)
                                            Gratis
                                        @else
                                            ${!! number_format($vitrina['precio'], 0, ",", ".") !!}
                                        @endif
                                    </span></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="dashboard-verifica" class="span4 acreditada">
                    <div id="line-footer"></div>
                    <div id="social">
                        <a target="_blank" href="https://twitter.com/ubbchile"><div id="twit" class="social-icon"></div></a>
                        <a target="_blank" href="http://www.youtube.com/user/udelbiobio"><div id="yout" class="social-icon"></div></a>
                        <a target="_blank" href="https://www.facebook.com/ubiobiochile"><div id="face" class="social-icon"></div></a>
                        <a target="_blank" href="http://cl.linkedin.com/in/ubiobio"><div id="link" class="social-icon"></div></a>
                        <a target="_blank" href="http://www.flickr.com/photos/ubiobio/"><div id="flick" class="social-icon"></div></a>
                    </div>
                    <div id="comision"></div>
                </div>
            </div>
        </div>
    </div><!--footer-->
    <div id="dialog-verifica" title="Verificar Certificado">
        <div id="form-verifica" style="margin:30px 0 0 0">
            <div class="inputwrapper bounceIn">
                <input class="validate[required]"  style="border:1px #A9A9A9 solid;width:300px;margin:0 0 10px 0" type="text" name="folio" id="folio_certificado" placeholder="Ingrese Folio Certificado" />
            </div>

            <div class="inputwrapper bounceIn">
                <input class="validate[required]" style="border:1px #A9A9A9 solid;width:300px;margin:0 0 10px 0" type="text" name="verificacion" id="verificacion_certificado" placeholder="Ingrese Código de Verificación" />
            </div>

            <div class="inputwrapper  bounceIn">
                <button id="btn-verificar-certificado" style="background:#16335F;border:1px solid grey;width:300px">Verificar</button>
            </div>
        </div>
        <div id="texto-verifica" style="margin:30px 0 0 0;display:none"></div>
    </div>
    {!! js_tag('jquery.nivo.slider.js') !!}
</body>
<!--</html>-->
{!! css_tag('smoothness/jquery-ui.css') !!}
<script type="text/javascript">
    jQuery(window).load(function() {
        jQuery('#slider').nivoSlider();
    });
    jQuery(function() {
        jQuery( "#dialog" ).dialog({
            autoOpen: false,
            height: 350,
            width: 350,
            modal: true
        });
        jQuery( "#dialog-verifica" ).dialog({
            autoOpen: false,
            height: 300,
            width: 350,
            modal: true
        });
        jQuery( "#dialog-recupera" ).dialog({
            autoOpen: false,
            height: 300,
            width: 350,
            modal: true
        });
        jQuery( "#ver_mas_vitrina" ).click(function() {
            jQuery( "#dialog" ).dialog( "open" );
        });
        jQuery( "#verifica-certificado" ).click(function() {
            jQuery("#form-verifica").show();
            jQuery("#texto-verifica").hide();
            jQuery( "#dialog-verifica" ).dialog( "open" );
        });
        jQuery( "#olvide-password" ).click(function() {
            jQuery("#form-recupera").show();
            jQuery("#rut_recupera").val('');
            jQuery("#mail_recupera").val('');
            jQuery("#fecha_nac_recupera").val('');
            jQuery("#ingreso_recupera").val('');
            jQuery("#texto-recupera").hide();
            jQuery( "#dialog-recupera" ).dialog( "open" );
        });
    });

</script>
