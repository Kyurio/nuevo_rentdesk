<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$token				= @$_POST['token'];

$id_company 	= $_SESSION["rd_company_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

if($token!=""){

$data = array("token" => $token);							
$resultado = $services->sendPostNoToken($url_services.'/contratoCab/forzarTermino',$data);		
//var_dump($resultado);
$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";

}//if($token=="" && $nombre!="")



?>