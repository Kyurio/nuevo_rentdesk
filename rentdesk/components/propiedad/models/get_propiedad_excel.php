<?php

// 1) Activar la visualización de errores para desarrollo (no en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// 2) Capturamos los 2 parámetros GET
$codigoPropiedad = $_GET['codigo_propiedad'] ?? '';
$propietario     = $_GET['propietario']     ?? '';

// 3) Instanciamos el QueryBuilder
$QueryBuilder = new QueryBuilder();

try {
    /**
     * ---------------------------------------------------------------------
     * 4) Definir la consulta principal
     * ---------------------------------------------------------------------
     */
    // Tabla base (FROM): vis_propiedades con alias "vp"
    $table = 'propiedades.vis_propiedades vp';

    // Ajustamos las columnas (incluimos 'dni' para poder filtrar por él)
    $columns = "
        cs.nombre AS sucursal,
        vp.ejecutivo_encargado AS ejecutivo,
        CONCAT(vpr.nombre_1, ' ', vpr.nombre_2, ' ', vpr.nombre_3) AS propietarios,
        vpr.dni,
        vp.tipo_propiedad,
        CONCAT(vp.direccion, ' ', vp.numero) AS direccion,
        vp.codigo_propiedad,
        vp.comuna,
        vp.region,
        vp.estado_propiedad AS estado,
        vp.asegurado,
        vp.avaluo_fiscal AS precio,
        vp.codigo_propiedad AS codigo_propiedad_vp
    ";

    // Definimos los JOINs necesarios
    $joins = [
        [
            'type'  => 'LEFT',
            'table' => 'propiedades.cuenta_sucursal cs',
            'on'    => 'vp.token_sucursal = cs.token'
        ],
        [
            'type'  => 'LEFT',
            'table' => 'propiedades.propiedad p',
            'on'    => 'vp.id_propiedad = p.id'
        ],
        [
            'type'  => 'LEFT',
            'table' => 'propiedades.propiedad_copropietarios pcop',
            'on'    => 'p.id = pcop.id_propiedad'
        ],
        [
            'type'  => 'LEFT',
            'table' => 'propiedades.vis_propietarios vpr',
            'on'    => 'pcop.id_propietario = vpr.id'
        ],
    ];

    // Sin condiciones SQL en el QueryBuilder (array vacío)
    $conditions = [];

    // 5) Ejecutar la consulta
    $result = $QueryBuilder->selectAdvanced(
        $table,
        $columns,
        $joins,
        $conditions,
        '',
        '',
        null,
        false,
        false
    );

    /**
     * ---------------------------------------------------------------------
     * 6) Filtrado manual en PHP con array_filter
     * ---------------------------------------------------------------------
     *   - $codigoPropiedad: buscar coincidencia en 'direccion' OR 'codigo_propiedad_vp'
     *   - $propietario: buscar coincidencia en 'propietarios' OR 'dni'
     */
    $codigoPropLower  = mb_strtolower($codigoPropiedad);
    $propietarioLower = mb_strtolower($propietario);

    $filteredResult = array_filter($result, function ($row) use ($codigoPropLower, $propietarioLower) {
        // Convertimos a minúsculas para comparar de forma case-insensitive
        $direccionLower = mb_strtolower($row['direccion'] ?? '');
        $codigoVP       = mb_strtolower($row['codigo_propiedad'] ?? '');
        $propietarios   = mb_strtolower($row['propietarios'] ?? '');
        $dni            = mb_strtolower($row['dni'] ?? '');

        // 1) Filtro por $codigoPropiedad -> busca en 'direccion' OR 'codigo_propiedad_vp'
        if (!empty($codigoPropLower)) {
            if (
                // Si no encuentra la subcadena en 'direccion' 
                // Y tampoco en 'codigo_propiedad_vp'
                (strpos($direccionLower, $codigoPropLower) === false) &&
                (strpos($codigoVP, $codigoPropLower) === false)
            ) {
                return false;
            }
        }

        // 2) Filtro por $propietario -> busca en 'propietarios' OR 'dni'
        if (!empty($propietarioLower)) {
            if (
                // Si no encuentra la subcadena en 'propietarios' 
                // Y tampoco en 'dni'
                (strpos($propietarios, $propietarioLower) === false) &&
                (strpos($dni, $propietarioLower) === false)
            ) {
                return false;
            }
        }

        return true;
    });

    // Reindexar los resultados
    $filteredResult = array_values($filteredResult);

    // 7) Retornar en formato JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($filteredResult, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
