<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");
 

$nombre			= $_POST['nombre'];
$email			= $_POST['email'];
$token_rol		= $_POST['rol'];
$clave			= $_POST['clave'];
$token			= @$_POST['token'];

$id_empleado 	= "";

if("" != $clave){
$clave	= md5($clave);
}

$id_company 	= $_SESSION["rd_company_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$token_nuevo	= md5(rand(99999, 99999999).$nombre.date("Y m d H s"));

if($_SESSION["rd_usuario_token"] == $token)
$_SESSION["rd_usuario_token"] = $token_nuevo;


$data_rol = array("token" => $token_rol,"idEmpresa" => $id_company);							
$result_rol = $services->sendPostNoToken($url_services.'/rol/token',$data_rol);		
$json_rol = json_decode($result_rol); 
$id_rol = $json_rol->idRol;

$ip = getRealIP();
$fecha = new DateTime();

if($token=="" && $nombre!="" && $email!="" ){
	
$data = array("email" => $email,
			  "idEmpleado" => $id_empleado,
			  "ipActualizacion" => $ip,
			  "ipRegistro" => $ip,
			  "nombreUsuario" => $nombre,
			  "password" => $clave,
			  "idRol" => $id_rol,
			  "token" => $token_nuevo,
			  "idEmpresa" => $id_company);					  
$resultado = $services->sendPostNoToken($url_services.'/usuario',$data);
$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token_nuevo,xxx,";

}//if($token=="" && $nombre!="")

//***********************************************************************************************************

if($token!="" && $nombre!="" && $email!="" ){
	
$data = array("email" => $email,
			  "idEmpleado" => $id_empleado,
			  "ipActualizacion" => $ip,
			  "ipRegistro" => $ip,
			  "nombreUsuario" => $nombre,
			  "password" => $clave,
			  "idRol" => $id_rol,
			  "token" => $token,
			  "idEmpresa" => $id_company);							
$resultado = $services->sendPut($url_services.'/usuario',$data);		

$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
	
}//if($token!="" && $nombre!=""){


?>