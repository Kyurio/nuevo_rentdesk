<?php


session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];


$idRol = @$_POST['idRol'];

$queryUpdateRol = "UPDATE propiedades.cuenta_roles
SET habilitado = false
WHERE id = $idRol";
$dataCab = array("consulta" => $queryUpdateRol);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

if ($resultadoCab) {
    echo "true";
} else {
    echo "false";
}
