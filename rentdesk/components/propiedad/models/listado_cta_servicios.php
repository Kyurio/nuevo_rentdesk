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
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);


$num_reg = 50;
$inicio = 0;

if(isset($_POST["idFicha"])) {
    $idFicha = $_POST["idFicha"];
    $queryCuentasServicio = "SELECT pcs.id, pcs.id_ficha_propiedad,pcs.monto_adeudado, pcs.fecha, tts.nombre as nombre_servicio from propiedades.propiedad_cta_servicios pcs
	inner join propiedades.tp_tipo_servicio tts on tts.id = pcs.id_tipo_servicio
    where pcs.id_ficha_propiedad = $idFicha
    AND pcs.habilitado = true
    order by pcs.fecha desc";

    // var_dump("QUERY COPROP: ", $queryCuentasServicio);
} 



$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCuentasServicio, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCuentasServicio = json_decode($resultado);

echo json_encode($objCuentasServicio);


?>