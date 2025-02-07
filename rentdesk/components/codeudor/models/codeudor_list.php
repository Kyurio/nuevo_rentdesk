<?php

@include("../../includes/sql_inyection.php");
@include("../../configuration.php");
@include("../../../includes/funciones.php");
@include("../../../includes/services_util.php");
echo "";
//************************************************************************************************************

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

$_SESSION["sesion_rd_current_propiedad_token"] = null;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token	= @$_GET["token"];

//************************************************************************************************************
//proceso para las navegaciones
$nav	= @$_GET["nav"];
$pag_origen = codifica_navegacion("component=propiedad&view=propiedad&token=$token&nav=$nav");

if (isset($nav)) {
	$nav = "index.php?" . decodifica_navegacion($nav);
} else {
	$nav = "index.php?component=propiedad&view=propiedad_list";
}


//************************************************************************************************************


/*para mejorar el rendimiento el servicio hace una ejecucion directa a la BBDD
es por esto que este objeto en particular debe utilizar los atributos tal como
estan creados en la base de datos y no como objetos
*/
// $result = null;
// $data = array("token" => $token, "idEmpresa" => $id_company);
// $resultado = $services->sendPostNoToken($url_services . '/propiedad/token', $data);
// if ($resultado) {
// 	$result_json = json_decode($resultado);
// 	foreach ($result_json as $result_r) {
// 		$result = $result_r;
// 	} //foreach($result_json as $result)
// }
//************************************************************************************************************


/*---------------------------- */
/*LLAMADO TABLAS PARAMETRICAS*/
/*TIPO PERSONA

$num_reg = 10;
$inicio = 0;

$query = "SELECT id, nombre, descripcion FROM propiedades.tp_tipo_persona";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objTipoPersona = json_decode($resultado);

/*---------------------------- */
/*TIPO DNI

$num_reg = 10;
$inicio = 0;

$query = "SELECT id, nombre, descripcion FROM propiedades.tp_tipo_dni";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objTipoDni = json_decode($resultado);
/*---------------------------- */

/*PRUEBA OBTENCIÓN LISTADO ARRENDATARIOS */
/*Consulta Cantidad de registros*/
// $query_count = "SELECT * FROM propiedades.vis_codeudores";

// $data = array("consulta" => $query_count);
// $resultado = $services->sendPostNoToken($url_services . '/util/count', $data);
// $cantidad_registros = $resultado;

// //var_dump("CANTIDAD CODEUDORES: ", json_decode($cantidad_registros));

// $num_reg = 10;
// $inicio = 0;

// $query = "SELECT * FROM propiedades.vis_codeudores";
// $cant_rows = $num_reg;
// $num_pagina = round($inicio / $cant_rows) + 1;
// $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
// $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
// $json = json_decode($resultado);


/*BUSQUEDA CON FILTRO LISTA CODEUDORES 
$filtro_nombre = $_POST["filtro_nombre"] ?? null;
$filtro_correo = $_POST["filtro_correo"] ?? null;
$filtro_dni = $_POST["filtro_dni"] ?? null;

// //var_dump("FILTROS: ", $filtro_nombre, $filtro_correo, $filtro_dni);


$num_reg = 99999;
$inicio = 0;

$query = "SELECT vc.*
FROM propiedades.vis_codeudores vc
WHERE vc.token_subsidiaria in (
	SELECT token_subsidiaria FROM propiedades.fn_subsidiarias_por_usuario(
		'$current_usuario->token',
		'$current_subsidiaria->token'
	)
)";

$whereConditions = [];

if (isset($filtro_nombre) && $filtro_nombre !== "") {
	$whereConditions[] = "(LOWER(nombre_1) LIKE LOWER('%$filtro_nombre%') 
        OR LOWER(nombre_2) LIKE LOWER('%$filtro_nombre%')
        OR LOWER(nombre_3) LIKE LOWER('%$filtro_nombre%'))";
}

if (isset($filtro_dni) && $filtro_dni !== "") {
	$whereConditions[] = "dni = '$filtro_dni'";
}

if (isset($filtro_correo)  && $filtro_correo !== "") {
	$whereConditions[] = "correo = '$filtro_correo'";
}

if (!empty($whereConditions)) {
	$query .= " AND " . implode(" OR ", $whereConditions);
}


// //var_dump("QUERY ACTUAL: ", $query);
// //var_dump("FILTRO DNI: ", $filtro_dni);
// //var_dump("FILTRO NOMBRE: ", $filtro_nombre);
// //var_dump("FILTRO CORREO: ", $filtro_correo);

$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


$dataTableCodeudores = [];

// //var_dump("LISTA TIPO PERSONAS: ", $objTipoPersona);
if ($json !== null) {
	// Iterate over each object in the array
	foreach ($json as $obj) {
		// Transform each field of the object

		$currentTipoPersona = current(array_filter($objTipoPersona, function ($cobj) use ($obj) {
			return $cobj->id == $obj->id_tipo_persona;
		}));
		$currentTipoDoc = current(array_filter($objTipoDni, function ($cobj) use ($obj) {
			return $cobj->id == $obj->id_tipo_dni;
		}));

		$transformedObj = [
			'id' => validateNull($obj->id ?? null),
			'token_persona' => validateNull($obj->token_persona ?? null),
			'token_codeudor' => validateNull($obj->token_codeudor ?? null),
			'nombre_1' => validateNull($obj->nombre_1 ?? null),
			'nombre_2' => validateNull($obj->nombre_2 ?? null),
			'nombre_3' => validateNull($obj->nombre_3 ?? null),
			'dni' => validateNull($obj->dni ?? null),
			'tipo_doc' => validateNull($currentTipoDoc->nombre ?? null),
			'tipo_persona' => validateNull($currentTipoPersona->nombre ?? null),
			'ficha_tecnica' => validateNull($obj->ficha_tecnica ?? null)


		];

		// Push the transformed object into the array
		$dataTableCodeudores[] = $transformedObj;
	}
}

function validateNull($item)
{
	return is_null($item) || $item === "" ? "-" : $item;
}

function redirectToCodeudorUrl($token)
{

	$addTokenQuery = "&token=" . urlencode($token);



	$redirectURL = "index.php?component=codeudor&view=codeudor" . $addTokenQuery;


	return $redirectURL;
}

function redirectToCreatePropiedad()
{

	$redirectURL = "index.php?component=codeudor&view=codeudor";

	return $redirectURL;
}
//************************************************************************************************************

$opcion_tipo_propiedad = "<option value=''>Seleccione</option>";
// $data_tipo_propiedad = array("idEmpresa" => $id_company);
// $resp_tipo_propiedad = $services->sendPostNoToken($url_services . '/tipoPropiedad/listaByEmpresa', $data_tipo_propiedad);
// $tipo_propiedads = json_decode($resp_tipo_propiedad);

// foreach ($tipo_propiedads as $tipo_propiedad_r) {

// 	$select_tipo_propiedad = "";
// 	if (@$result->id_tipo_propiedad == @$tipo_propiedad_r->idTipoPropiedad)
// 		$select_tipo_propiedad = " selected ";


// 	$opcion_tipo_propiedad = $opcion_tipo_propiedad . "<option value='$tipo_propiedad_r->idTipoPropiedad' $select_tipo_propiedad >$tipo_propiedad_r->descripcion</option>";
// } //foreach($roles as $rol)

$opcion_tipo_propiedad = "<select id='tipo_propiedad' name='tipo_propiedad' class='form-control' required >
$opcion_tipo_propiedad
</select>";


// $dataTableCodeudores = array(
// 	array("123456789", "ginaguerra@fuenzalida.com", "Departamento", "INVERSIONES ORION LTDA.", "Metropolitana", "Cerrillos", "-", "840", "203", "Vigente", "-", "-", "-", "#79653"),
// 	array("123456789", "ginaguerra@fuenzalida.com", "Departamento", "INVERSIONES ORION LTDA.", "Metropolitana", "Cerrillos", "-", "840", "203", "Vigente", "-", "-", "-", "#75959"),
// 	array("123456789", "ginaguerra@fuenzalida.com", "Departamento", "INVERSIONES ORION LTDA.", "Metropolitana", "Cerrillos", "-", "840", "203", "Vigente", "-", "-", "-", "#79951"),
// 	array("123456789", "ginaguerra@fuenzalida.com", "Departamento", "ALBERTO JAVIER MORDOJ ARENAS", "Metropolitana", "Cerrillos", "-", "840", "203", "Vigente", "-", "-", "-", "#83334"),
// 	array("123456789", "macarenaibaceta@fuenzalida.com", "Departamento", "MANUEL DAVID LEIVA SOTO", "Metropolitana", "Cerrillos", "-", "840", "203", "Vigente", "-", "-", "-", "#77148"),
// );

//************************************************************************************************************
*/