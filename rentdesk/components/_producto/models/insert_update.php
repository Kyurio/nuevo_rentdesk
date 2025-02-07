<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$descripcion_prod	= @$_POST['descripcionProd'];
$id_tipo_producto	= @$_POST['tipo_producto'];
$id_tipo_moneda		= @$_POST['id_tipo_moneda'];
$valor				= @$_POST['valor'];
$id_tipo_responsable= @$_POST['id_tipo_responsable'];
$renovable			= @$_POST['renovable'];
$dias_gracia_monto_mayor = @$_POST['diasGraciaMontoMayor'];
$id_tipo_monto		= @$_POST['id_tipo_monto'];
$activo     		= @$_POST['activo'];
$token				= @$_POST['token'];
$editable			= @$_POST['editable'];
$seleccionable		= @$_POST['seleccionable'];
$montoMayor			= @$_POST['id_monto_mayor'];
$prod_monto_mayor	= @$_POST['id_prod_monto_mayor'];
$texto_eecc			= @$_POST['texto_eecc'];
$minValor			= @$_POST['min_valor'];
$reajustable		= @$_POST['reajustable'];
$pagaIva    		= @$_POST['pagaIva'];
$proporcionalMes    = @$_POST['proporcionalMes'];

$valor	 = desformatea_number($valor,$_SESSION["separador_mil"]);
$minValor	 = desformatea_number($minValor,$_SESSION["separador_mil"]);

$id_company 	= $_SESSION["rd_company_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token_nuevo= md5(rand(99999, 99999999).$descripcion_prod.date("Y m d H s"));

if($token=="" && $descripcion_prod!=""){

$data = array("activo" => $activo,
			  "descripcionProd" => $descripcion_prod,
			  "diasGraciaMontoMayor" => $dias_gracia_monto_mayor,
			  "renovable" => $renovable,
			  "valor" => $valor,
			  "idTipoMoneda" => $id_tipo_moneda,
			  "idTipoProducto" => $id_tipo_producto,
			  "idTipoResponsable" => $id_tipo_responsable,
			  "idTipoMonto" => $id_tipo_monto,
			  "token" => $token_nuevo,
			  "idEmpresa" => $id_company,
			  "seleccionable" => $seleccionable,
			  "editable" => $editable,
			  "idMontoMayor" => $prod_monto_mayor,
			  "montoMayor" => $montoMayor,
			  "textoEecc"=> $texto_eecc,
			  "minValor"=> $minValor,
			  "reajustable" => $reajustable,
			  "pagaIva" => $pagaIva,
			  "proporcionalMes" => $proporcionalMes);							
$resultado = $services->sendPostNoToken($url_services.'/producto',$data);		

$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token_nuevo,xxx,";

}//if($token=="" && $nombre!="")

//***********************************************************************************************************

if($token!="" && $descripcion_prod!=""){
	
$data = array("activo" => $activo,
			  "descripcionProd" => $descripcion_prod,
			  "diasGraciaMontoMayor" => $dias_gracia_monto_mayor,
			  "renovable" => $renovable,
			  "valor" => $valor,
			  "idTipoMoneda" => $id_tipo_moneda,
			  "idTipoProducto" => $id_tipo_producto,
			  "idTipoResponsable" => $id_tipo_responsable,
			  "idTipoMonto" => $id_tipo_monto,
			  "token" => $token,
			  "idEmpresa" => $id_company,
			  "idEmpresa" => $id_company,
			  "seleccionable" => $seleccionable,
			  "editable" => $editable,
			  "idMontoMayor" => $prod_monto_mayor,
			  "montoMayor" => $montoMayor,
			  "textoEecc"=> $texto_eecc,
			  "minValor"=> $minValor,
			  "reajustable" => $reajustable,
			  "pagaIva" => $pagaIva,
			  "proporcionalMes" => $proporcionalMes);					  
$resultado = $services->sendPut($url_services.'/producto',$data);		

$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
	
	
}//if($token!="" && $nombre!=""){


?>