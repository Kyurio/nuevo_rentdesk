<?php
require "../../../app/model/QuerysBuilder.php";
use app\database\QueryBuilder;
$QueryBuilder = new QueryBuilder();
$id_propiedad=$_POST["id_propiedad"];
// Parámetros de configuración para selectAdvanced
$table = 'propiedades.fn_propiedades_para_contribuciones()'; // Tabla o función que actúa como tabla
$columns = 'rol, cuota, fecha, estado, tipo_rol, direccion, fecha_pago, ano_contrib, descripcion, valor_cuota, monto_pagado, idpropiedad, mes_contrib'; // Columnas a seleccionar
$conditions = [
    'id_propiedad' => $id_propiedad // Condición para filtrar por id_propiedad
];
$joins = []; // Sin JOINs en esta consulta
$groupBy = ''; // Sin agrupación
$orderBy = ''; // Sin orden específico
$limit = null; // Sin límite
$isCount = false; // No se está contando
$debug = false; // Cambiar a true para depuración

// Ejecutar la consulta usando selectAdvanced
$result = $QueryBuilder->selectAdvanced(
    $table,
    $columns,
    $joins,
    $conditions,
    $groupBy,
    $orderBy,
    $limit,
    $isCount,
    $debug
);
echo $result;