<?php

// Mostrar errores (solo para desarrollo, no en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// Instanciamos el QueryBuilder
$QueryBuilder = new QueryBuilder();

try {
    // Definir la consulta con las columnas solicitadas
    $table = 'propiedades.cuenta_usuario';
    $columns = "
        id,
        id_empresa,
        dni,
        id_tipo_dni,
        CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo,
        correo,
        token,
        habilitado,
        activo,
        autorizador
    ";

    // Definir los JOINs necesarios (si fueran necesarios, de lo contrario, dejar vacío)
    $joins = [];

    // Definir condiciones
    $conditions = [
        'habilitado' => ['=', true]
    ];

    // Ejecutar la consulta
    $result = $QueryBuilder->selectAdvanced(
        $table,
        $columns,
        $joins,
        $conditions,
        '',   // orderBy
        '',   // groupBy
        null, // limit
        false,
        false
    );

    // Validación adicional para asegurar que es un array
    $filteredResult = is_array($result) ? $result : [$result];

    // Filtrado de datos (opcional si se quiere filtrar en PHP)
    $filteredResult = array_filter($filteredResult, function ($row) {
        // Garantizar valores válidos y sin espacios extra
        return isset($row['id']) && !empty(trim($row['nombre_completo']));
    });

    // Reindexar el array
    $filteredResult = array_values($filteredResult);

    // Limpiar cualquier salida previa
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($filteredResult, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
} catch (Exception $e) {
    // Manejo de errores
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    exit;
}
