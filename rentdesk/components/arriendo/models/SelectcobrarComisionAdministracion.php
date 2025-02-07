<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../app/model/QuerysBuilder.php");
include("../../../configuration.php");

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();
$config = new Config();
$token = $_POST['token'];


try {

    $table = 'propiedades.ficha_arriendo';
    $columns = 'id, adm_comision_cobro, arriendo_comision_cobro';
    $joins = []; // No necesitas JOINs para esta consulta
    $conditions = [
        'token' => $token
    ];
    $groupBy = ''; // No necesitas agrupar
    $orderBy = ''; // No necesitas ordenar
    $limit = null; // Sin límite
    $isCount = false; // No estás contando resultados
    $debug = false; // Activar modo debug para ver la consulta generada

    $result = $QueryBuilder->selectAdvanced($table, $columns, $joins, $conditions, $groupBy, $orderBy, $limit, $isCount, $debug);

    echo json_encode($result);

} catch (\Throwable $th) {

    echo $th->getMessage();
}
