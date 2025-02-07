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
// $current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
// $current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

$ccIdFicha = @$_POST["id_ficha"];
$ccRazon = @$_POST["ccIngresoDescAutorizadoRazon"];
$ccMonto = @$_POST["ccIngresoDescAutorizadoMonto"];
$ccMoneda = @$_POST["ccIngresoDescAutorizadoMoneda"];
$ccCobraComision = @$_POST["ccIngresoDescAutorizadoCobraComision"];
$ccFecha = @$_POST["ccIngresoDescAutorizadoFecha"];
$ccTipoMovimiento = @$_POST['ccTipoMovimiento'];
$ccIdResponsable = @$_POST['ccidResponsable'];
$cccta_contableCargo = $_POST['cccta_contableCargo'];



// Parse the date and time using DateTime
$dateTime = new DateTime($ccFecha);


if (strpos($ccMonto, '.')){
    $ccMonto= str_replace(".", "", $ccMonto);
}else if (strpos($ccMonto, ',')){
    $ccMonto= str_replace(",", ".", $ccMonto);
}



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



/************************CONSUNLTA INFO DEL ARRENDATARIO************************************* */

$queryArrendatario = " select va.nombre_1 , va.nombre_2 , va.nombre_3, fa.id  from propiedades.propiedad p 
 inner join propiedades.ficha_arriendo fa  on p.id = fa.id_propiedad 
 left join propiedades.ficha_arriendo_arrendadores faa on faa.id_ficha_arriendo = fa.id 
 left join propiedades.vis_arrendatarios va on va.id = faa.id_arrendatario 
 where p.id =$ccIdFicha and fa.id_estado_contrato =1 ";

$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryArrendatario, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objArrendatario = json_decode($resultado);

$objetoArrendatario = @$objArrendatario[0];
$idFichaArriendo = @$objetoArrendatario->id;


// var_dump("current_usuario",$current_usuario );
if (isset($idFichaArriendo)) {
    $queryInsertCcDescuento = "INSERT INTO propiedades.ficha_arriendo_cta_cte_movimientos
(id_propiedad,id_ficha_arriendo, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, razon, cobro_comision, cta_contable, id_responsable)
 VALUES ($ccIdFicha,$idFichaArriendo,'$date $time', '$time', $ccTipoMovimiento, $ccMonto,'$ccRazon - $ccFecha',$ccCobraComision, $cccta_contableCargo, $ccIdResponsable)";
} else {
    $queryInsertCcDescuento = "INSERT INTO propiedades.ficha_arriendo_cta_cte_movimientos
    (id_propiedad, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, razon, cobro_comision, cta_contable, id_responsable)
     VALUES ($ccIdFicha,'$date $time', '$time', $ccTipoMovimiento, $ccMonto,'$ccRazon - $ccFecha',$ccCobraComision, $cccta_contableCargo, $ccIdResponsable)";
}


$dataCab = array("consulta" => $queryInsertCcDescuento);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

if ($resultadoCab != "OK") {
    echo ",xxx,ERROR,xxx,No se logró ingresar Descuento,xxx,-,xxx,";
    return;
}

echo ",xxx,OK,xxx,Descuento Ingresado Correctamente,xxx,-,xxx,";

