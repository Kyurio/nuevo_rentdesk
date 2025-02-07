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

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
// $current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
// $current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

$ccIdFicha = @$_POST["id_ficha"];
$ccRazon = @$_POST["ccIngresoPagoNLRazon"];
$ccMonto = @$_POST["ccIngresoPagoNLMonto"];
$ccMoneda = @$_POST["ccIngresoPagoNLMoneda"];
$ccFecha = @$_POST["ccIngresoPagoNLFecha"];
$ccTipoMovimientoAbono = @$_POST["ccTipoMovimientoAbono"];

$ccIdresponsable = $_POST["ccIdresponsable"];
$cccta_contable = $_POST["cccta_contable"];



if (strpos($ccMonto, '.')) {
    $ccMonto = str_replace(".", "", $ccMonto);
} else if (strpos($ccMonto, ',')) {
    $ccMonto = str_replace(",", ".", $ccMonto);
}

// Parse the date and time using DateTime
$dateTime = new DateTime($ccFecha);

// Get the date and time separately
date_default_timezone_set("America/Santiago");
$date = $dateTime->format('Y-m-d'); // Date format: YYYY-MM-DD
// $time = $dateTime->format('H:i:s'); // Time format: HH:MM:SS
$time = date("h:i:s");

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

    $queryIdArriendo = "select fa.id, fa.id_propiedad from propiedades.ficha_arriendo fa where fa.token = '$token' ";
    // var_dump($queryIdArriendo);

    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $queryIdArriendo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    // var_dump($resultado);

    $objIdArriendo = json_decode($resultado)[0];
}

// var_dump("current_usuario",$current_usuario );
try {


    if ($ccTipoMovimientoAbono == 1) {


        $queryInsertCcPago = "SELECT propiedades.fn_pago_online($ccMonto, $objIdArriendo->id_propiedad)";
        $dataCab = array("consulta" => $queryInsertCcPago);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

    } else {

        $queryInsertCcPago = "INSERT INTO propiedades.ficha_arriendo_cta_cte_movimientos
        (id_propiedad, id_ficha_arriendo, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, razon,cta_contable, id_responsable)
        VALUES ($objIdArriendo->id_propiedad, $objIdArriendo->id,'$ccFecha', '$time', $ccTipoMovimientoAbono, $ccMonto,'$ccRazon - $ccFecha', $cccta_contable,$ccIdresponsable)";
        $dataCab = array("consulta" => $queryInsertCcPago);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

    }



    echo  "transaccion realizada";
} catch (\Throwable $th) {
    echo $th->getMessage();
}
