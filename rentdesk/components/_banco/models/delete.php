<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token	    = $_POST['token'];
$id_company	= $_SESSION["rd_company_id"];


$data = array("token" => $token,
			  "idEmpresa" => $id_company);							
$resultado = $services->sendDelete($url_services.'/banco',$data);		

$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";

?>