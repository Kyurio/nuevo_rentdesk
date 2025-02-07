<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$token =  $_GET["token"] ?? null;




/*EN CASO DE EXISTIR TOKEN, SE TRAE LA INFORMACIÓN DE PERSONA RELACIONADA */
$result = null;

if (isset($token)) {
	$queryParams = array(
		'token_subsidiaria' => $current_subsidiaria->token,
		'token_persona' => $token
	);

	$resultado = $services->sendGet($url_services . '/rentdesk/personas', null, [], $queryParams);

	@$result = json_decode($resultado)[0];


	if (isset($result)) {
	} else {
		echo "<script>window.location.href = 'index.php?component=persona&view=persona';</script>";
	}
	// //var_dump("PERSONA POR TOKEN: ", $result);
}




// $id_company = $_SESSION["rd_company_id"];
// $id_usuario = $_SESSION["rd_usuario_id"];
// $token	= @$_GET["token"];
// $id_tipo_persona = 2;

// // $data = array("token" => $token, "idEmpresa" => $id_company, "idTipoPersona" => $id_tipo_persona);
// // $resultado = $services->sendPostNoToken($url_services . '/persona/token', $data);
// // $result = json_decode($resultado);

// //************************************************************************************************************

$opcion_tipo_documento = "<option value=''>Seleccione</option>";
/*SELECTOR - TIPO DOCUMENTO - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = "SELECT id, nombre, descripcion FROM propiedades.tp_tipo_dni";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);



$opcion_tipo_documento = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_tipo_documento = "";

	if (@$result->tipoDni->id == @$item->id) {
		$select_tipo_documento = " selected ";
	}

	$opcion_tipo_documento = $opcion_tipo_documento . "<option value='$item->id' $select_tipo_documento >$item->nombre</option>";
}


$opcion_tipo_documento = "<select id='tipo_documento' name='tipo_documento' class='form-control  form-select' onchange='valdidarTipoDni()' required >
$opcion_tipo_documento
</select>";


/************************************************************************************ */
$opcion_tipo_documento_repre = "<option value=''>Seleccione</option>";
/*SELECTOR - TIPO DOCUMENTO - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = "SELECT id, nombre, descripcion FROM propiedades.tp_tipo_dni";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);



$opcion_tipo_documento_repre = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_tipo_documento = "";

	if (@$result->tipoDni->id == @$item->id) {
		$select_tipo_documento = " selected ";
	}

	$opcion_tipo_documento_repre = $opcion_tipo_documento_repre . "<option value='$item->id' $select_tipo_documento >$item->nombre</option>";
}


$opcion_tipo_documento_repre = "<select id='tipo_documento_repre' name='tipo_documento_repre' class='form-control  form-select' onchange='valdidarTipoDni_repre()' form='form2'>
$opcion_tipo_documento_repre
</select>";


//************************************************************************************************************


/********  Comentado por José Barrera para su revision 19-04-2024
echo "holi";
$query = "SELECT complemento,comentario,comentario2 FROM propiedades.persona p , propiedades.persona_direcciones pd where p.token = '$token'  and pd.id_persona = p.id ";
var_dump($query);
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objPersonaDireccion = json_decode($resultado)[0];


$direccion_comentario = @$objPersonaDireccion->comentario;
$direccion_comentario2 = @$objPersonaDireccion->comentario2;

 ******** */
//***************************************************************************************************************

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

$opcion_estado_persona = "<select id='estado_persona' name='estado_persona' $disabled_estado class='form-control  form-select' required >
$opcion_estado_persona
</select>";


//************************************************************************************************************

$opcion_banco = "<option value=''>Seleccione</option>";
// $data_banco = array("idEmpresa" => $id_company);
// $resp_banco = $services->sendPostNoToken($url_services . '/banco/listaByEmpresa', $data_banco);
// $bancos = json_decode($resp_banco);

// foreach ($bancos as $banco_r) {

// 	$select_banco = "";
// 	if (@$result->banco->idBanco == @$banco_r->idBanco)
// 		$select_banco = " selected ";


// 	$opcion_banco = $opcion_banco . "<option value='$banco_r->idBanco' $select_banco >$banco_r->descripcion</option>";
// } //foreach($roles as $rol)

$opcion_banco = "<select id='banco' name='banco' class='form-control  form-select' required >
$opcion_banco
</select>";

//************************************************************************************************************
/*SELECTOR - TIPO PERSONA - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = "SELECT id, nombre, descripcion FROM propiedades.tp_tipo_persona";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


// //var_dump("TIPO PERSONA", $resultado);


$opcion_tipo_persona_legal = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$selected_tipo_persona = "";
	$select_tipo_persona_legal = "";

	if (@$result->tipoPersona->id == @$item->id) {
		$select_tipo_persona_legal = " selected ";
		$selected_tipo_persona = @$item->id;
	}

	$opcion_tipo_persona_legal = $opcion_tipo_persona_legal . "<option value='$item->id' $select_tipo_persona_legal >$item->nombre</option>";
}


if ($token) {

	$opcion_tipo_persona_legal = "<select id='tipo_persona_legal' name='tipo_persona_legal' class='form-control  form-select' required onchange='onChangeTipoPersona();' readonly>
	$opcion_tipo_persona_legal
	</select>";
} else {
	$opcion_tipo_persona_legal = "<select id='tipo_persona_legal' name='tipo_persona_legal' class='form-control  form-select' required onchange='onChangeTipoPersona();'>
	$opcion_tipo_persona_legal
	</select>";
}





/*SELECTOR - TIPO PROPIEDAD - MANTENER PARA RENTDESK */
$num_reg = 999;
$inicio = 0;

$query = "SELECT * from propiedades.tp_tipo_propiedad";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


$opcion_tipo_propiedad = "";

foreach ($json as $item) {
	$selected_tipo_propiedad = "";
	$select_tipo_propiedad = "";

	if (@$objPersonaDireccion->complemento == @$item->nombre) {
		$selected_tipo_propiedad = @$item->id;
		$select_tipo_propiedad = " selected ";
	}

	$opcion_tipo_propiedad = $opcion_tipo_propiedad . "<option value='$item->nombre' $select_tipo_propiedad >$item->nombre</option>";
}

$opcion_tipo_propiedad = "<select id='tipoPropiedad' name='tipoPropiedad' class='form-control  form-select' required >
$opcion_tipo_propiedad
</select>";

//************************************************************************************************************
/*SELECTOR - TIPO ESTADO CIVIL - MANTENER PARA RENTDESK */
$num_reg = 10;
$inicio = 0;

$query = "SELECT id, nombre, descripcion, habilitado FROM propiedades.tp_estado_civil";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


// //var_dump("TIPO ESTADO CIVIL", $resultado);


$opcion_estado_civil = "";

foreach ($json as $item) {
	$select_estado_civil = "";

	if (@$result->datosNatural->estadoCivil->id == @$item->id) {
		$select_estado_civil = " selected ";
	}
	$opcion_estado_civil = $opcion_estado_civil . "<option value='$item->id' $select_estado_civil >$item->nombre</option>";
}

$opcion_estado_civil = "<select id='estado_civil' name='estado_civil' class='form-control  form-select' required >
$opcion_estado_civil
</select>";



$num_reg = 50;
$inicio = 0;
$queryPais = "SELECT * FROM propiedades.tp_pais WHERE habilitado = true ORDER BY orden ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryPais, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objPais = json_decode($resultado);
$selectpais = "<select id=\"paisRepresentante\" name =\"paisRepresentante\"class=\"form-control form-select\" onchange=\"selectUbicacion('pais')\" form=\"form2\">";
$selectpais = $selectpais . '<option value="">Selecciona un Pais</option>';

foreach ($objPais as $pais) {
	$selectpais = $selectpais . '<option value="' . $pais->id . '">' . $pais->nombre . '</option>';
}

$selectpais = $selectpais . '</select>';








//************************************************************************************************************
// $comuna = @$result->comuna->idComuna;
// $region = @$result->comuna->region->idRegion;
// $pais = @$result->comuna->region->pai->idPais;

$comuna = @$result->direcciones[0]->comuna->id;
$region = @$result->direcciones[0]->comuna->region->token;
$pais = @$result->direcciones[0]->comuna->region->pais->token;

$comunaCom = @$result->direcciones[0]->comuna->id;
$regionCom = @$result->direcciones[0]->comuna->region->token;
$paisCom = @$result->direcciones[0]->comuna->region->pais->token;



$loadPaisComunaRegion = "";
if ($pais != "" && $comuna != "" && $region != "") {
	$loadPaisComunaRegion = "
				$(document).ready(function () {
						seteaRegionComuna('0',  '$pais',  '$region',  '$comuna')
				});
";
} else {
	$loadPaisComunaRegion = "
			$(document).ready(function () {
				seteaRegionComuna('0',  '',  '',  '')
			});
	";
}
/////////////////////// Tayendo al representante legal en caso de tener token
if (isset($token) && @$result->tipoPersona->id == 2) {





	$query = "SELECT psn.dni  FROM propiedades.persona ps 
 	inner join propiedades.persona_juridica pj on ps.id= pj.id_persona 
 	inner join propiedades.persona psn on psn.id  = pj.id_representante_legal 
 	where ps.token ='$token'";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$json = json_decode($resultado);
	foreach ($json as $item) {
		$dni = $item->dni;
	}
	echo "
	<script>
	$(document).ready(function () {
		setTimeout(function(){
		 $('#NDocumento').val('$dni');
			 $('#tipo_documento_repre').val(2);
			 $('#buscaPersona').click();
			  }, 1000)
		 	 setTimeout(function(){
			      guardarRepresentante();	
    		 }, 2000); // 1000 milisegundos = 1 segundo
	});
	</script>";
}


//************************************************************************************************************

/*SELECTOR - BANCO - MANTENER PARA RENTDESK */
$query = "SELECT * FROM propiedades.tp_banco  where habilitado = true";
$data = array("consulta" => $query);
$resultado  = $services->sendPostDirecto($url_services . '/util/objeto', $data);

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
/******************************Consulta Valores configuracion Subsidiaria************************************ */


$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_subsidiaria = $current_subsidiaria->id;

$num_reg = 50;
$inicio = 0;
$queryConfig = "SELECT * FROM propiedades.tp_configuracion_subsidiaria where id_subisidiaria=" . $id_subsidiaria;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryConfig, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultadoCuentas = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objConfig = json_decode($resultadoCuentas);
$objeto = $objConfig[0]; // Accede al primer elemento del array
$formato_dni = $objeto->formato_dni;
$formato_rut = $objeto->formato_rut;
$formato_pasaporte = $objeto->formato_pasaporte;

$flag_solo_rut = 0;
if ($formato_rut == 1 && $formato_pasaporte == 0 && $formato_dni == 0) {
	$flag_solo_rut = 1;
}
