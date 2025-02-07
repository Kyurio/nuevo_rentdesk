<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();

// Validar que lleguen las variables esperadas
if (!isset($_POST['monto']) || !isset($_POST['idPropiedad'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se recibieron los datos necesarios']);
    exit;
}

$monto       = $_POST['monto'];
$id_propiedad = $_POST['idPropiedad'];

try {
    // Ejecutar la función en la BD
    $result = $QueryBuilder->executeFunction('propiedades.fn_pago_online', [$monto, $id_propiedad]);

    echo json_encode([
        'status'  => 'success',
        'message' => 'Cheque depositado correctamente',
        'result'  => $result // si quieres devolver algo adicional
    ]);
} catch (Throwable $th) {
    echo json_encode([
        'status'  => 'error',
        'message' => $th->getMessage()
    ]);
}
