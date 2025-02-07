<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$token	= @$_GET["token"];


$data = array("token" => $token,"idEmpresa" => $id_company);							
$resultado = $services->sendPostNoToken($url_services.'/tipoProducto/token',$data);		

$result = json_decode($resultado); 

$opcion_activo="<option selected value='S'>Si</option>";

if(@$result->activo=="N"){
	$opcion_activo=$opcion_activo."<option selected value='N'>No</option>";
}else{
	$opcion_activo=$opcion_activo."<option value='N'>No</option>";
}

//************************************************************************************************************
$opcion_seleccionable="<option selected value='S'>Si</option>";
if(@$result->seleccionable=="N"){
	$opcion_seleccionable=$opcion_seleccionable."<option selected value='N'>No</option>";
}else{
	$opcion_seleccionable=$opcion_seleccionable."<option value='N'>No</option>";
}

//************************************************************************************************************

$opcion_tipo_responsable = "<option value='0'>Sin predefinir</option>";
$data_tipo_responsable = array("idEmpresa" => $id_company);							
$resp_tipo_responsable = $services->sendPostNoToken($url_services.'/tipoResposable/listaByEmpresa',$data_tipo_responsable);	
$tipo_responsables = json_decode($resp_tipo_responsable);

foreach($tipo_responsables as $tipo_responsable_r) {

$select_tipo_responsable = "";
if(@$result->idTipoResponsablePredefinido == @$tipo_responsable_r->idTipoResponsable)
$select_tipo_responsable = " selected ";


$opcion_tipo_responsable = $opcion_tipo_responsable . "<option value='$tipo_responsable_r->idTipoResponsable' $select_tipo_responsable >$tipo_responsable_r->descripcion</option>";
}//foreach($roles as $rol)

$opcion_tipo_responsable = "<select id='tipo_responsable' name='tipo_responsable' class='form-control' >
$opcion_tipo_responsable
</select>";

$reservado = "N";
if(@$result->reservado =="S"){
$reservado = "S";
}	

?>