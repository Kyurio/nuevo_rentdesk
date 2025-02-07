<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$token	= @$_GET["token"];


$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/tipoMoneda/token',$data);		

$result = json_decode($resultado); 

$opcion_activo="<option selected value='S'>Si</option>";

if(@$result->activo=="N"){
	$opcion_activo=$opcion_activo."<option selected value='N'>No</option>";
}else{
	$opcion_activo=$opcion_activo."<option value='N'>No</option>";
}

$data = array("token" => $token);	
$paises = $services->sendGetNoToken($url_services.'/pais/lista',$data);	
$json_paises = json_decode($paises);

$opcion_pais="<option value=''>Seleccione</option>";
foreach($json_paises as $pais){
	if(@$result->pai->descripcion==$pais->descripcion){
		$opcion_pais=$opcion_pais."<option selected value='$pais->idPais'>$pais->descripcion</option>";
	}else{
		$opcion_pais=$opcion_pais."<option value='$pais->idPais'>$pais->descripcion</option>";
	}
}

?>