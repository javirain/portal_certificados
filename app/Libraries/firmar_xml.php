<?php
/*
 * IMPORTANTE
 * Esta archivo ha sido portado, pero no ha sido verificado su funcionamiento. Antes de utilizarlo libremente
 * se hace necesario ajustar su funcionamiento para utilizar las herramientas que el framework ofrece, cuando sea
 * necesario. Se debe prestar especial atención a los "include" y "requiere".
 *
 * MAS IMPORTANTE: Muy probablemente este archivo ya no sirve por la implementación del certificado nuevo.
 */

$b64 = base64_encode($strings_xml);
$i = 1;
foreach($firmantes_valido_fea as $firmante_valido_fea) {
    $param  = [
        'docXML64' => $b64 ,
        'firmante' => $rut_firmante_valido_fea_sp,
        'usuario'  => 'certificados',
        'clave'	   => 'Z940Ya7LZy',
    ];
    $intentos = 1;
    while ($intentos <= 3) {
        $docFirmado = $client->call('firmarDocumentoUBB', $param);
        if($docFirmado['error'] == 1) {
            $intentos++;
        } else {
            $intentos = 4;
        }
    }
    if($docFirmado['error'] == 0) {
        query(
            "INSERT TMP_FIRMANTE (spid, correlativo, mae_rut) 
            VALUES (" . $_SESSION['sup_correlativo'] . $_SESSION['id_usuario'] . "," . $i . " , " . $firmante_valido_fea->mae_rut . ")");
    }
    $i++;
}
if($docFirmado['error'] == 0) {
    include_once "pages/PDF/BarcodeQR.php";
    $qr = new BarcodeQR();
    $qr->url($docFirmado['urlDcto']);
    $qr->draw(150, "qr/".$identificadores[0]->cef_folio.$identificadores[0]->cef_codigo_verificacion.".png");
    require("pages/PDF/crear-pdf.php");
    $unpack = unpack('H*hex',$string_pdf);
    $date = new DateTime($identificadores[0]->cef_fecha_certificado);
    $query = "SP_WEB_REGISTRA_CERTIFICADO_EMITIDO_FEA ".
        $_SESSION['pwe_correlativo'].",".
        $_SESSION['sup_correlativo'].",".
        $_SESSION['scf_correlativo'].",".
        $tipo_certificado.",".
        $dsc_correlativo.",'".
        $docFirmado['urlDcto']."','".
        $docFirmado['fileDctoXML64']."',".
        "0x".$unpack['hex'].",'".
        date("Y-m-d H:i:s",strtotime($cef_fecha_vigencia))."','".
        $nombre_origen."',".
        $identificadores[0]->cef_folio.",'".
        $identificadores[0]->cef_codigo_verificacion."','".
        substr($date->format('Y-m-d H:i:s.u')."'",0,-4)."',".
        $_SESSION['sup_correlativo'].$_SESSION['id_usuario'];
    //echo "$query";
    $cef_correlativo = query($query);
    //unlink("pages/PDF/qr/".$identificadores[0]->cef_folio.$identificadores[0]->cef_codigo_verificacion.".png");
}
