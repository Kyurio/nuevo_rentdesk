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
$insertSeguro = 0;
$insertServicio = 0;



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
	//Campos Seguros
	$TipoServicioSeguro = @$_POST['TipoServicioSeguro'];
	$TipoProveedorSeguro = @$_POST['TipoProveedorSeguro'];
	$montoServicioSeguro = @$_POST['montoServicioSeguro'];
	$PlanSeguro = @$_POST['PlanSeguro'];
	$monedaServicioSeguro = @$_POST['monedaServicioSeguro'];
	$periocidadServicioSeguro = @$_POST['periocidadServicioSeguro'];
	$servicioSeguroFechaVencimiento = @$_POST['servicioSeguroFechaVencimiento'];
	$servicioSeguroFechaInicio = @$_POST['servicioSeguroFechaInicio'];

	$servicioSeguroNotificacion = @$_POST['servicioSeguroNotificacion'];


	//Campos Servicios
	$TipoServicio = @$_POST['TipoServicio'];
	$TipoProveedorServicio = @$_POST['TipoProveedorServicio'];
	$montoServicio = @$_POST['montoServicio'];
	$PlanServicio = @$_POST['PlanServicio'];
	$monedaServicio = @$_POST['monedaServicio'];
	$periocidadServicio = @$_POST['periocidadServicio'];
	$servicioFechaInicio = @$_POST['servicioFechaInicio'];
	$servicioFechaVencimiento = @$_POST['servicioFechaVencimiento'];
	$numeroCliente =  @$_POST["numeroCliente"];



	if (strpos($montoServicio, '.')) {
		$montoServicio = str_replace(".", "", $montoServicio);
	} else if (strpos($montoServicio, ',')) {
		$montoServicio = str_replace(",", ".", $montoServicio);
	}



	if (strpos($montoServicioSeguro, '.')) {
		$montoServicioSeguro = str_replace(".", "", $montoServicioSeguro);
	} else if (strpos($montoServicioSeguro, ',')) {
		$montoServicioSeguro = str_replace(",", ".", $montoServicioSeguro);
	}

	if (!$fecha_inicio) {

		$fecha_inicio = date('Y-d-m');
	}



	//$montoServicio= str_replace(".", "", $montoServicio);

	$tokenFichaArriendo = @$_POST['token_arrendatario'];

	$num_reg = 10;
	$inicio = 0;


	$query = "SELECT id FROM propiedades.cuenta_usuario cu where token = '$id_usuario' ";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$objUsuarioId = json_decode($resultado)[0];

	$query = "SELECT id FROM propiedades.ficha_arriendo fa  where token = '$tokenFichaArriendo' ";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	var_dump($query);
	$objArriendoId = json_decode($resultado)[0];




	if ($TipoServicioSeguro != null && $TipoServicioSeguro != "") {
		$queryCabecera = " INSERT INTO propiedades.ficha_arriendo_servicios
                    (id_usuario, id_ficha_arriendo, id_tipo_servicio, monto, periodo, id_proveedor,tipo_moneda, fecha_inicio,fecha_vencimiento, habilitado,plan_servicio,notificacion_seguro)
                     VALUES ($objUsuarioId->id, $objArriendoId->id,$TipoServicioSeguro ,$montoServicioSeguro, '$periocidadServicioSeguro' , $TipoProveedorSeguro, '$monedaServicioSeguro', '$servicioSeguroFechaInicio','$servicioSeguroFechaVencimiento',true,'$PlanSeguro','$servicioSeguroNotificacion') ";
		var_dump($queryCabecera);
		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		//var_dump($resultadoCab);
		if ($resultadoCab != "OK") {
			echo ",xxx,ERROR,xxx,No se logro crear el seguro ,xxx,-,xxx,";
			return;
		}
		//var_dump($resultadoCab);

	}



	// Asignar valores por defecto si los campos no están definidos o están vacíos
	$montoServicio = isset($monto) ? $montoServicio : 0;
	$periocidadServicio = isset($periocidadServicio) && $periocidadServicio != "" ? $periocidadServicio : 'anual';
	$monedaServicio = isset($monedaServicio) && $monedaServicio != "" ? $monedaServicio : 'Pesos';
	$servicioFechaInicio = isset($servicioFechaInicio) && $servicioFechaInicio != "" ? $servicioFechaInicio : date('Y-m-d');
	$servicioFechaVencimiento = isset($servicioFechaVencimiento) && $servicioFechaVencimiento != "" ? $servicioFechaVencimiento :  date('Y-m-d');;
	$PlanServicio = isset($PlanServicio) && $PlanServicio != "" ? $PlanServicio : "No Aplica";
	$numeroCliente = isset($numeroCliente) && $numeroCliente != "" ? $numeroCliente : "NULL";


	$queryCabecera = " INSERT INTO propiedades.ficha_arriendo_servicios
                    (id_usuario, id_ficha_arriendo, id_tipo_servicio, monto, periodo, id_proveedor,tipo_moneda, fecha_inicio,fecha_vencimiento, habilitado,plan_servicio,numero_cliente)
                     VALUES ($objUsuarioId->id, $objArriendoId->id, $TipoServicio, $montoServicio, '$periocidadServicio', $TipoProveedorServicio, '$monedaServicio', '$servicioFechaInicio', '$servicioFechaVencimiento', true, '$PlanServicio', $numeroCliente)";
	var_dump($queryCabecera);

	$dataCab = array("consulta" => $queryCabecera);
	$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

	if ($resultadoCab != "OK") {
		echo ",xxx,ERROR,xxx,No se logro crear el servicio ,xxx,-,xxx,";
		return;
	}

	//var_dump($resultadoCab);



	//Si llega a este punto se inserto de manera de correcta
	echo ",xxx,OK,xxx,Se registro Servicio/Seguro de manera correcta,xxx,-,xxx,";




	//$services->sendPost($url_services . '/rentdesk/arriendos', $data, [], null);
}
