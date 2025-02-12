<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config = new Config;
$services = new ServicesRestful;
$url_services = $config->url_services;

$id_usuario = $_SESSION["rd_usuario_id"];

$Beneficiario_id_propietario = @$_POST["idPropietario"];
$Beneficiario_nombreBeneficiario = @$_POST["nombreBeneficiario"];
$Beneficiario_rutBeneficiario = @$_POST["rutBeneficiario"];
$Beneficiario_correoElectronicoBeneficiario = @$_POST["correoElectronicoBeneficiario"];
$Beneficiario_beneficiarioTelefonoFijo = @$_POST["beneficiarioTelefonoFijo"];
$Beneficiario_beneficiarioTelefonoMovil = @$_POST["beneficiarioTelefonoMovil"];
$Beneficiario_nombreTitular = @$_POST["nombreTitular"];
$Beneficiario_rutTitular = @$_POST["rutTitular"];
$Beneficiario_emailTitular = @$_POST["emailTitular"];
$Beneficiario_banco = @$_POST["banco"];
$Beneficiario_cuentaBanco = @$_POST["cuentaBanco"];
$Beneficiario_numCuenta = @$_POST["numCuenta"];
$token = @$_POST["token"];

// 🔹 Obtener el ID de la propiedad desde el token
$queryIdPropiedad = "SELECT id FROM propiedades.propiedad WHERE token = '$token'";
$data = ["consulta" => $queryIdPropiedad];
$resultado = $services->sendPostNoToken($url_services . '/util/objeto', $data);
$objIdPropiedad = json_decode($resultado)[0] ?? null;

if (!$objIdPropiedad) {
    http_response_code(404);
    echo json_encode(["error" => "Propiedad no encontrada"]);
    exit;
}

// 🔹 Insertar Beneficiario (usando RETURNING id para PostgreSQL o LAST_INSERT_ID() para MySQL)
$queryInsertBeneficiario = "INSERT INTO propiedades.persona_beneficiario
(id_propiedad, id_propietario, nombre, rut, correo, telefono_fijo, telefono_movil, cta_nombre_titular, cta_rut, cta_correo, cta_id_banco, id_tipo_cuenta, numero_cuenta)
VALUES ($objIdPropiedad->id, $Beneficiario_id_propietario, '$Beneficiario_nombreBeneficiario', '$Beneficiario_rutBeneficiario', 
'$Beneficiario_correoElectronicoBeneficiario', '$Beneficiario_beneficiarioTelefonoFijo', '$Beneficiario_beneficiarioTelefonoMovil', 
'$Beneficiario_nombreTitular', '$Beneficiario_rutTitular', '$Beneficiario_emailTitular', $Beneficiario_banco, $Beneficiario_cuentaBanco, $Beneficiario_numCuenta)
RETURNING id";



$dataInsert = ["consulta" => $queryInsertBeneficiario];
$resultadoInsert = $services->sendPostDirecto($url_services . '/util/dml', $dataInsert);
$objIdBeneficiario = json_decode($resultadoInsert)[0] ?? null;

// 🔹 Verificar si ya existe en `propiedad_copropietarios`
$queryCheck = "SELECT id from propiedades.persona_beneficiario order by id desc limit 1";
$dataCheck = ["consulta" => $queryCheck];
$resultadoCheck = $services->sendPostNoToken($url_services . '/util/objeto', $dataCheck);
$objCheck = json_decode($resultadoCheck)[0] ?? null;

$idBeneficiario = $objCheck->id;



// 🔹 Enviar respuesta final
echo true;
