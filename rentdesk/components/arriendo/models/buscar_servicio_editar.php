<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$tipo_servicio = @$_POST["tipo_servicio"];
$id_proveedor = @$_POST["id_proveedor"];
$id_tipo_servicio = @$_POST["id_tipo_servicio"];

$num_reg = 20;
$inicio = 0;

$query = " SELECT nombre, id FROM propiedades.tp_tipo_servicio  --where tipo_servicio = '$tipo_servicio'  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);

$opcion_seguro = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
    if ( $item->id == $id_tipo_servicio ){
	$opcion_seguro = $opcion_seguro . "<option  id='$item->id' value='$item->id'  selected>$item->nombre</option>";	
	//$opcion_seguro = $opcion_seguro . "<option value='$item->id'>$item->nombre_fantasia</option>";
	}else{
	$opcion_seguro = $opcion_seguro . "<option  id='$item->id' value='$item->id'  >$item->nombre</option>";	
	}
	
}

 // ********* Proveedor *************
$query = " SELECT nombre_fantasia,id FROM propiedades.tp_proveedor  where id_servicio = '$id_tipo_servicio'  ";

$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);

$opcion_proveedor = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
    if ( $item->id == $id_proveedor ){
	$opcion_proveedor = $opcion_proveedor . "<option  id='$item->id' value='$item->id'  selected>$item->nombre_fantasia</option>";	
	//$opcion_seguro = $opcion_seguro . "<option value='$item->id'>$item->nombre_fantasia</option>";
	}else{
	$opcion_proveedor = $opcion_proveedor . "<option  id='$item->id' value='$item->id'  >$item->nombre_fantasia</option>";	
	}
	//echo ",xxx,OK,xxx,-,xxx,proveedor,xxx,$opcion_proveedor";
}

echo ",xxx,OK,xxx,$opcion_seguro,xxx,seguro,xxx,$opcion_proveedor";


//echo ",xxx,OK,xxx,$opcion_seguro,xxx,seguro,xxx,";
//echo json_encode($objServicios);


?>