<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$token	= $_POST['token'];

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$hoy = date ("Y-m-d");

$data = array("token" => $token);	
$resultado = $services->sendPostNoToken($url_services.'/archivo/delete',$data);
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result = $result_r;
	}//foreach($result_json as $result)
	
	if ($result->status == "OK"){
		try {
				if( delete_file("../../../upload/fotos/".$result->archivo) === true ){
					$msg = "OK";
				}	
		}catch (Exception $e) {
				$msg = $e->getMessage(); 
		}
		echo ",xxx,OK,xxx,Archivo Eliminado exitosamente";
	}else{
		echo ",xxx,ERROR,xxx,Error al ejecutar servicio.";	
	}		
}

?>