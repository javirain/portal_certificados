@extends('certificados.layout.layout-cert')

@section('content')


@include('certificados.includes.inicio-dialog-form')
@include('certificados.includes.inicio-cambio-clave')


<div id="page-inicio">
    @include('certificados.includes.inicio-datos-usuario')
    <div id="datos_certificado" class="contenido-right">
    @include('certificados.includes.certificados-menu')
    </div>
</div>
@endsection()