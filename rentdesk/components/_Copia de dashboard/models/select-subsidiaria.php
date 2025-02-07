<?php
session_start();
//include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

////var_dump("ENTRÓ A SELECT SUBSIDIARIA");
////var_dump("POST SUBSIDIARIA: ", json_decode($_POST['subsidiaria']));

// Check if $_SESSION['rd_current_subsidiaria'] is set, otherwise initialize it
if (!isset($_SESSION['rd_current_subsidiaria'])) {
    $_SESSION['rd_current_subsidiaria'] = null; // or initialize it with a default value
}

if (!isset($_SESSION['sesion_rd_sucursales'])) {
    $_SESSION['sesion_rd_sucursales'] = null; // or initialize it with a default value
}

// Assuming $_SESSION['sesion_subsidiarias'] contains the serialized array
if (isset($_SESSION['sesion_rd_subsidiarias'])) {
    // Handle AJAX request to update session with selected subsidiaria
    if (isset($_POST['subsidiaria'])) {
        $_SESSION['rd_current_subsidiaria'] = serialize(json_decode($_POST['subsidiaria']));
        //var_dump("SUBSIDIARIA SELECCIONADA: ", $_SESSION['rd_current_subsidiaria']);

        /*LLAMADO A ENDPOINT SUCURSALES */
        $current_subsidiaria = json_decode($_POST['subsidiaria']);

        //var_dump("current_subsidiaria: ", $current_subsidiaria);
        $queryParamsSuc = array(
            'token_subsidiaria' => $current_subsidiaria->token
        );

    

        $resultadoSuc = $services->sendGet($url_services . '/rentdesk/cuentas/sucursales', null, [], $queryParamsSuc);

        $jsonSuc = json_decode($resultadoSuc);

        $_SESSION["sesion_rd_sucursales"] = serialize($jsonSuc);


        exit(0); // Stop further execution
    }
} else {
    echo 'No subsidiarias found in session storage.';
}
