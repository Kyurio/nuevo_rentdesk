<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];


$num_reg = 20;
$inicio = 0;

if (isset($_POST["token"])) {
	$token = $_POST["token"];

	$queryIdArriendo = "select fa.id from propiedades.ficha_arriendo fa where fa.token = '$token' ";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $queryIdArriendo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$objIdArriendo = json_decode($resultado)[0];
	/*
	$queryDocumento = " select fa.*,tts.tipo_servicio ,tts.nombre, tp.nombre_fantasia as proveedor from PROPIEDADES.ficha_arriendo_servicios fa, PROPIEDADES.tp_tipo_servicio tts ,propiedades.tp_proveedor tp
	where fa.id_ficha_arriendo = $objIdArriendo->id and fa.habilitado = true  and fa.id_tipo_servicio = tts.id and tts.id = tp.id_servicio and tts.tipo_servicio = 'basico' order by id desc ";
	*/
	$queryDocumento = "SELECT a.id, a.id_tipo_servicio, b.nombre, a.id_proveedor, a.numero_cliente, c.proveedor, a.plan_servicio, a.tipo_moneda, a.monto, a.periodo, a.fecha_inicio, a.fecha_vencimiento, a.fecha_modificacion from propiedades.ficha_arriendo_servicios a 
					inner join propiedades.tp_tipo_servicio b 
					on a.id_tipo_servicio = b.id
					inner join propiedades.tp_proveedor_servicio c
					on a.id_proveedor = c.id_proveedor
					where a.id_ficha_arriendo = $objIdArriendo->id and habilitado = true";
}


//$queryDocumento = " select * from propiedades.propiedad_archivos where estado = true order by id desc";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryDocumento, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objServicios = json_decode($resultado);

echo json_encode($objServicios);
