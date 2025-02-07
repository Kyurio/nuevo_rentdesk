<?php

// Habilitar visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include '../../../app/model/QuerysBuilder.php';
use app\database\QueryBuilder; 

$QueryBuilder = new QueryBuilder();

$sql = "SELECT propiedades.fn_saldos_de_contratos(0)";
$result = $QueryBuilder->executeComplexQuery($sql, [], false);

// Verifica si hay datos y envíalos como JSON
if ($result) {
   echo json_encode($result);
} else {
    echo ['error' => 'No se encontraron datos'];
}
