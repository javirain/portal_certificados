<body>
	<style>
		#face{ background:url({{ asset('images/fb.png') }}) }
		#twit{ background:url({{ asset('images/tt.png') }}) }
		#yout{ background:url({{ asset('images/yb.png') }}) }
		#link{ background:url({{ asset('images/in.png') }}) }
		#flick{ background:url({{ asset('images/fk.png') }}) }
	</style>

    {{-- MODAL MESA DE AYUDA --}}
	<div id="dialog-form-mesa">

		{{ Form::open(['id' => 'form-mesda-ayuda', 'class' => 'form-certificado validable', 'method' => 'post']) }}
		<div id="header-mesa">
			<div id="telefono">(54-41) 311 1218</div>
		</div>
		<fieldset class="header-mesa-fielset">
			<div id="enviando-mesa">Su consulta se ha enviado a con éxito.</div>
			<div class="field_modal"><label for="motivo_field">Motivo</label>
				<select style="width:308px" class="validate[required] text ui-widget-content ui-corner-all" id="motivo_field" name="motivo_field">
					<option value="">Seleccione un Motivo</option>
					@foreach($tipos_consulta as $tipo)
						<option value='{{ $tipo->TCP_CORRELATIVO }}'>{{ $tipo->TCP_DESCRIPCION }}</option>
					@endforeach
				</select></div>
			<div class="field_modal"><label for="asunto_field">Asunto</label>
				<input style="width:300px" type="text" name="asunto_field" id="asunto_field" class="validate[required] text ui-widget-content ui-corner-all" /></div>
			<div class="field_modal"><label for="correo_field">Correo Respuesta</label>
				<input style="width:300px" type="text" name="correo_field" id="correo_field" class="validate[required,custom[email]] text ui-widget-content ui-corner-all" /></div>
			<div class="field_modal"><label for="descripcion_field">Descripción</label>
				<textarea class="validate[required]" name="descripcion_field" id="descripcion_field" style="width:376px;height:70px;resize: none"></textarea></div>
		</fieldset>
	</form>
	<div id="enviar-mesa-ayuda-boton" style="margin:10px 0 0 0;padding:10px 0 0 0;float:left;width:100%">
		<a class="btn" id="enviar-mesa">ENVIAR</a>
	</div>
</div>
<div class="mainwrapper">
    <div class="header">
		<div class="wrapper">
			<div id="acceso">
				<div id="menu-header">
					<a target="_blank" href="http://ubiobio.cl/">UBB</a>
					<a id="verifica-certificado" href="#">VERIFICAR CERTIFICADO</a>
					<a href="{{ url('certificados-vigentes') }}">CERTIFICADOS VIGENTES</a>
					<a href="#" id="mesa-ayuda-header">MESA DE AYUDA</a>
				</div>
				<div id="menu-user">
					<a href="{{ url('certificados') }}"><h5>{{ utf8_encode(session('nombre_usuario')) }} {{ utf8_encode(session('apellido_usuario')) }} </h5></a>
					<!--<a href="?page=inicio"><h5>NOMBRE DE USUARIO COMPLETO</h5></a>-->
					<!-- TODO -->
					<a href="{{ url('logout') }}">cerrar sesión</a>
				</div>
			</div>
			<div id="logo-sombra"></div>
	        <div class="logo" style="margin-left: 0px;">
	            <a href="{{ url('/') }}"><img alt="" src="{{ asset('images/logo.png') }}"></a>
	        </div>
	        <div id="titulo"></div>
	        @if($page != 'inicio')
	        	<a href="?page=inicio"><div id="gohome"></div></a>
			@endif
		</div>
   	</div>
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
<div id="sombra-wrapper"></div>
    <div class="wrapper">
    	<div class="maincontent">