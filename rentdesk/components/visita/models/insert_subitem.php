<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$item		= $_POST['item'];
$respuesta	= $_POST['respuesta'];
$token_visita = $_POST['token'];
$id_company = $_SESSION["rd_company_id"];

$item = explode("|", $item);
$id_item_check 	= $item[0];
$valor_item = $item[1];

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$aleatorio = rand(9999,999999999);
$token_visita_respuesta = md5( $aleatorio . $token_visita. $id_item );

$data = array("token_visita" => $token_visita,
			  "id_item_check" => $id_item_check,
			  "valor_item" => $valor_item,
			  "respuesta" => $respuesta,
			  "token_visita_respuesta" => $token_visita_respuesta);	
$resultado = $services->sendPostNoToken($url_services.'/visita/insertVisitaRespuesta',$data);
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result = $result_r;
	}//foreach($result_json as $result)
	
	if ($result->status == "OK"){
		echo ",xxx,OK,xxx,$result->status";
	}else{
		echo ",xxx,ERROR,xxx,$result->status";	
	}		
}


 

?>