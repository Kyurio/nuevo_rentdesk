<?php
// Configuración de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configurar la respuesta como JSON
header('Content-Type: application/json');

function obtenerAdministradores() {
    // Configuración de la conexión
    $host = 'localhost';
    $port = '5432';
    $dbname = 'postgres';
    $user = 'arpis';
    $password = 'arpis';

    try {
        // Conexión a la base de datos
        $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

        // Verificar la conexión
        if (!$conn) {
            echo json_encode(['status' => 'error', 'message' => 'Error en la conexión: ' . pg_last_error()]);
            exit;
        }

        // Construir la consulta SQL para obtener administradores
        $query = "SELECT propiedades.fn_consulta_sucursales(1)";

        // Ejecutar la consulta
        $result = pg_query($conn, $query);

        if (!$result) {
            echo json_encode(['status' => 'error', 'message' => 'Error en la consulta: ' . pg_last_error($conn)]);
            exit;
        }

        // Procesar el resultado
        $data = pg_fetch_result($result, 0, 0); // Obtener el resultado como una cadena
        $data = json_decode($data, true); // Decodificar la cadena JSON

        // Verificar si hay resultados
        if (!$data || count($data) === 0) {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron resultados']);
        } else {
            echo json_encode(['status' => 'success', 'data' => $data]);
        }
    } catch (Throwable $th) {
        // Manejar excepciones
        echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
    } finally {
        // Cerrar la conexión
        if (isset($conn)) {
            pg_close($conn);
        }
    }
}

// Ejecutar la función para obtener administradores
obtenerAdministradores();
?>
