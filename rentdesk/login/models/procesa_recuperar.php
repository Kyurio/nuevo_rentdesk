<?php
include("../../configuration.php");
include("../../includes/funciones.php");
include("../../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_base = $config->urlbase;
$url_services = $config->url_services;

$token	     = @$_POST["token"];
$password	 = md5(@$_POST["pass"]);


$data = array("token" => $token);							
$resultado = $services->sendPostNoToken($url_services.'/usuario/token',$data);		
$json = json_decode ($resultado);

if(@$json->token != ""){
$data = array("idUsuario" =>@$json->idUsuario,"passwordAct" => @$json->password,"passwordNew" => $password);							
$resultado = $services->sendPostNoToken($url_services.'/usuario/changePass',$data);							
$json = json_decode ($resultado);	
	if ($json->status == 'OK'){
		echo "xxx,1,xxx";
	}else{
		echo "xxx,0,xxx";
	}		
}else{
echo "xxx,0,xxx";
}
	
	

?>