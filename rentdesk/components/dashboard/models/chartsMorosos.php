<?php

// Habilitar visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include '../../../app/model/QuerysBuilder.php';
use app\database\QueryBuilder; 

$QueryBuilder = new QueryBuilder();

$sql = "SELECT propiedades.fn_saldos_de_contratos(1)";
$result = $QueryBuilder->executeComplexQuery($sql, [], false);

if ($result) {
    // Decodificar el campo `fn_saldos_de_contratos` y enviarlo como JSON limpio
    $decodedResult = json_decode($result[0]['fn_saldos_de_contratos'], true); // Decodifica el JSON
    echo json_encode($decodedResult); // Reenviar como JSON limpio
} else {
    echo json_encode(['error' => 'No se encontraron datos']);
}