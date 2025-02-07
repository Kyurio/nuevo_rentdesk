<?php
session_start();
include("../../configuration.php");
include("../../includes/funciones.php");
include("../../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_base = $config->urlbase;
$url_services = $config->url_services;

$accion      = @$_POST["accion"];
$password_act = md5(@$_POST["password_act"]);
$password_new = md5(@$_POST["password_new"]);
$id_usuario  = $_SESSION["rd_usuario_id"];	

if($accion == "a9c5c54a0bed5ecd0340dbc718225efc"){ //actualizar

	$data = array("idUsuario" => $id_usuario,"passwordAct" => $password_act,"passwordNew" => $password_new);							
	$resultado = $services->sendPostNoToken($url_services.'/usuario/changePass',$data);							
	$json = json_decode ($resultado);
	
	if ($json->status == 'OK'){
		$_SESSION["rd_usuario_valido_arpis"]="true" ;
		$_SESSION["rd_usuario_token"] 	=@$json->token;
		$_SESSION["usuario_nombre"]	=@$json->nombreUsuario;
		$_SESSION["rd_usuario_id"]		=@$json->idUsuario;	
		$_SESSION["usuario_email"] 	=@$json->email; 
		$_SESSION["usuario_rol"]   	=@$json->rol->idRol;
		$_SESSION["company_token"] 	=@$json->empresa->token; 
		$_SESSION["rd_company_id"] 	=@$json->empresa->idEmpresa;
		$_SESSION["company_nombre"] =@$json->empresa->nombreFantasia;
		$_SESSION["company_email"]  =@$json->empresa->email;
		$_SESSION["company_zona"]   = "";
	    $_SESSION["company_logo"]   =@$json->empresa->logo;
		
		echo ",xxx,$json->status,xxx,$json->mensaje,xxx,";
	}else{				     
		echo ",xxx,$json->status,xxx,$json->mensaje,xxx,";
	}
}else{				     
	echo ",xxx,ERROR,xxx,Formulario invalido,xxx,";
}	

?>