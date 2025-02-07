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

$CoProp_id_ficha = @$_POST["idFicha"];
$CoProp_id = @$_POST["idPropietarioNuevo"];
$CoProp_id_cta_banc = @$_POST["idCtaBancaria"];

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
    // var_dump($queryIdPropiedad);

    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $queryIdPropiedad, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    // var_dump($resultado);

    $objIdPropiedad = json_decode($resultado)[0];
}

function limpiarCuentaBancaria($cuentaBancaria)
{
    // Usamos preg_replace para eliminar todo lo que no sean números
    return preg_replace('/\D/', '', $cuentaBancaria);
}
$id_cta_banc_clean = limpiarCuentaBancaria($CoProp_id_cta_banc);


$queryInsertCopropietario = "INSERT INTO propiedades.propiedad_copropietarios
(id_propiedad, id_propietario,id_cta_bancaria, id_moneda, nivel_propietario)
VALUES ($objIdPropiedad->id,$CoProp_id, $id_cta_banc_clean,2, 1)";

echo $queryInsertCopropietario;

$dataCab = array("consulta" => $queryInsertCopropietario);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

echo  "Propietario Guardado";
