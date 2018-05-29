<?php
/**
 * @var $request
 * @var $datos_alumno
 */
?>
<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Certificados UBB</title>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

{{ Html::style('css/validationEngine.jquery.css') }}
{{ Html::style('css/style.default.css') }}
{{ Html::style('css/bootstrap-fileupload.min.css') }}

{{ HTML::script('js/jquery-1.9.1.min.js') }}
{{ HTML::script('js/jquery.validationEngine-es.js') }}
{{ HTML::script('js/jquery.validationEngine.js') }}
{{ HTML::script('js/jquery-migrate-1.1.1.min.js') }}
{{ HTML::script('js/jquery-ui-1.9.2.min.js') }}
{{ HTML::script('js/modernizr.min.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/jquery.cookie.js') }}
{{ HTML::script('js/custom.js') }}

<script type="text/javascript">
    jQuery(document).ready(function(){
    	jQuery("#seleccionar_usuario").click(function(){
	    	jQuery("#dialog-form #buscador_de_usuarios .field_modal").hide();
	    	jQuery("#seleccionar_usuario").hide();
			jQuery.post(
			    "pages/inicio-datos-usuario-ajax.php", {
			        sup: "{{ $request->session()->get('sup_correlativo') }}",
                    rut: "{{ $request->session()->get('id_usuario') }}",
                    direccion_antiguo: "{{ $datos_alumno['direccion'] }}",
                    direccion: jQuery("#direccion_field").val(),
                    comuna_antiguo: "{{ $datos_alumno['codigo_comuna'] }}",
                    comuna: jQuery("#comuna_field").val(),
                    fecha_edit: "{{ date("Y-m-d") }}"}
                ).done(function(data) {
					if(data==1)
					{
						alert("Datos Personales actualizados correctamente");
						jQuery("#dialog-form").dialog( "close" );
						location.reload();
					}
			});
		});	
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
			jQuery.post( "pages/inicio-cambio-clave-ajax.php", {
			    rut: "{{ $request->session()->get('id_usuario') }}",
                password0: jQuery("#password0").val(),
                password1: jQuery("#password1").val(),
                sup: "{{ $request->session()->get('sup_correlativo') }}"}
                ).done(function( data ) {
					if(data==1)
					{
						alert("La contraseña fue actualizada correctamente");
						jQuery("#cambia_pass_submit").show();
						jQuery("#dialog-form-clave #cambia_pass_form .field_modal").show();
						jQuery("#dialog-form-clave").dialog( "close" );
					}
					else
					{
						alert("Contraseña no actualizada");
						jQuery("#cambia_pass_submit").show();
						jQuery("#dialog-form-clave #cambia_pass_form .field_modal").show();
					}
			});
		});
		jQuery("#enviar-mesa").click(function(){
	    	var flag = 1;
	    	if(jQuery("#motivo_field").val()=='')
	    	{
	    		jQuery('#motivo_field').validationEngine('showPrompt', '*Seleccione un Motivo');
	    		flag = 0;
	    	}
	    	if(jQuery("#correo_field").val()=='')
	    	{
	    		jQuery('#correo_field').validationEngine('showPrompt', '*Ingrese un Correo');
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
	    	if(flag==1){
		    	jQuery(".header-mesa-fielset .field_modal").hide();
		    	jQuery("#enviar-mesa").hide();	
		    	jQuery("#enviando-mesa").fadeIn().delay(1800).css("background","none");;
				jQuery.post( "pages/mailMesaAyuda.php", {
				    correo: jQuery("#correo_field").val(),
                    motivo: jQuery("#motivo_field").val(),
                    descripcion: jQuery("#descripcion_field").val(),
                    asunto: jQuery("#asunto_field").val(),
                    sup: "{{ $request->session()->get('sup_correlativo') }}" ,
                    pwe_correlativo: "{{ $request->session()->get('pwe_correlativo') }}",
                    correo_usuario: "{{ $request->session()->get('correo_usuario') }}",
                    nombre: "{{ $request->session()->get('nombre_usuario') }} {{ $request->session()->get('apellido_usuario') }}"}
                ).done(function( data ) {
                    if(data==1)
                    {
                        jQuery("#enviando-mesa").text("consulta enviada con éxito");
                    }
                });
				}
		});	
    });
</script>
</head>
@if (!empty($ERROR) && $error==1)
    <style>
        .login-alert{
            display: block;
        }
    </style>
@endif

</head>