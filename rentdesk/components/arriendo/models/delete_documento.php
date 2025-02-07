<?php 


session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];



$idDocumento = @$_POST['tokenEliminar'];
//var_dump($idDocumento);
$queryUpdateServicio = "UPDATE propiedades.propiedad_archivos
SET estado = false
WHERE token = '$idDocumento' ";

// var_dump($queryUpdateServicio);
$dataCab = array("consulta" => $queryUpdateServicio);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

echo $resultadoCab;

