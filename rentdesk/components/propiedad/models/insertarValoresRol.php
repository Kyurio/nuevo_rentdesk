<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services  = new ServicesRestful;
$url_services = $config->url_services;

$id_propiedad = $_POST["id_propiedad"] ?? null;
$valorRolAño = $_POST["valorRolAño"] ?? null;
$ValorRol = $_POST["ValorRol"] ?? null;
$mes = $_POST["mes"] ?? null;
$id_propiedades_roles = $_POST["id_propiedades_roles"] ?? null; // Manejo del valor id_propiedades_roles

// Asegurarse de que los valores estén capturados correctamente
if (isset($valorRolAño) && isset($ValorRol) && isset($mes) && isset($id_propiedades_roles)) {
    // Escapar los valores para evitar inyecciones SQL
    $queryCtaContable = "INSERT INTO propiedades.valores_roles(año, valor, cuota, id_propiedad, id_propiedades_roles) VALUES ('$valorRolAño', $ValorRol, '$mes', $id_propiedad, $id_propiedades_roles)";
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