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

$Comentario_id_ficha = @$_POST["id_ficha"];
$Comentario_comentario = @$_POST["ComentarioIngreso"];
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



// var_dump("current_usuario",$current_usuario );
$queryComentario = "INSERT INTO propiedades.propiedad_comentarios
(id_usuario, id_propiedad, comentario)
 VALUES ($objUsuarioId->id, $Comentario_id_ficha,'$Comentario_comentario')";

var_dump("QUETRY COMENTARIO: ", $queryComentario);
$dataCab = array("consulta" => $queryComentario);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

 var_dump("resultadoCab: ", $resultadoCab);

if ($resultadoCab != "OK") {
    echo ",xxx,ERROR,xxx,No se logró ingresar Comentario,xxx,-,xxx,";
    return;
}

echo ",xxx,OK,xxx,Comentario Ingresado Correctamente,xxx,-,xxx,";
//echo "insert ".$textoDiferencia." ".$tipoRegistro." ".$component." ".$view." ".$token;
