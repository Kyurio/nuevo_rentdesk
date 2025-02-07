<?php

session_start();

// Asegúrate de cargar tu QueryBuilder y la configuración necesaria
require "../../../app/model/QuerysBuilder.php"; // <-- Ajusta la ruta si es necesario
use app\database\QueryBuilder;

include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

// Instanciamos la configuración si la necesitas
$config       = new Config;
$services     = new ServicesRestful;
$url_services = $config->url_services;

/**
 * 1) Recuperamos el ID del recordatorio a eliminar (puede llamarse 'id_recordatorio' o como tú lo envíes).
 *    Asegúrate de que coincida con el nombre del campo que le pasas por POST desde tu AJAX o formulario.
 */
$id_recordatorio = $_POST['id_recordatorio'] ?? null;

/**
 * 2) Validar si el ID llegó, para evitar errores.
 *    Puedes incluir validaciones adicionales, por ejemplo, que sea un número entero.
 */
if (empty($id_recordatorio)) {
    // Retornar un JSON indicando el error
    echo json_encode([
        'success' => false,
        'message' => 'ID del recordatorio no proporcionado.'
    ]);
    exit;
}

// 3) Instanciamos el QueryBuilder
$QueryBuilder = new QueryBuilder();

/**
 * 4) Llamamos a la función delete.
 *    La función delete($table, $conditions) genera un DELETE con WHERE a partir de $conditions.
 *    Por ejemplo: DELETE FROM propiedades.propiedad_recordatorios WHERE id_recordatorio = :id_recordatorio
 */
try {
    $conditions = ['id' => $id_recordatorio];
    $deleteResult = $QueryBuilder->delete('propiedades.propiedad_recordatorios', $conditions);

    // Verificamos si la ejecución fue exitosa
    if ($deleteResult) {
        // Suponiendo que QueryBuilder->execute devuelve true si se ejecutó con éxito
        echo json_encode(['success' => true]);
    } else {
        // Aquí podría significar que no se encontró el registro o hubo otro problema
        echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el registro.']);
    }
} catch (Exception $e) {
    // Manejo de excepciones (por ejemplo, problemas de conexión, SQL mal formado, etc.)
    echo json_encode([
        'success' => false,
        'message' => 'Excepción: ' . $e->getMessage()
    ]);
}
