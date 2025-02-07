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

// 1. Obtener usuarios autorizadores
$queryAutorizadores = "SELECT *
    FROM propiedades.cuenta_usuario
    WHERE id_empresa = 1 
    AND autorizador = true 
    and habilitado = true";

$num_pagina =  round(1 / 9999) + 1;
$dataAutorizadores = array("consulta" => $queryAutorizadores, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultadoAutorizadores = $services->sendPostNoToken($url_services . '/util/paginacion', $dataAutorizadores, []);

$usuariosAutorizadores = json_decode($resultadoAutorizadores, true);


if (empty($usuariosAutorizadores)) {
  echo json_encode(false);
} else {
  echo json_encode($usuariosAutorizadores);
}
