<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config = new Config;
$services = new ServicesRestful;
$url_services = $config->url_services;

// Values received from AJAX
$numero = $_POST['numero'] ?? '';
$principal = $_POST['principal'] ?? '';
$mes_contrib = $_POST['mes_contrib'] ?? '';
$monto_pagado = $_POST['monto_pagado'] ?? '';
$fecha_pago = $_POST['fecha_pago'] ?? '';
$ano_contrib = $_POST['ano_contrib'] ?? '';
$idvaloresroles = $_POST['idvaloresroles'] ?? ''; // Correct variable name for ID
$valor_cuota = $_POST['valor_cuota'] ?? ''; // Ensure it matches the input name

// Validate and sanitize inputs
$mes_contrib = htmlspecialchars($mes_contrib ?? '', ENT_QUOTES);
$monto_pagado = htmlspecialchars($monto_pagado ?? '', ENT_QUOTES);
$fecha_pago = htmlspecialchars($fecha_pago ?? '', ENT_QUOTES);
$ano_contrib = htmlspecialchars($ano_contrib ?? '', ENT_QUOTES);
$idvaloresroles = htmlspecialchars($idvaloresroles ?? '', ENT_QUOTES);
$valor_cuota = htmlspecialchars($valor_cuota ?? '', ENT_QUOTES);

// Update valores_roles table
$queryValoresRoles = "
    UPDATE propiedades.valores_roles 
    SET 
        cuota = '$mes_contrib', 
        valor = '$monto_pagado', 
        año = '$ano_contrib'
    WHERE id = '$idvaloresroles'
";

// Data for the first update
$dataCab = array("consulta" => $queryValoresRoles);
$resultadoValoresRoles = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

// Check the result of the first query
if ($resultadoValoresRoles) {
    // Update valor_contrib in propiedad_roles table
    $queryPropiedadRoles = "
        UPDATE propiedades.propiedad_roles 
        SET valor_contrib = '$valor_cuota'
        WHERE id_propiedad = (SELECT id_propiedad FROM propiedades.valores_roles WHERE id = '$idvaloresroles')
    ";

    // Data for the second update
    $dataPropiedadRoles = array("consulta" => $queryPropiedadRoles);
    $resultadoPropiedadRoles = $services->sendPostDirecto($url_services . '/util/dml', $dataPropiedadRoles);

    // Check the result of the second query
    if ($resultadoPropiedadRoles) {
        echo json_encode(array("status" => "success", "message" => "Registro actualizado correctamente."));
    } else {
        echo json_encode(array("status" => "error", "message" => "Error al actualizar el valor de contribución."));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Error al actualizar el registro en valores_roles."));
}

?>
