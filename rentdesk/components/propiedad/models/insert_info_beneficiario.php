<?php

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
// $current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

$Beneficiario_id_ficha = @$_POST["idFicha"];
$Beneficiario_id_propietario = @$_POST["idPropietario"];
$Beneficiario_id_registro = @$_POST["idRegistro"];


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

$component = @$_POST["component"];
$view = @$_POST["view"];
$token = @$_POST["token"];
$item = @$_POST["item"];
$id_recurso = @$_POST["id_recurso"];
$id_item = @$_POST["id_item"];
// Obtener el objeto de sesión y convertirlo en un objeto PHP
$sesion_rd_login = unserialize($_SESSION['sesion_rd_login']);
// Acceder a la dirección de correo electrónico
$correo = $sesion_rd_login->correo;

$num_reg = 10;
$inicio = 0;

/*BUSQUEDA USUARIO POR TOKEN ACTUAL */
$query = "SELECT id FROM propiedades.cuenta_usuario cu where token = '$id_usuario' ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objUsuarioId = json_decode($resultado)[0];



if (isset($_POST["token"])) {
    $token = $_POST["token"];

    $queryIdPropiedad = "select p.id from propiedades.propiedad p where p.token = '$token' ";


    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $queryIdPropiedad, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    // var_dump($resultado);

    $objIdPropiedad = json_decode($resultado)[0];
}

$queryInsertBeneficiario = "INSERT INTO propiedades.persona_beneficiario
(id_propiedad, id_propietario, nombre, rut,correo, telefono_fijo, telefono_movil, cta_nombre_titular, cta_rut, cta_correo, cta_id_banco, id_tipo_cuenta, numero_cuenta)
VALUES ($objIdPropiedad->id,$Beneficiario_id_propietario, '$Beneficiario_nombreBeneficiario', '$Beneficiario_rutBeneficiario', '$Beneficiario_correoElectronicoBeneficiario','$Beneficiario_beneficiarioTelefonoFijo', '$Beneficiario_beneficiarioTelefonoMovil', '$Beneficiario_nombreTitular', '$Beneficiario_rutTitular', '$Beneficiario_emailTitular', $Beneficiario_banco, $Beneficiario_cuentaBanco, $Beneficiario_numCuenta)";

$dataCab = array("consulta" => $queryInsertBeneficiario);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);


if ($resultadoCab != "OK") {
    echo ",xxx,ERROR,xxx,No se logró ingresar beneficiario,xxx,-,xxx,";
    return;
}

echo ",xxx,OK,xxx,Beneficiario Ingresado Correctamente,xxx,-,xxx,";
