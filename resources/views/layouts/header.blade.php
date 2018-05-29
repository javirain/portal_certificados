<?php
/**
 * @var $portal
 */
?>
<!DOCTYPE html>
<html>
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>::{{ $portal['titulo'] }}::</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link type="image/x-icon" href="{{ URL::to('/') }}favicon.ico" rel="icon">
    <link type="image/x-icon" href="{{ URL::to('/') }}favicon.ico" rel="shortcut icon">

    {{ Html::style('css/style.default.css') }}
    {{ HTML::script('js/jquery-1.9.1.min.js') }}
    {{ HTML::script('js/jquery-migrate-1.1.1.min.js') }}
    {{ HTML::script('js/jquery-ui-1.10.3.custom.min.js') }}
    {{ HTML::script('js/modernizr.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/jquery.cookie.js') }}
    {{ HTML::script('js/rut.js', ['language' => 'javascript', 'type' => 'text/javascript']) }}
    {{ HTML::script('js/custom.js') }}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<script>
    jQuery(function() {
        jQuery( ".datepicker" ).datepicker();
        jQuery( "#dialog" ).dialog({
            autoOpen: false,
            height: 350,
            width: 350,
            modal: true,
        });
        jQuery( "#ver_mas_vitrina" ).click(function() {
            jQuery( "#dialog" ).dialog( "open" );
        });
    });

    jQuery(function(){
        jQuery( "#dialog-verifica" ).dialog({
            autoOpen: false,
            height: 450,
            width: 550,
            modal: true
        });
        jQuery( "#verifica-certificado" ).click(function() {
            jQuery( "#dialog-verifica" ).dialog( "open" );
        });
    });
</script>