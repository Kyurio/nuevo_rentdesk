<?php

// *********** bruno  *****************/

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config     = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

// Valores recibidos desde AJAX
$token = @$_GET['token'];

// Construir y ejecutar la consulta SQL
$query = "SELECT id FROM propiedades.propiedad WHERE token = '$token'";


$dataCab = array("consulta" => $query);

// Suponiendo que sendPostDirecto devuelve el resultado como un array
$resultadoCab = $services->sendPostNoToken($url_services . '/util/objeto', $dataCab, []);
echo $resultadoCab;
