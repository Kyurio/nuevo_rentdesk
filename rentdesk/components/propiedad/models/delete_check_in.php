<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$token	  		 = @$_POST['token'];
$token_propiedad = @$_POST['token_propiedad'];
$id_company 	 = $_SESSION["rd_company_id"];
$id_usuario 	 = $_SESSION["rd_usuario_id"];



$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


if($token!="" && $token_propiedad!=""){

$data = array("tokenPropiedad" => $token_propiedad,
			  "tokenVisita" => $token,
			  "idUsuario" => $id_usuario,
			  "accion" => "D");							
$resultado = $services->sendPostNoToken($url_services.'/propiedad/asignaCheckIn',$data);		
$json = json_decode($resultado); 
foreach($json as $result_r){
	$result = $result_r;
}
echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token_propiedad,xxx,";

}//if($token=="" && $nombre!="")
else{
	echo ",xxx,ERROR,xxx,No ha Ingresado valores,xxx,$token_propiedad,xxx,";
}	

?>