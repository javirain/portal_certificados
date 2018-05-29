<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use nusoap_client;

class WebserviceTest extends Controller
{

    /*
     * CONTROLADOR PARA PROBAR FUNCIONAMIENTO DEL WEB SERVICE PARA DATOS DE UN USUARIO
     * No es bonito pero es bonito.
     */

    public function index()
    {
        $client = new nusoap_client('http://antares.dci.ubiobio.cl/webservice/server.php?wsdl','wsdl');
        $usuario =      $client->call('FethRowAllUsuario', array('rut' => '16327196','clave' => '123'));
        $datos_alumno = $client->call('getDatosUsuario', array('rut' => '16327196'));

        $client_certificado = new nusoap_client('http://antares.dci.ubiobio.cl/webservice/server_certificados.php?wsdl','wsdl');
        $cert_gratis =  $client_certificado->call('getCertificadosGratis', array('rut' =>       '18412136'));
        $portal =       $client_certificado->call('PortalDefaultUsuario',array('id_usuario'=>   '18412136'));

        //dd(array_merge($usuario, $datos_alumno));
        dd($cert_gratis);
    }
}
