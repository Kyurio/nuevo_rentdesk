<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$patronIMG 	= "%\.(jpg|PNG|png|JPG|JPEG|jpeg|doc|DOC|docx|DOCX|pdf|PDF|)$%i";

// captura de arhivo
$fis_arch = $_FILES["archivo"]["name"];
// nombre alaetorio
$aleatorio = rand(9999,99999999);
// nombre del archivo
$doc_ima_fisico = 'CargoArenta';

if ($fis_arch!="") {
	preg_match($patronIMG, $fis_arch) == 1 ? $archivoValido ="S": $archivoValido ="N";
	if($archivoValido == "S"){
		$doc_ima = $fis_arch;
		$doc_ima_fisico =  date('Ymd_his') . "_cargoarenta." . pathinfo($fis_arch, PATHINFO_EXTENSION);


	move_uploaded_file($_FILES["archivo"]["tmp_name"], "../../../upload/arriendo/" . $doc_ima_fisico);
		try {
				if( delete_file("../../../upload/arriendo/".$mandato_anterior) === true ){
					$msg = "OK";
				}	
		}catch (Exception $e) {
				$msg = $e->getMessage(); 
		}
	}
}


echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
	
	
?>