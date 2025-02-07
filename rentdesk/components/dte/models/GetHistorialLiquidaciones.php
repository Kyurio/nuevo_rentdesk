<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);


require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// Configuración conexión bd
$QueryBuilder = new QueryBuilder();

try {

    $table = "propiedades.propiedad_comision_liquidacion pc";
    $columns = "
        UPPER(CONCAT(
            COALESCE(CONCAT(p.direccion, ' ', p.numero), ''),
            CONCAT(
                CASE 
                    WHEN p.numero_depto IS NOT NULL AND p.numero_depto <> '' THEN CONCAT(' Dpto ', p.numero_depto) 
                    ELSE '' 
                END,
                CASE 
                    WHEN p.piso IS NOT NULL AND p.piso <> 0 THEN CONCAT(' Piso ', p.piso) 
                    ELSE '' 
                END
            )
        )) AS direccion,
        pl.id_ficha_arriendo,
        pl.id_ficha_propiedad,
        pc.id_liquidacion,
        pc.tipo_comision,
        pc.folio,
        pc.tipo_documento,
        CASE 
            WHEN pc.tipo_documento = 39 THEN 'Boleta'
            WHEN pc.tipo_documento = 33 THEN 'Factura'
            WHEN pc.tipo_documento = 34 THEN 'Nota de Crédito'
            ELSE 'Otro'
        END AS tipo_documento_texto,
        pl.fecha_liquidacion
    ";

    $joins = [
        [
            'type' => 'INNER',
            'table' => 'propiedades.propiedad_liquidaciones pl',
            'on' => 'pc.id_liquidacion = pl.id'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.propiedad p',
            'on' => 'pl.id_ficha_propiedad = p.id'
        ]
    ];

    $conditions = [
        'pc.folio' => ['IS NOT NULL', null]
    ];
    

    $orderBy = "pc.id DESC";

    $result = $QueryBuilder->selectAdvanced(
        $table,
        $columns,
        $joins,
        $conditions,
        '',
        $orderBy,
        null,
        false,
        false
    );


    echo json_encode($result);
} catch (Exception $e) {
    // Enviar el mensaje de error en formato JSON
    echo json_encode(['error' => $e->getMessage()]);
}
