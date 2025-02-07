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


$idPropiedad = @$_POST['idPropiedad'];
$queryUpdatePropiedad = "UPDATE propiedades.propiedad
SET habilitado = false
WHERE id = $idPropiedad";
//var_dump($queryUpdateCheque);
$dataCab = array("consulta" => $queryUpdatePropiedad);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

echo "true";

