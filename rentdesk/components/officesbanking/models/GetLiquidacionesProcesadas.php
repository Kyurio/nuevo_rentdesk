<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// Configuración de conexión BD
$QueryBuilder = new QueryBuilder();

try {
    // Especificar los parámetros de la consulta
    $table = 'propiedades.propiedad_documentos_cierre';
    $columns = 'id, tipo, fecha_registro, archivo_officebanking';

    // Calcular la fecha límite en PHP
    $fechaLimite = date('Y-m-d', strtotime('-60 days'));

    // Condiciones para la consulta
    $conditions = [
        'tipo' => 'officebanking', // Filtrar estado
    ];

    // Ejecutar la consulta avanzada
    $result = $QueryBuilder->selectAdvanced(
        $table,
        $columns,
        [],
        $conditions,
        '',
        '',
        null,
        false
    );

    // Filtrar manualmente los resultados para archivo_officebanking IS NOT NULL y fecha_liquidacion >= :fechaLimite
    $filteredResult = array_filter($result, function ($row) use ($fechaLimite) {
        return !is_null($row['archivo_officebanking']) && $row['fecha_registro'] >= $fechaLimite;
    });

    // Establecer el tipo de contenido a JSON
    header('Content-Type: application/json');

    // Devolver los resultados como JSON
    echo json_encode(array_values($filteredResult)); // Convertir el array a un JSON válido

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
