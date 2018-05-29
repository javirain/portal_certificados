@extends('certificados.layout.layout-cert')

@section('content')

<div id="page-certificados-gratis-emitidos">
    <div id="datos_certificado" class="left">
        @include('certificados.includes.certificados-menu')
    </div>
    <div class="contenido-right">
        <form id="certificados-gratis-emitidos-form" method="post" action="?page=certificados-gratis-emitidos">
            <div id="listado-certificados-gratis" class="lista-cert">
                <div class="titulo"><span>CERTIFICADOS EMITIDOS COMPRADOS</span></div>
                <div class="listado">
                    <?php $body = "Estimado ".session('nombre_usuario').' '.session('apellido_usuario').":<br/><br/>Usted ha solicitado los siguientes Certificados:<br/><br/>" ?>


                    @foreach($_POST as $key => $value)
                        @if(substr_count($key,'cert')==1&&$key!='ok_firmar_certificados')
                            <?
                        //echo "key=> ".$key." value=>".$value."<br>";
                        $certificado = explode('-', $value);
                        $query = "	select CEF_URL,CEF_XML,CEF_CODIGO_VERIFICACION,CEF_NOMBRE_CERTIFICADO,CEF_DESCRIPCION_CERTIFICADO
                            from DETALLE_SOLICITUD_CERTIFICADO_FEA d
                            left join CERTIFICADO_EMITIDO_FEA c on
                                c.PWE_CORRELATIVO = d.PWE_CORRELATIVO
                                and c.SUP_CORRELATIVO = d.SUP_CORRELATIVO
                                and c.SCF_CORRELATIVO = d.SCF_CORRELATIVO
                                and c.DSC_CORRELATIVO = d.DSC_CORRELATIVO
                                and c.CEF_CORRELATIVO = d.CEF_CORRELATIVO
                            where c.PWE_CORRELATIVO = 2
                                and c.SUP_CORRELATIVO=".$_SESSION['sup_correlativo']."
                                and c.SCF_CORRELATIVO=".$_SESSION['scf_correlativo']."
                                and c.DSC_CORRELATIVO=".$certificado[9];
                        //echo $query;
                        $certificado_bd = query($query);
                        //print_r($certificado_bd);
                        if(!empty($certificado_bd[0]->CEF_URL)){
                        $ind_envio_mail = 1;
                        ?>
                        <div class="item-cert"><!---------------------------->
                            <div class="tipo-cert">{{ strtoupper($certificado_bd[0]->CEF_NOMBRE_CERTIFICADO) }}</div>
                            <div class="subtitulo-cert">{{ strtoupper($certificado_bd[0]->CEF_DESCRIPCION_CERTIFICADO) }}</div>
                            <a target="_blank" href="{{ $HtmlView.$certificado_bd[0]->CEF_URL }}"><div class="botn-dow-ver"></div></a>
                            <a target="_blank" href="pages/PDF/mostrar-pdf.php?pdf={{ $certificado_bd[0]->CEF_CODIGO_VERIFICACION }}"><div class="botn-dow"></div></a>
                            <a target="_blank" href="{{ $InfoView.$certificado_bd[0]->CEF_URL }}"><div class="botn-dow-ver-firmante">Ver Firmante</div></a>
                        </div><!-------------------------------------------->
                        <?php $body .= "<br/><a href='".$HtmlView.$certificado_bd[0]->CEF_URL."' target='_blank'>".strtoupper(utf8_decode($certificado_bd[0]->CEF_NOMBRE_CERTIFICADO))."</a><br/>" ?>
                        @else
                        <div class="item-cert"><!---------------------------->
                            <label class="text" style="width:400px;text-align: center">Entidad firmante ocupada, por favor comuníquese con la Mesa de Ayuda para obtener su certificado</label>
                        </div>
                        @endif
                    @endforeach
                    @if($ind_envio_mail == 1)
                        $body .= "<br/><br/>Atte.<br/>Portal de Certificados.";
                        $mail->MsgHTML($body);
                        $mail->Send();
                    @endif
                </div><!---------------------listado-------------------->
                <a href="pages/PDF/download.php?sup={{ session('sup_correlativo') }}&scf={{ session('scf_correlativo') }}"><div class="btn-emitir" style="padding:0 10px">descargar todos</div></a>
            </div>
        </form>
    </div>
</div>
@endsection()