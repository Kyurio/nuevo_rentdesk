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


// Construir y ejecutar la consulta SQL
$query = "SELECT nombre FROM propiedades.tp_tipo_recordatorio";


$dataCab = array("consulta" => $query);

// Suponiendo que sendPostDirecto devuelve el resultado como un array
$resultadoCab = $services->sendPostNoToken($url_services . '/util/objeto', $dataCab, []);
echo $resultadoCab;
