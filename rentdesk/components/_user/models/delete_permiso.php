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

$token_empresa	= $_POST['token_empresa'];
$token_sucursal	= $_POST['token_sucursal'];
$token	    	= $_POST['token'];
$id_company	= $_SESSION["rd_company_id"];


$data = array("tokenEmpresa" => $token_empresa,
			  "tokenSucursal" => $token_sucursal,
			  "token" => $token);							
$resultado = $services->sendPostNoToken($url_services.'/usuario/eliminaEmpSuc',$data);		
//var_dump($resultado);
$json_result = json_decode($resultado); 
foreach($json_result as $result_r) {
	$result = $result_r;
}	

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";

?>