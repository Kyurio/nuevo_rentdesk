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

// Asegurarse de que los valores estén capturados correctamente
if (isset($id)) {
    // Escapar los valores para evitar inyecciones SQL

    $queryCtaContable = "DELETE FROM propiedades.valores_roles WHERE id = $id";
    $dataCab = array("consulta" => $queryCtaContable);
    $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

    if ($resultadoCab != "OK") {
        echo "false";
        return;
    }

    echo "true";
} else {
    echo "Faltan valores en el POST.";
}

