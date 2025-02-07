<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$token_empresa			= $_POST['empresa'];
$valor_sucursal			= $_POST['sucursal'];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$sucursales = "";

if( $token_empresa=="" ){

$sucursales = "<select id='sucursal' name='sucursal'  required data-validation-required  class='form-control' disabled  >
<option value=''>Antes debe seleccionar la Empresa</option>
</selecct>";

}else{

//*************************************************************************************************

$data = array("idEmpresa" => $token_empresa);	
$sucursales = $services->sendPostNoToken($url_services.'/sucursal/listaByEmpresa',$data);	
$json_sucursales= json_decode($sucursales);

$sucursales="";
foreach($json_sucursales as $sucursal){
	$selected = " ";
	if($valor_sucursal=="$sucursal->idSucursal")
		$selected = " selected ";
	$sucursales = $sucursales . "
	<option value='$sucursal->idSucursal' $selected >$sucursal->nombreFantasia</option>";
}


$sucursales = "<select id='sucursal' name='sucursal'  required data-validation-required  class='form-control' >
<option value=''>Selecciona la Sucursal</option>
".$sucursales."
</select>";


}//if( $token_empresa=="" )






echo ",xxx,$sucursales,xxx,";



//*************************************************************************************************



?>