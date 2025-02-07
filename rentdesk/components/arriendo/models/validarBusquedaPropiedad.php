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
$id_propiedad = $_GET['id_propiedad'];

// Obtener el método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// Validar si el método no es DELETE
if ($metodo !== 'GET') {
    // Manejar el error
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido. Solo se permite DELETE.']);
    exit;
}

if (is_numeric($id_propiedad)) {

    $query_count = "SELECT upper(concat(direccion, numero, numero_depto,' piso: ',piso)) AS propiedad from propiedades.propiedad  where id = $id_propiedad  AND id_estado_propiedad = 6";
    $data = array("consulta" => $query_count);
    $resultado = json_decode($services->sendPostNoToken($url_services . '/util/objeto', $data));

    if ($resultado) {
        echo "La propiedad " . $resultado[0]->propiedad . " se encuentra <b class='text-danger'>  RETIRADA </b>";
    }
} else {


    echo "La propiedad no existe ";
}
