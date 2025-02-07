<?php
@include("../../includes/sql_inyection.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$current_sucursal = unserialize($_SESSION["rd_current_sucursal"]);

$_SESSION["sesion_rd_current_propiedad_token"] = null;

echo "";
//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=propiedad&view=propiedad_list");
if (isset($nav)) {
	$nav = "index.php?" . decodifica_navegacion($nav);
} else {
	$nav = "index.php?component=propiedad&view=propiedad_list";
}
//************************************************************************************************************


/*SELECTOR - Estado contrato - MANTENER PARA RENTDESK */
$num_reg = 9999;
$inicio = 0;

$query = " SELECT id,nombre,descripcion FROM  propiedades.cuenta_roles where habilitado = true and activo = true and id_tipo_rol = 2 order by nombre asc ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
 //var_dump("Arrendatario", $resultado);

$tipo_rol = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$tipoRol = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

	$tipo_rol = $tipo_rol . "<option  value='$item->id'  >$item->nombre</option>";
}

$tipo_rol = "<select id='tipoRol' name='tipoRol' class='form-control' data-select2-id='tipoRol'>
$tipo_rol
</select>";


/*SELECTOR - Estado contrato - MANTENER PARA RENTDESK */
$num_reg =99999;
$inicio = 0;

$query = " SELECT id,nombre,descripcion FROM  propiedades.cuenta_roles where habilitado = true and activo = true and id_tipo_rol = 2 order by nombre asc";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
 //var_dump("Arrendatario", $resultado);

$tipo_rol = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$tipoRol = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

	$tipo_rol = $tipo_rol . "<option  value='$item->id'  >$item->nombre</option>";
}

$tipo_rol = "<select id='tipoRol' name='tipoRol' class='form-control' data-select2-id='tipoRol'>
$tipo_rol
</select>";




/*SELECTOR - Estado contrato - MANTENER PARA RENTDESK */
$num_reg =99999;
$inicio = 0;

$query = " SELECT id,nombre FROM  propiedades.cuenta_sucursal where habilitada = true and id_subsidiaria = $current_subsidiaria->id order by nombre asc ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);
 //var_dump("Arrendatario", $resultado);

$sucursal = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$tipoRol = "";

	// var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

	$sucursal = $sucursal . "<option  value='$item->id'  >$item->nombre</option>";
}

$sucursal = "<select class='form-control js-example-responsive' name='sucursal[]' id='sucursal' multiple='multiple'>
$sucursal
</select>";

