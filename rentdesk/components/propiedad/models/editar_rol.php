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
$numero = $_POST['numero'];
$principal = $_POST['principal'];
$token = $_POST['token'];

// Construir y ejecutar la consulta SQL
$query = "UPDATE propiedades.propiedad_roles
          SET numero = '$numero', principal = $principal
          WHERE token = '$token'";

$dataCab = array("consulta" => $query);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

echo $resultadoCab;