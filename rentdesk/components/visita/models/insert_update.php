<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$tipo					= $_POST['id_tipo'];
$fecha					= $_POST['fecha'];
$direccion				= $_POST['direccion'];
$administradora			= $_POST['administradora'];
$correosolicitante		= $_POST['correosolicitante'];
$correoarrendatario		= $_POST['correoarrendatario'];
$arrendatariorecibe		= $_POST['arrendatariorecibe'];
$rut					= $_POST['rut'];
$correo					= $_POST['correo'];
$observaciones			= $_POST['observaciones'];
$token					= $_POST['token'];
$token_propiedad		= $_POST['token_propiedad'];
$estado_visita 			= $_POST["estado_visita"]; 
$inspector   			= $_POST["inspector"]; 


$id_company 			= $_SESSION["rd_company_id"]; 


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$patronIMG 	= "%\.(jpg|PNG|png|JPG|JPEG|jpeg|pdf|PDF|doc|DOC|docx|DOCX)$%i";

$fecha = fecha_normal_a_postgre($fecha);
$hora = date("H:i");
$aleatorio = rand(99999,99999999);
$hoy = date("Y-m-d");

$id_propiedad   = 0;

if ($token_propiedad != ""){
$data0 = array("token" => $token_propiedad,
			  "idEmpresa" => $id_company);				  
$resultado0 = $services->sendPostNoToken($url_services.'/propiedad/token',$data0);
if($resultado0){
	$result_json0 = json_decode($resultado0); 
	foreach($result_json0 as $result_r0) {
		$result0 = $result_r0;
		$id_propiedad = $result0->id_propiedad;
	}//foreach($result_json as $result)
}
}





if($token ==""){

/*Validacion para no poder crear nuevas visitas si no estan finalizadas las anteriores*/
$query = "	SELECT COUNT(*) cantidad
			FROM arpis.visita v,
				 arpis.estado_visita ev
			WHERE v.id_propiedad = $id_propiedad
			AND ev.id_estado_visita = v.id_estado_visita 
			AND ev.estado_final = 'N'
			";			
$data = array("consulta" => $query);	
$resultado = $services->sendPostNoToken($url_services.'/util/objeto',$data);
//echo   $resultado;
$cantidad = "0";
if($resultado){
	$result_json3 = json_decode($resultado); 
	foreach($result_json3 as $result_r3) {
		$result3			= $result_r3;
		$cantidad			= $result_r3->cantidad;
	}	
}

if ($cantidad > 0){
	echo ",xxxERROR,xxxDebe Finalizar las visitas anteriores para poder generar una nueva visita";
	die;
}	

$token_nuevo = md5( $aleatorio . $tipo. $fecha . $direccion . $rut . $correo );

$data = array("id_propiedad" => $id_propiedad,
			  "tipo" => $tipo,
			  "fecha" => $fecha,
			  "hora" => $hora,
			  "direccion" => $direccion,
			  "administradora" => $administradora,
			  "correo_solicitante" => $correosolicitante,
			  "correo_arrendatario" => $correoarrendatario,
			  "arrendatario_recibe" => $arrendatariorecibe,
			  "rut" => $rut,
			  "correo" => $correo,
			  "observaciones" => $observaciones,
			  "token" => $token_nuevo,
			  "id_empresa" => $id_company,
			  "inspector" => $inspector);	
$resultado = $services->sendPostNoToken($url_services.'/visita/insertVisita',$data);
if($resultado){
	$result_json = json_decode($resultado); 

	foreach($result_json as $result_r) {
		$result = $result_r;
	}//foreach($result_json as $result)
	
	if ($result->status == "OK"){
		$id_visita = $result->id_visita;
	}else{
		$id_visita = 0;
	}		
}

$fis_arch = $_FILES["imagen"]["name"];
if ($fis_arch!="") {
	preg_match($patronIMG, $fis_arch) == 1 ? $archivoValido ="S": $archivoValido ="N";
	if($archivoValido == "S"){
		$doc_ima = $fis_arch;
		$doc_ima_fisico =  "rut".date('Ymd_his') . "_ima$aleatorio." . pathinfo($fis_arch, PATHINFO_EXTENSION);


		move_uploaded_file($_FILES["imagen"]["tmp_name"], "../../../upload/rut/" . $doc_ima_fisico);
			

		$tokenArchivo = md5($doc_ima_fisico . $aleatorio);

		$data = array("idReferencia" => $id_visita,
					  "componente" => "visita",
					  "archivo" => $doc_ima_fisico,
					  "titulo" => "rut",
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

echo ",xxx$token_nuevo,xxx";


}else{
	
$query = "UPDATE arpis.visita SET tipo = '$tipo', fecha='$fecha', direccion='$direccion', administradora='$administradora', correo_solicitante='$correosolicitante', correo_arrendatario='$correoarrendatario', arrendatario_recibe='$arrendatariorecibe', rut='$rut', correo='$correo', observaciones='$observaciones', id_estado_visita='$estado_visita', inspector = '$inspector' WHERE token ='$token' ";

//var_dump($query);
$data = array("update" => base64_encode($query));

$resultado = $services->sendPostNoToken($url_services.'/visita/updateVisita',$data);
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result = $result_r;
	}//foreach($result_json as $result)
}	

$fis_arch = $_FILES["imagen"]["name"];
if ($fis_arch!="") {
	preg_match($patronIMG, $fis_arch) == 1 ? $archivoValido ="S": $archivoValido ="N";
	if($archivoValido == "S"){
		$doc_ima = $fis_arch;
		$doc_ima_fisico =  "rut".date('Ymd_his') . "_ima$aleatorio." . pathinfo($fis_arch, PATHINFO_EXTENSION);


	move_uploaded_file($_FILES["imagen"]["tmp_name"], "../../../upload/rut/" . $doc_ima_fisico);
	}

$id_visita = 0;
$data = array("token" => $token,
			  "idEmpresa" => $id_company);				  
$resultado = $services->sendPostNoToken($url_services.'/visita/token',$data);
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result = $result_r;
	}//foreach($result_json as $result)
	$id_visita = $result->id_visita;
}

$imagen_anterior = "";
$query = "SELECT a.* FROM arpis.archivo a  WHERE id_referencia = '$id_visita' AND componente ='visita' AND titulo='rut' ";
$data = array("query" => $query);	
$resultado = $services->sendPostNoToken($url_services.'/archivo/archivo',$data);

if($resultado){
	$result = json_decode($resultado); 
	$imagen_anterior = @$result->archivo;
	@unlink("../../../upload/rut/".$imagen_anterior);
}

$tokenArchivo = md5($doc_ima_fisico . $aleatorio);
$data = array("idReferencia" => $id_visita,
			  "componente" => "visita",
			  "archivo" => $doc_ima_fisico,
			  "titulo" => "rut",
			  "token" => $tokenArchivo);	
$resultado = $services->sendPostNoToken($url_services.'/archivo/insert',$data);
if($resultado){
	$result_json = json_decode($resultado); 
	foreach($result_json as $result_r) {
		$result = $result_r;
	}//foreach($result_json as $result)	
}


}

echo ",xxx$token,xxx";

}//if($token =="")



 

?>