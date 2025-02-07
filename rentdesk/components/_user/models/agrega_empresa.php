<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");
 
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$empresa		= @$_POST['empresa'];
$sucursal		= @$_POST['sucursal'];
$token			= @$_POST['token'];

if($empresa!="" && $token !=""){
	
$data = array("token" => $token,
			  "idEmpresa" => $empresa,
			  "idSucursal" => $sucursal);		  
$resp = $services->sendPostNoToken($url_services.'/usuario/asignaEmpSuc',$data);	
$json_resp= json_decode($resp);

foreach($json_resp as $result_r){
	$result = $result_r;
}

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
	
}//if($empresa!="" && $sucursal !="" && token !="")


?>