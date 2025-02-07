<?php

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token    = @$_GET["token"];


// Obtener el método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// Validar si el método no es DELETE
if ($metodo !== 'GET') {
    // Manejar el error
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido. Solo se permite GET.']);
    exit;
}

// tipo movimeintos cargos arrendatarios
$query_count = "SELECT * from propiedades.tp_tipo_movimiento_cta_cte where id_responsable in (2, 3) AND id_tipo_movimiento = 1 AND visible = true ";

$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);



echo $resultado;
