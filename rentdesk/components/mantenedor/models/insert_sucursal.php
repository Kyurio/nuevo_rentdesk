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


$component = @$_POST["component"];
$view = @$_POST["view"];
$token = @$_POST["token"];
$item = @$_POST["item"];
$id_recurso = @$_POST["id_recurso"];
$id_item = @$_POST["id_item"];
// Obtener el objeto de sesión y convertirlo en un objeto PHP
$sesion_rd_login = unserialize($_SESSION['sesion_rd_login']);
// Acceder a la dirección de correo electrónico

$num_reg = 10;
$inicio = 0;





$sucursalNombre=$_POST["sucursalNombre"];
$sucursalMatriz = @$_POST["sucursalMatriz"];
$sucursalActivo = @$_POST["sucursalActivo"];
// $tokenRegistro = $_POST["CtaContableToken"];

if($sucursalMatriz != "on"){
	$sucursalMatriz = false;
	var_dump($sucursalMatriz);
}else{
	$sucursalMatriz = true;
}

if($sucursalActivo != "on"){
	$sucursalActivo = false;
	
}else{
	$sucursalActivo = true;
}

// Validamos que correo no exista previamente
/*  Validacion?
$queryUsuario= " SELECT count(*) as cantidad
from  propiedades.cuenta_usuario
where id_empresa = 1  and UPPER(correo) = UPPER('$usuarioCorreo')
";

$num_pagina = round(1 / 9999) + 1;
$data = array("consulta" => $queryUsuario, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objUsuario = json_decode($resultado)[0];

if ($objUsuario->cantidad > 0) {
    echo ",xxx,ERROR,xxx,Ya se encuentra el usuario registrado en sistema,xxx,-,xxx,";
    return;
}
*/

// var_dump("current_usuario",$current_usuario );
var_dump($sucursalActivo);
$querySucursal = "INSERT INTO propiedades.cuenta_sucursal 
( id_subsidiaria, nombre, casa_matriz, activo,habilitada)
VALUES( $id_company, '$sucursalNombre', ". ($sucursalMatriz ? 'true' : 'false') ." ,". ($sucursalActivo ? 'true' : 'false') .", true) ";

var_dump($$querySucursal);
$dataCab = array("consulta" => $querySucursal);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

if ($resultadoCab != "OK") {
    echo ",xxx,ERROR,xxx,No se logró ingresar Usuario,xxx,-,xxx,";
    return;
}



echo ",xxx,OK,xxx,Usuario Ingresado Correctamente,xxx,-,xxx,";
