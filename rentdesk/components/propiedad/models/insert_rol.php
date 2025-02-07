<?php

// ************** bruno ****************

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_propiedad = $_POST['id_propiedad'];
$numero = $_POST['numero'];
$principal = $_POST['principal'];
$id_comuna = $_POST['id_comuna'];

$query = "INSERT INTO propiedades.propiedad_roles(
	 id_propiedad, numero, principal, id_comuna)
	VALUES ($id_propiedad, '$numero', $principal, $id_comuna);";

$dataCab = array("consulta" => $query);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

if ($resultadoCab) {
    echo true;
} else {
    echo false;
}