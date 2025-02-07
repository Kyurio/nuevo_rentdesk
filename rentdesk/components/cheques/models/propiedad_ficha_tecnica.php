<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$current_sucursales = unserialize($_SESSION['sesion_rd_sucursales']);
$current_sucursal = unserialize($_SESSION["rd_current_sucursal"]);

// var_dump("SUCURSAL ACTUAL: ", $current_sucursal);

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token	= @$_GET["token"] ?? null;

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

if (isset($token) && $token !== '') {
	/*PRUEBA OBTENCIÓN LISTADO PROPIEDADES */
	/*Consulta Cantidad de registros*/
	// $query_count = "SELECT * FROM propiedades.propiedad  where token = '$token'";

	// //var_dump($query_count);
	// $data = array("consulta" => $query_count);
	// $resultado = $services->sendPostNoToken($url_services . '/util/count', $data);
	// $cantidad_registros = $resultado;

	// //var_dump("CANTIDAD PROPIEDADES: ", json_decode($cantidad_registros));

	$num_reg = 100;
	$inicio = 0;

	$query = "SELECT cs.nombre as nombre_sucursal, * from propiedades.propiedad p, propiedades.vis_propiedades vp , propiedades.cuenta_sucursal cs
	where p.token = vp.token_propiedad 
	and cs.id = vp.id_sucursal 
	and p.token = '$token' ";

	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data);
	$json = json_decode($resultado);

	// var_dump("RESULTADO FICHA TECNICA PROPIEDAD: ", $json);
	if ($json !== null) {
		// Iterate over each object in the array
		foreach ($json as $obj) {
			$nombre_sucursal =  validateNull(@$obj->nombre_sucursal ?? null);
			$ficha_tecnica =  validateNull(@$obj->id_propiedad ?? null);
			$codigo_propiedad = validateNull(@$obj->codigo_propiedad ?? null);
			$ejecutivo =  validateNull(@$obj->ejecutivo ?? null);
			$tipo_propiedad =  validateNull(@$obj->tipo_propiedad ?? null);
			$propietario =  validateNull(@$obj->propietario ?? null);
			$comuna =  validateNull(@$obj->comuna ?? null);
			$direccion =  validateNull(@$obj->direccion ?? null);
			$numero =  validateNull(@$obj->numero ?? null);
			$numero_depto =  validateNull(@$obj->numero_depto ?? null);
			$estado_propiedad =  validateNull(@$obj->estado_propiedad ?? null);
			$id_contrato =  validateNull(@$obj->id_contrato ?? null);
			$avaluo_fiscal =  validateNull(@$obj->avaluo_fiscal ?? null);
			$fecha_ingreso =  validateNull((new DateTime(@$obj->fecha_ingreso))->format('d-m-Y') ?? null);
			$edificado =  validateNull(@$obj->edificado ?? null);
			$dormitorios =  validateNull(@$obj->dormitorios ?? null);
			$dormitorios_servicio =  validateNull(@$obj->dormitorios_servicio ?? null);
			$banos =  validateNull(@$obj->banos ?? null);
			$banos_visita =  validateNull(@$obj->banos_visita ?? null);
			$piscina =  validateNull(@$obj->piscina ?? null);
			$bodegas =  validateNull(@$obj->bodegas ?? null);
			$logias =  validateNull(@$obj->logias ?? null);
			$m2 =  validateNull(@$obj->m2 ?? null);

		}
	}

	/*OBTENCIÓN PROPIETARIOS */
	// $num_reg = 100;
	// $inicio = 0;

	// $query = "select vp.*, vp.nombre_1 ||' ' || vp.nombre_2||' ' || vp.nombre_3 as nombre from propiedades.propiedad p, propiedades.propiedad_copropietarios pcop, propiedades.vis_propietarios vp 
	// where p.id = pcop.id_propiedad 
	// and pcop.id_propietario = vp.id 
	// and pcop.habilitado = true
	// and p.token  ='$token'";

	// $cant_rows = $num_reg;
	// $num_pagina = round($inicio / $cant_rows) + 1;
	// $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	// $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data);
	// $jsonPropietarios = json_decode($resultado);


	// $dataTablePropietarios = [];

	// if ($jsonPropietarios !== null) {
	// 	// Iterate over each object in the array
	// 	foreach ($jsonPropietarios as $obj) {

	// 		// var_dump("obj: ", $obj);

	// 		// $dataTablePropietarios = $dataTablePropietarios . json_encode($obj->nombre);

	// 		// var_dump("dataTableCoPropietarios: ", $dataTableCoPropietarios);

	// 		// Transform each field of the object
	// 		$transformedObj = [
	// 			'nombre' => validateNull($obj->nombre ?? null),
	// 			"link" =>   $obj->token_propietario
	// 		];

	// 		// Push the transformed object into the array
	// 		$dataTablePropietarios[] = $transformedObj;
	// 	}
	// }

	// var_dump("dataTableCoPropietarios: ", $dataTablePropietarios);

	// if ($jsonPropietarios !== null) {
	// 	// Iterate over each object in the array
	// 	foreach ($jsonPropietarios as $obj) {
	// 		$token_propietario =  validateNull(@$obj->token_propietario ?? null);
	// 		$nombre = validateNull(@$obj->nombre_1 . " " . @$obj->nombre_2 . " " . @$obj->nombre_3 ?? null);
	// 	}
	// }
}

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

//************************************************************************************************************

$opcion_terreno = "<option selected value='N'>No</option>";

if (@$result->terreno == "S") {
	$opcion_terreno = $opcion_terreno . "<option selected value='S'>Si</option>";
} else {
	$opcion_terreno = $opcion_terreno . "<option value='S'>Si</option>";
}

//************************************************************************************************************

$opcion_edificado = "<option selected value='N'>No</option>";

if (@$result->edificado == "S") {
	$opcion_edificado = $opcion_edificado . "<option selected value='S'>Si</option>";
} else {
	$opcion_edificado = $opcion_edificado . "<option value='S'>Si</option>";
}


//************************************************************************************************************

$opcion_tipo_moneda = "<option value=''>Seleccione</option>";
// $data_tipo_moneda = array("idEmpresa" => $id_company);
// $resp_tipo_moneda = $services->sendPostNoToken($url_services . '/tipoMoneda/listaByEmpresa', $data_tipo_moneda);
// $tipo_monedas = json_decode($resp_tipo_moneda);

// foreach ($tipo_monedas as $tipo_moneda_r) {

// 	$select_tipo_moneda = "";
// 	if (@$result->id_moneda == @$tipo_moneda_r->idTipoMoneda)
// 		$select_tipo_moneda = " selected ";


// 	$opcion_tipo_moneda = $opcion_tipo_moneda . "<option value='$tipo_moneda_r->idTipoMoneda' $select_tipo_moneda >$tipo_moneda_r->descripcion</option>";
// } //foreach($roles as $rol)

$opcion_tipo_moneda = "<select id='tipo_moneda' name='tipo_moneda' class='form-control' required >
$opcion_tipo_moneda
</select>";


//************************************************************************************************************
$disabled_estado = "";
if (!$token == "") {
	/*Verifica si tiene el permiso para editar el estado*/
	$query_count = "select 1 
					from arpis.usuario u,
						 arpis.menu_rol mr,
						 arpis.menu m
					where u.id_usuario = $id_usuario
					and mr.id_rol = u.id_rol
					and m.id_menu = mr.id_menu
					and m.ref_externa = 'PROPIEDAD' ";

	$data = array("consulta" => $query_count);
	$resultado = $services->sendPostNoToken($url_services . '/util/count', $data);
	$cantidad_registros = $resultado;

	if (!$cantidad_registros) {
		$disabled_estado = "disabled";
	} else {
		if ($cantidad_registros > 0) {
			$disabled_estado = "";
		} else {
			$disabled_estado = "disabled";
		}
	}
}


$opcion_estado_propiedad = "<option value=''>Seleccione</option>";
// $data_estado_propiedad = array("idEmpresa" => $id_company);
// $resp_estado_propiedad = $services->sendPostNoToken($url_services . '/estadoPropiedad/listaByEmpresa', $data_estado_propiedad);
// $estado_propiedads = json_decode($resp_estado_propiedad);

// foreach ($estado_propiedads as $estado_propiedad_r) {

// 	$select_estado_propiedad = "";
// 	if (@$result->id_estado_propiedad == @$estado_propiedad_r->idEstadoPropiedad)
// 		$select_estado_propiedad = " selected ";


// 	$opcion_estado_propiedad = $opcion_estado_propiedad . "<option value='$estado_propiedad_r->idEstadoPropiedad' $select_estado_propiedad >$estado_propiedad_r->descripcion</option>";
// } //foreach($roles as $rol)

$opcion_estado_propiedad = "<select id='estado_propiedad' name='estado_propiedad' $disabled_estado class='form-control' required >
$opcion_estado_propiedad
</select>";

//************************************************************************************************************

$opcion_piscina = "<option selected value='N'>No</option>";

if (@$result->piscina == "S") {
	$opcion_piscina = $opcion_piscina . "<option selected value='S'>Si</option>";
} else {
	$opcion_piscina = $opcion_piscina . "<option value='S'>Si</option>";
}

//************************************************************************************************************

$opcion_amoblado = "<option selected value='N'>No</option>";

if (@$result->amoblado == "S") {
	$opcion_amoblado = $opcion_amoblado . "<option selected value='S'>Si</option>";
} else {
	$opcion_amoblado = $opcion_amoblado . "<option value='S'>Si</option>";
}

//************************************************************************************************************

$opcion_dfl2 = "<option selected value='N'>No</option>";

if (@$result->dfl2 == "S") {
	$opcion_dfl2 = $opcion_dfl2 . "<option selected value='S'>Si</option>";
} else {
	$opcion_dfl2 = $opcion_dfl2 . "<option value='S'>Si</option>";
}

//************************************************************************************************************

$opcion_amoblado = "<option selected value='N'>No</option>";

if (@$result->amoblado == "S") {
	$opcion_amoblado = $opcion_amoblado . "<option selected value='S'>Si</option>";
} else {
	$opcion_amoblado = $opcion_amoblado . "<option value='S'>Si</option>";
}


//************************************************************************************************************

/*SELECTOR - BANCO - MANTENER PARA RENTDESK */
$query = "SELECT * FROM propiedades.tp_banco  where habilitado = true";
$data = array("consulta" => $query );	
$resultado  = $services->sendPostDirecto($url_services.'/util/objeto',$data);

$json = json_decode($resultado);


// //var_dump("BANCO", $resultado);

$opcion_banco_edit =
	$opcion_banco = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$select_banco = "";

	// //var_dump("BANCO JSON: ", @$item->id == @$result->propietario->cuentasBancarias[0]->banco->id);

	if (@$result->propietario->cuentasBancarias[0]->banco->id == @$item->id) {
		//$select_banco = " selected ";
	}
	$opcion_banco = $opcion_banco . "<option value='$item->id' $select_banco >$item->nombre</option>";
}
$opcion_banco_edit = "<select id='bancoEdit' name='bancoEdit' class='form-control'   form='form22'>
$opcion_banco
</select>";
$opcion_banco = "<select id='banco' name='banco' class='form-control'  >
$opcion_banco
</select>";

//************************************************************************************************************
$participacion_total = 0;
$lista_propietarios = "";
// $data = array("token" => $token, "idEmpresa" => $id_company);
// $resultado = $services->sendPostNoToken($url_services . '/propiedad/propietarios', $data);
// if ($resultado) {
// 	$result_json = json_decode($resultado);
// 	foreach ($result_json as $result_r) {

// 		$lista_propietarios = $lista_propietarios . "    <tr>
// 	  <td height='28'>$result_r->tipo_documento</td>
// 	  <td height='28'>$result_r->num_documento</td>
// 	  <td height='28'>$result_r->nombre</td>
// 	  <td height='28'>$result_r->apellido_pat</td>
// 	  <td height='28'>$result_r->apellido_mat</td>
// 	  <td height='28'>$result_r->porcentaje</td>
// 	  <td height='28'><a href='index.php?component=propietario&view=propietario&token=$result_r->token&token_propiedad=$token&nav=$pag_origen'><i class='fas fa-search'></i></a></td>
// 	  <td height='28'><a href='javascript: deletePropietario(\"$result_r->token\",\"$token\");'><i class='far fa-trash-alt'></i></a></td>
// 	</tr>";
// 		$participacion_total = $participacion_total + $result_r->porcentaje;
// 	} //foreach($result_json as $result)
// }


$lista_propietarios = "
 <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" class='tabla-propiedad'>
  <tbody>
	<tr class='tp-titulo' >
	  <td height='28'><strong>Tipo. Documento</strong></td>	
	  <td height='28'><strong>Nro. Documento</strong></td>	
	  <td height='28'><strong>Nombre</strong></td>
	  <td height='28'><strong>Ap. Paterno</strong></td>
	  <td height='28'><strong>Ap. Materno</strong></td>
	  <td height='28'><strong>% Participación</strong></td>
	  <td height='28'><strong>Ver</strong></td>
	  <td height='28'><strong>Eliminar</strong></td>
	</tr>
	$lista_propietarios
  </tbody>
</table>
<br>
";


//************************************************************************************************************
$tiene_check_in = "N";
$lista_check_in = "";
// $data = array("token" => $token, "idEmpresa" => $id_company);
// $resultado = $services->sendPostNoToken($url_services . '/propiedad/checkIn', $data);
// if ($resultado) {
// 	$result_json = json_decode($resultado);
// 	foreach ($result_json as $result_r) {
// 		$tiene_check_in = "S";
// 		$fecha = fecha_postgre_a_normal($result_r->fecha);

// 		$lista_check_in = $lista_check_in . "    <tr>
// 	  <td height='28'>$fecha</td>
// 	  <td height='28'>$result_r->arrendatario_recibe</td>
// 	  <td height='28'>$result_r->rut</td>
// 	  <td height='28'>$result_r->correo</td>
// 	  <td height='28'><a href='index.php?component=visita&view=visita&token=$result_r->token&token_propiedad=$token&nav=$pag_origen'><i class='fas fa-search'></i></a></td>
// 	  <td height='28'><a href='javascript: deleteCheckIn(\"$result_r->token\",\"$token\");'><i class='far fa-trash-alt'></i></a></td>
// 	</tr>";
// 	} //foreach($result_json as $result)
// }


$lista_check_in = "
 <table border=\"1\" cellspacing=\"0\" cellpadding=\"0\" class='tabla-propiedad'>
  <tbody>
	<tr class='tp-titulo' >
	  <td height='28'><strong>Fecha</strong></td>	
	  <td height='28'><strong>Arrendatario recibe</strong></td>	
	  <td height='28'><strong>Rut</strong></td>
	  <td height='28'><strong>Email Contacto</strong></td>
	  <td height='28'><strong>Ver</strong></td>
	  <td height='28'><strong>Eliminar</strong></td>
	</tr>
	$lista_check_in
  </tbody>
</table>
<br>
";


//************************************************************************************************************

$opcion_sucursal = "<option value=''>Seleccione</option>";
// $data_sucursal = array("idEmpresa" => $id_company);
// $resp_sucursal = $services->sendPostNoToken($url_services . '/sucursal/listaByEmpresa', $data_sucursal);
// $sucursales = json_decode($resp_sucursal);

// foreach ($sucursales as $sucursal_r) {

// 	$select_sucursal = "";
// 	if (@$result->id_sucursal == @$sucursal_r->idSucursal)
// 		$select_sucursal = " selected ";


// 	$opcion_sucursal = $opcion_sucursal . "<option value='$sucursal_r->idSucursal' $select_sucursal >$sucursal_r->nombreFantasia</option>";
// } //foreach($roles as $rol)

$opcion_sucursal = "<select id='sucursal' name='sucursal' class='form-control' >
$opcion_sucursal
</select>";

$comuna = @$result->id_comuna;
$region = @$result->id_region;
$pais = @$result->id_pais;
$existe_archivo = "N";

if (@$result->mandato != "") {
	@$archivo = "<a href='javascript: borrarArchivo(\"$result->token\");'><i class='far fa-trash-alt'></i></a> <a href='upload/mandato/$result->mandato' target='_blank'> Ver Archivo <i class='fas fa-file'></i></a>";
	$existe_archivo = "S";
}

//************************************************************************************************************

$opcion_destino = "<option value=''>Seleccione</option>";
$select_destino_arriendo = "";
if (@$result->destino_arriendo == "HAB") {
	$select_destino_arriendo = " selected ";
}
$opcion_destino = $opcion_destino . "<option value='HAB' $select_destino_arriendo >Habitacional</option>";
$select_destino_arriendo = "";
if (@$result->destino_arriendo == "COM") {
	$select_destino_arriendo = " selected ";
}
$opcion_destino = $opcion_destino . "<option value='COM' $select_destino_arriendo >Comercial</option>";

$opcion_destino = "<select id='destino_arriendo' name='destino_arriendo' class='form-control' required data-validation-required >
$opcion_destino
</select>";



/*FICHA TECNICA - INFORMACIÓN */
$dataTableInfoComentarios = array(
	array("sandraarayac@fuen...", "PROPIEDAD VIENE CON CONTRATO DE OTRA CORREDORA, SEGURO SE CONSIDERA A CONTAR DEL PROXIMO ARRIENDO."),
	array("ruthbravo@fuenzal...", "Gestión de cobranza Renta Enero y Febrero 2023"),
	array("macarenaibaceta@f...", "POR RECHAZO, SE MODIFICA BANCO PARA TRANSFERENCIAS DE ACUERDO A LO INDICADO POR DUEÑO"),

);

/*FICHA TECNICA - CO-PROPIETARIOS */
$dataTableCoPropietarios = array(
	array("SOLANGE LORENA ITURBE CRESPO", "11.619.942-4", "SOLANGE LORENA ITURBE CRESPO", "11.619.942-4", "962729801 / Banco De Chile - Edwards", "100%"),
);

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
		//$select_cta_banco = " selected ";
	}
	$opcion_cta_banco = $opcion_cta_banco . "<option value='$item->id' $select_cta_banco >$item->nombre</option>";
}
$opcion_cta_banco_edit = "<select id='cta-bancoEdit' name='cta-bancoEdit' class='form-control'  form='form22'>
$opcion_cta_banco
</select>";
$opcion_cta_banco = "<select id='cta-banco' name='cta-banco' class='form-control'  >
$opcion_cta_banco
</select>";

/*FICHA TECNICA - CO-PROPIETARIOS agregar */
$dataTableAgregarCoPropietario = array(
	array("siturbe@carozzi.cl / SOLANGE LORENA ITURBE  CRESPO / SOLANGE LORENA  ITURBE  CRESPO / 11.619.942-4 / Banco De Chile - Edwards / 962729801"),
);
/*FICHA TECNICA - RETENCIONES */
$dataTableRetenciones = array(
	array("-", "-", "-", "-", "-"),
);

/*FICHA TECNICA - CUENTA CORRIENTE */
$dataTableCuentaCorriente = array(
	array("07/02/2024 12:01:07", "Pago mediante transferencia a la fecha", "-", "-$267.710", "$0"),
	array("07/02/2024 10:01:29", "Comisión: pagos recibidos al 07/02/2024 + IVA", "-", "-$26.235", "$267.710"),
	array("07/02/2024 10:01:29", "Arriendo: pagos recibidos al 07/02/2024", "$293.945", "-", "$293.945"),
	array("09/01/2024 12:13:45", "Pago mediante transferencia a la fecha", "-", "-$281.879", "$0"),
	array("09/01/2024 11:18:36", "Comisión: pagos recibidos al 09/01/2024 + IVA", "-", "-$27.623", "$281.879"),
);

/*FICHA TECNICA - CUENTAS DE SERVICIO */
$dataTableCuentasDeServicio = array(
	array("Abril 2023", "Cuenta de Luz", "$20.491"),
	array("Agosto 2023", "Cuenta de Luz", "$22.642"),
	array("Diciembre 2022", "Cuenta de Luz", "$20.549"),
	array("Diciembre 2023", "Cuenta de Luz", "$18.805"),
	array("Enero 2023", "Cuenta de Luz", "$22.101"),
);


/*SELECTOR - TIPO SERVICIO - MANTENER PARA RENTDESK */

$opcion_tipo_servicio = "<option value=''>Seleccione</option>";
$num_reg = 9999;
$inicio = 0;

$query = "SELECT id, nombre, descripcion, tipo_servicio FROM propiedades.tp_tipo_servicio where tipo_servicio is not null order by nombre desc";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultadoTipoServicio = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$jsonTipoServicio = json_decode($resultadoTipoServicio);



$opcion_tipo_servicio = "<option value=''>Seleccione</option>";

foreach ($jsonTipoServicio as $item) {
	$select_tipo_servicio = "";

	// if (@$result->tipoDni->id == @$item->id) {
	// 	$select_tipo_servicio = " selected ";
	// }

	$opcion_tipo_servicio = $opcion_tipo_servicio . "<option value='$item->id' $select_tipo_servicio >$item->nombre</option>";
}


$opcion_tipo_servicio = "<select id='modalCtaServicioCuenta' name='modalCtaServicioCuenta' class='form-control  form-select' form='form2'>
$opcion_tipo_servicio
</select>";

/*FICHA TECNICA - LIQUIDACIONES CO-PROPIETARIOS */
$dataTableLiqCoPropietarios = array(
	array("07/02/2024 10:01:29", "MANUEL DAVID LEIVA SOTO", "$267.710", "100.0%", "$267.710"),
	array("09/01/2024 11:18:36", "MANUEL DAVID LEIVA SOTO", "$281.879", "100.0%", "$267.710"),
	array("11/12/2023 11:36:41", "MANUEL DAVID LEIVA SOTO", "$281.879", "100.0%", "$267.710"),
	array("10/11/2023 16:20:18", "MANUEL DAVID LEIVA SOTO", "$255.010", "100.0%", "$267.710"),
	array("11/10/2023 12:14:22", "MANUEL DAVID LEIVA SOTO", "$255.010", "100.0%", "$267.710"),
);

$num_reg = 1;
$inicio = 0;
$sql_estado_arriendo = "select * from propiedades.ficha_arriendo fa 
inner join propiedades.ficha_arriendo_cta_cte_movimientos faccm 
on fa.id = faccm.id_ficha_arriendo 
where  fa.id_estado_contrato =1 and fa.id_propiedad = $ficha_tecnica
and faccm.id_liquidacion  is null ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $sql_estado_arriendo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$obj_estado_arriendo = json_decode($resultado);
if ($obj_estado_arriendo == "") {
	$estado_liquidar = false;
} else {
	$estado_liquidar = true;
}

/*FICHA TECNICA - NOTAS DE CRÉDITO */
$dataTableNotasDeCredito = array(
	array("-", "-", "-", "-"),
);



/*FICHA TECNICA - RECORDATORIOS */
$dataTableRecordatorios = array(
	array("-", "-", "-", "-", "-"),
);

/*FICHA TECNICA - HISTORIAL */
$dataTableHistorial = array(
	array("15/02/2024 16:47:24", "macarenaibaceta@fuenzalida.com", "Actualizar", "Dirección", "130760", "'Información adicional' de 'GOLD+SEG. (TRASPASO)' a 'GOLD+SEGURO - TRASPASO)','Latitude' de '-33.5099392' a '-33.5099828','Longitude' de '-70.7287826' a '-70.7347095'"),
	array("15/02/2024 16:47:24", "macarenaibaceta@fuenzalida.com", "Actualizar", "Propiedad", "106418", "'Documentos' de 'CONTRATO_5_DE_ABRIL_840__203.pdf' a 'MANDATO_5_DE_ABRIL_840__203.pdf' a 'CONTRATO_5_DE_ABRIL_840__203.pdf' a 'MANDATO_5_DE_ABRIL_840__203.pdf'"),
	array("15/02/2024 16:47:24", "macarenaibaceta@fuenzalida.com", "Actualizar", "Propiedad", "106418", "-"),
	array("08/02/2024 11:23:46", "Sistema Automático", "Actualizar", "Co ownership liquidation", "60011", "'Folio' de '' a '87904','Invoiced' de 'falso' a 'verdadero'"),
	array("08/02/2024 11:23:46", "Sistema Automático", "Actualizar", "Liquidation", "271007", "'Invoiced' de 'falso' a 'verdadero'"),
);

function validateNull($item)
{
	return is_null($item) || $item === "" ? "-" : $item;
}


/*AGREGADO MAYO 28 PATRICIO*/
/************************CONSUNLTA INFO DEL ARRENDATARIO************************************* */

$queryArrendatario = "SELECT fa.token as token_arriendo, va.nombre_1 , va.nombre_2 , va.nombre_3, fa.id  from propiedades.propiedad p 
 inner join propiedades.ficha_arriendo fa  on p.id = fa.id_propiedad 
 left join propiedades.ficha_arriendo_arrendadores faa on faa.id_ficha_arriendo = fa.id 
 left join propiedades.vis_arrendatarios va on va.id = faa.id_arrendatario 
 where p.id =$ficha_tecnica and fa.id_estado_contrato =1 ";

$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryArrendatario, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objArrendatario = json_decode($resultado);
$objetoArrendatario = @$objArrendatario[0];

$idFichaArriendo = @$objetoArrendatario->id;


/*OBTENCIÓN PROPIETARIO - BENEFICIARIOS */
// Transform each field of the object
$propietarios_con_saltos = "";
$propietarios = "";
$query2 = "SELECT cs.nombre as nombre_sucursal ,vp.* ,cu.*
			 FROM propiedades.vis_propiedades vp , propiedades.cuenta_sucursal cs ,propiedades.cuenta_usuario cu 
			 WHERE vp.habilitado = true 
			 and cs.id  = vp.id_sucursal 
			 and cu.id = vp.id_ejecutivo 
			 AND vp.token_sucursal in (
				 select token_sucursal from propiedades.fn_sucursales_por_usuario(
					 '$current_usuario->token',
					 '$current_subsidiaria->token',
					 '$current_sucursal->sucursalToken'
					 )
				 
			 ) 	and  vp.id_propiedad = $ficha_tecnica 
			 ";
$cant_rows = $num_reg;
//var_dump($query);
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query2, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_propiedades =@json_decode($resultado);
//var_dump($json_propiedades);

$query = "   SELECT vp.nombre_1 ||' '|| vp.nombre_2 ||' | ' || vp.dni as info_propietario ,pc.nivel_propietario,  pc.id_propietario
from propiedades.propiedad_copropietarios pc, 
propiedades.vis_propietarios vp ,propiedades.propietario_ctas_bancarias pcb , propiedades.tp_banco tb , propiedades.tp_tipo_persona ttp 
	where pc.id_propietario = vp.id
	and pcb.id_propietario  = pc.id_propietario  
	and pc.id_propiedad = $ficha_tecnica 
	and pcb.id = pc.id_cta_bancaria
	and tb.id = pcb.id_banco
	and vp.id_tipo_persona = ttp.id 
	and pc.habilitado  = true
union
select pb.nombre ||' | ' || pb.rut as info_propietario ,pc.nivel_propietario,  pc.id_propietario from propiedades.propiedad_copropietarios pc, 
propiedades.persona_beneficiario pb ,propiedades.vis_propietarios vp ,  propiedades.tp_banco tb
where pc.id_propietario = vp.id
and pc.id_propiedad = $ficha_tecnica 
and pc.id_beneficiario = pb.id 
and tb.id = pb.cta_id_banco
and pc.habilitado  = true
order by id_propietario , nivel_propietario   asc ";


$cant_rows = 100;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado2 = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
//var_dump($resultado);
@$json_propietarios = json_decode($resultado2);


foreach ($json_propietarios as $obj_propietarios) {
	if ($obj_propietarios->nivel_propietario == 2) {
		$propietarios = $propietarios . "zzz" . $obj_propietarios->info_propietario;
	} else {
		$propietarios = $propietarios . "xxx" . $obj_propietarios->info_propietario;
	}
}
$propietarios_con_saltos = str_replace("xxx", "<br><i class='fa-solid fa-house-user' style='color:#515151;font-size:12px;' title='Propietario' ></i> ", $propietarios);
$propietarios_con_saltos = str_replace("zzz", "<br>&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa-solid fa-user-large' style='font-size:10px; color:#707070;' title='Beneficiario'></i> ", $propietarios_con_saltos);

// var_dump("propietarios_con_saltos: ", $propietarios_con_saltos);




/*FICHA TECNICA - ROLES */
// reemplazar por la consulta sql que llene los datos del rol. 
$dataTablePropiedadRoles = array(
	array("Rol principal", "91-166", "Sin valor", "No existe valor", "No existe valor", "NO"),
);