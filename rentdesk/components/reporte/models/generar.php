<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$token					= @$_REQUEST['token'];
$tipoExportAux			= @$_REQUEST['tiporeporte'];
if(isset($tipoExportAux)){
$tipoExport_e			= explode("|",$tipoExportAux);
$tipoExport				= $tipoExport_e[1];
}

$empresaAux				= @$_REQUEST['empresa'];
if(isset($empresaAux)){
$empresa_e			= explode("|",$empresaAux);
$empresa				= $empresa_e[0];
$desc_empresa			= $empresa_e[1];
}else{
$empresa				= "0";
$desc_empresa			= "Todas";
}	

$oficinaAux				= @$_REQUEST['sucursal'];
if(isset($oficinaAux)){
$oficina_e			= explode("|",$oficinaAux);
$oficina				= $oficina_e[0];
$desc_oficina			= $oficina_e[1];
}else{
$oficina				= "0";
$desc_oficina			= "Todas";
}	

$periodo				= @$_REQUEST['periodo'];
$cod_propiedad			= @$_REQUEST['cod_propiedad'];
$num_doc_arrendatario 	= @$_REQUEST['num_doc_arrendatario'];
$num_doc_propietario 	= @$_REQUEST['num_doc_propietario'];	


$estado_propiedadAux				= @$_REQUEST['estado_propiedad'];
if(isset($estado_propiedadAux)){
$estado_propiedad_e		= explode("|",$estado_propiedadAux);
$estado_propiedad		= $estado_propiedad_e[0];
$desc_estado_propiedad	= $estado_propiedad_e[1];
}else{
$estado_propiedad				= "0";
$desc_estado_propiedad			= "Todos";
}

$estado_contratoAux				= @$_REQUEST['estado_contrato'];
if(isset($estado_contratoAux)){
$estado_contrato_e		= explode("|",$estado_contratoAux);
$estado_contrato		= $estado_contrato_e[0];
$desc_estado_contrato	= $estado_contrato_e[1];

if($desc_estado_contrato == "Todos"){
$estado_contrato				= "-1";
$desc_estado_contrato			= "Todos";
}	
}else{
$estado_contrato				= "-1";
$desc_estado_contrato			= "Todos";
}

$cod_contrato			= @$_REQUEST['cod_contrato'];

$tipo_visitaAux				= @$_REQUEST['tipo_visita'];
if(isset($tipo_visitaAux)){
$tipo_visita_e		= explode("|",$tipo_visitaAux);
$tipo_visita		= $tipo_visita_e[0];
$desc_tipo_visita	= $tipo_visita_e[1];
}else{
$tipo_visita				= "0";
$desc_tipo_visita			= "Todos";
}

$estado_visitaAux				= @$_REQUEST['estado_visita'];
if(isset($estado_visitaAux)){
$estado_visita_e		= explode("|",$estado_visitaAux);
$estado_visita		= $estado_visita_e[0];
$desc_estado_visita	= $estado_visita_e[1];
}else{
$estado_visita				= "0";
$desc_estado_visita			= "Todos";
}

$resultado_checkoutAux				= @$_REQUEST['resultado_checkout'];
if(isset($resultado_checkoutAux)){
$resultado_checkout_e		= explode("|",$resultado_checkoutAux);
$resultado_checkout		= $resultado_checkout_e[0];
$desc_resultado_checkout	= $resultado_checkout_e[1];
}else{
$resultado_checkout				= "0";
$desc_resultado_checkout			= "Todos";
}


$id_company 	= $_SESSION["rd_company_id"];
$logo 			= $_SESSION["company_logo"];
$nombre_usuario = $_SESSION["usuario_nombre"];
$id_usuario    	= $_SESSION["rd_usuario_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$url_reportes_reg = $config->url_reportes_reg;

$data = array("token" => $token,
			  "tipoExport" => $tipoExport,
			  "empresa" => $empresa,
			  "desc_empresa" => utf8_decode($desc_empresa),
			  "oficina" => $oficina,
			  "desc_oficina" => utf8_decode($desc_oficina),
			  "periodo" => $periodo,
			  "cod_propiedad" => $cod_propiedad,
			  "num_doc_arrendatario" => $num_doc_arrendatario,
			  "num_doc_propietario" => $num_doc_propietario,
			  "estado_propiedad" => $estado_propiedad,
			  "desc_estado_propiedad" => utf8_decode($desc_estado_propiedad),
			  "id_company" => $id_company,
			  "logo" => $logo,
			  "nombre_usuario" => utf8_decode($nombre_usuario),
			  "id_usuario" => $id_usuario,
			  "estado_contrato" => $estado_contrato,
			  "desc_estado_contrato" => utf8_decode($desc_estado_contrato),
			  "cod_contrato" => $cod_contrato,
			  "tipo_visita" => $tipo_visita,
			  "desc_tipo_visita" => $desc_tipo_visita,
			  "estado_visita" => $estado_visita,
			  "desc_estado_visita" => $desc_estado_visita,
			  "resultado_checkout" => $resultado_checkout,
			  "desc_resultado_checkout" => $desc_resultado_checkout);

$httpRequest = curl_init();
//Configuramos la petición HTTP.
curl_setopt($httpRequest, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($httpRequest, CURLOPT_POST, 1);
curl_setopt($httpRequest, CURLOPT_HEADER, 0);
curl_setopt($httpRequest, CURLOPT_URL, $url_reportes_reg);
curl_setopt($httpRequest, CURLOPT_POSTFIELDS, http_build_query($data) );

//Ejecutar la petición.
$result = curl_exec($httpRequest);
curl_close($httpRequest);
	

echo $result;			  



?>