<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token	= @$_GET["token"];

$current_sucursal = unserialize($_SESSION["rd_current_sucursal"]);
var_dump($current_sucursal->sucursalToken);
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
var_dump($current_subsidiaria);
$_SESSION["sesion_rd_current_propiedad_token"] = null;

//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=propiedad&view=propiedad&token=$token&nav=$nav");

if (isset($nav)) {
	$nav = "index.php?" . decodifica_navegacion($nav);
} else {
	$nav = "index.php?component=propiedad&view=propiedad_list";
}


//************************************************************************************************************
