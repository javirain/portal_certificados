<?php
/**
 * @var mixed   $cert_pagados
 * @var array   $unidad_solicitante
 * @var string  $url_site
 * @var Request $request
 */
echo "oli";
?>

@extends('certificados.layout.layout-cert')

@section('content')

<div id="dialog-pago"></div>
<div id="page-certificados-gratis">
    <div id="datos_certificado" class="left">
        @include('certificados.includes.certificados-menu')
    </div>
    <div class="contenido-right">
        <form id="certificados-pago-form" method="post" action="http://certificados_tbank.ubiobio.cl/portal/ubb_pago_nac.php" autocomplete="off">
            <div id="listado-certificados-pago" class="lista-cert">
                <div class="titulo"><span>CERTIFICADOS DE PAGO</span></div>
                <div class="listado">
                    @if(count($cert_pagados) >= 1)
                        @foreach($cert_pagados as $pagado)
                            <div class="item-cert">
                                <input class="checkbox"  name="cert-{{ $pagado['tipo'] }}-{{ $pagado['codigo1'] }}" type="checkbox" id="{{ $pagado['tipo'] }}-{{ $pagado['codigo1'] }}" value="{{ $pagado['tipo'] }}-{{ $pagado['titulo'] }}-{{ $pagado['codigo1'] }}-{{ $pagado['codigo2'] }}-{{ $pagado['subtitulo'] }}-{{ $pagado['year'] }}-{{ $pagado['periodo'] }}-{{ $pagado['valor'] }}-{{ $pagado['dias_vigencia'] }}" />
                                <label class="text" for="{{ $pagado['tipo'] }}-{{ $pagado['codigo1'] }}">
                                    <div class="tipo-cert">
                                        {!! utf8_encode(strtoupper($pagado['titulo'])) !!}
                                    <span>($ <b class="valor-cert" >{!! number_format($pagado['valor'], 0, ",", ".") !!}</b>)</span>
                                </div>
                                <div class="subtitulo-cert">{!! utf8_encode(strtoupper($pagado['subtitulo'])) !!}</div>
                                <div style="float:right;color:#E98F00;font-size:11px;width:100px;position:absolute;margin:45px 0 0 295px" class="vigencia">vigente por {{ $pagado['dias_vigencia'] }} días</div>
                            </label>
                        </div>
                    @endforeach
                @else
                    <div class="item-cert">
                        <label class="text" style="width:410px;text-align: center">No dispone de Certificados</label>
                    </div>
                @endif

                @if(isset($observacion[0]->mensaje))
                    <div class="item-cert">
                        <label class="text" style="width:410px;text-align: center;font-family: Arial !important;">{{ $observacion[0]->mensaje }}</label>
                        </div>
                    @endif
                </div>
                @if(count($cert_pagados)>=1)
                    <div id="box-total">
                        <span style="float:left">TOTAL: $</span><h3 id="txt-total">0</h3>
                        <input type="hidden" id="total_field" name="total_field" value="" />
                    </div>
                    <div id="correo-envio">
                        <div class="titulo-correo">CORREO DE ENVÍO DE CERTIFICADOS:</div>
                        <div id="correos">
                            <input name="correo1_pago" id="correo1_pago" class="validate[required,custom[email]]" type="text" placeholder="INGRESE CORREO" style="margin:0 10px 0 0 "/>
                            <input name="correo2_pago" id="correo2_pago" class="validate[required,custom[email]]" type="text" placeholder="REINGRESE CORREO" />
                        </div>
                    </div>

                    <input type="hidden" name="unidad_solicitante" value="{{ $unidad_solicitante[0]->rep_nombre }}" />
                    <input type="hidden" name="session_id" value="{{ session_id() }}" />
                    <input type="hidden" name="link_retorno_exito" value="{{ url('certificados-pago-emitidos') }}" />
                    <input type="hidden" name="link_retorno_fracaso" value="{{ url('certificados-pago') }}" />

                    <input type="hidden" name="pwe_correlativo" value="{{ session('pwe_correlativo') }}" />
                    <input type="hidden" name="sup_correlativo" value="{{ session('sup_correlativo') }}" />
                    <input type="hidden" name="scf_correlativo" value="{{ session('scf_correlativo') }}" />
                    <input type="hidden" name="id_usuario" value="{{ session('id_usuario') }}" />

                    <input type="hidden" name="rut" value="{{ session('datos_usuario')['rut'] }}" />
                    <input type="hidden" name="dv" value="{{ session('datos_usuario')['dv'] }}" />
                    <input type="hidden" name="nombres" value="{{ session('datos_usuario')['nombres'] }}" />
                    <input type="hidden" name="paterno" value="{{ session('datos_usuario')['paterno'] }}" />
                    <input type="hidden" name="materno" value="{{ session('datos_usuario')['materno'] }}" />
                    <input type="hidden" name="email" value="{{ session('datos_usuario')['email'] }}" />

                    <div class="btn-emitir" id="pago-emitidos">pagar</div>

                @endif
            </div>
        </form>
    </div>
</div>
@endsection()