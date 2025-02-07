<?php

session_start();

include("../../../app/model/QuerysBuilder.php");

use app\database\QueryBuilder;

// Instanciar QueryBuilder
$QueryBuilder = new QueryBuilder();

// Obtener parámetros de forma segura
$id_ficha = filter_input(INPUT_GET, 'ficha_tecnica', FILTER_SANITIZE_NUMBER_INT);
$id_ejecutivo = 64; // ID del ejecutivo especificado

// Configuración del query
$table = 'propiedades.propiedad_recordatorios AS pr';
$columns = 'pr.id, pr.id_propiedad, pr.fecha_notificacion, pr.repeticiones, pr.descripcion, pr.id_ejecutivo, pr.frecuencia_recordatorio, pr.tipo_recordatorio, cu.nombres || \' \' || cu.apellido_paterno || \' \' || cu.apellido_materno AS ejecutivo';
$joins = [
    [
        'type' => 'INNER',
        'table' => 'propiedades.cuenta_usuario AS cu',
        'on' => 'pr.id_ejecutivo = cu.id'
    ]
];
$conditions = [
    'id_propiedad' => ['=', $id_ficha], // Usar el nombre del campo sin alias
    //'id_ejecutivo' => ['=', $id_ejecutivo]
];

// Ejecutar la consulta con selectAdvanced
$resultado = $QueryBuilder->selectAdvanced($table, $columns, $joins, $conditions);

// Preparar respuesta
header('Content-Type: application/json');
if ($resultado) {
    echo json_encode([
        'success' => true,
        'data' => $resultado // DataTables espera esta clave para la lista de datos
    ]);
} else {
    echo json_encode([
        'success' => false,
        'data' => [], // Vacío para evitar errores
        'message' => 'No hay datos disponibles.'
    ]);
}
exit;
