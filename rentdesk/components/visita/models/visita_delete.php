<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$token				= $_POST['token'];
$id_company 		= $_SESSION["rd_company_id"]; 

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$hoy = date ("Y-m-d");

$id_visita ="";
$data = array("token" => $token,
			  "idEmpresa" => $id_company);		
$resultado = $services->sendPostNoToken($url_services.'/visita/token',$data);
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
	$id_visita	= @$result->id_visita;
}//foreach($result_json as $result)	
}

$query = "SELECT a.* FROM arpis.visita_item_check a WHERE id_visita='$id_visita' ";
$data = array("query" => $query);	
$resultado = $services->sendPostNoToken($url_services.'/visita/object',$data);
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result2 = $result_r;
		
		$query = "SELECT a.* FROM arpis.archivo a WHERE id_referencia='$result2->id_visita_item_check' AND componente='visita' AND titulo='foto' ";
		$data = array("query" => $query);	
		$resultado_2 = $services->sendPostNoToken($url_services.'/visita/object',$data);
		if($resultado_2){
			$result_json_2 = json_decode($resultado_2); 
			foreach($result_json_2 as $result_r2) {
				$result3 = $result_r2;
				$data = array("token" => $result3->token);	
				$resultado = $services->sendPostNoToken($url_services.'/archivo/delete',$data);
				@unlink("../../../upload/fotos/".$result3->archivo);
			}//foreach($result_json as $result)
		}		
	}//foreach($result_json as $result)
}	

$query = "SELECT a.* FROM arpis.archivo a WHERE id_referencia='$id_visita'  AND componente='visita' AND titulo='rut' ";
$data = array("query" => $query);	
$resultado = $services->sendPostNoToken($url_services.'/visita/object',$data);
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r3) {
	$result3 = $result_r3;
	$data = array("token" => $result3->token);	
	$resultado = $services->sendPostNoToken($url_services.'/archivo/delete',$data);
	@unlink("../../../upload/rut/".$result3->archivo);
}	
}
		

$data = array("token" => $token,
			  "idEmpresa" => $id_company);	
$resultado = $services->sendPostNoToken($url_services.'/visita/deleteVisita',$data);
if($resultado){
	$result = json_decode($resultado); 
}
 

?>