<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$producto		= @$_POST['producto'];
$fecha_inicio	= @$_POST['fecha_inicio'];
$valor_cuota	= @$_POST['valor_cuota'];
$plazo			= @$_POST['plazo'];
$token			= @$_POST['token'];
$token_contrato	= @$_POST['token_contrato'];

$id_company 	= $_SESSION["rd_company_id"];
$id_usuario 	= $_SESSION["rd_usuario_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$valor_cuota	 = desformatea_number($valor_cuota,$_SESSION["separador_mil"]);

$token_nuevo= md5(rand(99999, 99999999).$token_contrato.$producto.date("Y m d H s"));

if($token==""){

$data = array("token_producto" => $producto,
			  "fecha_inicio" => $fecha_inicio,
			  "valor_cuota" => $valor_cuota,
			  "plazo" => $plazo,
			  "token_contrato" => $token_contrato,
			  "token" => $token_nuevo,
			  "id_usuario" => $id_usuario);							
$resultado = $services->sendPostNoToken($url_services.'/contratoDet/new',$data);		

$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token_nuevo,xxx,";

}//if($token=="" && $nombre!="")

//***********************************************************************************************************

if($token!=""){
	

	
	
}//if($token!="" && $nombre!=""){


?>