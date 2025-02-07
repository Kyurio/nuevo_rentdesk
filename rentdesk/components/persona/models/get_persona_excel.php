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

    // Columnas a seleccionar
    // Usamos CONCAT para formar "nombre_completo" a partir de nombres y apellidos (persona_natural).
    // Si quisieras incluir también persona_juridica, ajustar la lógica de CONCAT o usar CASE.
    $columns = "
        CONCAT(pnt.nombres, ' ', pnt.apellido_paterno, ' ', pnt.apellido_materno) AS nombre_completo,
        ps.dni AS dni,
        ps.correo_electronico AS correo,
        ttp.nombre AS tipo_persona,
        pd.direccion AS direccion
    ";

    // Definición de JOINs
    // 'type' => '' indica INNER JOIN, 'type' => 'LEFT' indica LEFT JOIN
    $joins = [
        [
            'type' => 'LEFT', // persona_natural (puede que la persona sea jurídica)
            'table' => 'propiedades.persona_natural pnt',
            'on'   => 'ps.id = pnt.id_persona'
        ],
        [
            'type' => 'LEFT', // persona_juridica (puede que la persona sea natural)
            'table' => 'propiedades.persona_juridica pj',
            'on'   => 'ps.id = pj.id_persona'
        ],
        [
            'type' => '', // INNER JOIN con tp_tipo_persona
            'table' => 'propiedades.tp_tipo_persona ttp',
            'on'   => 'ttp.id = ps.id_tipo_persona'
        ],
        [
            'type' => '', // INNER JOIN para obtener dirección
            'table' => 'propiedades.persona_direcciones pd',
            'on'   => 'ps.id = pd.id_persona'
        ],
        [
            'type' => '',
            'table' => 'propiedades.tp_comuna tc',
            'on'   => 'tc.id = pd.id_comuna'
        ],
        [
            'type' => '',
            'table' => 'propiedades.tp_region tr',
            'on'   => 'tc.id_region = tr.id'
        ],
        [
            'type' => '',
            'table' => 'propiedades.tp_pais tp',
            'on'   => 'tr.id_pais = tp.id'
        ],
        [
            'type' => 'LEFT',
            'table' => 'propiedades.persona_propietario pp',
            'on'   => 'pp.id_persona = ps.id'
        ],
        [
            'type' => 'LEFT',
            'table' => 'propiedades.persona_arrendatario pa',
            'on'   => 'pa.id_persona = ps.id'
        ],
        [
            'type' => 'LEFT',
            'table' => 'propiedades.persona_codeudor pc',
            'on'   => 'pc.id_persona = ps.id'
        ],
    ];

    // Sin condiciones en la consulta (para evitar placeholders con puntos, etc.)
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
        '',
        '',
        null,
        false,
        false  // Debug = false para no imprimir la query
    );

    /**
     * ---------------------------------------------------------------------
     * Filtrado manual en PHP (opcional)
     * ---------------------------------------------------------------------
     * Si quieres filtrar por 'nombre_completo' (o por 'dni', etc.)
     * según el término de búsqueda, lo haces aquí.
     */
    if (!empty($searchTerm)) {
        $searchTermLower = mb_strtolower($searchTerm);

        // Filtramos el arreglo
        $filteredResult = array_filter($result, function ($row) use ($searchTermLower) {
            // Tomamos la columna 'nombre_completo' (también podrías usar 'dni', 'correo', etc.)
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
