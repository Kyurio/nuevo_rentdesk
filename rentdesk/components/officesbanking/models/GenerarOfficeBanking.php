<?php

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config         = new Config;
$services       = new ServicesRestful;
$url_services   = $config->url_services;

// Obtener el método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

$cierre = $_GET["cierre"];

/*Consulta Cantidad de registros 0= solo lectura 1= ejecuta Office Banking*/
$query_count = "SELECT propiedades.fn_genera_officeb($cierre, 1)";

$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);



echo $resultado;
