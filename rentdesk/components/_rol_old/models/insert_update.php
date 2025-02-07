<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$nombre	= $_POST['nombre'];
$token	= $_POST['token'];

$id_company 	= $_SESSION["rd_company_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token_nuevo = md5(rand(99999, 99999999).$nombre.date("Y m d H s"));

if($token=="" ){

$data = array("nombre" => $nombre,
			  "token" => $token_nuevo,
			  "idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/rol',$data);		

$result = json_decode($resultado); 
$token = $token_nuevo;

}else{  

if($token!="" ){
	
$data = array("nombre" => $nombre,
			  "token" => $token,
			  "idEmpresa" => $id_company);							
$resultado = $services->sendPut($url_services.'/rol',$data);		

$result = json_decode($resultado); 	

}
}


//*******************************************************************************************************


$dataDelete = array("idRol" => $result->idRol,"idEmpresa" => $id_company);
$resultado = $services->sendDelete($url_services.'/menuRol/deleteByRol',$dataDelete);	


$data_menu = array("idRol" => @$result->idRol,
				   "idEmpresa" => $id_company);							
$resp_menus = $services->sendPostNoToken($url_services.'/menu/menuForRol',$data_menu);	
$menus = json_decode($resp_menus);

foreach($menus as $menu) {
	
	$token_permiso = $menu->token;
	if( @$_POST["permiso_".$token_permiso]=="1"  ){
		$token_insert = md5(rand(99999, 99999999).$nombre.$menu->id_menu.$result->idRol.date("Y m d H s"));
		$data_insert = array("idMenu" => $menu->id_menu,
							  "idRol" => $result->idRol,
							  "token" => $token_insert,
							  "idEmpresa" => $id_company);												
		$resultado_insert = $services->sendPostNoToken($url_services.'/menuRol',$data_insert);	
	}	
}


echo ",xxx,OK,xxx,Los Permisos han sido asignados,xxx,$token,xxx,";



?>