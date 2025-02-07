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

$token	    	= $_POST['token'];
$id_company 	= $_SESSION["rd_company_id"];
$id_usuario 	= $_SESSION["rd_usuario_id"];


$data = array("token" => $token,
			  "id_usuario" => $id_usuario);							
$resultado = $services->sendDelete($url_services.'/contratoDet/delete',$data);		

$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";

?>