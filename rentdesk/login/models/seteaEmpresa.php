<?php
session_start();
include("../../configuration.php");
include("../../includes/funciones.php");
include("../../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_base = $config->urlbase;
$url_services = $config->url_services;

$empresa      	= @$_POST["empresa"];
$sucursal	    = @$_POST["sucursal"];
$id_usuario     = $_SESSION["rd_usuario_id"];	

$data = array("idUsuario" => $id_usuario,"tokenEmpresa" => $empresa);						
$resultado = $services->sendPostNoToken($url_services.'/usuario/loginEmpresa',$data);	
$json = json_decode ($resultado);

foreach($json as $result_r) {
	$result = $result_r;
	
	$_SESSION["rd_usuario_valido_arpis"]="true" ;
	$_SESSION["rd_usuario_token"] 	=@$result->token;
	$_SESSION["usuario_nombre"]	=@$result->nombre_usuario;
	$_SESSION["rd_usuario_id"]		=@$result->id_usuario;	
	$_SESSION["usuario_email"] 	=@$result->email; 
	$_SESSION["usuario_rol"]   	=@$result->id_rol;
	$_SESSION["company_token"] 	=@$result->token_empresa; 
	$_SESSION["rd_company_id"] 	=@$result->id_empresa_emp;
	$_SESSION["company_nombre"] =@$result->nombre_fantasia_emp;
	$_SESSION["company_email"]  =@$result->email_emp;
	$_SESSION["company_zona"]   = "";
	$_SESSION["company_logo"]   =@$result->logo_emp;
	$_SESSION["cant_decimales"] =@$result->cant_decimales_emp;
	$_SESSION["separador_mil"]  =@$result->separador_mil_emp;
	echo ",xxx,ok,xxx,1,xxx,"; 
}	
	
//Aqui setear Empresa y sucursal:



?>