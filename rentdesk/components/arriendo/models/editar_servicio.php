<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_usuario = $_SESSION["rd_usuario_id"];
$arrendatarios = "";
$fecha = date("Y-m-d");


$TipoEditarServicio = $_POST["TipoEditarServicio"];
$TipoProveedorEditar = $_POST["TipoProveedorEditar"];
$montoEditar = $_POST["montoEditar"];
$PlanEditar = $_POST["PlanEditar"];
$monedaEditar = $_POST["monedaEditar"];
$periocidadEditar = $_POST["periocidadEditar"];
$servicioFechaInicioEditar = $_POST["servicioFechaInicioEditar"];
$servicioFechaVencimientoEditar = $_POST["servicioFechaVencimientoEditar"];
$tokenRegistro = $_POST["ServicioTokenEditar"];
$numeroClienteEditar =  $_POST["numeroClienteEditar"];


if (strpos($montoEditar, '.')){
	$montoEditar= str_replace(".", "", $montoEditar);
}else if (strpos($montoEditar, ',')){
	$montoEditar= str_replace(",", ".", $montoEditar);
}



/*=================================================================*/
/*PROCESAMIENTO DE FORMULARIO
/*=================================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$query = "UPDATE propiedades.propiedad
	SET direccion = 'TEST 2 MODIFICADO DESDE PORTAL'
	WHERE token = '18bdc972e1358d7563c1f88e0013006f'";

	$queryParams = array(
		'token_subsidiaria' => $current_subsidiaria->token,

	);

	$data = array(
		"consulta" => $query

	);
	$resultado = $services->sendPut($url_services . '/rentdesk/utils/actualizar', $data, [], $queryParams);
	$json = json_decode($resultado);


	// Accessing form fields
	

	
	$num_reg = 10;
     $inicio = 0;
	 
	 
	$query = "SELECT id FROM propiedades.cuenta_usuario cu where token = '$id_usuario' ";
    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    $objUsuarioId = json_decode($resultado)[0];




	$queryCabecera= " UPDATE propiedades.ficha_arriendo_servicios
					SET   id_tipo_servicio=$TipoEditarServicio, monto=$montoEditar, periodo='$periocidadEditar', id_proveedor=$TipoProveedorEditar, tipo_moneda='$monedaEditar', 
					plan_servicio='$PlanEditar', fecha_inicio='$servicioFechaInicioEditar', fecha_vencimiento='$servicioFechaVencimientoEditar' , id_usuario_modificacion=$objUsuarioId->id, 
					fecha_modificacion='$fecha', numero_cliente = '$numeroClienteEditar'
					WHERE token = '$tokenRegistro' ";


					
	
		var_dump($queryCabecera);
              $dataCab = array("consulta" => $queryCabecera);
              $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);	
    //var_dump($resultadoCab);
	/*---------------------------- */


	
	
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro registrar documento,xxx,-,xxx,";
		return;
	}else{
		echo ",xxx,OK,xxx,Se registro documento,xxx,-,xxx,";
	}
	

	
	//$services->sendPost($url_services . '/rentdesk/arriendos', $data, [], null);
}
