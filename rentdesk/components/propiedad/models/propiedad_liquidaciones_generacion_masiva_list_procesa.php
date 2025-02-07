<?php   

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 

$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token    = @$_GET["token"];


// Obtener el método de la solicitud
$metodo = $_SERVER['REQUEST_METHOD'];

// Validar si el método no es DELETE
if ($metodo !== 'GET') {
    // Manejar el error
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido. Solo se permite DELETE.']);
    exit;
}

/*Consulta Cantidad de registros*/
$query_count = "SELECT 
    a.id,
    COALESCE(a.direccion || ' ' || a.numero) AS direccion,
    COALESCE(vpj.razon_social, vpn.nombres || ' ' || vpn.apellido_paterno || ' ' || vpn.apellido_materno) AS nombre_propietario
FROM 
    propiedades.propiedad a
INNER JOIN 
    propiedades.propiedad_copropietarios b ON b.id_propiedad = a.id
LEFT JOIN 
    propiedades.vis_persona_juridica vpj ON b.id_propietario = vpj.id
LEFT JOIN 
    propiedades.vis_persona_natural vpn ON b.id_propietario = vpn.id
WHERE 
    b.nivel_propietario = 1 
    AND b.habilitado = true
	AND direccion is not null";

$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);



echo $resultado;
