<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// Configuración conexión bd
$QueryBuilder = new QueryBuilder();

try {

    $table = 'propiedades.propiedad_liquidaciones AS pl';

    $columns = "
            pl.id AS id_liquidacion,
            pl.id, 
            pl.id_ficha_propiedad, 
            pl.fecha_liquidacion, 
            fa.adm_comision_id_tipo_documento AS documento_comision,
            fa.arriendo_comision_id_tipo_documento AS documento_arriendo,
            (
                SELECT COALESCE(monto, 0) 
                FROM propiedades.propiedad_comision_liquidacion 
                WHERE tipo_comision IN ('COMISIÓN CORRETAJE') AND
                propiedades.propiedad_comision_liquidacion.id_liquidacion = pl.id
                ORDER BY id DESC 
                LIMIT 1
            ) AS comision_arriendo,
            (
                SELECT COALESCE(monto, 0) 
                FROM propiedades.propiedad_comision_liquidacion 
                WHERE tipo_comision IN ('COMISIÓN ADMINISTRACIÓN') AND
                propiedades.propiedad_comision_liquidacion.id_liquidacion = pl.id
                ORDER BY id DESC 
                LIMIT 1
            ) AS comision_administracion,
            UPPER(CONCAT(p.direccion, ' #', p.numero)) AS direccion
    ";


    $joins = [
        [
            'type' => 'INNER',
            'table' => 'propiedades.ficha_arriendo AS fa',
            'on' => 'fa.id = pl.id_ficha_arriendo'
        ],
        [
            'type' => 'INNER',
            'table' => 'propiedades.propiedad AS p',
            'on' => 'fa.id_propiedad = p.id'
        ]
    ];

    $conditions = [
        'estado' => 0
    ];

    $groupBy = '';
    $orderBy = '';
    $limit = null;
    $isCount = false;
    $debug = false;

    // Llamar a la función
    $resultado = $QueryBuilder->selectAdvanced($table, $columns, $joins, $conditions, $groupBy, $orderBy, $limit, $isCount, $debug);

    echo json_encode($resultado);
} catch (Exception $e) {
    // Enviar el mensaje de error en formato JSON
    echo json_encode(['error' => $e->getMessage()]);
}