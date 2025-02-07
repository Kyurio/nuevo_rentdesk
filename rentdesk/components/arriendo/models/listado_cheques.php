<?php

// bruno

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
    $queryCheques = "SELECT 
    arriendo.id_propiedad as id_propiedad,
    ficha.fecha_cobro as fecha_cobro, ficha.razon as razon, ficha.monto,
    ficha.monto as monto, banco.nombre as nombre, ficha.girador as girador, ficha.id as id, 
    ficha.numero_documento as numero_documento, ficha.cantidad as cantidad, ficha.banco as banco,
    ficha.desposito as desposito, ficha.cobrar as cobrar, ficha.token as token, ficha.comentario as comentario
    FROM propiedades.ficha_arriendo_cheques as ficha
    INNER JOIN propiedades.tp_banco as banco
    ON ficha.banco = banco.id 
    INNER JOIN propiedades.ficha_arriendo as arriendo
    ON arriendo.id = $idFicha
    where ficha.habilitado=true
    and id_ficha_arriendo = $idFicha
    order by ficha.id";
}



$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCheques, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCheques = json_decode($resultado);

echo json_encode($objCheques);
