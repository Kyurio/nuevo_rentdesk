<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$token_item		= $_POST['item'];
$sufijo			= $_POST['sufijo'];
$token_visita	= $_POST['token'];
$id_company 	= $_SESSION["rd_company_id"];

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$aleatorio = rand(9999,999999999);
$token_item_check = md5( $aleatorio . $token_visita. $token_item );


$data = array("token_visita" => $token_visita,"token_item" => $token_item,"sufijo" => $sufijo,"token_item_check" => $token_item_check);	
$resultado = $services->sendPostNoToken($url_services.'/visita/insertItemCheck',$data);
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