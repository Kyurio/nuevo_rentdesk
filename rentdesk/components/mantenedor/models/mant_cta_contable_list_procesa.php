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



$queryCtaContables = "SELECT * from propiedades.tp_cta_contable tcc where habilitado = true  order by id  desc ";


//var_dump("QUERY HISTORIAL: ", $queryCtaContables);



$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCtaContables, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCtaContable = json_decode($resultado);


// echo json_encode($objCtaContable);


$dataCount = array("consulta" => $queryCtaContables);
$resultadoCount = $services->sendPostNoToken($url_services . '/util/count', $dataCount);
$cantidad_registros = $resultadoCount;

if ($cantidad_registros  != 0) {

  foreach ($objCtaContable as $result) {
    if ($coma == 1)
      $signo_coma = ",";

    $coma = 1;


    $id = $result->id;
    $nombre = $result->nombre;
    $nro_cuenta = $result->nro_cuenta;
    $habilitado = $result->habilitado ?? false;
    $activo = $result->activo ?? false;
    $tipo_movimiento = $result->tipo_movimiento;

  
    // $botones = "";
    $botones = "<div class='d-flex' style='gap: .5rem;'><a data-bs-toggle='modal' data-bs-target='#modalMantenedorEditarCuentaContable' type='button' onclick='cargarCtaContableEditar($id, \\\"$nombre\\\", $nro_cuenta, \\\"$tipo_movimiento\\\", $activo)' class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i></a>";
    $botones = $botones."<button onclick='eliminarCtaContable($id)' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i></button></div>";

    $datos = $datos . "
     $signo_coma
     [
     
      \"$id\",
      \"$nombre\",
      \"$nro_cuenta\",
      \"$habilitado\",
      \"$tipo_movimiento\",
      \"$activo\",
      \"$botones\"

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
