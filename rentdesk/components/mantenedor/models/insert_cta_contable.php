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

$cta_contable_id_ficha = @$_POST["id_ficha"];
$nombreCtaContable = @$_POST["nombreCtaContable"];
$ctaContableNroCuenta = @$_POST["ctaContableNroCuenta"];
$ctaContableActivo = @$_POST["ctaContableActivo"];
$ctaContableTipo = @$_POST["ctaContableTipo"];



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


// var_dump("current_usuario",$current_usuario );
$queryCtaContable = "INSERT INTO propiedades.tp_cta_contable 
(nombre, nro_cuenta, activo, tipo_movimiento)
 VALUES ('$nombreCtaContable', $ctaContableNroCuenta, $ctaContableActivo, '$ctaContableTipo')";

$dataCab = array("consulta" => $queryCtaContable);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);



if ($resultadoCab != "OK") {
    echo ",xxx,ERROR,xxx,No se logró ingresar cuenta contable,xxx,-,xxx,". $resultadoCab;
    return;
}

echo ",xxx,OK,xxx,Cuenta Contable Ingresada Correctamente,xxx,-,xxx,";
