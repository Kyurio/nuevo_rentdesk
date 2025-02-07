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


$id_cheque = @$_POST['idCheque'];
$queryUpdateCheque = "UPDATE propiedades.ficha_arriendo_cheques
SET habilitado = false
WHERE id = $id_cheque";
//var_dump($queryUpdateCheque);
$dataCab = array("consulta" => $queryUpdateCheque);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

echo "true";

