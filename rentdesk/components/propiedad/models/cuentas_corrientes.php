<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../app/model/QuerysBuilder.php");

use app\database\QueryBuilder;

header('Content-Type: application/json');
// Crear una instancia de la clase QueryBuilder
$QueryBuilder = new QueryBuilder();

$id = $_GET['id'];
try {
    // Nombre de la función SQL que deseas ejecutar
    $functionName = 'propiedades.fn_saldos_propietario'; // Reemplaza con el nombre de tu función SQL

    // Parámetros de la función SQL
    $params = [
        $id, // Reemplaza con los valores necesarios
        0
    ];

    // Ejecutar la función SQL
    $result = $QueryBuilder->executeFunction($functionName, $params);

    // Retornar el resultado en formato JSON
    echo $result;

} catch (\Throwable $th) {
    // Manejo de errores
    echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
} finally {
    // No es necesario cerrar conexiones manualmente con PDO
}
