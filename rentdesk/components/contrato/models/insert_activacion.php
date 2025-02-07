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

$id_company 	= $_SESSION["rd_company_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

if($token!=""){

$data = array("token" => $token);							
$resultado = $services->sendPostNoToken($url_services.'/contratoCab/activa',$data);		
//var_dump($resultado);
$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";

}//if($token=="" && $nombre!="")



?>