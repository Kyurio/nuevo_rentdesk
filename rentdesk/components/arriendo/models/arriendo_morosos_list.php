<?php

session_start();


include '../../../app/model/QuerysBuilder.php';
use app\database\QueryBuilder; 

$QueryBuilder = new QueryBuilder();


$sql = "SELECT propiedades.fn_saldos_de_contratos(1)";

$result = $QueryBuilder->executeComplexQuery($sql, [], false);

echo $result;