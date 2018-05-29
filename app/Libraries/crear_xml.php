<?php
/*
 * IMPORTANTE
 * Esta archivo ha sido portado, pero no ha sido verificado su funcionamiento. Antes de utilizarlo libremente
 * se hace necesario ajustar su funcionamiento para utilizar las herramientas que el framework ofrece, cuando sea
 * necesario. Se debe prestar especial atención a los "include" y "requiere".
 *
 * ATENCIÓN: La complejidad ciclomática en este archivo es muy alta. Se recomienda intensamente simplificar el uso
 * desmedido de sentencias "if", "if-els"e y "elseif". Este archivo no es apto para amantes del código bonito. Este
 * archivo tiene un enorme potencial de refactoring.
 */


/**
 * SIN DESCRIPCIÓN
 *
 * @param $rut
 * @param $ccr
 * @param $tipo_cer
 * @return array
 */
function getCertificadoDePago($rut,$ccr,$tipo_cer)
{
    $consulta = "ACADEMIA..Sp_WEB_RECUPERA_TITULOS_FEA ".$rut.",".$ccr.",".$tipo_cer.";";
    $query = query($consulta);
    $return = array();
    $return  = array(	'decreto' 		=> $query[0]->Nro_Decreto,
        'fecha_dec' 	=> $query[0]->Fecha_Decreto,
        'paterno' 		=> $query[0]->paterno,
        'materno' 		=> $query[0]->materno,
        'nombres' 		=> $query[0]->nombres,
        'rut' 			=> $query[0]->rut,
        'dv' 			=> $query[0]->dv,
        'tipo'			=> $query[0]->tipo_titulo,
        'titulo_nombre' => $query[0]->Nombre_titulo,
        'titulo_fecha'	=> $query[0]->Fecha_Titulo,
        'rol' 			=> $query[0]->Nro_Rol,
        'expediente' 	=> $query[0]->Letra_Expediente);
    return	$return;
}


/**
 * SIN DESCRIPCIÓN
 *
 * @param $rut
 * @param $ccr
 * @param $plan
 * @param $cert_ano
 * @param $cert_periodo
 * @param $motivo
 * @return array
 */
function getCertificadoGratis($rut,$ccr,$plan,$cert_ano,$cert_periodo,$motivo) {
    $query = "ACADEMIA..SP_WEB_RECUPERA_DATOS_ALUMNO_REGULAR ".$rut.",".$ccr.",".$plan.",".$cert_ano.",".$cert_periodo.",".$motivo.";";
    $query = query($query);
    $return = array();
    $return = array(	'rut' 			=> $query[0]->rut,
        'dv' 			=> $query[0]->dv,
        'nombres' 		=> $query[0]->nombre,
        'periodo'		=> $query[0]->periodo,
        'ano'			=> $query[0]->ano,
        'carrera'		=> $query[0]->nom_carrera,
        'campus'		=> $query[0]->campus,
        'programa'		=> $query[0]->tipo_programa,
        'nota'			=> $query[0]->nota);
    return $return;
}

/*
 * SCRIPT DE EJECUCIÓN
 */

$url_base_xml = "http://certificadosdev.ubiobio.cl/XML/";
$url_base_xsl = "http://certificadosdev.ubiobio.cl/XSL/";
//require_once("lib/Barcode39.php");
require_once("lib/fpdf/fpdf.php");
foreach($_POST as $key => $value) {
    if(substr_count($key,'cert')==1) {
        $certificado = explode('-', $value);
        if(isset($certificado[0])&&isset($certificado[1])&&isset($certificado[2])) {
            $xml 	= new DomDocument('1.0', 'ISO-8859-1');
            $xml->preserveWhiteSpace 	= false;
            $xml->formatOutput 			= true;
            /*
            tipo = $certificado[0];
            titulo = $certificado[1];
            codigo1 = $certificado[2];
            codigo2 = $certificado[3];
            subtitulo = $certificado[4];
            year = $certificado[5];
            periodo = $certificado[6];
            valor = $certificado[7];
            dias_vigencia = $certificado[8];
            dsc_correlativo = $certificado[9];
            */
            $tipo_certificado = $certificado[0];//tipo = 0
            //titulo = 1
            $codigo_origen1 = $certificado[2];//codigo1 = 2
            $codigo_origen2 = $certificado[3];//codigo2 = 3
            $nombre_origen = $certificado[4];//subtitulo = 4
            $cert_ano = $certificado[5];//year = 5
            $cert_periodo = $certificado[6];//periodo = 6
            $valor_certificado = $certificado[7];//valor = 7
            //dias_vigencia = 8
            $dsc_correlativo = $certificado[9];
            $identificadores = query("SP_WEB_GENERA_FOLIO_CODIGO_VERIFICACION_CERTIFICADO");

            if($tipo_certificado == 1) {
                $motivo				= $_POST['motivo'];
                $comuna			    = $_POST['comuna'];
                $cert_gratis_one    = getCertificadoGratis($_SESSION['id_usuario'],$codigo_origen1,$codigo_origen2,$cert_ano,$cert_periodo,$motivo);
            } elseif($tipo_certificado == 2 || $tipo_certificado == 3) {
                $cert_pagados_one			= getCertificadoDePago($_SESSION['id_usuario'],$codigo_origen1,$tipo_certificado);
            }
            if($tipo_certificado == 1) {
                $nombre_xsl = 'cert_alumno_regular';
            }
            if($tipo_certificado == 2) {
                $nombre_xsl = 'cert_titulo';
            }
            if($tipo_certificado == 3) {
                $nombre_xsl = 'cert_grado';
            }
            $xsl = $xml->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="'.$url_base_xsl.$nombre_xsl.'.xsl"');
            $xml->appendChild($xsl);

            $nombre_documento = $tipo_certificado.$codigo_origen1.date("YmdHis");
            /*$bc = new Barcode39($nombre_documento);
            $bc->barcode_text_size 	= 1;
            $bc->barcode_bar_thick 	= 2;
            $bc->barcode_bar_thin 	= 1;
            $bc->draw("../XML/code/bar/".$nombre_documento.".png");*/
            $root 	= $xml->createElement('certificado');
            $root 	= $xml->appendChild($root);
            $alumno	= $xml->createElement('alumno');
            $alumno = $root->appendChild($alumno);

            if($tipo_certificado == 1) {
                $rut_pdf	= number_format($cert_gratis_one['rut'], 0, ",", ".");
                $rut		= $xml->createElement('rut',number_format($cert_gratis_one['rut'], 0, ",", ".")."-".$cert_gratis_one['dv']);
                $rut 		= $alumno->appendChild($rut);
                $nombres	= $xml->createElement('nombres',$cert_gratis_one['nombres']);
                $nombres 	= $alumno->appendChild($nombres);
                $semestre	= $semestre_pdf = $cert_gratis_one['periodo'];
                $semestre_y	= $semestre_y_pdf = $cert_gratis_one['ano'];
                $programa	= $programa_pdf = $cert_gratis_one['programa'];
                $carrera	= $carrera_pdf = $cert_gratis_one['carrera'];
                $motivo		= query("	select rtrim(ltrim(mcr_descripcion)) as mcr_descripcion from academia..MOTIVO_CERTIFICADO where mcr_codigo=".$motivo);
                $motivo		= $motivo_pdf = $motivo[0]->mcr_descripcion;
                $query		= "select rtrim(ltrim(cmn_descripcion)) as cmn_descripcion from ubb..COMUNA where cmn_codigo=".$comuna;
                $comuna		= query($query);
                $lugar		= $lugar_pdf = $comuna[0]->cmn_descripcion;//$cert_gratis_one['campus'];
                $campus		= $campus_pdf = $cert_gratis_one['campus'];
                $nombre_pdf = $cert_gratis_one['nombres'];
            }
            if($tipo_certificado == 2 || $tipo_certificado == 3) {
                $rut	        = $xml->createElement('rut',number_format($cert_pagados_one['rut'], 0, ",", ".")."-".$cert_pagados_one['dv']);
                $rut 	        = $alumno->appendChild($rut);
                $decr_numero    = $cert_pagados_one['decreto'];
                $decr_fecha	    = $cert_pagados_one['fecha_dec'];
                $rol		    =  $cert_pagados_one['rol'];
            }
            if($tipo_certificado == 2) {
                $titulo			= $cert_pagados_one['titulo_nombre'];
                $titulo_fecha	= $cert_pagados_one['titulo_fecha'];
            }
            if($tipo_certificado == 3) {
                $grado			= $cert_pagados_one['titulo_nombre'];
                $grado_fecha	= $cert_pagados_one['titulo_fecha'];
            }

            $folio  = $folio_pdf = $identificadores[0]->cef_folio;
            if(date("d/m/Y",strtotime($cef_fecha_vigencia)) != date("d/m/Y")) {
                $fecha_vigencia = "hasta " . date("d-m-Y", strtotime($cef_fecha_vigencia));
            }
            else {
                $fecha_vigencia = "Indefinida";
            }
            $verificacion   = $verificacion_pdf = $identificadores[0]->cef_codigo_verificacion;
            if($tipo_certificado == 2 || $tipo_certificado == 3) {
                $nombres	= $xml->createElement('nombre',$cert_pagados_one['nombres']);
                $nombres 	= $alumno->appendChild($nombres);
                $paterno	= $xml->createElement('paterno',$cert_pagados_one['paterno']);
                $paterno 	= $alumno->appendChild($paterno);
                $materno	= $xml->createElement('materno',$cert_pagados_one['materno']);
                $materno 	= $alumno->appendChild($materno);
            }

            $fecha			= $fecha_pdf = date('d').' de '.getMes().' de '.date('Y').'.';
            $certificado	= $xml->createElement('datos_cartificado');
            $certificado 	= $root->appendChild($certificado);
            $tipo			= $xml->createElement('tipo',$tipo_certificado);
            $tipo 			= $certificado->appendChild($tipo);
            $verificacion			= $xml->createElement('verificacion',$verificacion);
            $verificacion 			= $certificado->appendChild($verificacion);
            $folio			= $xml->createElement('folio',$folio);
            $folio 			= $certificado->appendChild($folio);

            if($tipo_certificado == 1) {
                $semestre	= $xml->createElement('semestre',$semestre);
                $semestre 	= $certificado->appendChild($semestre);
                $semestre_y	= $xml->createElement('semestre_year',$semestre_y);
                $semestre_y = $certificado->appendChild($semestre_y);
                $programa	= $xml->createElement('programa',$programa);
                $programa 	= $certificado->appendChild($programa);
                $carrera	= $xml->createElement('carrera',$carrera);
                $carrera 	= $certificado->appendChild($carrera);
                $motivo		= $xml->createElement('motivo',$motivo);
                $motivo 	= $certificado->appendChild($motivo);
                $lugar		= $xml->createElement('lugar',$lugar);
                $lugar 		= $certificado->appendChild($lugar);
            }
            if($tipo_certificado == 2 || $tipo_certificado == 3) {
                $decr_numero	= $xml->createElement('decr_numero',$decr_numero);
                $decr_numero 	= $certificado->appendChild($decr_numero);
                $decr_fecha		= $xml->createElement('decr_fecha',$decr_fecha);
                $decr_fecha 	= $certificado->appendChild($decr_fecha);
                $rol		= $xml->createElement('rol',$rol);
                $rol 		= $certificado->appendChild($rol);
            }
            if($tipo_certificado == 2) {
                $titulo			= $xml->createElement('titulo',$titulo);
                $titulo 		= $certificado->appendChild($titulo);
                $titulo_fecha	= $xml->createElement('titulo_fecha',$titulo_fecha);
                $titulo_fecha 	= $certificado->appendChild($titulo_fecha);
            }
            if($tipo_certificado == 3) {
                $grado			= $xml->createElement('grado',$grado);
                $grado 			= $certificado->appendChild($grado);
                $grado_fecha	= $xml->createElement('grado_fecha',$grado_fecha);
                $grado_fecha 	= $certificado->appendChild($grado_fecha);
            }
            $campus		= $xml->createElement('campus',"CONCEPCIÓN");
            $campus 	= $certificado->appendChild($campus);

            /* Etiqueta codigo_qr */
            $codigo_qr		= $xml->createElement('codigo_qr',$url_site."qr/".$identificadores[0]->cef_folio.$identificadores[0]->cef_codigo_verificacion.".png");
            $codigo_qr 		= $certificado->appendChild($codigo_qr);
            $fecha		= $xml->createElement('fecha',$fecha);
            $fecha 		= $certificado->appendChild($fecha);
            $fecha_vigencia		= $xml->createElement('fecha_vigencia',$fecha_vigencia);
            $fecha_vigencia 	= $certificado->appendChild($fecha_vigencia);

            /* NOMBRE FIRMANTE */
            $firmantes_valido_fea   = query("SP_WEB_RECUPERA_FIRMANTE_FEA_VALIDO ".$tipo_certificado);
            $i = 1;
            foreach($firmantes_valido_fea as $firmante_valido_fea) {
                $nombre_firmante_valido_fea	= $firmante_valido_fea->mae_nombre;
                $nombre_firmante_valido_fea	= $xml->createElement('nombre_firmante'.$i,$nombre_firmante_valido_fea);
                $nombre_firmante_valido_fea	= $certificado->appendChild($nombre_firmante_valido_fea);
                /* RUT FIRMANTE */
                $rut_firmante_valido_fea	= $rut_firmante_valido_fea_sp = $firmante_valido_fea->mae_rut.'-'.$firmante_valido_fea->mae_dv;
                $rut_firmante_valido_fea	= $xml->createElement('rut_firmante'.$i,$rut_firmante_valido_fea);
                $rut_firmante_valido_fea	= $certificado->appendChild($rut_firmante_valido_fea);
                $cargo_firmante				= $xml->createElement('cargo_firmante',$firmantes_valido_fea[0]->cargo);
                $cargo_firmante				= $certificado->appendChild($cargo_firmante);
                $i++;
            }

            $xml->formatOutput = false;
            $strings_xml = $xml->saveXML();
            if($strings_xml) {
                $xml_creado = 1;
            }
            if($xml_creado == 1) {
                require("pages/firmar_xml.php");
            }
        }
    }
}
