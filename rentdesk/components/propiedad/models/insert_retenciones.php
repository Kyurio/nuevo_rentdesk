<?php
require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();
// Configuración de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurar la respuesta como JSON
header('Content-Type: application/json');

// Obtener datos de la solicitud
$data = json_decode(file_get_contents("php://input"), true);
$id_arriendo = $data['id_arriendo'] ?? null;
$id_propiedad = $data['id_propiedad'] ?? null;
$tipo_retencion = $data['tipo_retencion'] ?? null;
$monto_retencion = $data['monto_retencion'] ?? null;
$fecha_desde = $data['fecha_desde'] ?? null;
$fecha_hasta = $data['fecha_hasta'] ?? null;
$razon_retencion = $data['razon_retencion'] ?? null;

// Establecer la fecha actual
$fecha_actual = date('Y-m-d');

// var_dump($data, $id_arriendo, $id_propiedad, $tipo_retencion, $monto_retencion, $fecha_desde, $fecha_hasta, $razon_retencion, $fecha_actual);
// die();

// Validar datos recibidos
if (!isset($id_arriendo, $id_propiedad, $tipo_retencion, $monto_retencion)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Datos incompletos',
        'missing_data' => [
            'id_arriendo' => $id_arriendo ?? 'null',
            'id_propiedad' => $id_propiedad ?? 'null',
            'tipo_retencion' => $tipo_retencion ?? 'null',
            'monto_retencion' => $monto_retencion ?? 'null',
            'fecha_desde' => $fecha_desde ?? 'null',
            'fecha_hasta' => $fecha_hasta ?? 'null',
            'razon_retencion' => $razon_retencion ?? 'null'
        ]
    ]);
    exit; // Termina la ejecución si los datos son incompletos
}

// Eliminar el símbolo de moneda y formatear el monto a numérico
$monto_retencion = str_replace(['$', '.'], '', $monto_retencion);
$monto_retencion = floatval($monto_retencion); // Convertir a número

// Función que utiliza el QueryBuilder para ejecutar la función SQL
function generarRetencion($id_arriendo, $id_propiedad, $tipo_retencion, $monto_retencion, $fecha_desde, $fecha_hasta, $fecha_actual, $razon_retencion)
{
    global $QueryBuilder;

    // Ejecutar la función SQL utilizando el QueryBuilder
    $result = $QueryBuilder->executeFunction('propiedades.fn_genera_retencion', [
        $id_arriendo,
        $id_propiedad,
        $tipo_retencion,
        $monto_retencion,
        $fecha_desde ?? $fecha_actual,
        $fecha_hasta ?? $fecha_actual,
        $razon_retencion
    ]);

    // Manejo de la respuesta
    if ($result === null) {
        echo json_encode(['status' => 'error', 'message' => 'Error al generar la retención']);
    } elseif ($result == -1) {
        echo json_encode(['status' => 'error', 'message' => 'La propiedad ya tiene retenciones pendientes']);
    } elseif ($result == -2) {
        echo json_encode(['status' => 'error', 'message' => 'El monto de la retención es mayor que el monto del arriendo']);
    } else {
        echo json_encode(['status' => 'success', 'data' => 'Retención generada correctamente', 'retenciones' => $result]);
    }
}

// Ejecutar la función para generar la retención
generarRetencion($id_arriendo, $id_propiedad, $tipo_retencion, $monto_retencion, $fecha_desde, $fecha_hasta, $fecha_actual, $razon_retencion);
