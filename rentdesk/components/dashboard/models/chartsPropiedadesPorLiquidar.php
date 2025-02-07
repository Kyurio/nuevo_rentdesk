<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir la clase QueryBuilder
require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// Crear una instancia del QueryBuilder
$queryBuilder = new QueryBuilder();

try {



    // Nombre de la función SQL
    $functionName = 'propiedades.fn_propiedades_por_liquidar2';

    // Parámetros de la función
    $params = [0]; // En este caso, solo un parámetro con valor 0

    // Llamada a la función SQL
    $result = $queryBuilder->executeFunction($functionName, $params);
    echo $result;


} catch (Exception $e) {
    // Manejo de errores
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
