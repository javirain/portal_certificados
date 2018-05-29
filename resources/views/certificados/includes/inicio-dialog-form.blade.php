<?php
/**
 * @var string  $src
 * @var mixed   $datos_alumno
 */
?>
<div id="dialog-form" title="" >
    <form id="buscador_de_usuarios" action="#" method="post">
        <div class="foto_alumnos">
            <div id="foto">
                <img width="130" style="float:left" src="{{ $src }}">
            </div>
        </div>
        <fieldset>
            <div class="titulo" >DATOS PERSONALES</div>
            <div class="field_modal">
                <span class="etiqueta" style="float: left; width: 55px;"><b>NOMBRE</b></span>
                <span class="item">{{ $datos_alumno['nombres']}} {{ $datos_alumno['paterno'] }} {{ $datos_alumno['materno'] }}</span>
            </div>
            <div class="field_modal">
                <span class="etiqueta" style="float: left; width: 55px;"><b>R.U.T</b></span>
                <span class="item">{{ $datos_alumno['rut'] }}-{{ $datos_alumno['dv'] }}</span>
            </div>
            <div class="field_modal"><label for="direccion_field"><b>Dirección</b></label>
                <input value="{!! utf8_encode($datos_alumno['direccion']) !!}" type="text" name="nombre_field" id="direccion_field" class="text ui-widget-content ui-corner-all" /></div>
            <div class="field_modal"><label for="region_field"><b>Región</b></label>
                <select name="region_field" id="region_field" class="text ui-widget-content ui-corner-all">
                    @foreach(session('regiones') as $region)
                        <option @if($datos_alumno['codigo_region'] == $region['codigo']) selected='' @endif value='{{ $region['codigo'] }}'>{!! utf8_encode($region['descripcion']) !!}</option>
                    @endforeach
                </select>
            </div>
            <div class="field_modal"><label for="provincia_field"><b>Provincia</b></label>
                <select name="provincia_field" id="provincia_field" class="text ui-widget-content ui-corner-all">
                    @foreach (session('provincias') as $provincia)
                        <option @if($datos_alumno['codigo_provincia'] == $provincia['codigo']) selected='' @endif value='{{ $provincia['codigo'] }}'>{!! utf8_encode($provincia['descripcion']) !!}</option>
                    @endforeach
                </select>
            </div>
            <div class="field_modal"><label for="comuna_field"><b>Comuna</b></label>
                <select name="comuna_field" id="comuna_field" class="text ui-widget-content ui-corner-all">
                    @foreach(session('comunas') as $comuna)
                        <option @if($datos_alumno['codigo_comuna'] == $comuna['codigo']) selected='' @endif value='{{ $comuna['codigo'] }}'>{!! utf8_encode($comuna['descripcion']) !!}</option>
                    @endforeach
                </select>
            </div>
        </fieldset>
    </form>
    <div id="boton_seleccionar_usuario" style="margin:10px 0 0 0;padding:10px 0 0 0;float:left;width:100%">
        <a id="seleccionar_usuario" class="btn">GUARDAR CAMBIOS</a>
    </div>

</div>