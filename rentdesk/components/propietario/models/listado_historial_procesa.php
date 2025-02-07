<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
@$inicio        = $_POST["start"];
@$num_reg        = $_POST["length"];
@$num_reg_principal        = $_POST["length"];

$draw            = @$_POST["draw"];
$inicio            = @$_POST["start"];
@$fin            = @$_POST["length"];
$busqueda         = @$_POST["search"]["value"];

$cantidad_filtrados = 0;
$cantidad_registros = 0;


$orden         = "";
if (!empty($_POST["order"][0]["column"]))
    $orden         = @$_POST["order"][0]["column"];

$direccion = "";
if (!empty($_POST["order"][0]["dir"]))
    $direccion = @$_POST["order"][0]["dir"];


$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];


$coma = 0;
$signo_coma = "";
$datos        = "";

if ($inicio == "") {
    $inicio = 0;
}
if ($num_reg == "") {
    $num_reg = 99999;
}

$cant_rows = $num_reg;


// aqui modifico jose hernandez, agrege el filtro por
if (isset($_GET["idFicha"])) {
    $idFicha = $_GET["idFicha"];
    //$queryHistorial = "select  * from propiedades.historial h where id_recurso = $idFicha  order by id  desc ";
    $queryHistorial = "select  * from propiedades.historial where components = 'propietario' order by id  desc ";
}

// var_dump("QUERY HISTORIAL: ", $queryHistorial);



$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryHistorial, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objHistorial = json_decode($resultado);


// echo json_encode($objHistorial);


$dataCount = array("consulta" => $queryHistorial);
$resultadoCount = $services->sendPostNoToken($url_services . '/util/count', $dataCount);
$cantidad_registros = $resultadoCount;

if ($cantidad_registros  != 0) {

    foreach ($objHistorial as $result) {
        if ($coma == 1)
            $signo_coma = ",";

        $coma = 1;


        $fecha = $result->fecha;
        $responsable = $result->responsable;
        $accion = $result->accion;
        $item = $result->item;
        $id_item = $result->id_item;
        $descripcion = $result->descripcion;


        $datos = $datos . "
     $signo_coma
     [
     
      \"$fecha\",
      \"$responsable\",
      \"$accion\",
      \"$item\",
      \"$id_item\",
      \"$descripcion\"

    ]";
    }

    echo "
{
  \"draw\": 1,
  \"recordsTotal\": $cantidad_registros,
  \"recordsFiltered\": $cantidad_registros,
  \"data\": [
    $datos
  ]
}";
} else {
    echo "
{
  \"draw\": 0,
  \"recordsTotal\": 0,
  \"recordsFiltered\": 0,
  \"data\": [
    $datos
  ]
}";
}
