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


$num_reg = 50;
$inicio = 0;

if (isset($_POST["idFicha"])) {
    $idFicha = $_POST["idFicha"];
    $queryInfoComentarios = "SELECT ficha.*, ficha.comentario as comentario, TO_CHAR(ficha.fecha_comentario,'DD/MM/YYYY HH24:MI:SS')  as fecha_comentario, TO_CHAR(ficha.fecha_comentario,'DD/MM/YYYY HH24:MI:SS')  as fecha_modificacion,
    cu.nombres||' '||cu.apellido_paterno AS nombre_usuario 
    FROM propiedades.ficha_arriendo_comentarios as ficha
    LEFT JOIN propiedades.cuenta_usuario cu ON ficha.id_usuario_modificacion  = cu.id
    WHERE ficha.id_ficha_arriendo = $idFicha
    AND ficha.habilitado = true 
    order by ficha.id";
}



$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryInfoComentarios, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objInfoComentarios = json_decode($resultado);

echo json_encode($objInfoComentarios);
