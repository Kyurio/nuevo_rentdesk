<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];


$num_reg = 50;
$inicio = 0;

if (isset($_POST["idFicha"])) {
    $idFicha = $_POST["idFicha"];
    $queryCcMovimientos = "SELECT propiedades.fn_saldos_arrendatario($idFicha, 0)";
}





$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCcMovimientos, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCcMovimientos = json_decode($resultado);

echo json_encode($objCcMovimientos);
