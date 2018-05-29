</div>
</div>
<div class="footer">

    <div class="wrapper">
        <div class="row-fluid">
            <div id="dashboard-right" class="span4 mesa-ayuda">
                <div id="mesa-ayuda">
                    <div id="telefono">(54-41) 311 1218</div><!-------REEMPLAZAR AQUÍ EL NÚMERO DE TELÉFONO POR CONSULTA A BD---------->
                </div>
            </div>
            <div id="dashboard-right" class="span4 listado-certificado">
                <div id="titulo-listado"></div>
                <ul>
                    @foreach ($vitrinas as $vitrina)
                        <li>{{ formatear_string_arreglo($vitrina, 'descripcion') }} @if ($vitrina['nuevo'] == 1)<span>(Nuevo)</span>@endif</li>
                    @endforeach
                </ul>
                <div id="ver_mas_vitrina">ver más</div>

                <script>
                    jQuery(function() {
                        jQuery( "#dialog" ).dialog({
                            autoOpen: false,
                            height: 350,
                            width: 'auto',
                            modal: true
                        });
                        jQuery( "#ver_mas_vitrina" ).click(function() {
                            jQuery( "#dialog" ).dialog( "open" );
                        });
                    });
                </script>
                <div id="dialog" title="Certificados Disponibles">
                    <div class="listado-certificado">
                        <ul>
                            @foreach($vitrinas as $vitrina)
                            <li>{{ formatear_string_arreglo($vitrina, 'descripcion') }}
                                @if($vitrina['nuevo'] == 1) <span>(Nuevo)</span>@endif
                                <span class="precio">@if($vitrina['precio']==0) Gratis @else ${!! number_format($vitrina['precio'], 0, ",", ".") !!}@endif</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div id="dashboard-right" class="span4 acreditada">
                <div id="line-footer"></div>
                <div id="social">
                    <a target="_blank" href="https://twitter.com/ubbchile"><div id="twit" class="social-icon"></div></a>
                    <a target="_blank" href="http://www.youtube.com/user/udelbiobio"><div id="yout" class="social-icon"></div></a>
                    <a target="_blank" href="https://www.facebook.com/ubiobiochile"><div id="face" class="social-icon"></div></a>
                    <a target="_blank" href="http://cl.linkedin.com/in/ubiobio"><div id="link" class="social-icon"></div></a>
                    <a target="_blank" href="http://www.flickr.com/photos/ubiobio/"><div id="flick" class="social-icon"></div></a>
                </div>			<div id="comision"></div>
            </div>
        </div>

    </div>

</div><!--footer-->
</div><!--mainwrapper-->
</body>
</html>