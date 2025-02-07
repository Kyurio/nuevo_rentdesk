<?php   

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$id_propietario =  $_POST["id"];


$query_count = "SELECT 
    CASE 
        WHEN numero IS NULL OR numero = '' THEN 'false' 
        ELSE 'true' 
    END AS estado_cuenta

FROM 
    propiedades.propietario_ctas_bancarias 
WHERE
	id_propietario = $id_propietario
	and habilitado = true";

$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);

echo $resultado;
