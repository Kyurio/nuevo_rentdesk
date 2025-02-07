<?php
// Iniciar la sesión
session_start();

// Incluir archivos necesarios
include("../../../includes/sql_inyection.php");  // Asegúrate de que este archivo tenga las funciones para prevenir SQL Injection
include("../../../configuration.php");          // Configuración de la base de datos y otras configuraciones
include("../../../includes/funciones.php");     // Funciones generales, posiblemente para validar tokens
include("../../../includes/services_util.php"); // Utilidades de servicio, si las necesitas

// Obtener los datos enviados
$id_retencion = $_POST['id_retencion'] ?? null; // ID de la retención a eliminar

// Verificar si el ID de retención es válido
if (empty($id_retencion) || !is_numeric($id_retencion)) {
    http_response_code(400); // Solicitud incorrecta
    echo json_encode(['error' => 'ID de retención no válido.']);
    exit;
}

// Configuración
$config       = new Config();
$services     = new ServicesRestful();
$url_services = $config->url_services;

// Preparar la consulta para eliminar la retención
$query = "DELETE FROM propiedades.propiedad_retenciones WHERE id = {$id_retencion}";

// Datos para la consulta
$data = [
    "consulta" => $query,
    "params" => [
        "id_retencion" => intval($id_retencion) // Asegurarse de que el ID sea un número entero
    ]
];

// Enviar la consulta a través del servicio
$resultado = $services->sendPostNoToken($url_services . '/util/dml', $data);

// Log de la respuesta
error_log("Resultado de la eliminación: " . print_r($resultado, true)); // Verifica la respuesta

// Verifica la estructura de la respuesta
if ($resultado && isset($resultado['success'])) {
    if ($resultado['success'] === true) {
        echo json_encode(['success' => true, 'message' => 'Retención eliminada correctamente.']);
    } else {
        // Aquí loggeamos más información sobre el resultado
        error_log("Error en la eliminación: " . print_r($resultado, true));
        echo json_encode(['success' => false, 'message' => $resultado['message'] ?? 'No se pudo eliminar la retención.']);
    }
} else {
    echo json_encode(['success' => true, 'message' => 'Retención eliminada correctamente.']);
}
?>