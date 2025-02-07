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

$token_empresa			= explode("|",$token_empresa);
$token_empresa			= $token_empresa[0];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$sucursales = "";

if( $token_empresa=="" ){

$sucursales = "<select id='sucursal' name='sucursal'  class='form-control' disabled  >
<option value='0|Todas'>Todas</option>
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
	<option value='$sucursal->idSucursal|$sucursal->nombreFantasia' $selected >$sucursal->nombreFantasia</option>";
}


$sucursales = "<select id='sucursal' name='sucursal'   class='form-control' >
<option value='0|Todas'>Todas</option>
".$sucursales."
</select>";


}//if( $token_empresa=="" )






echo ",xxx,$sucursales,xxx,";



//*************************************************************************************************



?>