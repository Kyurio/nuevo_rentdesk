<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();

// Obtener datos del POST
$filePath = $_POST['filePath'];
$fileName = $_POST['fileName'];
$tipo = $_POST["tipo"];
$id_officebanking = $_POST["id_officebanking"];

// var_dump($filePath, $fileName, $cierre);
// die();

try {
    $table = 'propiedades.propiedad_documentos_cierre';
    $data = [
        'nombre_archivo' => $fileName,
        'ruta' => $filePath,
        'fecha_registro' => date('Y-m-d H:i:s'),
        'tipo' => $tipo,
        'archivo_officebanking' => $id_officebanking
    ];

    $result = $QueryBuilder->insert($table, $data);
    echo json_encode(['success' => true, 'message' => 'Registro insertado exitosamente.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
