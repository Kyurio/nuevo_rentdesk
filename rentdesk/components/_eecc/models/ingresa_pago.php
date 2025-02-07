<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$medio_pago		= @$_POST['medio_pago'];
$monto_pagado	= @$_POST['monto_pagado'];
$monto_cheque	= @$_POST['monto_cheque'];	
$cod_autorizacion = @$_POST['cod_autorizacion'];
$token			= @$_POST['token'];
$token_contrato	= @$_POST['token_contrato'];

$id_company 	= $_SESSION["rd_company_id"];
$id_usuario 	= $_SESSION["rd_usuario_id"];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$monto_pagado	 = desformatea_number($monto_pagado,$_SESSION["separador_mil"]);
$monto_cheque	 = desformatea_number($monto_cheque,$_SESSION["separador_mil"]);

if($token_contrato!="" && ($monto_pagado!="" ||$monto_cheque!="")){
	//var_dump($medio_pago);
	
if($medio_pago != "4"){
$data = array("tokenContrato" => $token_contrato,
			  "montoPago" => $monto_pagado,
			  "idMedioPago" => $medio_pago,
			  "codAutorizacion" => $cod_autorizacion);	
//var_dump($data);			  
$resultado = $services->sendPostNoToken($url_services.'/eecc/ingresaPago',$data);		
}else{
 
$cantidad_cheques	= @$_POST['cantidadCheques'];	
$cheques = array();
for ($x = 1; $x <= $cantidad_cheques; $x++){
    $cheque = new stdClass();
	$cheque->banco = @$_POST['banco'.$x];
	$cheque->serie = @$_POST['serie'.$x];
	$cheque->fecha = fecha_normal_a_postgre(@$_POST['fecha'.$x]);
	$cheque->monto = desformatea_number(@$_POST['monto'.$x],$_SESSION["separador_mil"]);
	
	if($cheque->monto != null){
		array_push($cheques, $cheque);
	}	
}//for
$json_cheques = json_encode($cheques);

$data = array("tokenContrato" => $token_contrato,
			  "montoPago" => $monto_cheque,
			  "idMedioPago" => $medio_pago,
			  "cheques" => $json_cheques,
			  "idUsuario" => $id_usuario);	
//var_dump($data);				  
$resultado = $services->sendPostNoToken($url_services.'/eecc/ingresaPagoCheque',$data);
	
}	

$result = json_decode($resultado); 

echo ",xxx,$result->status,xxx,$result->mensaje,xxx,,xxx,";

}{//if($token=="" && $nombre!="")

echo ",xxx,ERROR,xxx,Datos Invalidos,xxx,,xxx,";
}
//***********************************************************************************************************


?>