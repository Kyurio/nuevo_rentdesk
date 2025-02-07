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


$data = array("token" => $token);							
$resultado = $services->sendPostNoToken($url_services.'/packCab/deletePack',$data);			
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result = $result_r;
	}//foreach($result_json as $result)
	echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
}
?>