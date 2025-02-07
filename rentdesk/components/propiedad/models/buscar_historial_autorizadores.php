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

// Obtener usuario por correo (usuario sesión actual)
$queryUsuario = "SELECT *
    FROM propiedades.cuenta_usuario
    WHERE id_empresa = 1 AND UPPER(correo) = UPPER('$correo')";

$num_pagina =  round(1 / 9999) + 1;
$data = array("consulta" => $queryUsuario, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);

$objUsuario = json_decode($resultado, true)[0];

if (!$objUsuario) {
    echo "Usuario no encontrado";
    exit;
}

// 1. Obtener historial usuarios autorizadores
$queryHistorialAutorizadores = "SELECT * FROM propiedades.historial_autorizadores, propiedades.cuenta_usuario as cu
                                WHERE id_usuario_autorizador = cu.id
                                and id_usuario = {$objUsuario['id']} 
                                and autorizador = true
                                and habilitado = true";

$num_pagina =  round(1 / 9999) + 1;
$dataAutorizadores = array("consulta" => $queryHistorialAutorizadores, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultadoAutorizadores = $services->sendPostNoToken($url_services . '/util/paginacion', $dataAutorizadores, []);

$usuariosHistorialAutorizadores = json_decode($resultadoAutorizadores, true);


if (empty($usuariosHistorialAutorizadores)) {
  echo false;
} else {
  echo json_encode($usuariosHistorialAutorizadores);
}
