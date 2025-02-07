<?php

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
include("../../../app/model/QuerysBuilder.php");

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
    echo json_encode(['error' => 'Método no permitido. Solo se permite DELETE.']);
    exit;
}

/*Consulta Cantidad de registros*/
$query_count = "SELECT pl.cierre             as cierre
                    , to_char(pl.fecha_liquidacion ::DATE, 'DD/MM/YYYY') as fecha_liquidacion
                    , count(*)     as Cantidad
                from propiedades.propiedad_liquidaciones pl
                where estado = 1
                group by pl.cierre, to_char(pl.fecha_liquidacion ::DATE, 'DD/MM/YYYY')";

$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);


if($resultado > 0){
    echo $resultado;
}else {
    echo 0;
}
