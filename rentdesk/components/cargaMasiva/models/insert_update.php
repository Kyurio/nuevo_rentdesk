<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$token	= @$_POST['token'];

$id_company 	= $_SESSION["rd_company_id"];
$id_usuario    	= $_SESSION["rd_usuario_id"];


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token_proceso= md5(rand(99999, 99999999).$token.date("Y m d H s"));
$idEstadoProcesoCarga = 1;
$idEstadoArchivoInvalido = 11;
$idEstadoArchivoCargado = 2;
$doc_ima_fisico = "";
$nombre_archivo_ori = "";

$data = array("idUsuario" => $id_usuario,
			  "archivo" => $doc_ima_fisico,
			  "tokenCargaMasiva" => $token,
			  "idEmpresa" => $id_company,
			  "idEstadoProcesoCarga" => $idEstadoProcesoCarga,
			  "tokenProceso" => $token_proceso,
			  "nombreArchivoOri" => $nombreArchivoOri);			
		  
$resultado = $services->sendPostNoToken($url_services.'/archivo/gestionaCargaMasiva',$data);		
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result = $result_r;
	}//foreach($result_json as $result)
}

if ($result->status === 'ERROR'){
	echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
}else{
	$patronIMG 	= "%\.(txt|TXT|csv|CSV)$%i";

	$fis_arch = $_FILES["archivo"]["name"];
	$aleatorio = rand(9999,99999999);
	$select_prefijo = "";
	if ($fis_arch!="") {
		preg_match($patronIMG, $fis_arch) == 1 ? $archivoValido ="S": $archivoValido ="N";
		if($archivoValido == "S"){
			$query = "	select *
					from arpis.cm_carga_masiva 
					where token = '$token'
					";			
			$data = array("consulta" => $query);	
			$resultado = $services->sendPostNoToken($url_services.'/util/objeto',$data);			
				
			if($resultado){
				$result_json3 = json_decode($resultado); 
				foreach($result_json3 as $result_r3) {
					$result3			= $result_r3;
					$select_prefijo = $result3->cod_etl;
				 
				}	
			}
			
			
			$nombre_archivo_ori = $fis_arch;
			$doc_ima_fisico =  $select_prefijo."_". date('Ymd_his') . "_cm$aleatorio$token_proceso." . pathinfo($fis_arch, PATHINFO_EXTENSION);
			move_uploaded_file($_FILES["archivo"]["tmp_name"], "../../../upload/cargaMasiva/" . $doc_ima_fisico);
			
			$data = array("idUsuario" => $id_usuario,
						  "archivo" => $doc_ima_fisico,
						  "tokenCargaMasiva" => $token,
						  "idEmpresa" => $id_company,
						  "idEstadoProcesoCarga" => $idEstadoArchivoCargado,
						  "tokenProceso" => $token_proceso,
						  "nombreArchivoOri" => $nombre_archivo_ori);
			// //var_dump($data);				
			$resultado = $services->sendPostNoToken($url_services.'/archivo/gestionaCargaMasiva',$data);		
			if($resultado){
				$result_json = json_decode($resultado); 
				foreach($result_json as $result_r) {
					$result = $result_r;
					echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
				}//foreach($result_json as $result)
			}
						
		}else{
			$data = array("idUsuario" => $id_usuario,
						  "archivo" => $doc_ima_fisico,
						  "tokenCargaMasiva" => $token,
						  "idEmpresa" => $id_company,
						  "idEstadoProcesoCarga" => $idEstadoArchivoInvalido,
						  "tokenProceso" => $token_proceso,
						  "nombreArchivoOri" => $nombreArchivoOri);		
			// //var_dump($data);			  
			$resultado = $services->sendPostNoToken($url_services.'/archivo/gestionaCargaMasiva',$data);		
			if($resultado){
				echo ",xxx,ERROR,xxx,El Archivo es Invalido,xxx,$token,xxx,";
			}
		}	
	}

}	


?>