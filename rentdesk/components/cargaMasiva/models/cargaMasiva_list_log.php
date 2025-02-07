<?php
@include("../../includes/sql_inyection.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;



$token	= @$_GET["token"];
$id_company = $_SESSION["rd_company_id"]; 
$rol_usuario = $_SESSION["usuario_rol"];
$nombre			= @$_GET["n"];


//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=cargaMasiva_list_log&view=cargaMasiva_list_log&token=$token&nav=$nav");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=cargaMasiva_list_log&view=cargaMasiva_list_log";
}	

if(isset($nombre)){
$nombre = base64_decode($nombre); 
}

?>