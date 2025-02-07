<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$descripcion	= @$_POST['descripcion'];
$orden			= @$_POST['orden'];
$activo     	= @$_POST['activo'];
$token			= @$_POST['token'];
$seleccionable	= @$_POST['seleccionable'];
$tipoResponsable= @$_POST['tipo_responsable'];

$id_company 	= $_SESSION["rd_company_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token_nuevo= md5(rand(99999, 99999999).$descripcion.date("Y m d H s"));

if($token=="" && $descripcion!=""){

$data = array("activo" => $activo,
			  "descripcion" => $descripcion,
			  "orden" => $orden,
			  "token" => $token_nuevo,
			  "idEmpresa" => $id_company,
			  "seleccionable" => $seleccionable,
			  "idTipoResponsablePredefinido" => $tipoResponsable);							
$resultado = $services->sendPostNoToken($url_services.'/tipoProducto',$data);		

$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token_nuevo,xxx,";

}//if($token=="" && $nombre!="")

//***********************************************************************************************************

if($token!="" && $descripcion!=""){
	
$data = array("activo" => $activo,
			  "descripcion" => $descripcion,
			  "orden" => $orden,
			  "token" => $token,
			  "idEmpresa" => $id_company,
			  "seleccionable" => $seleccionable,
			  "idTipoResponsablePredefinido" => $tipoResponsable);							
$resultado = $services->sendPut($url_services.'/tipoProducto',$data);		

//var_dump($resultado);
$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
	
	
}//if($token!="" && $nombre!=""){


?>