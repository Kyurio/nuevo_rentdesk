<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$token_arrendatario	= @$_POST['token_arrendatario'];
$token_propiedad	= @$_POST['token_propiedad'];
$token_usuario		= @$_POST['token_usuario'];
$ref_contrato		= @$_POST['ref_contrato'];
$estado_contrato    = @$_POST['estado_contrato'];
$fecha_contrato     = @$_POST['fecha_contrato'];
$reajuste    		= @$_POST['reajuste'];
$mes_reajuste    	= @$_POST['mes_reajuste'];
$dia_vencimiento    = @$_POST['dia_vencimiento'];
$monto_garantia    	= @$_POST['monto_garantia'];
$tipo_moneda    	= @$_POST['tipo_moneda'];
$token				= @$_POST['token'];
$fecha_termino_contrato     = @$_POST['fecha_termino_contrato'];

$id_company 	= $_SESSION["rd_company_id"];
$archivoContrato = "";

$monto_garantia	 = desformatea_number($monto_garantia,$_SESSION["separador_mil"]);

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token_nuevo= md5(rand(99999, 99999999).$token_arrendatario.$token_propiedad.date("Y m d H s"));
if($token==""){
$estado_contrato = 0;
$data = array("token_arrendatario" => $token_arrendatario,
			  "token_propiedad" => $token_propiedad,
			  "token_usuario" => $token_usuario,
			  "ref_contrato" => $ref_contrato,
			  "estado_contrato" => $estado_contrato,
			  "fecha_contrato" => fecha_normal_a_postgre($fecha_contrato),
			  "reajuste" => $reajuste,
			  "mes_reajuste" => $mes_reajuste,
			  "dia_vencimiento" => $dia_vencimiento,
			  "monto_garantia" => $monto_garantia,
			  "tipo_moneda" => $tipo_moneda,
			  "token" => $token_nuevo,
			  "id_empresa" => $id_company,
			  "archivoContrato" => $archivoContrato,
			  "fecha_termino_contrato" => fecha_normal_a_postgre($fecha_termino_contrato));							
$resultado = $services->sendPostNoToken($url_services.'/contratoCab/new',$data);		

$result = json_decode($resultado); 


}//if($token=="" && $nombre!="")

//***********************************************************************************************************


//******************************************************************************************

if($token==""){
	$token = $token_nuevo;
}//if($token=="")


$id_archivo 		= "";
$archivo_anterior 	= "";
	
$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/contratoCab/token',$data);	

if($resultado){

$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
	$id_contrato 		= @$result->id_contrato;
	$archivo_anterior 	= @$result->archivo_contrato;
}//foreach($result_json as $result)
}


$patronIMG 	= "%\.(jpg|PNG|png|JPG|JPEG|jpeg|doc|DOC|docx|DOCX|pdf|PDF|xls|XLS|xlsx|XLSX)$%i";

$fis_arch = $_FILES["archivo"]["name"];
$aleatorio = rand(9999,99999999);
$doc_ima_fisico = $archivo_anterior;
if ($fis_arch!="") {
	preg_match($patronIMG, $fis_arch) == 1 ? $archivoValido ="S": $archivoValido ="N";
	if($archivoValido == "S"){
		$doc_ima = $fis_arch;
		$doc_ima_fisico =  date('Ymd_his') . "_contrato$aleatorio$id_contrato." . pathinfo($fis_arch, PATHINFO_EXTENSION);


	move_uploaded_file($_FILES["archivo"]["tmp_name"], "../../../upload/contrato/" . $doc_ima_fisico);
		try {
				if( delete_file("../../../upload/contrato/".$archivo_anterior) === true ){
					$msg = "OK";
				}	
		}catch (Exception $e) {
				$msg = $e->getMessage(); 
		}
	}
}

if($token!=""){

$archivoContrato = @$doc_ima_fisico;	
$data = array("token_arrendatario" => $token_arrendatario,
			  "token_propiedad" => $token_propiedad,
			  "token_usuario" => $token_usuario,
			  "ref_contrato" => $ref_contrato,
			  "estado_contrato" => $estado_contrato,
			  "fecha_contrato" => fecha_normal_a_postgre($fecha_contrato),
			  "reajuste" => $reajuste,
			  "mes_reajuste" => $mes_reajuste,
			  "dia_vencimiento" => $dia_vencimiento,
			  "monto_garantia" => $monto_garantia,
			  "tipo_moneda" => $tipo_moneda,
			  "token" => $token,
			  "id_empresa" => $id_company,
			  "archivoContrato" => $archivoContrato,
			  "fecha_termino_contrato" => fecha_normal_a_postgre($fecha_termino_contrato));	
$resultado = $services->sendPut($url_services.'/contratoCab/update',$data);		

$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
	
	
}//if($token!="" && $nombre!="")
	


?>