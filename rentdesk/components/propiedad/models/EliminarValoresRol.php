<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services  = new ServicesRestful;
$url_services = $config->url_services;

$id = $_POST["id"];

// Preparar la respuesta en formato JSON
$response = array("success" => false, "message" => "Error desconocido.");

if (isset($id)) {
    // Escapar los valores para evitar inyecciones SQL
    $id = intval($id); // Asegúrate de que el ID sea un entero

    $queryCtaContable = "DELETE FROM propiedades.valores_roles WHERE id = $id";
    $dataCab = array("consulta" => $queryCtaContable);
    $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

    if ($resultadoCab == "OK") {
        $response["success"] = true;
        $response["message"] = "Registro eliminado exitosamente.";
    } else {
        $response["message"] = "Error al eliminar el registro.";
    }
} else {
    $response["message"] = "Faltan valores en el POST.";
}

// Enviar la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);