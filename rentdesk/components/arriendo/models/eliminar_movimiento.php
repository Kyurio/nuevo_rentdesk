<?php
session_start();
require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// Instancia de tu QueryBuilder
$QueryBuilder = new QueryBuilder();

// Recibir parámetro POST
$idcc = $_POST['idcc'] ?? null;
$id_usuario = $_SESSION["rd_usuario_id" ?? null];

// Validación básica
if (empty($idcc)) {
    echo json_encode(['error' => 'idcc no proporcionado']);
    exit;
} elseif (empty($id_usuario)) {
    echo json_encode(['error' => 'id_usuario no proporcionado']);
    exit;
}

// Asignar el primer valor encontrado a la variable $id_propietario
try {
    // Ejecutar la función en la BD
    $result = $QueryBuilder->executeFunction('propiedades.fn_elimina_registro_ctacte', [$idcc, $id_usuario]);

    echo json_encode([
        'status'  => 'success',
        'message' => 'Movimiento eliminado correctamente',
        'result'  => $result // si quieres devolver algo adicional
    ]);
} catch (Throwable $th) {
    echo json_encode([
        'status'  => 'error',
        'message' => $th->getMessage()
    ]);
}
