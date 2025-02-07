<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$num_reg = 50;
$inicio = 0;

$id_propiedad = $_POST["fichaLiq"];
$queryLiq="select * from propiedades.propiedad_liquidaciones where id_ficha_propiedad = ".$id_propiedad." order by id desc" ;
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryLiq, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objLiq = json_decode($resultado);

echo json_encode($objLiq);