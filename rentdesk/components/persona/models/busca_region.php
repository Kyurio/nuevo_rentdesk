<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$num_reg = 50;
$inicio = 0;


if(isset($_POST["idPais"])) {
$idPais = $_POST["idPais"];
$queryUbicacion ="SELECT  * from propiedades.tp_region tr where  id_pais =".$idPais." order by id ";
}
if(isset($_POST["idRegion"])) {
$idRegion = $_POST["idRegion"];
$queryUbicacion ="SELECT  * from propiedades.tp_comuna tc  where  id_region =".$idRegion." order by bn";

}
if(isset($_POST["idComuna"])) {
$idComuna = $_POST["idComuna"];
$queryUbicacion ="";
}
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryUbicacion, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCheques = json_decode($resultado);

echo json_encode($objCheques);