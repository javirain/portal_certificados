<?php
/*
* SCRIPT PARA GESTIÓN DE LA CONEXIÓN A BASE DE DATOS MSSQL
*/

/**
* Intenta generar una conexión con el servidor MSSQL.
* Devuelve la conexión de lograrse con éxito, sino devuelve false.
*
* @return false|resource
*/
function conectar(){

$server_mssql="sistemasnodo2.dci.ubiobio.cl,10433";
$db_mssql="Portal_procesos";
$usuario_mssql="web_portal_certificado";
$pass_mssql="Lunes24";

//
$cnx=odbc_connect("Driver={ODBC Driver 17 for SQL Server};Server=sistemasnodo2.dci.ubiobio.cl,10433;Database=Portal_procesos;",$usuario_mssql, $pass_mssql);



if( $cnx === false ) {
   
    exit( print_r( odbc_error(), true));
}
return $cnx;

/*

---------------------------------------------------

    / 142117924 (RUT de Joan, para pruebas)
    $connectionInfo = array( "Database"=>"Portal_procesos", "UID" => "web_portal_certificado", "PWD" => "Lunes24");
    $cnx = sqlsrv_connect("sistemasnodo2.dci.ubiobio.cl,10433", $connectionInfo);  //146.83195.106
    //$cnx = mssql_connect("test.dci.ubiobio.cl", "web_portal_certificado", "Test_2016");  //146.83195.106
    if( $cnx === false ) {
        exit( print_r( sqlsrv_errors(), true));
    }
    return $cnx;


*/



}

/**
* Ejecuta una consulta al servidor MSSQL y devuelve el resultado como un arreglo. Si no se encuentran resultados
* por cualquier motivo devuelve un arreglo vacío.
*
* @param $consulta
* @return array
*/
function query($consulta){

    $cnx = conectar();
        $result = odbc_do($cnx, $consulta);
        // $result = odbc_exec($cnx, $consulta);

        $registros = array();
        while ($dato = odbc_fetch_object($result))
            array_push($registros, $dato);
            odbc_free_result($result);
        odbc_close($cnx);
        return $registros;
/*
----------------------------------------------------------------



 $cnx = conectar();
    $result = sqlsrv_query($cnx, $consulta);
    $registros = array();

        while ($dato = sqlsrv_fetch_object($result))
            array_push($registros, $dato);
            sqlsrv_free_stmt($result);

    sqlsrv_close($cnx);
    return $registros;


*/

}

/**
* Llama a actualizar un valor en la base de datos MSSQL
*
* @param $consulta
*/

function update($consulta){
    
    $cnx = conectar();
    $result=odbc_do($cnx, $consulta);
       // $result = odbc_exec($cnx, $consulta);

    odbc_free_result($result);  
    odbc_close($cnx);

/*

----------------------------------------------------------------

$cnx = conectar();
    $result=sqlsrv_query($cnx, $consulta);
    sqlsrv_free_stmt($result);
    sqlsrv_close($cnx);


*/


}

/**
* Codifica un string utilizando alguna técnica (actualmente sha1) y devuelve su base64.
*
* @param $hash_source
* @return string
*/
function rsha1($hash_source) {
/*$hash = mhash (MHASH_SHA1, $hash_source);
$hex_hash = bin2hex ($hash);
return base64_encode ($hash);*/
$hash = sha1($hash_source,1);
return base64_encode($hash);
}

/**
* Convierte un objeto en un arreglo.
*
* @param $d
* @return array
*/
function objectToArray($d) {
if (is_object($d)) {
// Gets the properties of the given object
// with get_object_vars function
$d = get_object_vars($d);
}

if (is_array($d)) {
/*
* Return array converted to object
* Using __FUNCTION__ (Magic constant)
* for recursive call
*/
return array_map(__FUNCTION__, $d);
}
else {
// Return array
return $d;
}
}

/**
* Retorna el mes al que corresponde un cierto timestamp. Si no se especifica el timestamp devuelve el mes actual.
*
* @param int $timestamp
* @return mixed
*/
function getMes( $timestamp = 0 ){
$timestamp = $timestamp == 0 ? time() : $timestamp;
$meses = array('','enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre');
return $meses[date("n", $timestamp)];
}

?>