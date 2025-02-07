<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

session_start();

include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

// Validar idFicha
$idFicha = $_POST["idFicha"];

// Consulta para Copropietarios
$queryCopropietarios = "SELECT *,  
    a.dni AS rut_propietario,
    concat(nombres, ' ', apellido_paterno, ' ', apellido_materno, ' ', giro) AS nombre
    FROM propiedades.persona a 
    INNER JOIN propiedades.propiedad_copropietarios b 
    ON a.id = b.id_propietario 
    LEFT JOIN propiedades.persona_natural c
    ON a.id = c.id_persona
    LEFT JOIN propiedades.persona_juridica d
    ON a.id = d.id_persona
    WHERE id_propiedad = $idFicha AND habilitado = true";

$dataCopropietarios = ["consulta" => $queryCopropietarios];
$resultadoCopropietarios = json_decode($services->sendPostNoToken($url_services . '/util/objeto', $dataCopropietarios), true);

// Consulta para Beneficiarios
$queryBeneficiarios = "SELECT * FROM propiedades.persona_beneficiario WHERE id_propiedad = $idFicha";
$dataBeneficiarios = ["consulta" => $queryBeneficiarios];
$resultadoBeneficiarios = json_decode($services->sendPostNoToken($url_services . '/util/objeto', $dataBeneficiarios), true);

// Validar si se obtuvieron datos
if (!is_array($resultadoCopropietarios)) {
    $resultadoCopropietarios = [];
}
if (!is_array($resultadoBeneficiarios)) {
    $resultadoBeneficiarios = [];
}

// Unimos los resultados en un solo JSON
$response = [
    "copropietarios" => $resultadoCopropietarios,
    "beneficiarios" => $resultadoBeneficiarios
];

//header('Content-Type: application/json');
echo json_encode($response);
?>
