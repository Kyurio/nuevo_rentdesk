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
$idRol = @$_GET["idRol"];
$idUsuario = @$_GET["id"];


$num_reg = 20;
$inicio = 0;

$query = " SELECT id,nombre,descripcion FROM  propiedades.cuenta_roles where habilitado = true and activo = true and id_tipo_rol = 2  ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);

$opcion_rol = "<option value=''>Seleccione</option>";

foreach ($json as $item) {


	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);
    if ( $item->id == $idRol ){
	$opcion_rol = $opcion_rol . "<option  id='$item->id' value='$item->id'  selected>$item->nombre</option>";	
	//$opcion_seguro = $opcion_seguro . "<option value='$item->id'>$item->nombre_fantasia</option>";
	}else{
	$opcion_rol = $opcion_rol . "<option  id='$item->id' value='$item->id'  >$item->nombre</option>";	
	}
	
}


/*SELECTOR - Estado contrato - MANTENER PARA RENTDESK */
$num_reg =99999;
$inicio = 0;

$query = " SELECT id,nombre FROM  propiedades.cuenta_sucursal where habilitada = true and id_subsidiaria = $id_company order by nombre asc ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
 //var_dump("Arrendatario", $resultado);

$sucursal = "<option value=''>Seleccione</option>";

foreach ($json as $item) {

	$query = " SELECT count(*) as cantidad FROM  propiedades.cuenta_usuario_sucursales where id_usuario = $idUsuario and id_sucursal = $item->id ";
	$cant_rows = $num_reg;

	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		//var_dump($query);
	$jsonUsuario = json_decode($resultado)[0];
	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

	if ($jsonUsuario->cantidad > 0) {
			$sucursal = $sucursal . "<option selected value='$item->id'  >$item->nombre</option>";

	}else{
			$sucursal = $sucursal . "<option  value='$item->id'  >$item->nombre</option>";

	}
}

echo ",xxx,OK,xxx,$opcion_rol,xxx,$sucursal,xxx,";

?>