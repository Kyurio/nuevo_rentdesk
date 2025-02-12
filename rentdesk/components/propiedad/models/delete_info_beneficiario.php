<?php


session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$id = $_POST["id_beneficiario"];


$queryUpdateInfoComentario = "UPDATE propiedades.persona_beneficiario SET habilitado = false WHERE id = $id";
$dataCab = array("consulta" => $queryUpdateInfoComentario);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);


if ($resultadoCab) {
    echo "true";
} else {
    echo "false";
}
