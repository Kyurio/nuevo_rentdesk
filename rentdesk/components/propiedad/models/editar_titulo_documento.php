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
$carpeta = "upload\arriendo";


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
	
	$documentoTitulo = @$_POST['TituloEditar'];
	$tokenRegistro = @$_POST['TokenEditar'];
	
	$num_reg = 10;
     $inicio = 0;
	 
	 
	$query = "SELECT id FROM propiedades.cuenta_usuario cu where token = '$id_usuario' ";
    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    $objUsuarioId = json_decode($resultado)[0];
	
	
	

	$queryCabecera= " UPDATE propiedades.propiedad_archivos
                    SET   titulo='$documentoTitulo', id_usuario_ultima_modificacion=$objUsuarioId->id, fecha_ultima_actualizacion='$fecha'
					WHERE token_agrupador = '$tokenRegistro' ";
		


					
	
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
