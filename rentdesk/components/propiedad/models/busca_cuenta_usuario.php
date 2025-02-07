<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$existe = 0;
$num_reg = 10;
$inicio = 0;

// Obtener el objeto de sesión y convertirlo en un objeto PHP
$sesion_rd_login = unserialize($_SESSION['sesion_rd_login']);
$correo = $sesion_rd_login->correo;

// 1. Obtener usuario por correo
$queryUsuario = "SELECT *
    FROM propiedades.cuenta_usuario
    WHERE id_empresa = 1 AND UPPER(correo) = UPPER('$correo')";

$num_pagina =  round(1 / 9999) + 1;
$data = array("consulta" => $queryUsuario, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);

$objUsuario = json_decode($resultado, true)[0];

if (!$objUsuario) {
  echo false;
} else {

  echo json_encode($objUsuario);
}
