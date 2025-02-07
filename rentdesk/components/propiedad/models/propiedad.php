<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

@include("../../includes/sql_inyection.php");
@include("../../configuration.php");
@include("../../../includes/funciones.php");
@include("../../../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$token =  $_GET["token"] ?? null;
$current_sucursales = unserialize($_SESSION['sesion_rd_sucursales']);
$token_pais = "";
$token_region = "";
$id_comuna = "";
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_subsidiaria = $current_subsidiaria->id;
// //var_dump($current_sucursales);

//************************************************************************************************************
//proceso para las navegaciones
// $pag_origen = codifica_navegacion("component=propiedad&view=propiedad&token=$token");

// if (isset($token)) {
// 	$nav = "index.php?" . decodifica_navegacion($nav);
// } else {
// 	$nav = "index.php?component=propiedad&view=propiedad_list";
// }


//************************************************************************************************************

$num_reg = 50;
$inicio = 0;
$queryConfig = "SELECT * FROM propiedades.tp_configuracion_subsidiaria where id_subisidiaria=" . $id_subsidiaria;
$num_pagina = round($inicio / $num_reg) + 1;
$data = array("consulta" => $queryConfig, "cantRegistros" => $num_reg, "numPagina" => $num_pagina);
$resultadoConfig = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objConfig = json_decode($resultadoConfig);
$objeto = $objConfig[0]; // Accede al primer elemento del array
$formato_dni = $objeto->formato_dni;
$formato_rut = $objeto->formato_rut;
$formato_pasaporte = $objeto->formato_pasaporte;

$flag_solo_rut = 0;
if ($formato_rut == 1 && $formato_pasaporte == 0 && $formato_dni == 0) {
	$flag_solo_rut = 1;
}



$result = null;

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

	$query = "select * from propiedades.propiedad p, propiedades.vis_propiedades vp 
	where p.token = vp.token_propiedad and p.token = '$token' ";


	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data);
	$json = json_decode($resultado);
	$result = $json[0];


	$id_ficha = $result->id;
	$token_propiedad_actual = $token;
	// //var_dump("DATOS PROPIEDAD OBTENIDOS CON TOKEN URL: ", $result);


	if (isset($result)) {
		$num_reg = 10;
		$inicio = 0;

		$query = "select vp.* from propiedades.propiedad p, propiedades.propiedad_copropietarios pc,propiedades.vis_propietarios vp 
		where p.token = '$token'
		and pc.id_propiedad = p.id 
		and vp.id  = pc.id_propietario";

		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data);
		$json = json_decode($resultado);

		if (isset($json)) {
			$resultPropietario = $json[0];
		} else {
			$resultPropietario = null;
		}
	}
}

//************************************************************************************************************
/*SELECTOR - TIPO COMUNA REGION PAIS - MANTENER PARA RENTDESK */
$num_reg = 999;
$inicio = 0;


if (@$result->id_comuna != null && @$result->id_comuna != "") {
	$query = "	select tc.id as comuna_id ,tr.token  as region_token , tp.token  as pais_token
	from propiedades.tp_comuna tc, propiedades.tp_region tr , propiedades.tp_pais tp  
	where tc.id = $result->id_comuna and tc.id_region = tr.id and tr.id_pais = tp.id";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$json = json_decode($resultado)[0];


	@$token_pais = $json->pais_token;
	@$token_region = $json->region_token;
	@$id_comuna = $json->comuna_id;
}


//************************************************************************************************************
//************************************************************************************************************
/*SELECTOR - ROL - MANTENER PARA RENTDESK */
$num_reg = 999;
$inicio = 0;


if (@$result->id != null && @$result->id != "") {
	$query = "	select numero
	from propiedades.propiedad_roles
	where id_propiedad = $result->id";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$json = json_decode($resultado)[0];


	@$rol_propiedad = $json->numero;
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
/*SELECTOR - TIPO PROPIEDAD - MANTENER PARA RENTDESK */
$num_reg = 999;
$inicio = 0;

$query = "SELECT * from propiedades.tp_tipo_propiedad WHERE habilitado = 'true' ORDER BY nombre ASC ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


$opcion_tipo_propiedad = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$selected_tipo_propiedad = "";
	$select_tipo_propiedad = "";

	if (@$result->id_tipo_propiedad == @$item->id) {
		$selected_tipo_propiedad = @$item->id;
		$select_tipo_propiedad = " selected ";
	}

	$opcion_tipo_propiedad = $opcion_tipo_propiedad . "<option value='$item->id' $select_tipo_propiedad >$item->nombre</option>";
}

$opcion_tipo_propiedad = "<select id='tipoPropiedad' name='tipoPropiedad' class='form-control  form-select' required >
$opcion_tipo_propiedad
</select>";

//************************************************************************************************************

$opcion_terreno = "<option selected value='N'>No</option>";

if (@$result->terreno  == true) {
	$opcion_terreno = $opcion_terreno . "<option selected value='S'>Si</option>";
} else {
	$opcion_terreno = $opcion_terreno . "<option value='S'>Si</option>";
}

//************************************************************************************************************

$opcion_edificado = "<option selected value='false'>No</option>";

if (@$result->edificado == true) {
	$opcion_edificado = $opcion_edificado . "<option selected value='true'>Si</option>";
} else {
	$opcion_edificado = $opcion_edificado . "<option value='true'>Si</option>";
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

$opcion_tipo_moneda = "<select id='tipo_moneda' name='tipo_moneda' class='form-control' >
$opcion_tipo_moneda
</select>";

/*************************************************************************/
/*SELECTOR - ESTADO PROPIEDAD - MANTENER PARA RENTDESK */
$num_reg = 999;
$inicio = 0;

$query = "SELECT * from propiedades.tp_estado_propiedad  WHERE habilitado = 'true'  ORDER BY nombre ASC";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


$opcion_estado_propiedad = "<option value=''>Seleccione</option>";

foreach ($json as $item) {
	$selected_estado_propiedad = "";
	$select_estado_propiedad = "";

	if (@$result->id_estado_propiedad == @$item->id) {
		$selected_estado_propiedad = @$item->id;
		$select_estado_propiedad = " selected ";
	}

	$opcion_estado_propiedad = $opcion_estado_propiedad . "<option value='$item->id' $select_estado_propiedad >$item->nombre</option>";
}

$opcion_estado_propiedad = "<select id='estadoPropiedad' name='estadoPropiedad' class='form-control  form-select' required >
$opcion_estado_propiedad
</select>";

//************************************************************************************************************

/*************************************************************************/
/*SELECTOR - MOTIVO RETENCION - MANTENER PARA RENTDESK */
$num_reg = 50;
$inicio = 0;

$query = "SELECT * from propiedades.tp_motivo_retencion where habilitado = true ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_motivo_retencion = json_decode($resultado);


$opcion_motivo_retencion = "<option value=''>Seleccione</option>";

foreach ($json_motivo_retencion as $item) {

	if (@$result->id_motivo_retencion == @$item->id) {
		$opcion_motivo_retencion = $opcion_motivo_retencion . "<option value='$item->id' selected >$item->nombre</option>";
	} else {
		$opcion_motivo_retencion = $opcion_motivo_retencion . "<option value='$item->id' >$item->nombre</option>";
	}
}

$opcion_motivo_retencion = "<select name='motivoRetencion' id='motivoRetencion' class='form-control  form-select'>
$opcion_motivo_retencion
</select>";

//************************************************************************************************************


/*SELECTOR - TIPO MONEDA - MANTENER PARA RENTDESK */
$num_reg = 50;
$inicio = 0;

$query = "SELECT * from propiedades.tp_tipo_moneda where habilitado = true and id_pais = 1 ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_tipo_moneda = json_decode($resultado);


$opcion_tipo_moneda = "<option value=''>Seleccione</option>";

foreach ($json_tipo_moneda as $item) {

	if (@$result->id_moneda == @$item->id) {
		$opcion_tipo_moneda = $opcion_tipo_moneda . "<option value='$item->id' selected >$item->nombre</option>";
	} else {
		$opcion_tipo_moneda = $opcion_tipo_moneda . "<option value='$item->id' >$item->nombre</option>";
	}
}

$opcion_tipo_moneda = "<select name='monedaRetencion' id='monedaRetencion' class='form-control form-select '>
$opcion_tipo_moneda
</select>";

//************************************************************************************************************


$opcion_piscina = "<option selected value='false'>No</option>";

if (@$result->piscina == "S") {
	$opcion_piscina = $opcion_piscina . "<option selected value='true'>Si</option>";
} else {
	$opcion_piscina = $opcion_piscina . "<option value='true'>Si</option>";
}



$opcion_liquidacion = "<option selected value='false'>No</option>";

if (@$result->mostrar_liquidacion == true) {
	$opcion_liquidacion = $opcion_liquidacion . "<option selected value='true'>Si</option>";
} else {
	$opcion_liquidacion = $opcion_liquidacion . "<option value='true'>Si</option>";
}

//************************************************************************************************************

$opcion_amoblado = "<option selected value='false'>No</option>";

if (@$result->amoblado == true) {
	$opcion_amoblado = $opcion_amoblado . "<option selected value='true'>Si</option>";
} else {
	$opcion_amoblado = $opcion_amoblado . "<option value='true'>Si</option>";
}

//************************************************************************************************************
//Se deja por defecto en SI por que si el valor es true significa que no lo tiene habilitado
$select_true = "";
$select_false = "";

if (@$result->dfl2  == true) {
	$select_true = "Selected";
} else {
	$select_false = "Selected";
}
$opcion_dfl2 = "<option $select_false value='false'>Si</option>";
$opcion_dfl2 = $opcion_dfl2 . "<option  $select_true value='true'>No</option>";
//************************************************************************************************************

$opcion_exento_contribucion = "<option selected value='false'>No</option>";

if (@$result->exento_contribuciones  == true) {
	$opcion_exento_contribucion = $opcion_exento_contribucion . "<option selected value='true'>Si</option>";
} else {
	$opcion_exento_contribucion = $opcion_exento_contribucion . "<option value='true'>Si</option>";
}

$precio = number_format(@$result->precio, 0, ',', '.');

//************************************************************************************************************

$opcion_paga_constribuciones = "<option selected value='Fuenzalida'>Fuenzalida</option>";

if (@$result->paga_contribuciones  == "Propietario") {
	$opcion_paga_constribuciones = $opcion_paga_constribuciones . "<option selected value='Propietario'>Propietario</option>";
} else {
	$opcion_paga_constribuciones = $opcion_paga_constribuciones . "<option value='Propietario'>Propietario</option>";
}

//************************************************************************************************************
$opcion_naturaleza = "<option selected value='No agrícola'>No agrícola</option>";

if (@$result->naturaleza  == "Agrícola") {
	$opcion_naturaleza = $opcion_naturaleza . "<option selected value='Agrícola'>Agrícola</option>";
} else {
	$opcion_naturaleza = $opcion_naturaleza . "<option value='Agrícola'>Agrícola</option>";
}


/*SELECTOR - destino arriendo - MANTENER PARA RENTDESK */
$num_reg = 50;
$inicio = 0;

$query = "SELECT * from propiedades.tp_destino_arriendo where habilitado = true ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json_destino_arriendo = json_decode($resultado);


$opcion_destino_arriendo = "<option value=''>Seleccione</option>";

foreach ($json_destino_arriendo as $item) {

	if (@$result->id_destino_arriendo == @$item->id) {
		$opcion_destino_arriendo = $opcion_destino_arriendo . "<option value='$item->id' selected >$item->nombre</option>";
	} else {
		$opcion_destino_arriendo = $opcion_destino_arriendo . "<option value='$item->id' >$item->nombre</option>";
	}
}

$opcion_destino_arriendo = "<select name='destinoArriendo' id='destinoArriendo' class='form-control form-select' placeholder='Elige un destino del bien raíz' required>
$opcion_destino_arriendo
</select>";




$opcion_banco = "<option value=''>Seleccione</option>";
// $data_banco = array("idEmpresa" => $id_company);
// $resp_banco = $services->sendPostNoToken($url_services . '/banco/listaByEmpresa', $data_banco);
// $bancos = json_decode($resp_banco);

// foreach ($bancos as $banco_r) {

// 	$select_banco = "";
// 	if (@$result->id_banco == @$banco_r->idBanco)
// 		$select_banco = " selected ";


// 	$opcion_banco = $opcion_banco . "<option value='$banco_r->idBanco' $select_banco >$banco_r->descripcion</option>";
// } //foreach($roles as $rol)

$opcion_banco = "<select id='banco' name='banco' class='form-control' required >
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


/*Consulta Cantidad de registros*/
//$QuerySucursales = "SELECT * from propiedades.cuenta_sucursal where habilitada = true AND activo = true";

$QuerySucursales = "SELECT propiedades.fn_consulta_sucursales($id_subsidiaria)";
$data = array("consulta" => $QuerySucursales);
$current_sucursales = json_decode($services->sendPostNoToken($url_services . '/util/objeto', $data));

$opcion_sucursal = "<option value=''>Seleccione</option>";


foreach ($current_sucursales as $obj) {

	foreach ($obj->fn_consulta_sucursales as $sucursal) {
		$select_sucursal = "";

		if (@$result->token_sucursal == @$sucursal->token) {
			$select_sucursal = " selected ";
		}

		$opcion_sucursal = $opcion_sucursal . "<option value='$sucursal->token' $select_sucursal >$sucursal->nombre</option>";
	}
}


$opcion_sucursal = "<select id='sucursal' name='sucursal' class='form-control  form-select' required >
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


//************************************************************************************************************
$comuna = @$result->comuna->id;
$region = @$result->comuna->region->idRegion;
$pais = @$result->comuna->region->pai->idPais;

$comunaCom = @$result->comuna->id;
$regionCom = @$result->comuna->region->idRegion;
$paisCom = @$result->comuna->region->pai->idPais;

// $comuna = @$result->direcciones[0]->comuna->id;
// $region = @$result->direcciones[0]->comuna->region->token;
// $pais = @$result->direcciones[0]->comuna->region->pais->token;

// $comunaCom = @$result->direcciones[0]->comuna->id;
// $regionCom = @$result->direcciones[0]->comuna->region->token;
// $paisCom = @$result->direcciones[0]->comuna->region->pais->token;


$loadPaisComunaRegion = "";
if ($token_pais != "" && $id_comuna != "" && $token_region != "") {
	$loadPaisComunaRegion = "
				$(document).ready(function () {
						seteaRegionComuna('0',  '$token_pais',  '$token_region',  '$id_comuna')
				});
";
} else {
	$loadPaisComunaRegion = "
			$(document).ready(function () {
				seteaRegionComuna('0',  '',  '',  '')
			});
	";
}
