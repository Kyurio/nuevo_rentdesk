<?php
session_start();
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar si se ha proporcionado el parámetro `id_propiedades_roles`
if (!isset($_GET['id_propiedades_roles']) || empty($_GET['id_propiedades_roles'])) {
    echo json_encode(['error' => 'ID de propiedades roles no proporcionado']);
    exit;
}

$id_propiedades_roles = $_GET['id_propiedades_roles'];

$config = new Config();
$services = new ServicesRestful();
$url_services = $config->url_services;

// Crear la consulta SQL
$query_count = "
    SELECT 
        a.id, 
        a.año, 
        a.valor, 
        CASE 
            WHEN a.cuota = 1 THEN 'Abril'
            WHEN a.cuota = 2 THEN 'Junio'
            WHEN a.cuota = 3 THEN 'Septiembre'
            WHEN a.cuota = 4 THEN 'Noviembre'
            ELSE 'Desconocido'
        END AS mes,
        a.cuota,
        a.cobrado, 
        a.pagado
    FROM propiedades.valores_roles a 
    INNER JOIN propiedades.propiedad b 
    ON a.id_propiedad = b.id
    WHERE a.id_propiedades_roles = $id_propiedades_roles
";

// Enviar la consulta al servicio y obtener los datos
$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);

$decoded_result = json_decode($resultado, true);

if (!is_array($decoded_result) || empty($decoded_result)) {
    echo json_encode(['error' => 'No se encontraron datos']);
    exit;
}

header('Content-Type: application/json');
echo json_encode(['result' => $decoded_result]);

?>
