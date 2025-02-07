<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();

// Obtener datos del POST
$archivo_officebanking = $_POST["archivo_officebanking"];
$tipo = $_POST["tipo"];

try {
    $table = 'propiedades.propiedad_documentos_cierre';
    $data = [
        'archivo_officebanking' => $archivo_officebanking,
        'tipo' => $tipo
    ];

    $result = $QueryBuilder->select($table, $data);

    echo json_encode(['success' => true, 'data' => $result]); // Muestra los resultados obtenidos
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
