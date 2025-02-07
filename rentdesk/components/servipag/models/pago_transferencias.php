<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

$QueryBuilder = new QueryBuilder();

try {
    // Obtener datos enviados desde la solicitud AJAX
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $fechasContables = $QueryBuilder->selectAdvanced('propiedades.servipag', 'fecha_contab');

    foreach ($data as $item) {
        // Cambiar formato de fecha
        $fechaPago = DateTime::createFromFormat('d-m-Y', $item['fecha_pago']);
        if (!$fechaPago) {
            throw new Exception('Formato de fecha incorrecto: ' . $item['fecha_pago']);
        }
        $fechaPagoFormateada = $fechaPago->format('Y-m-d');

        $result = $QueryBuilder->executeFunction('propiedades.fn_pago_transferencia', [
            $item['monto_pagado'],
            $item['id_propiedad'],
            $fechaPagoFormateada,
            0
        ]);

        // Actualiza el estado de procesado a true
        $dataActualizacion = ['procesado' => true];
        $conditions = ['id' => $item['id_servipag']];
        $resultUpdate = $QueryBuilder->update('propiedades.servipag', $dataActualizacion, $conditions);
    }


    echo json_encode([
        'status' => 'success',
        'message' => 'Datos procesados exitosamente'
    ]);
} catch (Throwable $th) {
    echo json_encode([
        'status' => 'error',
        'message' => $th->getMessage()
    ]);
}
