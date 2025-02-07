<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

@include("../../includes/sql_inyection.php");
@include("../../configuration.php");
@include("../../../includes/funciones.php");
@include("../../../includes/services_util.php");
@include("../../components/propietario/js/js.js");

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$token =  $_GET["token"] ?? null;

//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
if (isset($nav)) {
	$nav = "index.php?" . decodifica_navegacion($nav);
} else {
	$nav = "index.php?component=propietario&view=propietario_list";
}
//************************************************************************************************************

// //var_dump("TOKEN: ", $token);
/*EN CASO DE EXISTIR TOKEN, SE TRAE LA INFORMACIÓN DE PROPIETARIO RELACIONADA */
$resultPropietario = null;
$result = null;

if (isset($token)) {

	/*PRUEBA OBTENCIÓN LISTADO PROPIETARIOS */
	$num_reg = 10;
	$inicio = 0;

	$query = "SELECT * FROM propiedades.vis_propietarios where token_propietario = '$token'";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$resultPropietario = json_decode($resultado)[0];

	// //var_dump("PERSONA POR TOKEN: ", $result);
}

if (isset($resultPropietario->token_persona)) {

	/*BUSQUEDA PERSONA POR TOKEN EN PROPIETARIO */
	$queryParams = array(
		'token_subsidiaria' => $current_subsidiaria->token,
		"token_persona" => $resultPropietario->token_persona
	);

	$resultado = $services->sendGet($url_services . '/rentdesk/personas', null, [], $queryParams);

	$result = json_decode($resultado)[0];

	// //var_dump("PERSONA POR PROPIETARIO: ", $resultado);
}



// $id_company = $_SESSION["rd_company_id"];
// $id_usuario = $_SESSION["rd_usuario_id"];
// $token	= @$_GET["token"];
// $id_tipo_persona = 2;

// $data = array("token" => $token, "idEmpresa" => $id_company, "idTipoPersona" => $id_tipo_persona);
// $resultado = $services->sendPostNoToken($url_services . '/persona/token', $data);
// $result = json_decode($resultado);

//************************************************************************************************************

$opcion_tipo_documento = "<option value=''>Seleccione</option>";
// $data_tipo_documento = array("idEmpresa" => $id_company);
// $resp_tipo_documento = $services->sendPostNoToken($url_services . '/tipoDocumento/listaByEmpresa', $data_tipo_documento);
// $tipo_documentos = json_decode($resp_tipo_documento);

// foreach ($tipo_documentos as $tipo_documento_r) {

// 	$select_tipo_documento = "";
// 	if (@$result->tipoDocumento->idTipoDocumento == @$tipo_documento_r->idTipoDocumento)
// 		$select_tipo_documento = " selected ";


// 	$opcion_tipo_documento = $opcion_tipo_documento . "<option value='$tipo_documento_r->idTipoDocumento' $select_tipo_documento >$tipo_documento_r->descripcion</option>";
// } //foreach($roles as $rol)

$opcion_tipo_documento = "<select id='tipo_documento' name='tipo_documento' class='form-control' required >
$opcion_tipo_documento
</select>";

//************************************************************************************************************
$disabled_estado = "";
// if (!$token == "") {
// 	/*Verifica si tiene el permiso para editar el estado*/
// 	$query_count = "select 1 
// 					from arpis.usuario u,
// 						 arpis.menu_rol mr,
// 						 arpis.menu m
// 					where u.id_usuario = $id_usuario
// 					and mr.id_rol = u.id_rol
// 					and m.id_menu = mr.id_menu
// 					and m.ref_externa = 'PROPIETARIO' ";

// 	$data = array("consulta" => $query_count);
// 	$resultado = $services->sendPostNoToken($url_services . '/util/count', $data);
// 	$cantidad_registros = $resultado;

// 	if (!$cantidad_registros) {
// 		$disabled_estado = "disabled";
// 	} else {
// 		if ($cantidad_registros > 0) {
// 			$disabled_estado = "";
// 		} else {
// 			$disabled_estado = "disabled";
// 		}
// 	}
// }

$opcion_estado_persona = "<option value=''>Seleccione</option>";
// $data_estado_persona = array("idEmpresa" => $id_company);
// $resp_estado_persona = $services->sendPostNoToken($url_services . '/estadoPersona/listaByEmpresa', $data_estado_persona);
// $estado_personas = json_decode($resp_estado_persona);

// foreach ($estado_personas as $estado_persona_r) {

// 	$select_estado_persona = "";
// 	if (@$result->estadoPersona->idEstadoPersona == @$estado_persona_r->idEstadoPersona)
// 		$select_estado_persona = " selected ";


// 	$opcion_estado_persona = $opcion_estado_persona . "<option value='$estado_persona_r->idEstadoPersona' $select_estado_persona >$estado_persona_r->descripcion</option>";
// } //foreach($roles as $rol)

$opcion_estado_persona = "<select id='estado_persona' name='estado_persona' $disabled_estado class='form-control' required >
$opcion_estado_persona
</select>";


//************************************************************************************************************

/*SELECTOR - BANCO - MANTENER PARA RENTDESK */
$query = "SELECT * FROM propiedades.tp_banco  where habilitado = true";
$data = array("consulta" => $query );	
$resultado  = $services->sendPostDirecto($url_services.'/util/objeto',$data);

$json = json_decode($resultado);


// //var_dump("BANCO", $resultado);


$opcion_banco = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_banco = "";

	// //var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

	if (@$result->propietario->cuentasBancarias[0]->banco->id == @$item->id) {
		$select_banco = " selected ";
	}
	$opcion_banco = $opcion_banco . "<option value='$item->id' $select_banco >$item->nombre</option>";
}

$opcion_banco = "<select id='banco' name='banco' class='form-control' required >
$opcion_banco
</select>";

//************************************************************************************************************
/*SELECTOR - CUENTA BANCO - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = "SELECT * FROM propiedades.tp_cta_bancaria where habilitado = true";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


// //var_dump("CUENTA BANCO", $resultado);


$opcion_cta_banco = "<option value=''>Seleccione</option>";
// //var_dump("RESULT DESDE SELECT: ", $result);
foreach ($json as $item) {
	$select_cta_banco = "";

	if (@$result->propietario->cuentasBancarias[0]->tipoCuenta->id == @$item->id) {
		$select_cta_banco = " selected ";
	}
	$opcion_cta_banco = $opcion_cta_banco . "<option value='$item->id' $select_cta_banco >$item->nombre</option>";
}

$opcion_cta_banco = "<select id='cta-banco' name='cta-banco' class='form-control' required >
$opcion_cta_banco
</select>";

//************************************************************************************************************

$opcion_tipo_persona_legal = "<option value=''>Seleccione</option>";
$select_tipo_persona_legal = "";
if (@$result->personalidadLegal == "NAT") {
	$select_tipo_persona_legal = " selected ";
}
$opcion_tipo_persona_legal = $opcion_tipo_persona_legal . "<option value='NAT' $select_tipo_persona_legal >Natural</option>";
$select_tipo_persona_legal = "";
if (@$result->personalidadLegal == "JUD") {
	$select_tipo_persona_legal = " selected ";
}
$opcion_tipo_persona_legal = $opcion_tipo_persona_legal . "<option value='JUD' $select_tipo_persona_legal >Jurídica</option>";

$opcion_tipo_persona_legal = "<select id='tipo_persona_legal' name='tipo_persona_legal' class='form-control' required >
$opcion_tipo_persona_legal
</select>";

//************************************************************************************************************
/*SELECTOR - PERSONA - MANTENER PARA RENTDESK */
/*BUSQUEDA LISTA PERSONAS */
$queryParams = array(
	'token_subsidiaria' => $current_subsidiaria->token
);

$resultado = $services->sendGet($url_services . '/rentdesk/personas', null, [], $queryParams);

$json = json_decode($resultado);

$opcion_persona = "<option value=''>Seleccione</option>";
$selected_persona = "";

foreach ($json as $item) {
	$select_persona = "";

	if (@$resultPropietario->token_persona == @$item->token) {
		$select_persona = " selected";
		$selected_persona = @$item->id;
	}

	$opcion_persona = $opcion_persona . "<option value='$item->token' $select_persona >$item->id - $item->token</option>";
}

$readOnlyAttr = !empty($selected_persona) ? "readonly" : ""; // Check if a value is selected


$opcion_persona = "<select id='persona' name='persona' class='form-control' required onchange='onChangePersona(this.value);' $readOnlyAttr>
$opcion_persona
</select>";



$comuna = @$result->comuna->idComuna;
$region = @$result->comuna->region->idRegion;
$pais = @$result->comuna->region->pai->idPais;

$comunaCom = @$result->comunaCom->idComuna;
$regionCom = @$result->comunaCom->region->idRegion;
$paisCom = @$result->comunaCom->region->pai->idPais;
