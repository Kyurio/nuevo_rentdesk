<?php

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();

// Recuperar token desde POST
$token = $_POST['token'] ?? null;


// Validación básica (opcional)
if (empty($token)) {
    echo json_encode(['error' => 'Token no proporcionado']);
    exit;
}

// Construimos la expresión de columnas que deseas obtener
$columns = "
    UPPER(
        CONCAT(
            COALESCE(CONCAT(pro.direccion, ' ', pro.numero), ''),
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
    ) AS direccion
";

// Configuramos el JOIN
$joins = [
    [
        'type' => 'INNER',
        'table' => 'propiedades.propiedad pro',
        'on'   => 'fa.id_propiedad = pro.id'
    ]
];

// Creamos la condición WHERE por token
$conditions = [
    'fa.token' => $token
];

// Llamamos a la función selectAdvanced
$result = $QueryBuilder->selectAdvanced(
    'propiedades.ficha_arriendo fa',  // Tabla con alias
    $columns,                         // Columnas a seleccionar
    $joins,                           // Joins
    $conditions,                      // Condiciones (WHERE)
    '',                               // GROUP BY
    '',                               // ORDER BY
    null,                             // LIMIT
    false,                            // isCount
    false                             // debug
);

// Respuesta
if (!empty($result)) {
    echo json_encode($result);
} else {
    echo json_encode([]);
}
