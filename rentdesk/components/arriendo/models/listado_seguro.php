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

if(isset($_POST["token"])) {
    $token = $_POST["token"];
	
	$queryIdArriendo ="select fa.id from propiedades.ficha_arriendo fa where fa.token = '$token' ";
	$cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $queryIdArriendo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    $objIdArriendo = json_decode($resultado)[0];
	/*
	$queryDocumento = " select fa.*,tts.tipo_servicio ,tts.nombre, tp.nombre_fantasia as proveedor from PROPIEDADES.ficha_arriendo_servicios fa, PROPIEDADES.tp_tipo_servicio tts ,propiedades.tp_proveedor tp
	where fa.id_ficha_arriendo = $objIdArriendo->id and fa.habilitado = true  and fa.id_tipo_servicio = tts.id and tts.id = tp.id_servicio and tts.tipo_servicio = 'seguro' order by id desc ";
	*/
	$queryDocumento = " select fa.*,tts.tipo_servicio ,tts.nombre, tp.nombre_fantasia as proveedor ,cu.nombres||' '||cu.apellido_paterno AS nombre_usuario
						from PROPIEDADES.ficha_arriendo_servicios fa
						LEFT JOIN propiedades.cuenta_usuario cu ON fa.id_usuario_modificacion  = cu.id
						LEFT JOIN PROPIEDADES.tp_tipo_servicio tts ON fa.id_tipo_servicio = tts.id 
						LEFT JOIN propiedades.tp_proveedor tp ON tts.id = tp.id_servicio  
						where fa.id_ficha_arriendo = $objIdArriendo->id and fa.habilitado = true  
						and tts.tipo_servicio = 'seguro' order by id desc";

} 

//$queryDocumento = " select * from propiedades.propiedad_archivos where estado = true order by id desc";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryDocumento, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objServicios = json_decode($resultado);

echo json_encode($objServicios);


?>