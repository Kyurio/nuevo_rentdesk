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
     * Definir la consulta principal
     * ---------------------------------------------------------------------
     */
    // Tabla principal (FROM)
    $table = 'propiedades.persona AS p';

    // Columnas a seleccionar (con CASE para persona natural/jurídica)
    // Ojo: en PostgreSQL, usa CONCAT con comillas simples.
    $columns = "
        p.dni,
        CASE
            WHEN ttp.id = 1 THEN CONCAT(pn.nombres, ' ', pn.apellido_paterno, ' ', pn.apellido_materno)
            WHEN ttp.id = 2 THEN pj.razon_social
            ELSE 'Tipo Desconocido'
        END AS nombre,
        ttp.nombre AS tipo_persona
    ";

    // Definición de los JOINs
    $joins = [
        [
            'type' => '', // El método ya agrega la palabra JOIN
            'table' => 'propiedades.persona_propietario pp',
            'on'   => 'p.id = pp.id_persona'
        ],
        [
            'type' => '', // INNER JOIN con tp_tipo_persona
            'table' => 'propiedades.tp_tipo_persona ttp',
            'on'   => 'p.id_tipo_persona = ttp.id'
        ],
        [
            'type' => 'LEFT', // Persona natural (puede no existir)
            'table' => 'propiedades.persona_natural pn',
            'on'   => 'p.id = pn.id_persona'
        ],
        [
            'type' => 'LEFT', // Persona jurídica (puede no existir)
            'table' => 'propiedades.persona_juridica pj',
            'on'   => 'p.id = pj.id_persona'
        ],
    ];

    // Sin condiciones en la consulta (para evitar placeholders con puntos)
    $conditions = [];

    // 5) Ejecutamos la consulta con QueryBuilder
    $result = $QueryBuilder->selectAdvanced(
        $table,
        $columns,
        $joins,
        $conditions,
        '',
        '',
        null,
        false,
        false  // Debug = false para no imprimir el SQL
    );

    /**
     * ---------------------------------------------------------------------
     * Filtrado manual en PHP (opcional)
     * ---------------------------------------------------------------------
     * Filtramos los resultados por el término de búsqueda, 
     * buscando coincidencias en la columna calculada 'nombre'.
     */
    if (!empty($searchTerm)) {
        // Convertimos a minúsculas para comparación case-insensitive
        $searchTermLower = mb_strtolower($searchTerm);

        // Filtramos el arreglo con array_filter
        $filteredResult = array_filter($result, function ($row) use ($searchTermLower) {
            // 'nombre' es la columna calculada (CASE WHEN ... THEN ...)
            $nombreLower = mb_strtolower($row['nombre'] ?? '');
            $dni = mb_strtolower($row['dni'] ?? '');
            // Retornamos true si 'nombreCompleto' contiene la subcadena de búsqueda
            return (strpos($nombreLower, $searchTermLower) !== false
                || strpos($dni, $searchTermLower) !== false);
        });

        // Reindexar el arreglo filtrado
        $filteredResult = array_values($filteredResult);
    } else {
        // Si no hay búsqueda, devolvemos todo tal cual
        $filteredResult = array_values($result);
    }

    /**
     * ---------------------------------------------------------------------
     * Responder en formato JSON
     * ---------------------------------------------------------------------
     */
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($filteredResult, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // Mostrar error en caso de excepción
    echo "Error: " . $e->getMessage();
}
