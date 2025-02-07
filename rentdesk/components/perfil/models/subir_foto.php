<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$token		= @$_POST['token'];
$fotoActual	= @$_POST['fotoActual'];
$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];

$ruta_fotos = "../../../upload/foto-perfil/";
$msg ="";

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;



$patronIMG 	= "%\.(jpg|PNG|png|JPG|JPEG|jpeg)$%i";

$fis_arch =  $_FILES["fileUpload"]["name"];
if ($fis_arch!="") {
	preg_match($patronIMG, $fis_arch) == 1 ? $archivoValido ="S": $archivoValido ="N";

	if($archivoValido == "S"){
		$doc_ima = $fis_arch;
		$doc_ima_fisico = md5(date('Ymd_his').rand(99999, 99999999)."_ima")."." . pathinfo($fis_arch, PATHINFO_EXTENSION);
		$foto = $ruta_fotos.$doc_ima_fisico;
		move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $foto);
		
		$data = array("id_usuario" => $id_usuario,
					  "foto" => $doc_ima_fisico);	
		$resultado = $services->sendPostNoToken($url_services.'/usuario/updateFoto',$data);	
		if($resultado){
			$result_json = json_decode($resultado); 
			foreach($result_json as $result_r) {
				$result = $result_r;
			}//foreach($result_json as $result)
		}
		
		if ($result->status == "OK"){
			if($fotoActual!="" && $fotoActual!="no-foto.png"){
				try {
						if( delete_file($ruta_fotos.$fotoActual) === true ){
							$msg = "OK";
						}	
							
				}catch (Exception $e) {
						$msg = $e->getMessage(); 
				}
			}
			echo ",xxx,OK,xxx,Imagen Actualizada exitosamente";
		}else{
			echo ",xxx,ERROR,xxx,Error al ejecutar servicio de actualizacion.";	
		}	
	}else{
		echo ",xxx,ERROR,xxx,El archivo no es valido.";	
	}
}else{
	echo ",xxx,ERROR,xxx,El archivo no es valido.";	
}


?>