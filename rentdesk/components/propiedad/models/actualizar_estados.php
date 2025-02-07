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
$id_propiedad = $_POST['id_propiedad'];
echo $token;
echo str_repeat('&nbsp;', 5);

// Construir y ejecutar la consulta SQL
$query = "UPDATE propiedades.propiedad_roles
          SET principal = false 
          WHERE id_propiedad = $id_propiedad";

$dataCab = array("consulta" => $query);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

echo $resultadoCab;
