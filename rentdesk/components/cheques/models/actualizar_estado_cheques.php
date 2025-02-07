<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Requerir el QueryBuilder
require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// 2. Instanciarlo
$QueryBuilder = new QueryBuilder();

// 3. Verificar que se reciba el token por POST
if (!isset($_POST['token']) || empty($_POST['token'])) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'No se recibió el token'
    ]);
    exit;
}

$token = $_POST['token'];

try {
    // 4. Construir data y conditions para actualizar
    $data = [
        'desposito' => true // o 't' si la columna es tipo boolean con 't'/'f', etc.
    ];
    $conditions = [
        'token' => $token
    ];

    // 5. Ejecutar la actualización
    $updateResult = $QueryBuilder->update(
        'propiedades.ficha_arriendo_cheques',
        $data,
        $conditions
    );

    // Si deseas verificar filas afectadas (depende de cómo implementaste "execute" en QueryBuilder)
    // $updateResult podría devolver número de filas, bool, etc.
    // Ajusta según tu implementación.

    // 6. Responder
    echo json_encode([
        'status'  => 'success',
        'message' => 'Cheque actualizado correctamente',
        'result'  => $updateResult
    ]);
} catch (Throwable $th) {
    echo json_encode([
        'status'  => 'error',
        'message' => $th->getMessage()
    ]);
}
