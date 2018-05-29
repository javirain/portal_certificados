<?php
/**
 * @var string $url_site
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Certificados UBB</title>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" href="{{ asset('recursos_certificados/css/validationEngine.jquery.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('recursos_certificados/css/style.default.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('recursos_certificados/css/bootstrap-fileupload.min.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('recursos_certificados/css/footer.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('recursos_certificados/css/header.css') }}" type="text/css" />
    <!--<link rel="stylesheet" href="css/style.shinyblue.css" type="text/css" />-->
    <script type="text/javascript" src="{{ asset('recursos_certificados/js/jquery-1.9.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('recursos_certificados/js/jquery.validationEngine-es.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('recursos_certificados/js/jquery.validationEngine.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('recursos_certificados/js/jquery-migrate-1.1.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('recursos_certificados/js/jquery-ui-1.9.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('recursos_certificados/js/modernizr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('recursos_certificados/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('recursos_certificados/js/jquery.cookie.js') }}"></script>
    <script type="text/javascript" src="{{ asset('recursos_certificados/js/custom.js') }}"></script>

        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery("#seleccionar_usuario").click(function(){
                    jQuery("#dialog-form #buscador_de_usuarios .field_modal").hide();
                    jQuery("#seleccionar_usuario").hide();
                    jQuery.post( "pages/inicio-datos-usuario-ajax.php", {
                        sup: "{{ session('sup_correlativo') }}",
                        rut: "{{ session('id_usuario') }}",
                        direccion_antiguo:'{{ session('direccion') }}',
                        direccion: jQuery("#direccion_field").val(),
                        comuna_antiguo: "{{ session('codigo_comuna') }}",
                        comuna: jQuery("#comuna_field").val(),
                        fecha_edit: "{!! date("Y-m-d") !!}"
                    }.done(function(data) {
                        if(data===1)
                        {
                            alert("Datos Personales actualizados correctamente");
                            jQuery("#dialog-form").dialog( "close" );
                            location.reload();
                        }
                    }))
                });

                // CAMBIAR CONTRASEÑA
                jQuery("#cambia_pass_form").validationEngine('attach', {
                    relative: true,
                    autoHidePrompt:true,
                    promptPosition:"topLeft",
                    onValidationComplete: function(form, status){
                        if(status){
                            if(jQuery('#password1').val()!=jQuery('#password2').val()){
                                alert("las contraseñas no coinciden");
                            }else{
                                return true;
                            }
                        }
                    }
                });
                jQuery("#cambia_pass_submit").click(function(){
                    jQuery("#dialog-form-clave #cambia_pass_form .field_modal").hide();
                    jQuery("#cambia_pass_submit").hide();

                    jQuery.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var token = jQuery("input[name=_token]").val();
                    jQuery.ajax({
                        method: 'POST',
                        url: "{{ url('cambiar-clave') }}",
                        data: {
                            _token: token,
                            rut: "{{ session('id_usuario') }}",
                            password0: jQuery("#password0").val(),
                            password1: jQuery("#password1").val(),
                            sup: "{{ session('sup_correlativo') }}"
                        },
                        success: function( data ) {
                            if(data === 1) {
                                alert("La contraseña fue actualizada correctamente");
                                jQuery("#cambia_pass_submit").show();
                                jQuery("#dialog-form-clave #cambia_pass_form .field_modal").show();
                                jQuery("#dialog-form-clave").dialog( "close" );
                            }
                            else {
                                alert("Contraseña no actualizada");
                                jQuery("#cambia_pass_submit").show();
                                jQuery("#dialog-form-clave #cambia_pass_form .field_modal").show();
                            }
                        }


                    });
                });

                // MESA DE AYUDA
                jQuery("#enviar-mesa").click(function(){
                    var flag = 1;
                    if(jQuery("#motivo_field").val()=='')
                    {
                        jQuery('#motivo_field').validationEngine('showPrompt', '*Seleccione un Motivo');
                        flag = 0;
                    }
                    if(jQuery("#correo_field").val()=='')
                    {
                        jQuery('#correo_field').validationEngine('showPrompt', '*Ingrese un Correox');
                        flag = 0;
                    }
                    if(jQuery("#asunto_field").val()=='')
                    {
                        jQuery('#asunto_field').validationEngine('showPrompt', '*Ingrese un Asunto');
                        flag = 0;
                    }
                    if(jQuery("#descripcion_field").val()=='')
                    {
                        jQuery('#descripcion_field').validationEngine('showPrompt', '*ingrese una Descripción');
                        flag = 0;
                    }
                    if(flag==1){ // pero mira todos esos if, papá
                        jQuery(".header-mesa-fielset .field_modal").hide();
                        jQuery("#enviar-mesa").hide();
                        jQuery("#enviando-mesa").fadeIn().delay(1800).css("background","none");
                        jQuery.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        var token = jQuery("input[name=_token]").val();
                        jQuery.ajax({
                            method: 'POST',
                            url: "{{ route('mesa-ayuda') }}",
                            data: {
                                _token: token,
                                correo: jQuery('#correo_field').val(),
                                motivo: jQuery('#motivo_field').val(),
                                descripcion: jQuery('#descripcion_field').val(),
                                asunto: jQuery('#asunto_field').val(),
                                sup: '{{ session('sup_correlativo') }}',
                                pwe_correlativo: '{{ session('pwe_correlativo') }}',
                                correo_usuario: '{{ session('correo_usuario') }}',
                                nombre: '{{ formatear_string(session('nombre_usuario')) }} {{ formatear_string(session('apellido_usuario')) }}'
                            },
                            success: function( data ) {
                                if(data == 1) {
                                    jQuery("#enviando-mesa").text("consulta enviada con éxito");
                                    console.log(data);
                                }
                            }

                        });

                    }
                });
            });
        </script>
    @if (!empty($error) && $error ==1)
        <style>
            .login-alert{
                display: block;
            }
        </style>
    @endif
</head>
@include('certificados.includes.header-body')
<div class="wrapper">
    <div class="maincontent">
        @yield('content')
    </div>
</div>
@include('certificados.includes.footer')