<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use nusoap_client;
use PHPMailer\PHPMailer\PHPMailer;
use stdClass;


/**
 * /////////////////////////////////////////////////////////////////COMENTARIO////////////////////////////////////////////////////////////////////////////////
 * DON'T FORGET: No escribiré comentarios innecesarios o innecesariamente largos.
 * No tengo problema con que dejen código de prueba comentado (de hecho los dejé y algunos los usé), sino cuando ponen
 * como 300 slashes ("/") con una palabra al medio, o lineas horizontales cada 3 lineas para... dejarlo más ordenado?
 * se ve horrible. Espero que nadie nunca lo vuelva a hacer en la vida. Con amor <3.
 * ----------------------------------------------------------FIN DEL COMENTARIO-----------------------------------------------------------------------------
 *
 * Class CertificadoController
 * @package App\Http\Controllers
 */
class CertificadoController extends Controller
{

    /* VARIABLES DE INSTANCIA */

    /**
     * Nunca entendí muy bien... creo que dice que "tipo" de portal se consulta.
     *
     * @var string
     */
    protected $PWE_CORRELATIVO;

    /**
     * Abstracción del determinación del tipo de acceso que un visitante recibirá.
     *
     * @var stdClass
     */
    protected $acceso;

    /**
     * Clientes nusoap para conextar con los webservice de la universidad.
     *
     * NOTA: No conectarán si no se llaman desde un equipo conectado directamente a las redes cableadas de la
     * universidad, o a través de VPN.
     *
     * @var nusoap_client
     * @var nusoap_client
     */
    protected $client;
    protected $client_certificado;

    /**
     * La lista con los datos que serán enviados a las vistas. Permite definir valores comunes para evitar
     * instrucciones repetidas en cada función.
     *
     * @var array
     */
    protected $datos_vista = [];


    /**
     * Valida la sesión e inicializa los objetos soap_client
     *
     * @return mixed
     */
    public function inicializar()
    {
        if (session('id_usuario') === null) {
            return redirect('/');
        }
        $this->PWE_CORRELATIVO = 2;
        $this->acceso = get_acceso();

        // Cliente SOAP
        $this->client = new nusoap_client(SOAP_SERVER . '?wsdl','wsdl');
        $error = $this->client->getError();
        if ($error) {
            return view('errores.constructor', ['error' => $error]);
        }

        // Cliente SOAP para certificados
        $this->client_certificado = new nusoap_client(SOAP_SERVER_CERT . '?wsdl','wsdl');
        $error = $this->client_certificado->getError();
        if ($error) {
            return view('errores.constructor', ['error' => $error]);
        }

        // Obtener posibles tipos de consulta para modal de contacto
        $query = "select * from TIPO_CONSULTA_PORTAL";
        $tcp = query($query);

        // Añadir los datos comunes a todas las vistas
        $this->datos_vista['url_site'] = URL_SITE;
        $this->datos_vista['tipos_consulta'] = $tcp;
        $this->datos_vista['client'] = $this->client;
        $this->datos_vista['client_certificado'] = $this->client_certificado;
        $this->datos_vista['acceso'] = $this->acceso;
        $this->datos_vista['vitrinas'] = call_vitrina($this->client_certificado, 5);

        return true;
    }


    /**
     * Carga la pantalla básica para usuarios conectados
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function inicio()
    {
        $validacion = $this->inicializar();
        if ($validacion !== true) {
            return $validacion;
        }
        return view('certificados.pages.inicio', array_merge($this->datos_vista, [
            'page'                  => 'inicio',
            'src'                   => session('src'),
            'datos_alumno'          => session('datos_alumno'),
            'regiones'              => session('regiones'),
            'provincias'            => session('provincias'),
            'comunas'               => session('comunas'),
        ]));
    }


    /**
     * Controla generación y despliegue de vista "certificados gratis"
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed}
     */
    public function certificados_gratis()
    {
        $validacion = $this->inicializar();
        if ($validacion !== true) {
            return $validacion;
        }
        $cert_gratis = $this->client_certificado->call(
                'getCertificadosGratis',
                array('rut' => session('id_usuario'))
        );
        return view('certificados.pages.certificados-gratis', array_merge($this->datos_vista, [
            'page'                  => 'certificados-gratis',
            'cert_gratis'           => $cert_gratis,
        ]));
    }


    /**
     * Controla generación y despliegue de vista "certificados gratis emitidos"
     *
     * NOTA: No tengo idea donde usan esto.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function certificados_gratis_emitidos(Request $request)
    {
        $validacion = $this->inicializar();
        if ($validacion !== true) {
            return $validacion;
        }
        foreach ($_POST as $key => $value) {
            if (substr_count($key,'cert')==1) {

                $certificado = explode('-', $value);
                //print_r($certificado);
                if (isset($certificado[0])&&isset($certificado[1])&&isset($certificado[2])) {
                    $tipo_certificado 		= $certificado[0];
                    $codigo_1				= $certificado[2];
                    $codigo_2				= $certificado[3];
                    $descripcion 			= $certificado[4];
                    $query = "SP_WEB_REGISTRA_DETALLE_SOLICITUD_CERTIFICADO_FEA ".
                        session('pwe_correlativo').",".
                        session('sup_correlativo').",".
                        session('scf_correlativo').",".
                        $tipo_certificado.",'".
                        $descripcion."',".
                        $codigo_1.",".
                        $codigo_2.",'".
                        session('correo1')."';";
                    $dsc_correlativo = query($query);
                    //print_r($dsc_correlativo);
                    $cef_fecha_vigencia = $dsc_correlativo[0]->cef_fecha_vigencia;
                    $_POST['cert-'.$tipo_certificado.'-'.$codigo_1] = $certificado[0].'-'.$certificado[1].'-'.$certificado[2].'-'.$certificado[3].'-'.$certificado[4].'-'.$certificado[5].'-'.$certificado[6].'-'.$certificado[7].'-'.$certificado[8].'-'.$dsc_correlativo[0]->dsc_correlativo;
                }
            }
        }

        if($request->ok_firmar_certificados == 1)
        {
            //Require_once("pages/crear_xml.php");
        }
        // TODO Esto es un adefesio, mitad viejo mitad nuevo. Todos los homúnculos deben morir. (mejorar)
        ob_start();
        ?>
        <div class="contenido-right">
            <form id="certificados-gratis-emitidos-form" method="post" action="?page=certificados-gratis-emitidos">
                <div id="listado-certificados-gratis" class="lista-cert">
                    <div class="titulo"><span>CERTIFICADOS EMITIDOS GRATUITOS</span></div>
                    <div class="listado">
        <?php
        $opening_divs = ob_get_clean();

        $mail = new PHPMailer();
        $mail->Mailer = "smtp";
        $mail->Host = "smtp.ubiobio.cl";
        $body = "Estimado ".session('nombre_usuario').' '.session('apellido_usuario').":<br/><br/>Usted ha solicitado los siguientes Certificados:<br/><br/>";
        $mail->SetFrom('certificados@ubiobio.cl', 'Certificados UBB');
        $mail->AddAddress($request->correo1,session('nombre_usuario').' '.session('apellido_usuario'));
        $mail->Subject =  "Certificados UBB Solicitados ";
        $ind_envio_mail = 0;

        return view('certificados.pages.certificados-gratis-emitidos', array_merge($this->datos_vista, [
            'request'               => $request,
            'page'                  => 'certificados-gratis-emitidos',
            'ind_envio_mail'        => $ind_envio_mail,
            'HtmlView'              => HTML_VIEW,
            'opening_divs'          => $opening_divs,
        ]));
    }


    /**
     * Controla generación y despliegue de vista "certificados pago"
     *
     * NOTA IMPORTANTE: Esta función no puede ser probada por no contar con un usuario (RUT) que cuente c
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function certificados_pago()
    {
        $validacion = $this->inicializar();
        if ($validacion !== true) {
            return $validacion;
        }

        // Buscar el correlativo que sirve para... algo.
        $this->client_certificado->soap_defencoding = 'UTF-8';
        $query = "SP_WEB_REGISTRA_SOLICITUD_CERTIFICADO_FEA ".session('pwe_correlativo').",".session('sup_correlativo').",0	";
        $scf_correlativo = query($query);
        session(['scf_correlativo', $scf_correlativo]); // Mala práctica, buscar mejor solución (no usar sesión)

        // Obtener certificados
        $cert_pagados = $this->client_certificado->call('getCertificadosPagados', array('rut' => session('id_usuario')));
        $query = " SP_WEB_CERTIFICADOS_DISPONIBLES_PAGADOS_OBSERVACION ".session('id_usuario');
        $observacion = query($query);

        // Determinar la unidad solicitante del certificado
        $query = "select rep_nombre from Vrae..reparticion
									where rep_codigo = (select rep_secretaria_general from Academia..PARAMETROS_TITULOS_GRADOS)";
        $unidad_solicitante = query($query);

        return view('certificados.pages.certificados-pago', array_merge($this->datos_vista, [
            'page'                  => 'certificados-pago',
            'cert_pagados'          => $cert_pagados,
            'observacion'           => $observacion,
            'unidad_solicitante'    => $unidad_solicitante,
        ]));
    }


    /**
     * Controla generación y despliegue de vista "certificados pago emitidos"
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function certificados_pago_emitidos(Request $request)
    {
        $validacion = $this->inicializar();
        if ($validacion !== true) return $validacion;

        $cef_fecha_vigencia = $request->cef_fecha_vigencia;
        $mail = new PHPMailer();
        $mail->Mailer = "smtp";
        $mail->Host = "smtp.ubiobio.cl";
        $mail->SetFrom('certificados@ubiobio.cl', 'Certificados UBB');
        $mail->AddAddress($request->correo1_pago,session('nombre_usuario').' '.session('apellido_usuario'));
        $mail->Subject =  "Certificados UBB Solicitados ";
        $ind_envio_mail = 0;

        return view('certificados.pages.certificados-pago-emitidos', array_merge($this->datos_vista, [
            'page'                  => 'certificados-pago-emitidos',
            'HtmlView'              => HTML_VIEW,
            'InfoView'              => INFO_VIEW,
            'ind_envio_mail'        => $ind_envio_mail,
        ]));
    }


    /**
     * Controla generación y despliegue de vista "certificados actuales"
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function certificados_actuales()
    {
        $validacion = $this->inicializar();
        if ($validacion !== true) return $validacion;

        return view('certificados.pages.certificados-actuales', array_merge($this->datos_vista, [
            'page'                  => 'certificados-actuales',
        ]));
    }


    /**
     * Controla generación y despliegue de vista "certificados vigentes"
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function certificados_vigentes()
    {
        $validacion = $this->inicializar();
        if ($validacion !== true) return $validacion;

        $new_url = get_tiny_url('http://pruebascustodiafirma1311.acepta.com/v01/910e279744bd63fd60ba43f8dc0107ef59056879');
        return view('certificados.pages.certificados-vigentes', array_merge($this->datos_vista, [
            'page'                  => 'certificados-vigentes',
            'src'                   => session('src'),
            'datos_alumno'          => session('datos_alumno'),
            'regiones'              => session('regiones'),
            'provincias'            => session('provincias'),
            'comunas'               => session('comunas'),
            'HtmlView'              => HTML_VIEW,
            'new_url'               => $new_url,
        ]));
    }

}
