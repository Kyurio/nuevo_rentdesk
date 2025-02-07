<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();


if (!isset($_POST['token']) || empty($_POST['token'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se recibió el token']);
    exit;
}

$token = $_POST['token'];

try {
    $data = ['estado_transferencia' => true];
    $conditions = ['token' => $token];

    $updateResult = $QueryBuilder->update('propiedades.ficha_servipag', $data, $conditions);

    echo json_encode([
        'status' => 'success',
        'message' => 'Transferencia actualizada correctamente',
        'result' => $updateResult
    ]);
} catch (Throwable $th) {
    echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
}
