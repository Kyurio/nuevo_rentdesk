<?php
session_start();
// include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


// $formData = $_POST["formData"];
// $itemList = $_POST["itemList"];

$itemsJSON = @$_POST["itemData"];
//var_dump("FORMULARIO DATOS: ", $itemsJSON);

$persona	= @$_POST['persona'];
$bancos	= @$_POST['banco'];
$ctaBancos		 = @$_POST['cta-banco'];
$numCuenta = @$_POST['numCuenta'];
$emailTitular = @$_POST['emailTitular'];
// $fono 		 = @$_POST['fono'];
// $celular	 = @$_POST['celular'];
// $email     	 = @$_POST['email'];
// $personalidadLegal	   = @$_POST['tipo_persona_legal'];
// $giro	   = @$_POST['giro'];
// $nombreFantasia	   = @$_POST['nombreFantasia'];
// $razonSocial	   = @$_POST['razonSocial'];
// $estadoCivil	   = @$_POST['estado_civil'];
// $direccion	   = @$_POST['direccion'];
// $comuna	   = @$_POST['comuna'];
// $nroComplemento	   = @$_POST['nroComplemento'];
// $correoElectronico = @$_POST['correoElectronico'];
$token = @$_POST['token'];



// $digitoVerificador	= @$_POST['digitoVerificador'];
// $comuna      = @$_POST['comuna'];
// $direccion   = @$_POST['direccion'];
// $estado_persona = @$_POST['estado_persona'];
// $numCuenta = @$_POST['numCuenta'];
// $banco     = @$_POST['banco'];
// $token	   = @$_POST['token'];

// $comunaCom      = @$_POST['comunacom'];
// $direccionCom   = @$_POST['direccioncom'];

// $id_company 	= $_SESSION["rd_company_id"];
// $id_tipo_persona = 2;

// $id_comuna = explode("|", $comuna);
// $id_comuna = $id_comuna[0];

// $id_comuna_com = explode("|", $comunaCom);
// $id_comuna_com = $id_comuna_com[0];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

// $token_nuevo = md5(rand(99999, 99999999) . $dni . $nombre . date("Y m d H s"));
/*---------------------------- */
/*LLAMADO TABLAS PARAMETRICAS*/
/* TP_BANCO - MANTENER PARA RENTDESK */
// $num_reg = 10;
// $inicio = 0;

// $query = "SELECT * FROM propiedades.tp_banco  where habilitado = true and id = $banco";
// $cant_rows = $num_reg;
// $num_pagina = round($inicio / $cant_rows) + 1;
// $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
// $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
// $datosBanco = json_decode($resultado)[0];

// //var_dump("BANCO ACTUAL: ", $datosBanco);

/* TP_BANCARIA - MANTENER PARA RENTDESK */
// $num_reg = 10;
// $inicio = 0;

// $query = "SELECT * FROM propiedades.tp_cta_bancaria  where habilitado = true and id = $ctaBanco";
// $cant_rows = $num_reg;
// $num_pagina = round($inicio / $cant_rows) + 1;
// $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
// $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
// $datosCtaBanco = json_decode($resultado)[0];

// //var_dump("CUENTA BANCO ACTUAL: ", $datosCtaBanco);

/*---------------------------- */
/*BUSQUEDA PERSONA */
$queryParams = array(
	'token_subsidiaria' => $current_subsidiaria->token,
	'token_persona' => $persona
);

$resultado = $services->sendGet($url_services . '/rentdesk/personas', null, [], $queryParams);

$request = json_decode($resultado)[0];

/*---------------------------- */
/*CREACIÓN/ACTUALIZACIÓN PERSONA */

// foreach ($itemList as $index => $item) {
// 	// Access individual fields of each item
// 	$name = $item["name"];
// 	$email = $item["email"];
// 	// Process the data as needed

// 	echo "name: $name, email: $email<br>";
// }



// for ($i = 0; $i < count($bancos); $i++) {
// 	$banco = $bancos[$i];
// 	$ctaBanco = $ctaBancos[$i];

// 	// Process the data (for example, you can insert it into a database)
// 	// Here, we're just echoing the data for demonstration purposes
// 	echo "banco: $banco, ctaBanco: $ctaBanco<br>";
// }


if (isset($persona) && $persona != "") {

	// Update the request object

	//var_dump("DATOS A ENVIAR PATCH: ", $request);
	//var_dump("----------------------");


	// $infoPropietario = array(
	// 	"propietario" => array(
	// 		"cuentasBancarias" => array(
	// 			array(
	// 				"banco" => $datosBanco,
	// 				"correoElectronico" => $emailTitular,
	// 				"habilitado" => true,
	// 				"id" => 2,
	// 				"numero" => $numCuenta,
	// 				"principal" => true,
	// 				"tipoCuenta" => $datosCtaBanco
	// 			)
	// 		)
	// 	),
	// );

	// Create the request object
	$infoPropietario =  array(
		"propietario" => array(
			"cuentasBancarias" => json_decode($itemsJSON, true)
		),
	);

	// Merge the base request with the conditional part
	$request = array_merge((array)$request, $infoPropietario);

	$request = str_replace('},"propietario"', ',"propietario"', json_encode($request));
	$request = str_replace('Z[UTC]', '', $request);

	//var_dump("DATOS A ENVIAR PATCH MODIFICADOS: ", $request);

	$services->sendPatch($url_services . '/rentdesk/personas', json_decode($request), [], null);

	echo ",xxx,OK,xxx,Persona Actualizada Correctamente,xxx,-,xxx,";
} else {

	// Create the request object
	$infoPropietario =  array(
		"propietario" => array(
			"cuentasBancarias" => json_decode($itemsJSON, true)
		),
	);

	// Merge the base request with the conditional part
	$request = array_merge((array)$request, $infoPropietario);

	$request = str_replace('},"propietario"', ',"propietario"', json_encode($request));
	$request = str_replace('Z[UTC]', '', $request);


	// //var_dump("RESULTADO: ", $resultado);
	//var_dump("DATOS A ENVIAR POST: ", $request);
	//var_dump("----------------------");

	$services->sendPost($url_services . '/rentdesk/personas', json_decode($request), [], null);

	echo ",xxx,OK,xxx,Persona Ingresada Correctamente,xxx,-,xxx,";
}

//var_dump("FORMULARIO DATOS: ", $itemsJSON);
//***********************************************************************************************************
