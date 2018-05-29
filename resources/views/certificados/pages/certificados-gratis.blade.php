<?php
/**
 * @var Request         $request
 * @var nusoap_client   $client_certificado
 */
?>

@extends('certificados.layout.layout-cert')

@section('content')
<?php
$query = "SP_WEB_REGISTRA_SOLICITUD_CERTIFICADO_FEA ".session('pwe_correlativo').",".session('sup_correlativo').",1";
//$scf_correlativo = query($query);
$scf_correlativo = 12345;
//$request->session()->put('scf_correlativo', $scf_correlativo[0]->scf_correlativo);
session(['scf_correlativo', 12345]);
?>
<div id="page-certificados-gratis">
    <div id="datos_certificado" class="left">
        @include('certificados.includes.certificados-menu')
    </div>
    <div class="contenido-right">
        <!--<form id="certificados-gratis-form" method="post" action="?page=certificados-gratis-emitidos" autocomplete="off">-->
        {{ Form::open(['url' => url('certificados-gratis-form'), 'id' => 'certificados-gratis-form', 'autocomplete' => 'off']) }}
            <div id="listado-certificados-gratis" class="lista-cert">
                <div class="titulo"><span>CERTIFICADOS GRATUITOS</span></div>
                <div class="listado">
                    @if(count($cert_gratis) >= 1)
                        @foreach($cert_gratis as $gratis_one)
                        <div class="item-cert">
                            <input class="checkbox"
                                   name="cert-{{ $gratis_one['tipo'] }}-{{ $gratis_one['codigo1'] }}"
                                   type="checkbox" id="{{ $gratis_one['tipo'] }}-{{ $gratis_one['codigo1'] }}"
                                   value="{{ $gratis_one['tipo'] }}-{{ $gratis_one['titulo'] }}-{{ $gratis_one['codigo1'] }}-{{ $gratis_one['codigo2'] }}-{{ $gratis_one['subtitulo'] }}-{{ $gratis_one['year'] }}-{{ $gratis_one['periodo'] }}-{{ $gratis_one['valor'] }}-{{ $gratis_one['dias_vigencia'] }}" />
                            <label class="text" for="{{ $gratis_one['tipo'] }}-{{ $gratis_one['codigo1'] }}">
                                <div class="tipo-cert">{{ strtoupper($gratis_one['titulo']) }}</div>
                                <div class="subtitulo-cert">{{ strtoupper($gratis_one['subtitulo']) }}</div>
                            </label>
                            @if($gratis_one['tipo']==1)
                            <div class="req-regular">
                                <select name="motivo" class="validate[required]">
                                    <option value="">MOTIVO</option>
                                    {{ $motivos = $client_certificado->call('getMotivos') }}
                                    @foreach($motivos as $motivo)
                                        <option value='{{ $motivo['codigo'] }}'>{{ $motivo['descripcion'] }}</option>;
                                    @endforeach
                                </select>
                                <select name="comuna" class="validate[required]">
                                    <option value="">COMUNA</option>
                                    {{ $comunas = $client->call('getAllComunas') }}
                                    @foreach($comunas as $comuna)
                                        <option value='{{ $comuna['codigo'] }}'>{{ $comuna['descripcion'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    @else
                    <div class="item-cert"><!---------------------------->
                        <label class="text" style="width:100%;text-align: center">No dispone de Certificados</label>
                    </div>
                    @endif
                    </div><!---------------------listado-------------------->
                @if(count($cert_gratis) >= 1)
                <div id="correo-envio">
                    <div class="titulo-correo">CORREO DE ENVÍO DE CERTIFICADOS:</div>
                    <div id="correos">
                        <input name="correo1" id="correo1" type="text" class="validate[required,custom[email]]" placeholder="INGRESE CORREO" style="margin:0 10px 0 0 "/>
                        <input name="correo2" id="correo2" type="text" class="validate[required,custom[email]]" placeholder="REINGRESE CORREO" />
                    </div>
                </div>
                <input type="hidden" name="ok_firmar_certificados" value="1" />
                <div class="btn-emitir" id="gratis-emitidos">solicitar</div>

                @endif
            </div>
        </form>
    </div>
</div>
@endsection()