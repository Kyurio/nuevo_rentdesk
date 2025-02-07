<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";
use app\database\QueryBuilder;
$QueryBuilder = new QueryBuilder();

// Validación de datos recibidos
if (!isset($_POST['monto'], $_POST['idPropiedad'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit;
}

$monto = $_POST['monto'];
$id_propiedad = $_POST['idPropiedad'];

function GetDeposito($monto, $id_propiedad, $QueryBuilder)
{
    try {

        // Consulta SQL con parámetros preparados
        $result = $QueryBuilder->executeFunction('propiedades.fn_pago_online', [$monto, $id_propiedad]);
        echo $result;

    } catch (\Throwable $th) {
        echo json_encode(['status' => 'error', 'message' => $th->getMessage()]);
    } finally {
        // Cerrar la conexión
        if (isset($conn)) {
            pg_close($conn);
        }
    }
}

// Ejecutar la función
GetDeposito($monto, $id_propiedad, $QueryBuilder);