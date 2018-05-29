<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use nusoap_client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class HomeController extends Controller
{

    /* DECLARACIÓN DE VARIABLES DE INSTANCIA */
    protected $acceso;
    protected $HtmlView = "http://cumsille1411.acepta.com/ca4webv3/HtmlView?url=";
    protected $InfoView = "http://cumsille1411.acepta.com/ca4webv3/InfoView?url=";
    /**
     * @var nusoap_client $this ->client
     * @var nusoap_client $client_certificado
     */
    protected $client;
    protected $client_certificado;


    /**
     * Valida la sesión e inicializa los objetos soap_client
     *
     * @return mixed
     */
    public function inicializar()
    {
        if (session('id_usuario') !== null) return redirect('certificados');
        $this->acceso = get_acceso();
        /********DECLARACION CLIENTE SOAP***************/
        // Cliente SOAP
        $this->client = new nusoap_client(SOAP_SERVER . '?wsdl', 'wsdl');
        $error = $this->client->getError();
        if ($error) return view('errores.constructor', ['error' => $error]);

        // Cliente SOAP para certificados
        $this->client_certificado = new nusoap_client(SOAP_SERVER_CERT . '?wsdl', 'wsdl');
        $error = $this->client_certificado->getError();
        if ($error) return view('errores.constructor', ['error' => $error]);
        return true;
    }


    /**
     * Cierra la sesión del usuario
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        Session::flush();
        return redirect(url('/'));
    }


    /**
     * Intenta inicializar la sesión para un nuevo usuario. Retorna TRUE si se inicia la sesión con éxito.
     * El código para realizar las labores de conexión fue reducido considerablemente. ¿Se imaginan el doble del código
     * de esta función en cada archivo con información para generar vistas (seudo-controladores), que eran
     * aproximadamente unos 10?
     *
     * NOTA: Un prominente programador podría reducir mucho más la implementación de esta función, simplificando el
     * uso de las sesiones utilizando objetos tal vez, o siendo más elocuente en el uso del lenguaje. A Laravel le
     * gusta que seas elocuente, considerar para el futuro.
     *
     * @param LoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login(LoginRequest $request)
    {
        echo $this->inicializar();
        $PWE_CORRELATIVO = 2;
        $usuario = substr(str_replace('.', '', stripslashes($request->username)), 0, -2);
        $clave = stripslashes($request->password);
        $param = array('rut' => $usuario, 'clave' => $clave);
        $respuesta = $this->client->call('ValidaUsuario', $param);

        if ($respuesta == 1) {
            $usuario = $this->client->call('FethRowAllUsuario', $param);
            $PW_ID = $this->client_certificado->call('PortalDefaultUsuario', array('id_usuario' => session('id_usuario')));
            session([
                'loggin' => true,
                'pwe_correlativo' => $PWE_CORRELATIVO,
                'id_usuario' => $usuario['rut'],
                'nombre_usuario' => $usuario['nombres'],
                'apellido_usuario' => $usuario['paterno'],
                'materno_usuario' => $usuario['materno'],
                'correo_usuario' => $usuario['correo'],
                'sup_correlativo' => $this->client_certificado->call('InsertSession', [
                    'pwe_id' => 2,
                    'usu_log' => $usuario['rut'],
                    'sup_session' => session_id(),
                    'sup_fecha' => date("Y-m-d H:i:s")]),
            ]);

            if (Session::has('select_portal')) {
                dd("SELECT PORTAL");
                $datos_portal = $this->client_certificado->call('getDatosPortal', array('pw_id' => session('select_portal')));
                session([
                    'id_portal' => $datos_portal['pwe_id'],
                    'titulo_portal' => $datos_portal['titulo'],
                    'descripcion_portal' => $datos_portal['descripcion'],
                    'url_portal' => $datos_portal['url'],
                ]);
            } elseif (!Session::has('id_portal')) {
                $PW_ID = $this->client_certificado->call('PortalDefaultUsuario', array('id_usuario' => session('id_usuario')));
                $datos_portal = $this->client_certificado->call('getDatosPortal', array('pw_id' => $PW_ID));
                session([
                    'id_portal' => $datos_portal['pwe_id'],
                    'titulo_portal' => $datos_portal['titulo'],
                    'descripcion_portal' => $datos_portal['descripcion'],
                    'url_portal' => $datos_portal['url'],
                ]);
            }

            if (Session::has('id_usuario')) {
                $datos_alumno = $this->client->call('getDatosUsuario', array('rut' => session('id_usuario')));
                session(['datos_usuario' => $datos_alumno]);
            }

            $imageData = session('datos_usuario')['foto'];
            session([
                'src' => 'data: ' . _mime_content_type($imageData) . ';base64,' . $imageData,
                'regiones' => $this->client->call('getRegiones', array()),
                'provincias' => !empty($this->datos_alumno) ? $this->client->call('getProvincias', array('region' => $this->datos_alumno['codigo_region'])) : [],
                'comunas' => !empty($this->datos_alumno) ? $this->client->call('getComunas', array('provincia' => $this->datos_alumno['codigo_provincia'])) : [],
            ]);

            return redirect(url('certificados'));
        } else {
            return Redirect::back()->withErrors(['Usuario o contraseña no válidos.']);
        }
    }

    /**
     * Carga la vista de autenticación
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $validacion = $this->inicializar();
        if ($validacion !== true) {
            return $validacion;
        }
        $PW_ID = 2;
        //$param_pwID = array('pw_id' => $PW_ID);
        $portal = $this->client_certificado->call('getDatosPortal', array('pw_id' => $PW_ID));

        return view('home.index', [
            'acceso_gratis' => $this->acceso->gratis,
            'acceso_pago' => $this->acceso->pago,
            'acceso_bypass' => $this->acceso->bypass,
            'client_certificado' => $this->client_certificado,
            'PW_ID' => $PW_ID,
            'url_site' => URL_SITE,
            'portal' => $portal,
            'vitrinas' => call_vitrina($this->client_certificado, 5),
        ]);

    }


    /**
     * Envía un correo luego de una interacción con el formulario de contacto para la mesa de ayuda.
     *
     * La función debería funcionar (dah), pero tengo que probarlo montado en un servidor.
     *
     * @param Request $request
     */
    public function correo_mesa_ayuda(Request $request)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            //$mail->Host = "smtp.ubiobio.cl";
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = 'xxx@gmail.com';
            $mail->Password = 'xxx';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;
            $mail->SMTPDebug = 1;

            $mail->setFrom('certificados@ubiobio.cl', 'Mesa Ayuda Certificados UBB');
            $mail->addReplyTo('certificados@ubiobio.cl', 'Mesa Ayuda Certificados UBB');


            // Información del administrador
    //        $query = 'select MAP_RUT_ADMIN from MESA_AYUDA_PORTAL where PWE_CORRELATIVO=' . $_POST['pwe_correlativo'];
    //        $rut_admin = query($query);
    //        $query_admin = "select o.mae_email as email, m.mae_nombre as nombres, m.mae_apellido_paterno as apellido_paterno, m.mae_apellido_materno as apellido_materno
    //						from Creditotest..MAEPER m
    //						inner join Creditotest..OTROS_ANTECEDENTES o on
    //							o.mae_rut = m.mae_rut
    //						where m.mae_rut = " . $rut_admin[0]->MAP_RUT_ADMIN;
    //        $datos_admin = query($query_admin);
    //        $mail->AddAddress($datos_admin[0]->email, $datos_admin[0]->nombres . ' ' . $datos_admin[0]->apellido_paterno);
            $mail->addAddress('anillano@alumnos.ubiobio.cl', 'El Wachito Carnuo');
            $mail->AddCC($request->correo, $request->nombre);

            // Consultar descripcion del motivo a partir de correlativo
            $motivo = $request->motivo;
            $query = "select TCP_DESCRIPCION from TIPO_CONSULTA_PORTAL where TCP_CORRELATIVO=$motivo";
            $result = query($query);
            foreach ($result as $tipo) {
                $motivo = $tipo->TCP_DESCRIPCION;
            }

            // Contenido
            $mail->isHTML(true);
            $body = "Se ha enviado la consulta a la Mesa de Ayuda correspondiente, con los siguientes datos:<br/><br/>";
            $body .= "<b>Motivo</b>: " . $motivo . "<br/><br/>";
            $body .= "<b>Descripción</b>: " . $request->descripcion;
            $mail->Subject = "Mesa de ayuda Certificados: " . $request->asunto;
            $mail->Body = $body;

            $mail->send();

        } catch (Exception $e) {
            //Storage::put('salida-correo.txt', "Error al enviar el mensaje: " . $mail->ErrorInfo); // escribe errores en un archivo porque no se pueden ver.

//          $query_id = query("SELECT isnull(MAX(CPC_CORRELATIVO),0)+1 as CPC_MAX FROM CONTACTO_PORTAL_CERTIFICACIONES WHERE PWE_CORRELATIVO=2 AND SUP_CORRELATIVO=" . $request->sup);
//                $cpc_id = $query_id[0]->CPC_MAX;
//                $query = "insert into CONTACTO_PORTAL_CERTIFICACIONES (	PWE_CORRELATIVO,
//                                                     SUP_CORRELATIVO,
//                                                    CPC_CORRELATIVO,
//                                                    ECP_CORRELATIVO,
//                                                    TCP_CORRELATIVO,
//                                                    CPC_FECHA_CONTACTO,
//                                                    CPC_OBSERVACION,
//                                                    CPC_ASUNTO,
//                                                    CPC_CORREO_RESPUESTA)
//                                    VALUES		(	2,
//                                                    " . $_POST['sup'] . ",
//                                                    " . $cpc_id . ",
//                                                    1,
//                                                    " . $_POST['motivo'] . ",
//                                                    getdate(),
//                                                    '" . $_POST['descripcion'] . "',
//                                                    '" . $_POST['asunto'] . "',
//                                                    '" . $_POST['correo'] . "')";
//                  $result = query($query);
        }

    }


    /**
     * Función para cambiar la clave del usuario utilizando la ventana modal.
     *
     * NOTA: No puedo probarlo/debuggearlo sin tener garantías de que no me voy a pitear nada ya que son webservices
     * para modificar información sensible de las personas en el sistema.
     *
     * @param Request $request
     */
    public function cambiar_clave(Request $request)
    {
        Storage::put('salida-clave.txt', $request->rut.", ".$request->password0.", ".$request->password1); // escribe errores en un archivo porque no se pueden ver.
        $param = array('rut' => $request->rut, 'clave1' => $request->password0 , 'clave2' => $request->password1);
        $result = $this->client->call('cambioPasswordUsuario', $param);
        if($result == 1) {
            $query2 = 'SP_WEB_REGISTRA_ACTUALIZACION_DATOS_PERSONALES_PORTAL_FEA 2,'.$request->sup . ',null,null,1';

            /*
             * Esto modifica la contraseña. Descomentar si sabe lo que hace.
             */
            // $result2 = query($query2);
        }
        echo $result;
    }


}
