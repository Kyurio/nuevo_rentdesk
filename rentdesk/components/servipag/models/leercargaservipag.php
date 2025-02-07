<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../../../app/model/QuerysBuilder.php");
include("../../../configuration.php");

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();
$config = new Config();

$table = 'propiedades.ficha_arriendo fa';

$columns = "
    se.id,
    se.rut_cliente AS rut,
    fa.id_propiedad AS ficha_propiedad,
    fa.id AS id_arriendo,
    fa.token,
    UPPER(
        CONCAT(
            COALESCE(CONCAT(pro.direccion, ' ', pro.numero), ''),
            CONCAT(
                CASE 
                    WHEN pro.numero_depto IS NOT NULL AND pro.numero_depto <> '' 
                    THEN CONCAT(' Dpto ', pro.numero_depto) 
                    ELSE '' 
                END, 
                CASE 
                    WHEN pro.piso IS NOT NULL AND pro.piso <> 0 
                    THEN CONCAT(' Piso ', pro.piso) 
                    ELSE '' 
                END
            )
        )
    ) AS direccion,
    fa.precio AS arriendo,
    (SELECT ROUND(valor, 0) FROM propiedades.indicadores WHERE fecha = se.fecha_pago) AS indicador_valor,
    CASE fa.id_moneda_precio
        WHEN 3 THEN ROUND((fa.precio * (SELECT ROUND(valor, 0) FROM propiedades.indicadores WHERE fecha = se.fecha_pago)), 0)
        ELSE fa.precio
    END AS valor_arriendo,
    se.monto AS monto_pagado,
    CASE fa.id_moneda_precio
        WHEN 3 THEN (se.monto - ROUND((fa.precio * (SELECT ROUND(valor, 0) FROM propiedades.indicadores WHERE fecha = se.fecha_pago)), 0))
        ELSE (se.monto - fa.precio)
    END AS diferencia,
    fa.id_moneda_precio AS tipo_moneda,
    se.fecha_pago,
    ec.estado_contrato AS estado
";

$joins = [
    [
        'type' => 'INNER',
        'table' => 'propiedades.servipag se',
        'on' => '(se.id_documento = fa.id OR se.id_documento = fa.contrato_ctrlp OR se.id_documento = fa.contrato_adm)'
    ],
    [
        'type' => 'INNER',
        'table' => 'propiedades.propiedad pro',
        'on' => 'pro.id = fa.id_propiedad'
    ],
    [
        'type' => 'INNER',
        'table' => 'propiedades.tp_estado_contrato ec',
        'on' => 'ec.idestado_contrato = fa.id_estado_contrato'
    ]
];

$conditions = [
    'id_estado_contrato' => ['=', 1],
    'procesado' => ['=', 'false']
];

$orderBy = 'se.rut_cliente';

$data = $QueryBuilder->selectAdvanced(
    $table,
    $columns,
    $joins,
    $conditions,
    '',
    $orderBy
);



// Retornar los datos como JSON
header('Content-Type: application/json');
echo json_encode($data);
