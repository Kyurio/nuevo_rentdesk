<?php
include("../configuration.php");
include("../includes/funciones.php");
include("../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_base = $config->urlbase;
$url_services = $config->url_services;
$apiKey = $config->apiKey;
$apiDir = $config->apiDir;

$fecha  = date("Y-m-d");	

echo "Fecha Proceso $fecha ";
echo "\n********      Cargando UF para empresa 2    Resultado = ";
$data = array("direccion" => $apiDir,
			  "codMoneda" => "UF",
			  "fecha" => $fecha,
			  "idEmpresa" => "2",
			  "apiKey"=> $apiKey);							
$resultado = $services->sendPostNoToken($url_services.'/util/actMoneda',$data);		
echo $resultado; 

echo "\n********      Cargando USD para empresa 2    Resultado = ";
$data = array("direccion" => $apiDir,
			  "codMoneda" => "USD",
			  "fecha" => $fecha,
			  "idEmpresa" => "2",
			  "apiKey"=> $apiKey);							
$resultado = $services->sendPostNoToken($url_services.'/util/actMoneda',$data);		
echo $resultado; 	
	

echo "\n********      Cargando IPC para Chile    Resultado = ";
$data = array("direccion" => $apiDir,
			  "codMoneda" => "IPC",
			  "fecha" => $fecha,
			  "idEmpresa" => "2",
			  "apiKey"=> $apiKey);							
$resultado = $services->sendPostNoToken($url_services.'/util/actMoneda',$data);		
echo $resultado; 	
	
?>