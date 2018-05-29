<?php
    /** @var stdClass $acceso */
?>
<div class="box">
    <div id="titulo-cer"><span>CERTIFICADOS EN LINEA</span></div>
    @if($acceso->gratis == 1)
    <div class="cert-disponible" id="gratis">
        <div class="titulo">CERTIFICADOS DISPONIBLES</div>
        <a href="{{ url('certificados-gratis') }}" class="btn-emitir">emitir</a>
    </div>
    @endif
    @if($acceso->pago == 1)
    <div class="cert-disponible" id="pagado">
        <div class="titulo">CERTIFICADOS DISPONIBLES</div>
        <a href="{{ url('certificados-pago') }}" class="btn-emitir">comprar</a>
         <a href="{{ url('email') }}" class="btn-emitir">Enviar Correo</a>

    </div>

    <div id="cards"></div>
    @endif
</div>