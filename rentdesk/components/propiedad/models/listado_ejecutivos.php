<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$token = $_GET['token'];


// if (!$token) {

    $query = "SELECT CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) as nombres, correo, id
FROM propiedades.cuenta_usuario
where habilitado = true
order by correo asc";

// } else {

//     $query = "SELECT CONCAT(nombres, ' ', apellido_paterno, ' ', apellido_materno) as nombres, correo, a.id, b.token
// FROM propiedades.cuenta_usuario a
// INNER JOIN propiedades.propiedad  b
// ON a.id = b.id_ejecutiva_encargada
// where a.habilitado = true AND b.token= '$token'";

// }

$data = array("consulta" => $query);
$resultado  = $services->sendPostDirecto($url_services . '/util/objeto', $data);

echo $resultado;
