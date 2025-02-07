<?php
header('Content-Type: application/json');

require "../../../app/model/QuerysBuilder.php"; 
use app\database\QueryBuilder;

// Crear instancia de QueryBuilder o tu conexión de base de datos
$queryBuilder = new QueryBuilder();

// Obtener los datos JSON recibidos
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Verificar si hubo un error al decodificar el JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([ 
        'status' => 'error', 
        'message' => 'Error al decodificar el JSON: ' . json_last_error_msg() 
    ]);
    exit;
}

// Asegúrate de que los datos estén presentes
if (!isset($data['json_entrada']) || !is_array($data['json_entrada'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Datos incompletos o el formato de "json_entrada" no es un array.'
    ]);
    exit;
}

// El campo json_entrada debe ser un array JSON
$jsonEntrada = $data['json_entrada'];  // Es un array ya decodificado en PHP

// Crear un nuevo array con la estructura esperada
$v_registro = [];

foreach ($jsonEntrada as $item) {
    $v_registro[] = [
        'id_propiedad' => $item['idPropiedad'],
        'detalle' => [
            'rol' => $item['rol'],
            'num_cuota' => $item['numCuota'],
            'valor_cuota' => $item['valorCuota'],
            'mes' => $item['mesContrib']
        ]
    ];
}

// Llamar a la función utilizando QueryBuilder
try {
    $result = $queryBuilder->executeFunction(
        'propiedades.fn_contribuciones_genera_retencion',
        [json_encode($v_registro)] // Pasamos el JSON como parámetro
    );

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Retención generada correctamente.'
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'message' => 'Retención generada correctamente.'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en la ejecución de la función: ' . $e->getMessage()
    ]);
}
?>
