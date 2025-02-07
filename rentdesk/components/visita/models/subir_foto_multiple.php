<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$token 		= $_GET["token"];
$archivo 	= $_GET["archivo"];


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];

$patronIMG 	= "%\.(jpg|PNG|png|JPG|JPEG|jpeg)$%i";

$id_visita_item_check = "";
$query = "SELECT a.* FROM arpis.visita_item_check a WHERE token = '$token'  ";
$data = array("query" => $query);	
$resultado = $services->sendPostNoToken($url_services.'/visita/object',$data);
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
	$id_visita_item_check = @$result->id_visita_item_check;
}//foreach($result_json as $result)	
}	



//Como el elemento es un arreglos utilizamos foreach para extraer todos los valores
foreach($_FILES[$archivo]['tmp_name'] as $key => $tmp_name){




$fis_arch = $_FILES[$archivo]["name"][$key];
$aleatorio = rand(9999,99999999);

if ($fis_arch!="") {
	preg_match($patronIMG, $fis_arch) == 1 ? $archivoValido ="S": $archivoValido ="N";
	if($archivoValido == "S"){
		$doc_ima = $fis_arch;
		$doc_ima_fisico =  date('Ymd_his') . "_foto$aleatorio$id_visita_item_check." . pathinfo($fis_arch, PATHINFO_EXTENSION);


		move_uploaded_file($_FILES[$archivo]["tmp_name"][$key], "../../../upload/fotos/" . $doc_ima_fisico);



		$tokenArchivo = md5($aleatorio.$id_visita_item_check.date("Y-m-d-h-i-s")  );
		$data = array("idReferencia" => $id_visita_item_check,
					  "componente" => "visita",
					  "archivo" => $doc_ima_fisico,
					  "titulo" => "foto",
					  "token" => $tokenArchivo);	
		$resultado = $services->sendPostNoToken($url_services.'/archivo/insert',$data);
		if($resultado){
			$result_json = json_decode($resultado); 
			foreach($result_json as $result_r) {
				$result = $result_r;
			}//foreach($result_json as $result)	
		}
	
	}
}


}//foreach($_FILES["archivo"]['tmp_name'] as $key => $tmp_name)


?>