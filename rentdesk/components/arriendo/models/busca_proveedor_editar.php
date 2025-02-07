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
$TipoServicioSeguro = @$_POST["TipoEditarSeguro"];
$TipoServicio = @$_POST["TipoServicioEditar"];
$opcion_proveedor_seguro = "";
$opcion_proveedor_servicio = "";



$num_reg = 20;
$inicio = 0;

//if($TipoServicio != null && $TipoServicio != ""){
	$queryProveedor = "SELECT id,nombre_fantasia from PROPIEDADES.tp_proveedor 
	";

$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryProveedor, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


$opcion_proveedor_servicio = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
    $opcion_proveedor_servicio = $opcion_proveedor_servicio . "<option value='$item->id'>$item->nombre_fantasia</option>";
	//$opcion_proveedor_servicio = $opcion_proveedor_servicio . "<option  id='$item->id' value='$item->id' $select_propiedad  >$item->nombre_fantasia</option>";
}
/*
$opcion_proveedor_servicio = "<select id='TipoProveedorServicio' name='TipoProveedorServicio' class='form-control '  data-select2-id='TipoProveedorServicio' >
$opcion_proveedor_servicio
</select>";
*/

//}

if($TipoServicioSeguro != null && $TipoServicioSeguro != ""){
	$queryProveedor = " select id,nombre_fantasia from PROPIEDADES.tp_proveedor 
	where id_servicio = $TipoServicioSeguro ";

	
	//$queryDocumento = " select * from propiedades.propiedad_archivos where estado = true order by id desc";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryProveedor, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


$opcion_proveedor_seguro = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_propiedad = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
    $opcion_proveedor_seguro = $opcion_proveedor_seguro . "<option value='$item->id'>$item->nombre_fantasia</option>";
	//$opcion_proveedor_seguro = $opcion_proveedor_seguro . "<option  id='$item->id' value='$item->id' $select_propiedad  >$item->nombre_fantasia</option>";
}
/*
$opcion_proveedor_seguro = "<select id='TipoProveedorSeguro' name='TipoProveedorSeguro' class='form-control '  data-select2-id='TipoProveedorSeguro' >
$opcion_proveedor_seguro
</select>";
*/
//echo $opcion_proveedor_seguro;
//echo ",xxx,OK,xxx,$opcion_proveedor_seguro,xxx,seguro,xxx,";
}

echo ",xxx,OK,xxx,$opcion_proveedor_servicio,xxx,$opcion_proveedor_seguro,xxx,";
//echo json_encode($objServicios);


?>