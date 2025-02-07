<?php

// 1) Mostrar errores para depuración (no usar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2) Incluir la clase QueryBuilder
require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// 3) Capturamos un posible parámetro de búsqueda ?searchTerm=valor
$searchTerm = $_GET['searchTerm'] ?? '';

// 4) Instanciamos el QueryBuilder
$QueryBuilder = new QueryBuilder();

try {
    /**
     * ---------------------------------------------------------------------
     * Definimos la consulta principal (tabla, columnas, JOINs)
     * ---------------------------------------------------------------------
     */
    // Tabla base (FROM), alias 'ps'
    $table = 'propiedades.persona ps';

    // Columnas a seleccionar (iguales al query que nos diste)
    $columns = "
        CONCAT(pnt.nombres, ' ', pnt.apellido_paterno, ' ', pnt.apellido_materno) AS nombre_completo,
        ps.dni AS dni,
        pd.direccion AS direccion,
        ps.correo_electronico AS correo,
        ttp.nombre AS tipo_persona
    ";

    // Definición de JOINs para reflejar el query dado
    // 'type' => '' => INNER JOIN, 'type' => 'LEFT' => LEFT JOIN
    $joins = [
        [
            'type' => 'LEFT',  // LEFT JOIN persona_natural
            'table' => 'propiedades.persona_natural pnt',
            'on'   => 'ps.id = pnt.id_persona'
        ],
        [
            'type' => 'LEFT',  // LEFT JOIN persona_juridica
            'table' => 'propiedades.persona_juridica pj',
            'on'   => 'ps.id = pj.id_persona'
        ],
        [
            'type' => '',  // INNER JOIN con tp_tipo_persona
            'table' => 'propiedades.tp_tipo_persona ttp',
            'on'   => 'ttp.id = ps.id_tipo_persona'
        ],
        [
            'type' => '',  // INNER JOIN para persona_direcciones
            'table' => 'propiedades.persona_direcciones pd',
            'on'   => 'ps.id = pd.id_persona'
        ],
        [
            'type' => '',  // INNER JOIN tp_comuna
            'table' => 'propiedades.tp_comuna tc',
            'on'   => 'tc.id = pd.id_comuna'
        ],
        [
            'type' => '',  // INNER JOIN tp_region
            'table' => 'propiedades.tp_region tr',
            'on'   => 'tc.id_region = tr.id'
        ],
        [
            'type' => '',  // INNER JOIN tp_pais
            'table' => 'propiedades.tp_pais tp',
            'on'   => 'tr.id_pais = tp.id'
        ],
        [
            'type' => '',  // INNER JOIN tp_tipo_dni
            'table' => 'propiedades.tp_tipo_dni ttd',
            'on'   => 'ttd.id = ps.id_tipo_dni'
        ],
        [
            'type' => 'LEFT',  // LEFT JOIN persona_codeudor
            'table' => 'propiedades.persona_codeudor pc',
            'on'   => 'pc.id_persona = ps.id'
        ],
    ];

    // No definimos condiciones extra en este ejemplo
    $conditions = [];

    /**
     * ---------------------------------------------------------------------
     * Ejecutamos la consulta con el QueryBuilder
     * ---------------------------------------------------------------------
     */
    $result = $QueryBuilder->selectAdvanced(
        $table,
        $columns,
        $joins,
        $conditions,
        '',   // group by
        '',   // order by
        null, // limit
        false,
        false  // Debug = false para no imprimir la query
    );

    /**
     * ---------------------------------------------------------------------
     * Filtrado manual en PHP (opcional)
     * ---------------------------------------------------------------------
     * Si deseas filtrar por 'nombre_completo' (o por 'dni', etc.)
     * según el término de búsqueda, lo haces aquí.
     */
    if (!empty($searchTerm)) {
        $searchTermLower = mb_strtolower($searchTerm);

        // Filtramos el arreglo
        $filteredResult = array_filter($result, function ($row) use ($searchTermLower) {
            $nombreCompleto = mb_strtolower($row['nombre_completo'] ?? '');
            $dni = mb_strtolower($row['dni'] ?? '');
            // Retornamos true si 'nombreCompleto' contiene la subcadena de búsqueda
            return (strpos($nombreCompleto, $searchTermLower) !== false
                || strpos($dni, $searchTermLower) !== false);
        });

        // Reindexar para evitar saltos de índice
        $filteredResult = array_values($filteredResult);
    } else {
        $filteredResult = array_values($result);
    }

    /**
     * ---------------------------------------------------------------------
     * Retornamos la respuesta en formato JSON
     * ---------------------------------------------------------------------
     */
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($filteredResult, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // Mostrar error si ocurre alguna excepción
    echo "Error: " . $e->getMessage();
}
