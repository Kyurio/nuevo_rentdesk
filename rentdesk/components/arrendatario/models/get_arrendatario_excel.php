<?php

// Activa la visualización de errores en pantalla para ayudar en el desarrollo:
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Se incluye el archivo que contiene la clase QueryBuilder
require "../../../app/model/QuerysBuilder.php";

// Se usa la clase QueryBuilder dentro del namespace app\database
use app\database\QueryBuilder;

// 1. Capturamos el parámetro de búsqueda que viene por GET, si no existe, se asigna cadena vacía
$searchTerm = $_GET['searchTerm'] ?? '';

// Se crea una instancia del QueryBuilder para conectarse a la BD y ejecutar consultas
$QueryBuilder = new QueryBuilder();

try {
    // Tabla principal de la cual se obtendrán los datos (cláusula FROM)
    $table = 'propiedades.ficha_arriendo_arrendadores AS faa';

    // Columnas a seleccionar en la consulta (con CONCAT para construir el nombre completo)
    $columns = "faa.id_ficha_arriendo,
                p.id AS id_persona,
                p.dni,
                CONCAT(pn.nombres, ' ', pn.apellido_paterno, ' ', pn.apellido_materno) AS nombre_completo";

    // Definición de los JOINs a realizar en la consulta
    $joins = [
        [
            'type' => '', // El QueryBuilder añade 'JOIN' internamente
            'table' => 'propiedades.persona p',
            'on'   => 'faa.id_arrendatario = p.id'
        ],
        [
            'type' => '',
            'table' => 'propiedades.persona_natural pn',
            'on'   => 'p.id = pn.id_persona'
        ],
    ];

    // NO agregamos condiciones en la BD para evitar el problema del placeholder con punto (pn.nombres)
    $conditions = [];

    // Se ejecuta la consulta con QueryBuilder (sin modo debug)
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

    // Si el usuario escribió algo en el campo de búsqueda, procedemos a filtrar en PHP
    if (!empty($searchTerm)) {
        // Convertimos el término a minúsculas para hacer la comparación case-insensitive
        $searchTermLower = mb_strtolower($searchTerm);

        // array_filter filtra el arreglo retornando solo los registros que cumplan cierta condición
        $filteredResult = array_filter($result, function ($row) use ($searchTermLower) {
            // Convertimos el campo 'nombre_completo' a minúsculas para comparar sin distinción de may/min
            $nombreCompleto = mb_strtolower($row['nombre_completo'] ?? '');
            $dni = mb_strtolower($row['dni'] ?? '');
            // Retornamos true si 'nombreCompleto' contiene la subcadena de búsqueda
            return (strpos($nombreCompleto, $searchTermLower) !== false
                || strpos($dni, $searchTermLower) !== false);
        });

        // array_filter deja los índices originales. array_values reindexa el arreglo desde 0, 1, 2...
        $filteredResult = array_values($filteredResult);
    } else {
        // Si no se ingresó término de búsqueda, devolvemos todos los registros consultados
        $filteredResult = array_values($result);
    }

    // Se devuelve la respuesta como JSON:
    //  - Indicamos que el contenido es JSON
    //  - Con JSON_UNESCAPED_UNICODE evitamos que se escapen caracteres acentuados.
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($filteredResult, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // Si ocurre un error, lo mostramos en pantalla (o podrías manejarlo de otra forma en producción)
    echo "Error: " . $e->getMessage();
}
