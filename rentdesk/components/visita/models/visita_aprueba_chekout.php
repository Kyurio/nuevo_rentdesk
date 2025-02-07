<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$token_item_check  = $_POST['token'];
$resultado	= $_POST['tipo'];
$observacion	= $_POST['texto'];

$id_company = $_SESSION["rd_company_id"];

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;	

$data = array("token_item_check" => $token_item_check,
			  "resultado" => $resultado,
			  "observacion" => $observacion);	
$resultado = $services->sendPostNoToken($url_services.'/visita/respCheckout',$data);
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result = $result_r;
	}//foreach($result_json as $result)
	
	if ($result->status == "OK"){
		echo ",xxx,OK,xxx,$result->status";
	}else{
		echo ",xxx,ERROR,xxx,$result->status";	
	}		
}


 

?>