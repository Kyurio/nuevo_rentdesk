<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$token				= $_POST['token'];
$id_company 	= $_SESSION["rd_company_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$contrato_anterior 	= "";
	
$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/contratoCab/token',$data);	

if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
	$id_contrato 		= @$result->id_contrato;
	$contrato_anterior 	= @$result->archivo_contrato;
	
	try {
		if( delete_file("../../../upload/contrato/".$contrato_anterior) === true ){
			$msg = "OK";
		}	
	}catch (Exception $e) {
			$msg = $e->getMessage(); 
	}
		
}//foreach($result_json as $result)
}

if($token!=""){
$mandato = "";	
$data = array( "token" => @$result->token,
			  "id_empresa" => @$result->id_empresa);	
$resultado = $services->sendPostNoToken($url_services.'/contratoCab/borraAdjunto',$data);	
//var_dump($resultado);
$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
	
	
}//if($token!="" && $nombre!=""){
?>