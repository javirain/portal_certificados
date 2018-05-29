<?php
/**
 * @var Request $request
 * @var nusoap_client $client
 */
if(session('id_usuario')){
    $datos_alumno = $client->call('getDatosUsuario', array('rut' => session('id_usuario')));
}
if (!empty($datos_alumno)) {
    $src = 'data: ' . _mime_content_type($datos_alumno['foto']) . ';base64,' . $datos_alumno['foto'];
} else {
    $src = '#';
}
?>
<div id="dialog-form-clave" title="" >
        {{ Form::open(['id' => 'cambia_pass_form', 'method' => 'post']) }}
        <div class="foto_alumnos">
            <div id="foto"><?='<img width="130" style="float:left" src="'.$src.'">'?></div>
        </div>
        <fieldset>
            <div class="titulo" >CAMBIAR CONTRASEÑA</div>
            <div class="field_modal"><label for="password0"><b>Contraseña Actual</b></label>
                <input Type="password" name="password0" id="password0" class="validate[required]" /></div>
            <div class="field_modal"><label for="password1"><b>Nueva Contraseña</b></label>
                <input Type="password" name="password1" id="password1" class="validate[required]" /></div>
            <div class="field_modal"><label for="password2"><b>Repetir Nueva Contraseña</b></label>
                <input Type="password" name="password2" id="password2" class="validate[required]" /></div>
        </fieldset>

        <div id="boton_seleccionar_usuario" style="margin:10px 0 0 0;padding:10px 0 0 0;float:left;width:100%">
            <a id="cambia_pass_submit" class="btn">GUARDAR CAMBIOS</a>
        </div>	</form>
</div>