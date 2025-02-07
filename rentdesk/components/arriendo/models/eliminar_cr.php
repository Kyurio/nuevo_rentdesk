<?php 

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config = new Config;
$services = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];

// Obtener el ID del cuerpo de la solicitud POST
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

echo "este es el id " . $id;

$queryUpdateServicio = "DELETE FROM propiedades.ficha_arriendo_cta_cte_movimientos WHERE id = $id";
$dataCab = array("consulta" => $queryUpdateServicio);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

echo $queryUpdateServicio;

echo "true";
