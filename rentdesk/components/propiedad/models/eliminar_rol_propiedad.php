<?php
// Iniciar la sesión
session_start();

// Incluir archivos necesarios
include("../../../includes/sql_inyection.php");  // Asegúrate de que este archivo tenga las funciones para prevenir SQL Injection
include("../../../configuration.php");          // Configuración de la base de datos y otras configuraciones
include("../../../includes/funciones.php");     // Funciones generales, posiblemente para validar tokens
include("../../../includes/services_util.php"); // Utilidades de servicio, si las necesitas

// Obtener los datos enviados
$token_rol = $_POST['token_rol'] ?? null; // Cambio de token a token_rol

// Verificar el token (esto debe estar en funciones.php o en otro lugar)
$config         = new Config;
$services       = new ServicesRestful;
$url_services   = $config->url_services;

// Obtener el método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// // Validar si el método no es POST
// if ($metodo !== 'DELETE') {
//     // Manejar el error
//     http_response_code(405); // Método no permitido
//     echo json_encode(['error' => 'Método no permitido. Solo se permite POST.']);
//     exit;
// }

/*Consulta Cantidad de registros*/
$query_count = "DELETE FROM propiedades.propiedad_roles WHERE token='$token_rol'";
$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/dml', $data);
echo ($resultado);
