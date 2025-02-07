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
    $queryInfoArrendatarios = "SELECT pe.token AS token_persona, va.dni, va.nombre_1||' '||va.nombre_2 as nombre_arrendatario ,va.correo_electronico ,va.telefono_fijo,va.telefono_movil, fa.token
    FROM propiedades.ficha_arriendo_arrendadores a, 
    propiedades.vis_arrendatarios va, 
    propiedades.ficha_arriendo fa, 
    propiedades.propiedad p,
	propiedades.persona pe
    WHERE va.id = a.id_arrendatario 
    and fa.id = a.id_ficha_arriendo 
    and p.id  = fa.id_propiedad 
	and va.dni = pe.dni
    and fa.id = $idFicha
    order by fa.id desc";
}



$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryInfoArrendatarios, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objInfoArrendatarios = json_decode($resultado);

echo json_encode($objInfoArrendatarios);
