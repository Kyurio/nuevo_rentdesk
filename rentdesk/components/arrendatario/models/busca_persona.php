<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$num_docu		= $_POST['num_docu'];
$tipo_docu		= @$_POST['tipo_docu'];
$id_company = $_SESSION["rd_company_id"];
$id_tipo_persona = 1;
$existe = 0;

if($num_docu!="")

$result = null;
$data = array("num_docu" => $num_docu,"tipo_docu" => $tipo_docu,"idEmpresa" => $id_company,"idTipoPersona" => $id_tipo_persona);							
$resultado = $services->sendPostNoToken($url_services.'/persona/ajax',$data);
if($resultado){
$result_json = json_decode($resultado); 
foreach($result_json as $result_r) {
	$result = $result_r;
	$existe = 1;
}//foreach($result_json as $result)
}

if($existe > 0 ){
echo "xxx,OKxxx,xxx,$result->token";
}else{
echo "xxx,ERRORxxx,xxx, ";
}

?>