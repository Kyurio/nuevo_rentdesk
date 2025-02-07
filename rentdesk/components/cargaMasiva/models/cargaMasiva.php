<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token			= @$_GET["t"];
$nombre			= @$_GET["n"];
$id_company 	= $_SESSION["rd_company_id"]; 
$rol_usuario 	= $_SESSION["usuario_rol"];
$id_usuario    	= $_SESSION["rd_usuario_id"];
$get_nombre		= $nombre;


//************************************************************************************************************
//proceso para las navegaciones
$pag_origen = codifica_navegacion("component=cargaMasiva&view=cargaMasiva&token=$token&nav=$nav");

if(isset($nav)){
	$nav = "index.php?".decodifica_navegacion($nav);
}else{
	$nav = "index.php?component=cargaMasiva&view=cargaMasiva_list";
}	

if(isset($nombre)){
$nombre = base64_decode($nombre); 
}



?>