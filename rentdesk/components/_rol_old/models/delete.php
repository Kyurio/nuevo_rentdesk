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

$id_company = $_SESSION["rd_company_id"];
$token			= $_POST['token'];


$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/rol/token',$data);		

$result = json_decode($resultado); 

if($result->nombre!="SuperAdmin"){		
	$dataDelete = array("idRol" => $result->idRol,"idEmpresa" => $id_company);
	$resultado = $services->sendDelete($url_services.'/menuRol/deleteByRol',$dataDelete);					
				
	$resultado = $services->sendDelete($url_services.'/rol',$data);		
	$result = json_decode($resultado); 
	echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
}

?>