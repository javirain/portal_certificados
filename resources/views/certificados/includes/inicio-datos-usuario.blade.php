<?php
/**
 * @var string  $src
 */
?>
<div id="datos_usuario">
    <div id="foto"><?='<img width="122" style="float:right" src="'.$src.'">'?></div>
    <div id="datos">
        <div class="box">
            <div class="titulo">DATOS PERSONALES</div>
            <div class="etiqueta">R.U.T</div>
            <?php //dd($request->session()->get('datos_usuario')) ?>
            <div class="item">{!! formatear_rut(session('datos_usuario')) !!}</div>
            <div class="etiqueta">Nombre</div>
            <div class="item">{!! formatear_nombre(session('datos_usuario'), true) !!}</div>
            <div class="etiqueta">Direccion</div>
            <div class="item">{!! formatear_string_arreglo(session('datos_usuario'), 'direccion') !!}</div>
            <div class="etiqueta">Region</div>
            <div class="item">{!! formatear_string_arreglo(session('datos_usuario'), 'region') !!}</div>
            <div class="etiqueta">Provincia</div>
            <div class="item">{!! formatear_string_arreglo(session('datos_usuario'), 'provincia') !!}</div>
            <div class="etiqueta">Comuna</div>
            <div class="item">{!! formatear_string_arreglo(session('datos_usuario'), 'comuna') !!}</div>
            <div class="etiqueta">Correo</div>
            <div class="item">{!! formatear_string_arreglo(session('datos_usuario'), 'email') !!}</div>
        </div>
        @if(session('datos_usuario')['ind_modificar'] == 1)
        <span id="editar_datos_alumno" class="editar_alumno" >
            <span>editar información</span>
        </span>
        @endif

        <span id="editar_clave_alumno" class="editar_alumno" >
            <span>editar contraseña</span>
        </span>

    </div>
</div>