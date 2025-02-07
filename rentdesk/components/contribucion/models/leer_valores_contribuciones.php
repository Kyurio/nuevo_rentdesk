<?php
require "../../../app/model/QuerysBuilder.php";
use app\database\QueryBuilder;

// Configuración de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Instancia de QueryBuilder
$QueryBuilder = new QueryBuilder();

// Función para formatear la fecha en el formato 'Y-m-d'
function formatearFecha($fecha) {
    $date = DateTime::createFromFormat('d/m/Y', $fecha);
    return $date ? $date->format('Y-m-d') : $fecha; // Si no puede formatear, retorna la fecha original
}

function obtenerPropiedadesParaContribuciones() {
    global $QueryBuilder;

    // Consulta SQL para llamar a la función correctamente
    $query = "SELECT * FROM propiedades.fn_propiedades_para_contribuciones()"; 

    // Usar selectAdvanced para ejecutar la consulta
    $results = $QueryBuilder->selectAdvanced(
        'propiedades.fn_propiedades_para_contribuciones()', // Llamada a la función
        '*', // Seleccionar todas las columnas
        // [], // No se requieren joins en este caso
        [], // No hay condiciones
        '', // No se requiere GROUP BY
        '', // No se requiere ORDER BY
        null // No se aplica LIMIT
    );

    // Verificar si los resultados existen y decodificar el JSON
    if (!empty($results)) {
        $json_data = json_decode($results[0]['fn_propiedades_para_contribuciones'], true); // Decodificar el JSON

        // Formatear las fechas si existen
        foreach ($json_data as &$row) {
            if (isset($row['fecha'])) {
                $row['fecha'] = formatearFecha($row['fecha']);
            }

            if (isset($row['fecha_pago'])) {
                $row['fecha_pago'] = formatearFecha($row['fecha_pago']);
            }
        }

        // Enviar los resultados en formato JSON
        echo json_encode(['data' => $json_data]);
    } else {
        echo json_encode(['data' => []]); // Retornar un array vacío si no hay resultados
    }
}

// Llamar la función para enviar los datos en formato JSON
obtenerPropiedadesParaContribuciones();
?>
