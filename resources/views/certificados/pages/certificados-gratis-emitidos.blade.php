<?php
/**
 * @var array       $certificados
 * @var array       $certificado_bd
 * @var string      $HtmlView
 * @var PHPMailer   $mail
 */
?>

@extends('certificados.layout.layout-cert')

@section('content')

{!! $opening_divs !!}
                    @foreach($request->all() as $key => $value) {
                        @if(substr_count($key,'cert') == 1 && $key != 'ok_firmar_certificados')
                            @if(!empty($certificado_bd[0]->CEF_URL))
                                {!! $ind_envio_mail = 1 !!}
                                <div class="item-cert">
                                    <div class="tipo-cert">{{ strtoupper($certificado_bd[0]->CEF_NOMBRE_CERTIFICADO) }}</div>
                                    <div class="subtitulo-cert">{{ strtoupper($certificado_bd[0]->CEF_DESCRIPCION_CERTIFICADO) }}</div>
                                    <a target="_blank" href="{{ $HtmlView.$certificado_bd[0]->CEF_URL }}"><div class="botn-dow-ver"></div></a>
                                    <a target="_blank" href="{{ url('certificados/pdf/mostrar/' . $certificado_bd[0]->CEF_CODIGO_VERIFICACION) }}"><div class="botn-dow"></div></a>
                                    <a target="_blank" href="{{ $InfoView.$certificado_bd[0]->CEF_URL }}"><div class="botn-dow-ver-firmante">Ver Firmante</div></a>
                                </div>
                                <?php $body .= "<br/><a href='{!! $HtmlView.$certificado_bd[0]->CEF_URL !!}' target='_blank'>".strtoupper(utf8_decode($certificado_bd[0]->CEF_NOMBRE_CERTIFICADO))."</a><br/>" ?>
                            @else
                                <div class="item-cert"><!---------------------------->
                                    <label class="text" style="width:400px;text-align: center">Entidad firmante ocupada, por favor intente emitir el certificado nuevamente</label>
                                </div>
                            @endif
                        @endif
                    @endforeach

                    @if($ind_envio_mail == 1)
                        {!! $body .= "<br/><br/>Atte.<br/>Portal de Certificados.";
                        $mail->MsgHTML($body);
                        $mail->Send(); !!}
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
@endsection()