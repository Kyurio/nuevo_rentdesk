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


/*FICHA TECNICA - INFORMACIÓN CUENTAS BANCARIAS*/
$dataTableInfoCuentasBancarias = array(
	array("-", "-", "-", "-", "-", "-")

);

/*FICHA TECNICA - INFORMACIÓN COMENTARIOS */
$dataTableInfoComentarios = array(
	array("-", "-"),

);

/*FICHA TECNICA - INFORMACIÓN COMENTARIOS */
$dataTableCuentaCorriente = array(
	array("-", "-", "-", "-", "-", "-"),

);

/*FICHA TECNICA - INFORMACIÓN COMENTARIOS */
$dataTablePropiedades = array(
	array("DIEGO DE VELASQUEZ 2071 Estacionamiento ESTAC. 248, Providencia, Región Metropolitana"),

);

/*FICHA TECNICA - LIQUIDACIONES ACTUALES */
$dataTableLiquidacionesActuales = array(
	array("-", "Total", "-", "$0", "$0", "$0", "$0", "$0", "$0"),
);


/*FICHA TECNICA - LIQUIDACIONES ANTIGUAS */
$dataTableLiquidacionesAntiguas = array(
	array("-", "Total", "-", "$0", "$0", "$0", "$0", "$0", "$0"),
);


/*FICHA TECNICA - HISTORIAL */
$dataTableHistorial = array(
	array("12/07/2022 19:52:28", "Sistema Automático", "Crear", "Dirección", "100148", "	Comuna: Providencia, Complemento: DEPTO. 1104, Región: Metropolitana, Calle: DIEGO DE VELASQUEZ, Número: 2087, Tipo de propiedad: Departamento"),
	array("12/07/2022 19:52:28", "Sistema Automático", "Actualizar", "Propietario", "37465", "-"),
	array("09/07/2022 20:25:56", "felipe@controlprop.cl", "Crear", "Propietario", "37465", "Correo electrónico: norespondergracias@fuenzalida.com, Nombre: ADRIANA MERCEDES, Teléfono fijo: 2336201.0, Teléfono celular: 0.0, Apellido: BRANDI VARAS, RUT: 7.190.851-8, Company: 437"),
);
