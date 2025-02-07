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
$idNuevo = "";
$idGrupo = "";
$RolAccesos="";
$sucursales="";

$orden         = "";
if (!empty($_POST["order"][0]["column"]))
  $orden         = @$_POST["order"][0]["column"];

$direccion = "";
if (!empty($_POST["order"][0]["dir"]))
  $direccion = @$_POST["order"][0]["dir"];


$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];

//var_dump($id_company);


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



$querySucursal = " select cs.* from propiedades.cuenta_sucursal cs where  cs.id_subsidiaria = $id_company and habilitada = true order by id desc";

 //var_dump("QUERY HISTORIAL: ", $querySucursal);



$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $querySucursal, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objSucursal = json_decode($resultado);


// echo json_encode($objRol);


$dataCount = array("consulta" => $querySucursal);
$resultadoCount = $services->sendPostNoToken($url_services . '/util/count', $dataCount);
$cantidad_registros = $resultadoCount;

if ($cantidad_registros  != 0) {

  foreach ($objSucursal as $result) {
    if ($coma == 1)
      $signo_coma = ",";

    $coma = 1;


    $id_subsidiaria = $result->id_subsidiaria;
	$id = $result->id;
    $nombre = $result->nombre;
    $casa_matriz = $result->casa_matriz  ?? false;
    $habilitado = $result->habilitada ?? false;
    $activo = $result->activo ?? false;
    // $botones = "";
	
	if ($casa_matriz == false){$casa_matriz = 0;}
	if ($activo == false) {$activo = 0;}
	
	
		$querySucursal_Propiedad = " select count(*) as cantidad from propiedades.propiedad cs where id_sucursal = $id  ";

	//var_dump("QUERY HISTORIAL: ", $querySucursal);
	
	
	
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $querySucursal_Propiedad, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$objSucursal_prop = json_decode($resultado)[0];	

    $botones = "<div class='d-flex' style='gap: .5rem;'><a data-bs-toggle='modal' data-bs-target='#modalMantenedorEditarSucursalEditar' type='button' onclick='cargarSucursalEditar($id,\\\"$nombre\\\",$casa_matriz,$activo)' class='btn btn-info m-0 d-flex' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i></a>";
	
	if ($objSucursal_prop->cantidad != 0){
		$botones = $botones."<button type='button' onclick='avisoSucursal()' class='btn btn-secondary m-0' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i> </button>";
	}else{
	$botones = $botones . "<button onclick='eliminarSucursal($id)' type='button' class='btn btn-danger m-0 d-flex' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i></button></div>";
	}
	


    $datos = $datos . "
     $signo_coma
     [
     
      \"$id\",
      \"$nombre\",
	  \"$casa_matriz\",
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
