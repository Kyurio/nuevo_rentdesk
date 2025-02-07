<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();

// Obtener datos del POST
$id_liquidacion = $_POST["id_liquidacion"];
$id_date = $_POST["id_date"];

// Validar que se reciba el id_liquidacion
if (empty($id_liquidacion)) {
    echo json_encode(['success' => false, 'message' => 'El campo id_liquidacion es obligatorio.']);
    exit;
}

$fecha = new DateTime();


try {
    $table = 'propiedades.propiedad_liquidaciones';
    $data = [
        'archivo_officebanking' => $id_date, // Datos a actualizar
    ];
    $conditions = [
        'id' => $id_liquidacion, // Condición para la actualización
    ];

    // Llamar a la función update
    $result = $QueryBuilder->update($table, $data, $conditions);

    echo json_encode(['success' => true, 'message' => 'Registro actualizado exitosamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
