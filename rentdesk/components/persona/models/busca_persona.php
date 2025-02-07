 <?php

	// Notificar solamente errores de ejecución
	error_reporting(E_ERROR | E_WARNING | E_PARSE);


	session_start();
	include("../../../includes/sql_inyection.php");
	include("../../../configuration.php");
	include("../../../includes/conexionMysql.php");
	include("../../../includes/funciones.php");
	include("../../../includes/resize.php");
	include("../../../includes/services_util.php");


	$rut		= $_POST['num_docu'];

	$config		= new Config;
	$services   = new ServicesRestful;
	$url_services = $config->url_services;


	$num_reg = 50;
	$inicio = 0;


	$query = "SELECT dni, token FROM propiedades.persona WHERE dni = '$rut'";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$request = json_decode($resultado);

	if($request) {
		
		echo "|false|".$request[0]->token."|";

	}else{

		echo "|true|";
	
	}

