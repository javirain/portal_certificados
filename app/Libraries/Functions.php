<?php
/*
 * FUNCIONES AUXILIARES
 * Esta clase tiene como función extraer el comportamiento redundante dentro de la aplicación original. Aunque no
 * lo parezca ahora, el siguiente comportamiento se encontraba repetido muchas veces, básicamente en cada clase
 * que implementaba una vista.
 */


/**
 * Obtiene la dirección IP del cliente.
 *
 * @return mixed
 */
function obtenerDireccionIP()
{
    if (!empty($_SERVER ['HTTP_CLIENT_IP'] )) {
        $ip = $_SERVER ['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER ['HTTP_X_FORWARDED_FOR'] )) {
        $ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER ['REMOTE_ADDR'];
    }

    return $ip;
}

/**
 * Crea una etiqueta LINK para el CSS en el template de una vista.
 *
 * IMPORTANTE: Esta función deberá ser reemplazada por extensiones del lenguaje de template (blade) por defecto si
 *             es que se desea maximizar el acercamiento al framework.
 *
 * @param $r_url
 * @param array $options
 * @return string
 */
function css_tag($r_url, $options=[])
{
    return '<link rel="stylesheet" href="' . asset('css/' . $r_url) . '" "' . parse_options($options) . '>';
}


/**
 * Crea una etiqueta SCRIPT para el JS en el template de una vista.
 *
 * IMPORTANTE: Esta función deberá ser reemplazada por extensiones del lenguaje de template (blade) por defecto si
 *             es que se desea maximizar el acercamiento al framework.
 *
 * @param $r_url
 * @param array $options
 * @return string
 */
function js_tag($r_url, $options=[])
{
    return '<script type="text/javascript" src="' . asset('js/' . $r_url) . '" "' . parse_options($options) . '></script>';
}


/**
 * Devuelve una representación en forma de arreglo de un set de opciones, en algun formato iterable (objetos, por
 * ejemplo). La utilidad es comunicar un elemento iterable en peticiones HTTP en forma de texto.
 *
 * IMPORTANTE: Esta función fue creada con intención de portar comportamiento de la aplicación original sin intervenir
 * la lógica. Si se desea acercar más los estándares de laravel, esta función puede simplificarse con el uso de no más
 * de 2 líneas de código utilizando funciones específicas, modificaciones que fueron postergadas por ser poco
 * relevantes para el funcionamiento.
 *
 * @param $options
 * @return string
 */
function parse_options($options)
{
    $atts = [];
    foreach ($options as $option => $value)
        array_push($atts, $option . '="' . $value . '""');
    return join(' ', $atts);
}


/**
 * Función que se encarga de entregar las vitrinas (elemento del dominio) que sean obtenidas separadas como elementos
 * en un arreglo. La intención es devolver como máximo las primeras5 vitrinas.
 *
 * @param $vitrinas
 * @return array
 */
function slice_vitrinas($vitrinas)
{
    if (count($vitrinas) <= 5) return $vitrinas;
    return array_slice($vitrinas, 0, 5);
}


/**
 * Función que solicita a un web service las vitrinas asociadas a algún certificado. El comportamiento de esta función
 * puede ser combinado/sincronizado con el de la funcion anterior "slice_vitrina", en especial la generalización de los
 * límites de vitrina por consulta.
 *
 * @param nusoap_client $certificado
 * @param bool|int $limit
 * @return array
 */
function call_vitrina($certificado, $limit=false)
{
    $vitrinas = $certificado->call('getVitrina', array());
    if($limit) {
        if (count($vitrinas) <= $limit) return $vitrinas;
        return array_slice($vitrinas, 0, $limit);
    }
    return $vitrinas;
}


/**
 * SOlicita una TinyURL para la URL completa entregada. Esto es... útil... supongo?
 *
 * @param $url
 * @return mixed
 */
function get_tiny_url($url)
{
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}


/**
 * Función "mime_content_type" alternativa a la original para asegurar compatibilidad con el framework.
 *
 * @param $filename
 * @return bool|string
 */
function _mime_content_type($filename)
{
    $result = new finfo();
    if (is_resource($result) === true) {
        return $result->file($filename, FILEINFO_MIME_TYPE);
    }

    return false;
}


/**
 * Obtiene los valores de bit de acceso. No tengo idea que son los bits de acceso, ni para que sirven. Creo que me
 * lo explicaron, pero probablemente no le encontré mucho sentido porque se me olvidó... la cosa es que funciona.
 *
 * @return stdClass
 */
function get_acceso()
{
    // Valores iniciales para los accesos
    $gratis = 1;
    $pago	= 1;
    $bypass	= 1;

    // IPs registradas (que significan algo)
    $ips_gratis = array("146.83.198.79", "146.83.202.102", "146.83.202.113");
    $ips_pago	= array("146.83.198.79", "146.83.202.108", "146.83.202.107");
    $ips_bypass	= array("146.83.198.79", "146.83.202.108","146.83.202.107");

    // Evaluar valor correspondiente de los accesos
    $ipCliente = obtenerDireccionIP();
    $acceso = new stdClass();
    $acceso->gratis =   (in_array($ipCliente, $ips_gratis)) ?   1 : $gratis;
    $acceso->pago =     (in_array($ipCliente, $ips_pago)) ?     1 : $pago;
    $acceso->bypass =   (in_array($ipCliente, $ips_bypass)) ?   1 : $bypass;

    return $acceso;
}


/**
 * Da formato al nombre de una persona, recibiendo la información en un arreglo (que perfectamente podría tener más
 * información que la solicitada). Puede decidirse si incluir o no el apellido materno (es un poco injusto con nuestras
 * madres, pero a veces el nombre completo no cabe bien en la vista HTML.
 * Codifica el nombre en formato UTF8 para evitar errores en la visualización de caracteres especiales (acentos y eñes)
 *
 * @param $datos_usuario
 * @param bool $apellido_materno
 * @return string
 */
function formatear_nombre($datos_usuario, $apellido_materno = false)
{
    return utf8_encode($datos_usuario['nombres'] . ' ' . $datos_usuario['paterno'] . ($apellido_materno ? ' ' . $datos_usuario['materno'] : ''));
}


/**
 * Codifica un elemento de un arreglo en formato UTF8 para evitar errores en la visualización de caracteres especiales
 * (acentos y eñes). El arreglo puede contener muchos elementos que no sean relevantes, pero se analizará únicamente
 * el que se encuentre bajo el índice que se indique.
 *
 * @param $datos_usuario
 * @param $indice
 * @return string
 */
function formatear_string_arreglo ($arreglo, $indice)
{
    return utf8_encode($arreglo[$indice]);
}


/**
 * Codifica una cadena de texto en formato UTF8 para evitar errores en la visualización de caracteres especiales
 * (acentos y eñes).
 *
 * @param $string
 * @return string
 */
function formatear_string ($string)
{
    return utf8_encode($string);
}


/**
 * Da formato al RUT de una persona, utilizando la información contenido en un arreglo que defina al menos un índice
 * "rut" y otro índice "dv". Esto funciona porque las salidas de las solicitudes de datos de usuario al web service
 * entregan estos índices siempre que exista la sesión y el usuario.
 *
 * @param $datos_usuario
 * @return string
 */
function formatear_rut ($datos_usuario)
{
    return strrev(join('.', str_split(strrev($datos_usuario['rut']), 3))) . '-' . $datos_usuario['dv'];
}