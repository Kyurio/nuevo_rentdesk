<?php

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

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
$data_tipo_moneda = array("idEmpresa" => $id_company);
$resp_tipo_moneda = $services->sendPostNoToken($url_services . '/tipoMoneda/listaByEmpresa', $data_tipo_moneda);
$tipo_monedas = json_decode($resp_tipo_moneda);

foreach ($tipo_monedas as $tipo_moneda_r) {

	$select_tipo_moneda = "";
	if (@$result->id_moneda == @$tipo_moneda_r->idTipoMoneda)
		$select_tipo_moneda = " selected ";


	$opcion_tipo_moneda = $opcion_tipo_moneda . "<option value='$tipo_moneda_r->idTipoMoneda' $select_tipo_moneda >$tipo_moneda_r->descripcion</option>";
} //foreach($roles as $rol)

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
$data_estado_propiedad = array("idEmpresa" => $id_company);
$resp_estado_propiedad = $services->sendPostNoToken($url_services . '/estadoPropiedad/listaByEmpresa', $data_estado_propiedad);
$estado_propiedads = json_decode($resp_estado_propiedad);

foreach ($estado_propiedads as $estado_propiedad_r) {

	$select_estado_propiedad = "";
	if (@$result->id_estado_propiedad == @$estado_propiedad_r->idEstadoPropiedad)
		$select_estado_propiedad = " selected ";


	$opcion_estado_propiedad = $opcion_estado_propiedad . "<option value='$estado_propiedad_r->idEstadoPropiedad' $select_estado_propiedad >$estado_propiedad_r->descripcion</option>";
} //foreach($roles as $rol)

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

$opcion_banco = "<option value=''>Seleccione</option>";
$data_banco = array("idEmpresa" => $id_company);
$resp_banco = $services->sendPostNoToken($url_services . '/banco/listaByEmpresa', $data_banco);
$bancos = json_decode($resp_banco);

foreach ($bancos as $banco_r) {

	$select_banco = "";
	if (@$result->id_banco == @$banco_r->idBanco)
		$select_banco = " selected ";


	$opcion_banco = $opcion_banco . "<option value='$banco_r->idBanco' $select_banco >$banco_r->descripcion</option>";
} //foreach($roles as $rol)

$opcion_banco = "<select id='banco' name='banco' class='form-control' required >
$opcion_banco
</select>";

//************************************************************************************************************
$participacion_total = 0;
$lista_propietarios = "";
$data = array("token" => $token, "idEmpresa" => $id_company);
$resultado = $services->sendPostNoToken($url_services . '/propiedad/propietarios', $data);
if ($resultado) {
	$result_json = json_decode($resultado);
	foreach ($result_json as $result_r) {

		$lista_propietarios = $lista_propietarios . "    <tr>
	  <td height='28'>$result_r->tipo_documento</td>
	  <td height='28'>$result_r->num_documento</td>
	  <td height='28'>$result_r->nombre</td>
	  <td height='28'>$result_r->apellido_pat</td>
	  <td height='28'>$result_r->apellido_mat</td>
	  <td height='28'>$result_r->porcentaje</td>
	  <td height='28'><a href='index.php?component=propietario&view=propietario&token=$result_r->token&token_propiedad=$token&nav=$pag_origen'><i class='fas fa-search'></i></a></td>
	  <td height='28'><a href='javascript: deletePropietario(\"$result_r->token\",\"$token\");'><i class='far fa-trash-alt'></i></a></td>
	</tr>";
		$participacion_total = $participacion_total + $result_r->porcentaje;
	} //foreach($result_json as $result)
}


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
$data = array("token" => $token, "idEmpresa" => $id_company);
$resultado = $services->sendPostNoToken($url_services . '/propiedad/checkIn', $data);
if ($resultado) {
	$result_json = json_decode($resultado);
	foreach ($result_json as $result_r) {
		$tiene_check_in = "S";
		$fecha = fecha_postgre_a_normal($result_r->fecha);

		$lista_check_in = $lista_check_in . "    <tr>
	  <td height='28'>$fecha</td>
	  <td height='28'>$result_r->arrendatario_recibe</td>
	  <td height='28'>$result_r->rut</td>
	  <td height='28'>$result_r->correo</td>
	  <td height='28'><a href='index.php?component=visita&view=visita&token=$result_r->token&token_propiedad=$token&nav=$pag_origen'><i class='fas fa-search'></i></a></td>
	  <td height='28'><a href='javascript: deleteCheckIn(\"$result_r->token\",\"$token\");'><i class='far fa-trash-alt'></i></a></td>
	</tr>";
	} //foreach($result_json as $result)
}


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
$data_sucursal = array("idEmpresa" => $id_company);
$resp_sucursal = $services->sendPostNoToken($url_services . '/sucursal/listaByEmpresa', $data_sucursal);
$sucursales = json_decode($resp_sucursal);

foreach ($sucursales as $sucursal_r) {

	$select_sucursal = "";
	if (@$result->id_sucursal == @$sucursal_r->idSucursal)
		$select_sucursal = " selected ";


	$opcion_sucursal = $opcion_sucursal . "<option value='$sucursal_r->idSucursal' $select_sucursal >$sucursal_r->nombreFantasia</option>";
} //foreach($roles as $rol)

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


/* QRY PARA OBTENER FICHA BASE */
$num_reg = 10;
$inicio = 0;

$query = "SELECT 
    a.*, 
    TO_CHAR(a.fecha_inicio, 'DD/MM/YYYY') AS fecha_inicio_formato,
    TO_CHAR(a.fecha_termino_real, 'DD/MM/YYYY') AS fecha_termino_real_formato,  
    b.nombre AS moneda_precio,
	c.nombre AS moneda_multa
FROM 
    propiedades.ficha_arriendo a 
INNER JOIN 
    propiedades.tp_tipo_moneda b ON a.id_moneda_precio = b.id 
INNER JOIN 
    propiedades.tp_tipo_moneda c ON a.id_moneda_multa = c.id
WHERE 
    a.token = '$token'
";

$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_ficha_arriendo = json_decode($resultado);



foreach ($json_ficha_arriendo as $item_ficha_arriendo) {
	$id_ficha = validateNull(@$item_ficha_arriendo->id);
	$fecha_inicio = validateNull(@$item_ficha_arriendo->fecha_inicio_formato);
	$fecha_termino_real = validateNull(@$item_ficha_arriendo->fecha_termino_real_formato);

	if (@$item_ficha_arriendo->id_moneda_precio == 2) {

		$precio = '$ ' . number_format($item_ficha_arriendo->precio, 0, '.', '.');
	} else {

		$precio = validateNull(@$item_ficha_arriendo->precio);
	}

	$duracion_contrato_meses = validateNull(@$item_ficha_arriendo->duracion_contrato_meses);
	$monto_garantia = validateNull(@$item_ficha_arriendo->monto_garantia);
	$pago_garantia_propietario = validateNull(@$item_ficha_arriendo->pago_garantia_propietario);
	$cobro_mes_calendario = validateNull(@$item_ficha_arriendo->cobro_mes_calendario);
	$monto_multa_atraso = validateNull(@$item_ficha_arriendo->monto_multa_atraso);
	$moneda_precio = validateNull(@$item_ficha_arriendo->moneda_precio);
	$moneda_multa = validateNull(@$item_ficha_arriendo->moneda_multa);
	$tipo_multa = validateNull(@$item_ficha_arriendo->tipo_multa);
	$dias_pago_gracia = $item_ficha_arriendo->dias_pago_gracia;
}


$num_reg = 10;
$inicio = 0;

/* QRY PARA OBTENER INFORMACION PROPIETARIO BASE */

$query = " SELECT 
CONCAT(
                p.direccion, 
                ' #', p.numero, 
                CASE 
                    WHEN p.numero_depto IS NOT NULL AND p.numero_depto <> '' THEN CONCAT(' Dpto ', p.numero_depto) 
                    ELSE '' 
                END, 
                CASE 
                    WHEN p.piso IS NOT NULL AND p.piso <> 0 THEN CONCAT(' Piso ', p.piso) 
                    ELSE '' 
                END
            ) AS direccion,
p.token,
vp.nombre_1 || ' ' || vp.nombre_2 AS nombre_propietario,
vp.token_propietario AS token_propietario,
vp2.nombre_1 || ' ' || vp2.nombre_2 AS nombre_representante,
vp2.token_propietario AS token_representante
FROM propiedades.ficha_arriendo fa
JOIN propiedades.propiedad p ON fa.id_propiedad = p.id
JOIN propiedades.propiedad_copropietarios pc ON pc.id_propiedad = p.id
JOIN propiedades.vis_propietarios vp ON vp.id = pc.id_propietario
LEFT JOIN propiedades.pj_representante_legal pjrl ON vp.id = pjrl.id_persona_juridica
LEFT JOIN propiedades.vis_propietarios vp2 ON pjrl.id_representante_legal = vp2.id
where 
		   fa.token = '$token' and fa.id_propiedad  = p.id   
		   and pc.id_propiedad = p.id
           and  vp.id  = pc.id_propietario
           order by fa.id desc ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado_propiedad = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_propiedad = json_decode($resultado_propiedad);

// var_dump("RESULTADO FICHA ARRIENDO INFO: ", $resultado_propiedad);

// var_dump("JSON FICHA ARRIENDO INFO: ", $json_propiedad);


foreach ($json_propiedad as $item_propiedad) {
	$propiedad_direccion = @$item_propiedad->direccion;
	$propiedad_token = @$item_propiedad->token;
	$propiedad_nombre_propietario = @$item_propiedad->nombre_propietario;
	$propiedad_token_propietario = @$item_propiedad->token_propietario;
	$propiedad_nombre_representante = @$item_propiedad->nombre_representante;
	$propiedad_token_representante = @$item_propiedad->token_representante;
}

/* QRY PARA OBTENER ARRENDATARIOS  */

//     $query = "select va.nombre_1||' '||va.nombre_2 as nombre_arrendador ,va.correo_electronico ,va.telefono_fijo,va.telefono_movil, fa.token
//               from propiedades.ficha_arriendo_arrendadores a, 
//               propiedades.vis_arrendatarios va, propiedades.ficha_arriendo fa , propiedades.propiedad p 
//               where  fa.token = '$token' 
// 			  and va.id = a.id_arrendatario 
//               and fa.id = a.id_ficha_arriendo 
//               and p.id  = fa.id_propiedad  
//               order by fa.id desc ";
//     $cant_rows = $num_reg;
//     $num_pagina = round($inicio / $cant_rows) + 1;
//     $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
//     $resultado_arrendatarios = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
// 	$json_arrendatarios = json_decode($resultado_arrendatarios);

// 	$dataTableInfoArrendatario = [];

// 	if ($json_arrendatarios !== null) {
// 	// Iterate over each object in the array
// 	foreach ($json_arrendatarios as $obj) {
// 		// Transform each field of the object
// 		$transformedObj = [
// 			'Arrendatario' => validateNull($obj->nombre_arrendador ?? null),
// 			'correo_electronico' => validateNull($obj->correo_electronico ?? null),
// 			'telefono_movil' => validateNull($obj->telefono_movil ?? null),
// 			'telefono_fijo' => validateNull($obj->telefono_fijo ?? null),
// 			'token' => validateNull($obj->token ?? null),

// 		];
// 		// Push the transformed object into the array
// 		$dataTableInfoArrendatario[] = $transformedObj;
// 	}
// }

function validateNull($item)
{
	return is_null($item) || $item === "" ? "-" : $item;
}

/*
$dataTableInfoArrendatario = array(
	array("ANDREA CAROLINA SALVADOR GONZALEZ", "textilessalvador@gmail.com", "948517243", "-"),

);
*/

/* QRY PARA OBTENER COMENTARIOS ARRIENDO  */

//     $query = "select fac.* from propiedades.ficha_arriendo fa , propiedades.ficha_arriendo_comentarios fac 
//               where fa.token = '$token' 
//               and fa.id = fac.id_ficha_arriendo 
//               order by fa.id desc ";
//     $cant_rows = $num_reg;
//     $num_pagina = round($inicio / $cant_rows) + 1;
//     $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
//     $resultado_comentarios = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
// 	$json_comentarios = json_decode($resultado_comentarios);

// 	$dataTableInfoComentarios = [];

// 	if ($json_comentarios !== null) {
// 	// Iterate over each object in the array
// 	foreach ($json_comentarios as $obj) {
// 		// Transform each field of the object
// 		$transformedObj = [
// 			'comentario' => validateNull($obj->comentario ?? null),
// 			'fecha_comentario' => validateNull($obj->fecha_comentario ?? null),
// 			'token' => validateNull($obj->token ?? null),
// 		];
// 		// Push the transformed object into the array
// 		$dataTableInfoComentarios[] = $transformedObj;
// 	}
// }

// $dataTableInfoComentarios = array(
// 	array("ginaguerra@fuenza...", "box 107 bod 106"),

// );

/*FICHA TECNICA - CUENTA CORRIENTE */
$dataTableCuentaCorriente = array();

/*FICHA TECNICA - CHEQUES */
////////////Select Banco Cheques
/*SELECTOR - BANCO - MANTENER PARA RENTDESK */
$query = "SELECT * FROM propiedades.tp_banco  where habilitado = true";
$data = array("consulta" => $query);
$resultado  = $services->sendPostDirecto($url_services . '/util/objeto', $data);


$objTipoChequeBancos = json_decode($resultado);
$selectBancoEditar = "<select id='tipo_banco_editar' name='tipo_banco_editar' class='form-control' required ><option value=''>Seleccione</option>";
$selectBanco = "<select id='tipo_banco' name='tipo_banco' class='form-control' required ><option value=''>Seleccione</option>";


foreach ($objTipoChequeBancos as $banco) {

	$selectBancoEditar = $selectBancoEditar . "<option value='$banco->id'>$banco->nombre</option>";
	$selectBanco = $selectBanco . "<option value='$banco->id'>$banco->nombre</option>";
}
$selectBancoEditar = $selectBancoEditar . "</select>";

$selectBanco = $selectBanco . "</select>";

$num_reg = 50;
$inicio = 0;
$queryCheques = "select * from propiedades.ficha_arriendo_cheques  where habilitado=true";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCheques, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCheques = json_decode($resultado);


$dataTableCheques = $objCheques;




//****************************************************************************************************************** */

/*FICHA TECNICA - GARANTÍA */
$query = "SELECT * FROM propiedades.garantia_movimientos  where id_arriendo = '$id_ficha' ORDER BY fecha_movimiento DESC ";

$data = array("consulta" => $query);
$resultado  = $services->sendPostDirecto($url_services . '/util/objeto', $data);

$array_garantias = array();

$result_json = json_decode($resultado);
if ($result_json) {
	foreach ($result_json  as $result) {

		//$id  = $result->id;
		$fecha_original = $result->fecha_movimiento;
		$tipo_moneda = 'PESOS';
		$razon 	= $result->razon;
		$monto 	= $result->monto;
		$pagado = $result->pagado;

		if ($fecha != "")
			$fecha = DateTime::createFromFormat('Y-m-d', $fecha_original)->format('d-m-Y');

		if ($monto > 0)
			$monto = @number_format($monto, 0, '', '.');

		if ($pagado != "Si")
			$pagado = "No";

		$notificado 	= $result->notificado;

		$array_garantias[] = array($fecha, $razon, $tipo_moneda, "$" . $monto, $pagado);
	}
}


if (empty($array_garantias)) {
	$array_garantias[] = array("-", "-", "-", "-", "-", "-");
}

// Puedes definir el array $dataTableGarantia usando los datos del array $array_garantias
$dataTableGarantia = $array_garantias;

$dataTableGarantiaDocumentos = array(
	array("-"),
);




$dataTableGarantiaComentarios = array(
	array("-"),
);


/*FICHA TECNICA - CUENTAS DE SERVICIO */
$dataTableCobros = array();


/*FICHA TECNICA - REAJUSTE */
$dataTableReajuste = array();
/*FICHA TECNICA - RECORDATORIOS */
$dataTableRecordatorios = array();
/*FICHA TECNICA - HISTORIAL */
$dataTableHistorial = array();
