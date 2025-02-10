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

// Llamamos a la función selectAdvanced
$result = $QueryBuilder->selectAdvanced(
    'propiedades.propiedad pro',  // Tabla con alias
    $columns,                     // Columnas a seleccionar
    [],                           // Joins (no necesitamos en este caso)
    ['token' => $token],      // Condición WHERE (por defecto usa '=')
    '',                           // GROUP BY
    '',                           // ORDER BY
    null,                         // LIMIT
    false,                        // isCount
    false                         // debug
);

// $result debería retornar un array con los datos obtenidos
if (!empty($result)) {
    // Si quieres solo la primera fila, puedes hacer:
    // echo json_encode($result[0]);
    // Si quieres todas las filas, tal cual:
    echo json_encode($result);
} else {
    echo json_encode([]);
}
