jQuery.noConflict();

jQuery(document).ready(function(){
	jQuery("#btn-verificar-certificado").click(function(){
		jQuery.post( "../_include/pages/verifica-certificado.php", { folio: jQuery("#folio_certificado").val(),verificacion: jQuery("#verificacion_certificado").val()})
			.done(function( data ) {
				if(data==0)
				{
					alert("Certificado no válido");
				}
				else{
					jQuery("#form-verifica").hide();
					jQuery("#texto-verifica").show();
					jQuery("#texto-verifica").html(data);
				}
		});
	});
	jQuery('.leftmenu .dropdown > a').click(function(){
		if(!jQuery(this).next().is(':visible'))
			jQuery(this).next().slideDown('fast');
		else
			jQuery(this).next().slideUp('fast');	
		return false;
	});
	if(jQuery.uniform) 
	   jQuery('input:checkbox, input:radio, .uniform-file').uniform();
		
	if(jQuery('.widgettitle .close').length > 0) {
		  jQuery('.widgettitle .close').click(function(){
					 jQuery(this).parents('.widgetbox').fadeOut(function(){
								jQuery(this).remove();
					 });
		  });
	}
   jQuery('<div class="topbar"><a class="barmenu">'+
		    '</a></div>').insertBefore('.mainwrapper');
	
	jQuery('.topbar .barmenu').click(function() {
		  
		  var lwidth = '260px';
		  if(jQuery(window).width() < 340) {
					 lwidth = '240px';
		  }
		  if(!jQuery(this).hasClass('open')) {
					 jQuery('.rightpanel, .headerinner, .topbar').css({marginLeft: lwidth},'fast');
					 jQuery('.logo, .leftpanel').css({marginLeft: 0},'fast');
					 jQuery(this).addClass('open');
		  } else {
					 jQuery('.rightpanel, .headerinner, .topbar').css({marginLeft: 0},'fast');
					 jQuery('.logo, .leftpanel').css({marginLeft: '-'+lwidth},'fast');
					 jQuery(this).removeClass('open');
		  }
	});
	// show/hide left menu
	jQuery(window).resize(function(){
		  if(!jQuery('.topbar').is(':visible')) {
		         jQuery('.rightpanel, .headerinner').css({marginLeft: '260px'});
					jQuery('.logo, .leftpanel').css({marginLeft: 0});
		  } else {
		         jQuery('.rightpanel, .headerinner').css({marginLeft: 0});
					jQuery('.logo, .leftpanel').css({marginLeft: '-260px'});
		  }
   });
	// dropdown menu for profile image
	jQuery('.userloggedinfo img').click(function(){
		  if(jQuery(window).width() < 480) {
					 var dm = jQuery('.userloggedinfo .userinfo');
					 if(dm.is(':visible')) {
								dm.hide();
					 } else {
								dm.show();
					 }
		  }
   });
	
	// change skin color
	jQuery('.skin-color a').click(function(){ return false; });
	jQuery('.skin-color a').hover(function(){
		var s = jQuery(this).attr('href');
		if(jQuery('#skinstyle').length > 0) {
			if(s!='default') {
				jQuery('#skinstyle').attr('href','css/style.'+s+'.css');	
				jQuery.cookie('skin-color', s, { path: '/' });
			} else {
				jQuery('#skinstyle').remove();
				jQuery.cookie("skin-color", '', { path: '/' });
			}
		} else {
			if(s!='default') {
				jQuery('head').append('<link id="skinstyle" rel="stylesheet" href="css/style.'+s+'.css" type="text/css" />');
				jQuery.cookie("skin-color", s, { path: '/' });
			}
		}
		return false;
	});
	
	// load selected skin color from cookie
	if(jQuery.cookie('skin-color')) {
		var c = jQuery.cookie('skin-color');
		if(c) {
			jQuery('head').append('<link id="skinstyle" rel="stylesheet" href="css/style.'+c+'.css" type="text/css" />');
			jQuery.cookie("skin-color", c, { path: '/' });
		}
	}
	jQuery("#region_field").change(function(){
		jQuery.post( "pages/getProvincia.php", { region: jQuery("#region_field").val()})
			.done(function( data ) {
				jQuery("#provincia_field").html(data);
		});
		jQuery("#comuna_field").html("<option  value='0'>Seleccione una provincia...</option>");
	});	  
	jQuery("#provincia_field").change(function(){
		jQuery.post( "pages/getComuna.php", { provincia: jQuery("#provincia_field").val()})
			.done(function( data ) {
				jQuery("#comuna_field").html(data);
		});
	});	
	jQuery("cerrar_dialogo").click(function(){
		$("#dialog-form").dialog( "close" );
	});
	jQuery("#gratis-emitidos").click(function(){
		jQuery("#certificados-gratis-form").submit();
	});	
	jQuery("#pago-emitidos").click(function(){
		jQuery("#certificados-pago-form").submit();
	});	
	jQuery("#listado-certificados-pago input[type=checkbox]").change(function(){
		//alert(jQuery(this).siblings().children(".tipo-cert").children().children(".valor-cert").html());
		var valueInput = jQuery(this).siblings().children(".tipo-cert").children().children(".valor-cert").html();
		var txtTotal = jQuery("#txt-total").html();
		var newtotal;
		if(jQuery(this).is(':checked')) {  
			newtotal = format(parseFloat(txtTotal.replace('.',''))+parseFloat(valueInput.replace('.','')));
            jQuery("#txt-total").html(newtotal);
            jQuery("#total_fiel").val(newtotal);  
        } else {
        	newtotal = format(parseFloat(txtTotal.replace('.',''))-parseFloat(valueInput.replace('.','')));
        	jQuery("#txt-total").html(newtotal); 
        	jQuery("#total_fiel").val(newtotal)
        }
	});
	jQuery("#certificados-pago-form").submit(function(){
		var txtTotal = jQuery("#txt-total").html();
		txtTotal = txtTotal.replace(".","");
		jQuery("#total_field").val(txtTotal);
	});
	jQuery("#certificados-pago-form").validationEngine('attach', {
		relative: true,
		scroll: false,
		promptPosition:"topLeft",
		autoHidePrompt: true,
    	autoHideDelay: 2000,
    	fadeDuration: 0.2,
		onValidationComplete: function(form, status){
			if(status){	
	    		if(jQuery('#correo1_pago').val()!=jQuery('#correo2_pago').val()){
					//alert("los correos no coinciden");
					jQuery('#correo-envio').validationEngine('showPrompt', 'Los correos no coinciden');
				}else{
        			return true;
				}
			}
  		}  
	});
	jQuery('#certificados-pago-form').submit(function(e) { 
	     var check = jQuery("input[type='checkbox'].checkbox:checked").length;	
	        if (check<1){
	        	jQuery('#certificados-pago-form').validationEngine('showPrompt', 'Seleccione al menos un certificado');
	           	e.preventDefault();
	        }
	});
	jQuery('#certificados-gratis-form').submit(function(e) { 
	     var check = jQuery("input[type='checkbox'].checkbox:checked").length;	
	        if (check<1){
	        	jQuery('#certificados-gratis-form').validationEngine('showPrompt', 'Seleccione al menos un certificado');
	           	e.preventDefault();
	        }
	});
	jQuery("#certificados-gratis-form").validationEngine('attach', {
		relative: true,
		scroll: false,
		promptPosition:"topLeft",
		autoHidePrompt: true,
    	autoHideDelay: 2000,
    	fadeDuration: 0.2,
		onValidationComplete: function(form, status){
			if(status){	
	    		if(jQuery('#correo1').val()!=jQuery('#correo2').val()){
					alert("los correos no coinciden");
				}else{
        			return true;
				}
			}
  		}  
	});
	
	jQuery("#form-mesda-ayuda").validationEngine('attach', {
		relative: true,
		scroll: false,
		promptPosition:"topLeft",
		autoHidePrompt: true,
    	autoHideDelay: 2000,
    	fadeDuration: 0.2 
	});
	jQuery(document).keydown(function(event) {
        if (event.ctrlKey==true && (event.which == '118' || event.which == '86')) {
           // alert('thou. shalt. not. PASTE!');
            event.preventDefault();
         }
    });

});

 function format(input)
    {
    var num = parseFloat(input);
    if(!isNaN(num)){
    	num = num.toString().split("").reverse().join("").replace(/(?=\d*\.?)(\d{3})/g,'$1.');
    num = num.split("").reverse().join("").replace(/^[\.]/,"");
    return num;
    }

    else{ alert('Solo se permiten numeros');
    return input.value.replace(/[^\d\.]*/g,"");
    }
   }

jQuery(function() {
	jQuery.urlParam = function(name){
	    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
	    return results[1] || 0;
	}
	/*if(jQuery.urlParam('page')=='' || jQuery.urlParam('page')=='inicio')
	{
		var name = jQuery( "#direccion_field" ),
		region = jQuery( "#region_field" ),
		provincia = jQuery("#provincia_field"),
		comuna = jQuery("#comuna_field"),
		allFields = jQuery( [] ).add( direccion_field ).add( region_field ).add(provincia_field).add(comuna_field),
		tips = jQuery( ".validateTips" );
	}*/
	jQuery( "#dialog-form").dialog({
		autoOpen: false,
		height: 410,
		width: 480,
		resizable: false,
		modal: true
	});
		jQuery( "#dialog-verifica" ).dialog({
			autoOpen: false,
			height: 300,
			width: 350,
			modal: true,
		});
		jQuery( "#verifica-certificado" ).click(function() {
			jQuery("#form-verifica").show();
			jQuery("#texto-verifica").hide();
			jQuery("#folio_certificado").val('');
			jQuery("#verificacion_certificado").val('');
			jQuery( "#dialog-verifica" ).dialog( "open" );
		});
			
	jQuery( "#dialog-form-clave").dialog({
		autoOpen: false,
		height: 325,
		width: 500,
		resizable: false,
		modal: true
	});
	jQuery( "#dialog-form-mesa").dialog({
		autoOpen: false,
		height: 460,
		width: 480,
		resizable: false,
		modal: true
	});
	jQuery( "#dialog-pago").dialog({
		autoOpen: false,
		height: 500,
		width: 500,
		resizable: false,
		modal: true/*,
		  open: function(event, ui) {
		  jQuery('#divInDialog').load('test.html', function() {
		     alert('Load was performed.');
		   });
		  }*/
	});
	
	/*jQuery( "#prueba-pago" ).click(function() {
		var openpage=$(this).attr("href");
		$("#dialog-pago").dialog( "option", "buttons", { 
	        "Close": function() { 
	            $(this).dialog("close");
	            $(this).dialog("destroy");
	        } 
	    });
	    $("#dialog-pago").load(openpage);
	    return false;
			
	});*/
	jQuery( "#prueba-pago" ).click(function() {
		var openpage=jQuery(this).attr("href");
		jQuery( "#dialog-pago" ).load(openpage);
		//jQuery( "#dialog-pago" ).dialog( "open" );
		return(false);
	});
	jQuery( "#editar_datos_alumno" ).click(function() {
		jQuery( "#dialog-form" ).dialog( "open" );
	});
	jQuery( "#editar_clave_alumno" ).click(function() {
		jQuery( "#dialog-form-clave" ).dialog( "open" );
	});
	jQuery( "#mesa-ayuda" ).click(function() {
		jQuery("#enviando-mesa").hide();
		jQuery(".header-mesa-fielset .field_modal").show();
		jQuery("#enviar-mesa").show();
		jQuery("#motivo_field").val('');
		jQuery("#correo_field").val('');
		jQuery("#asunto_field").val('');
		jQuery("#descripcion_field").val('');
		jQuery( "#dialog-form-mesa" ).dialog( "open" );
	});
	jQuery( "#mesa-ayuda-header" ).click(function() {
		jQuery("#enviando-mesa").hide();
		jQuery(".header-mesa-fielset .field_modal").show();
		jQuery("#enviar-mesa").show();
		jQuery("#motivo_field").val('');
		jQuery("#correo_field").val('');
		jQuery("#asunto_field").val('');
		jQuery("#descripcion_field").val('');
		jQuery( "#dialog-form-mesa" ).dialog( "open" );
	});
});