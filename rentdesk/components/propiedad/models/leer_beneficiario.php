
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$idFicha = $_POST["idFicha"];
$queryCopropietarios = "SELECT * FROM propiedades.persona_beneficiario WHERE id_propiedad = $idFicha";

$data = array("consulta" => $queryCopropietarios);
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);


echo $resultado;
