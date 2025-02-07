<?php

session_start();

// Asegúrate de cargar tu QueryBuilder y la configuración necesaria
require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

// Instanciamos la configuración
$config       = new Config;
$services     = new ServicesRestful;
$url_services = $config->url_services;

// 1) Recuperar los datos del formulario
$token             = $_POST['token'] ?? null;  // Token recibido desde JS
$tipo_recordatorio = $_POST['tipoRecordatorio'] ?? null;
$fecha_notificacion = $_POST['fechaNotificacion'] ?? null;
$descripcion       = $_POST['descripcionRecordatorio'] ?? null;
$id_ejecutivos     = isset($_POST['idEjecutivos']) ? explode(',', $_POST['idEjecutivos']) : [];

// 2) Validar token
if (empty($token)) {
    echo json_encode(['error' => true, 'message' => 'El token no fue recibido.']);
    exit;
}

// 3) Instanciar el QueryBuilder
$QueryBuilder = new QueryBuilder();

// 4) Buscar id_propiedad usando el token
try {
    $propiedadResult = $QueryBuilder->selectAdvanced(
        'propiedades.propiedad',
        'id AS id_propiedad',
        [],
        ['token' => ['=', $token]]
    );

    // Validar si se obtuvo un resultado
    if (!$propiedadResult || count($propiedadResult) === 0) {
        echo json_encode(['error' => true, 'message' => 'No se encontró una propiedad con ese token.']);
        exit;
    }

    // Extraer el id_propiedad de la consulta
    $id_propiedad = (int)$propiedadResult[0]['id_propiedad'];
} catch (Exception $e) {
    echo json_encode(['error' => true, 'message' => 'Error al obtener id_propiedad: ' . $e->getMessage()]);
    exit;
}


// 5) Validar que al menos un ejecutivo ha sido seleccionado y otros datos
$id_ejecutivos = array_filter($id_ejecutivos, function ($value) {
    return ctype_digit($value) && $value !== '';
});

if (empty($id_ejecutivos) || empty($tipo_recordatorio) || empty($fecha_notificacion)) {
    echo json_encode(['error' => true, 'message' => 'Debe completar todos los campos correctamente.']);
    exit;
}

try {
    $inserciones_exitosas = 0;

    // 6) Insertar un registro por cada ejecutivo seleccionado
    foreach ($id_ejecutivos as $id_ejecutivo) {
        $id_ejecutivo = (int) $id_ejecutivo;

        $dataToInsert = [
            'id_propiedad'       => $id_propiedad,
            'fecha_notificacion' => $fecha_notificacion,
            'id_ejecutivo'       => $id_ejecutivo,
            'descripcion'        => $descripcion,
            'tipo_recordatorio'  => $tipo_recordatorio
        ];

        // Ejecutar inserción
        $insertResult = $QueryBuilder->insert('propiedades.propiedad_recordatorios', $dataToInsert);

        if ($insertResult) {
            $inserciones_exitosas++;
        }
    }

    // 7) Confirmar resultado
    if ($inserciones_exitosas > 0) {
        echo json_encode(['success' => true, 'message' => "$inserciones_exitosas recordatorio(s) insertado(s)."]);
    } else {
        echo json_encode(['error' => true, 'message' => 'No se pudo insertar ningún recordatorio.']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
