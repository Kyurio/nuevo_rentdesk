<?php

//*********** bruno  *****************/

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config     = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

// Valores recibidos desde AJAX
$name = $_POST['name'];
$token = $_POST['token'];
$boolean = $_POST['boolean'];

// Construir y ejecutar la consulta SQL
$query = "UPDATE propiedades.ficha_arriendo_cheques
          SET $name = '$boolean' 
          WHERE token = '$token'";

$dataCab = array("consulta" => $query);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

echo $resultadoCab;
