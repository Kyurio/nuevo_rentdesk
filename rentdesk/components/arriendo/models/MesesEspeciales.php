<?php

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token = $_GET["token"];


/*Consulta Cantidad de registros*/
$query_count = "SELECT a.*, b.token from propiedades.ficha_arriendo_reajustes_fijacion_mes a
INNER JOIN propiedades.ficha_arriendo b ON a.id_arriendo = b.id
WHERE token = '$token' ORDER BY id_mes";

$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);



echo $resultado;
