<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config = new Config;
$services = new ServicesRestful;
$url_services = $config->url_services;

@$inicio = $_POST["start"] ?: 0;
@$num_reg = $_POST["length"] ?: 99999;

$draw = @$_POST["draw"];
$busqueda = @$_POST["search"]["value"];

$cantidad_registros = 0;
$datos = [];

// Verifica si se pasó el idFicha
$idFicha = isset($_GET["idFicha"]) ? $_GET["idFicha"] : null;

// Construcción de la consulta
$queryHistorial = "SELECT * FROM propiedades.historial";
if ($idFicha) {
    $queryHistorial .= " WHERE components = 'propiedad' AND id_recurso = $idFicha";
}
$queryHistorial .= " ORDER BY id DESC";

// Preparación de la paginación
$num_pagina = round($inicio / $num_reg) + 1;
$data = ["consulta" => $queryHistorial, "cantRegistros" => $num_reg, "numPagina" => $num_pagina];
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objHistorial = json_decode($resultado);

// Obtener la cantidad total de registros
$dataCount = ["consulta" => $queryHistorial];
$resultadoCount = $services->sendPostNoToken($url_services . '/util/count', $dataCount);
$cantidad_registros = $resultadoCount;

// Procesar el historial si hay registros
if (!empty($objHistorial)) {
    foreach ($objHistorial as $result) {
        $datos[] = [
            $result->fecha,
            $result->responsable,
            $result->accion,
            $result->item,
            $result->id_item,
            $result->descripcion
        ];
    }
}

// Respuesta JSON
echo json_encode([
    "draw" => (int)$draw,
    "recordsTotal" => (int)$cantidad_registros,
    "recordsFiltered" => (int)$cantidad_registros,
    "data" => $datos
]);