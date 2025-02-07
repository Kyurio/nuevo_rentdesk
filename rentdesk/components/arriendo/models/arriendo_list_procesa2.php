<?php
session_start();
@include("../../../includes/sql_inyection.php");
@include("../../../configuration.php");
@include("../../../includes/funciones.php");
@include("../../../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$current_sucursal = unserialize($_SESSION["rd_current_sucursal"]);

@$inicio		= $_POST["start"];
@$num_reg		= $_POST["length"];
$num_reg_principal		= $_POST["length"];

$draw			= @$_POST["draw"];
$inicio			= @$_POST["start"];
$fin			= @$_POST["length"];
$busqueda 		= @$_POST["search"]["value"];

$cantidad_filtrados = 0;
$cantidad_registros = 0;
$coma = 0;
$datos		= "";
$signo_coma = "";
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



/****************** Busqueda con filtros *************************/
$codigo_propiedad = @$_GET["codigo_propiedad"];
//$EstadoArriendo = @$_POST["EstadoArriendo"]; // 1 - Activo , 2 - Inactivo , 3 - todos
$estadoPropiedad = @$_GET["estadoPropiedad"];
$estadoContrato = @$_GET["estadoContrato"];
$dniPropietario = @$_GET["Propietario"];
$dniArrendatario = @$_GET["Arrendatario"];
$activo = @$_GET["activos"];
$filtros = "";
$propiedadActivasFiltros = "";
$propiedadInactivasFiltros = "";
$propiedadTodosFiltros = "";



if ($codigo_propiedad != "" && $codigo_propiedad != null) {
	$filtros = $filtros . "AND p.codigo_propiedad  like '%$codigo_propiedad%' ";
}


if ($estadoContrato != "" && $estadoContrato != null) {
	$filtros = $filtros . "AND fa.id_estado_contrato = $estadoContrato ";
}

if ($estadoPropiedad != "" && $estadoPropiedad != null) {
	$filtros = $filtros . "AND p.id_estado_propiedad = $estadoPropiedad ";
}

if ($estadoPropiedad != "" && $estadoPropiedad != null) {
	$filtros = $filtros . "AND p.id_estado_propiedad = $estadoPropiedad ";
}


if ($dniPropietario != "" && $dniPropietario != null) {
	$filtros = $filtros . "AND vp.dni = '$dniPropietario' ";
}

if ($dniArrendatario != "" && $dniArrendatario != null) {
	$filtros = $filtros . "AND va.dni = '$dniArrendatario' ";
}

/*
if ($EstadoArriendo == 1){
	$propiedadActivasFiltros = $filtros;
}

if ($EstadoArriendo == 2){
	$propiedadInactivasFiltros = $filtros;
}

if ($EstadoArriendo == 3){
	$propiedadTodosFiltros = $filtros;
}
*/





/***********************************************************************************************************************************************/
$query = " select id from propiedades.cuenta_sucursal cs where token = '$current_sucursal->sucursalToken' ";
$cant_rows = 99999;
$num_pagina = round(0 / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado_sucursal = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
//var_dump($resultado);
$json_sucursal = json_decode($resultado_sucursal)[0];


//$num_reg = 10;
//$inicio = 0;

if ($activo == "1") {
	$query = "SELECT   upper(va.nombre_1 || ' '  || va.nombre_2  || ' ' ||  va.nombre_3 ) as nombre_arrendador , tep.nombre as estado_propiedad  ,p.id ,p.direccion||', N°'||p.numero  as propiedad,to_char(fa.fecha_inicio,'DD/MM/YYYY') as fecha_ingreso ,fa.precio,'#'||fa.id  as id_ficha, fa.token, p.id as propiedad_id, p.token AS propiedad_token
              from propiedades.ficha_arriendo_arrendadores a, 
              propiedades.vis_arrendatarios va, propiedades.ficha_arriendo fa , propiedades.propiedad p 
               ,propiedades.tp_estado_propiedad tep
              where va.id = a.id_arrendatario
 			  and fa.id_sucursal = $json_sucursal->id
              and fa.id = a.id_ficha_arriendo 
              and p.id  = fa.id_propiedad  
			  and p.id_estado_propiedad = tep.id
			  and fa.id_estado_contrato in (1)
			  and fa.habilitado = true
			  $filtros
              order by fa.id desc ";


	$data = array("consulta" => $query);
	$resultado = $services->sendPostNoToken($url_services . '/util/count', $data);
	$cantidad_registros = $resultado;



	$cant_rows = $num_reg_principal;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	//var_dump($resultado);
	$json = json_decode($resultado);





	$dataTableArriendoActivos = [];

	if ($json !== null) {
		// Iterate over each object in the array
		foreach ($json as $obj) {
			if ($coma == 1)
				$signo_coma = ",";

			$coma = 1;

			$cantidad_filtrados++;
			$query_estado_contrato = "SELECT tca.nombre as estado_contrato FROM propiedades.tp_contrato_arriendo tca , propiedades.ficha_arriendo fa 
				where fa.token = '$obj->token' and  fa.id_estado_contrato = tca.id ";
			$cant_rows = $num_reg;
			$num_pagina = round($inicio / $cant_rows) + 1;
			$data = array("consulta" => $query_estado_contrato, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
			$resultado_estado_contrato = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
			//var_dump($resultado);
			@$json_estado_contrato = json_decode($resultado_estado_contrato)[0];

			$montoFomateado = number_format($obj->precio, 0, '.', '.');


			// Transform each field of the object
			$propietarios_con_saltos = "";
			$propietarios = "";
			$query = "  SELECT vp.nombre_1 ||' '|| vp.nombre_2 ||' | ' || vp.dni as info_propietario ,pc.nivel_propietario,  pc.id_propietario
										from propiedades.propiedad_copropietarios pc, 
										propiedades.vis_propietarios vp ,propiedades.propietario_ctas_bancarias pcb , propiedades.tp_banco tb , propiedades.tp_tipo_persona ttp 
											where pc.id_propietario = vp.id
											and pcb.id_propietario  = pc.id_propietario  
											and pc.id_propiedad = $obj->propiedad_id 
											and pcb.id = pc.id_cta_bancaria
											and tb.id = pcb.id_banco
											and vp.id_tipo_persona = ttp.id 
											and pc.habilitado  = true
										union
										select pb.nombre ||' | ' || pb.rut as info_propietario ,pc.nivel_propietario,  pc.id_propietario from propiedades.propiedad_copropietarios pc, 
										propiedades.persona_beneficiario pb ,propiedades.vis_propietarios vp ,  propiedades.tp_banco tb
										where pc.id_propietario = vp.id
										and pc.id_propiedad = $obj->propiedad_id 
										and pc.id_beneficiario = pb.id 
										and tb.id = pb.cta_id_banco
										and pc.habilitado  = true
										order by id_propietario , nivel_propietario   asc";
			$cant_rows = $num_reg;
			$num_pagina = round($inicio / $cant_rows) + 1;
			$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
			$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
			//var_dump($query);
			@$json_propietarios = json_decode($resultado);
			//var_dump($json_propietarios);
			if ($json_propietarios != "") {
				foreach ($json_propietarios as $obj_propietarios) {
					if ($obj_propietarios->nivel_propietario == 2) {
						$propietarios = $propietarios . "zzz" . $obj_propietarios->info_propietario;
					} else {
						$propietarios = $propietarios . "xxx" . $obj_propietarios->info_propietario;
					}
				}
			}
			$propietarios_con_saltos = str_replace("xxx", "<br><i class='fa-solid fa-house-user' style='color:#515151;font-size:12px;' title='Propietario' ></i> ", $propietarios);
			$propietarios_con_saltos = str_replace("zzz", "<br>&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa-solid fa-user-large' style='font-size:10px; color:#707070;' title='Beneficiario'></i> ", $propietarios_con_saltos);


			// Transform each field of the object
			$transformedObj = [
				'propiedad' => validateNull($obj->propiedad ?? null),
				'estado_propiedad' => validateNull($obj->estado_propiedad ?? null),
				'estado_contrato' => validateNull($json_estado_contrato->estado_contrato ?? null),
				'Propietario' => validateNull($propietarios_con_saltos ?? null),
				'Arrendatario' => validateNull($obj->nombre_arrendador ?? null),
				'Fecha_Inicio' => validateNull($obj->fecha_ingreso ?? null),
				'Precio' => validateNull($montoFomateado ?? null),
				'ficha_tecnica' => validateNull($obj->id_ficha ?? null),
				'token' => validateNull($obj->token ?? null),

			];

			$botones =  "<a href='index.php?view=arriendo_editar&component=arriendo&token=$obj->token' type='button' class='btn btn-info m-0' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i> </a>";
			$ficha_arriendo = "<a href='index.php?component=arriendo&view=arriendo_ficha_tecnica&token=$obj->token' class='link-info' > $obj->id_ficha</a>";
			$datos = $datos . "
				$signo_coma
				[
				
				\"$ficha_arriendo \",
				\"<a href='index.php?component=propiedad&view=propiedad_ficha_tecnica&token=$obj->propiedad_token' target='_blank'> #$obj->propiedad_id</a>  - $obj->propiedad \",
				\"$obj->estado_propiedad\",
				\"$json_estado_contrato->estado_contrato\",
				\"$propietarios_con_saltos\",
				\"$obj->nombre_arrendador\",
				\"$obj->fecha_ingreso\",
				\"$montoFomateado\",
				\"$botones\"
				]";


			// Push the transformed object into the array
			$dataTableArriendoActivos[] = $transformedObj;
		}
	}
}


if ($activo == "2") {
	$query = "SELECT  upper(va.nombre_1 || ' '  || va.nombre_2  || ' ' ||  va.nombre_3 ) as nombre_arrendador , tep.nombre as estado_propiedad  ,p.id ,p.direccion||', N°'||p.numero  as propiedad,to_char(fa.fecha_ingreso,'DD/MM/YYYY') as fecha_ingreso ,fa.precio,'#'||fa.id  as id_ficha, fa.token, p.id as propiedad_id, p.token AS propiedad_token
              from propiedades.ficha_arriendo_arrendadores a, 
              propiedades.vis_arrendatarios va, propiedades.ficha_arriendo fa , propiedades.propiedad p 
               ,propiedades.tp_estado_propiedad tep
              where va.id = a.id_arrendatario
 			  and fa.id_sucursal = $json_sucursal->id
              and fa.id = a.id_ficha_arriendo 
              and p.id  = fa.id_propiedad  
			  and p.id_estado_propiedad = tep.id
			  and fa.id_estado_contrato in (2)
			  and fa.habilitado = true
			  $filtros
              order by fa.id desc ";
	//var_dump($query);

	$data = array("consulta" => $query);
	$resultado = $services->sendPostNoToken($url_services . '/util/count', $data);
	$cantidad_registros = $resultado;



	$cant_rows = $num_reg_principal;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	//var_dump($resultado);
	$json = json_decode($resultado);





	$dataTableArriendoActivos = [];

	if ($json !== null) {
		// Iterate over each object in the array
		foreach ($json as $obj) {
			if ($coma == 1)
				$signo_coma = ",";

			$coma = 1;

			$cantidad_filtrados++;
			$query_estado_contrato = "SELECT tca.nombre as estado_contrato FROM propiedades.tp_contrato_arriendo tca , propiedades.ficha_arriendo fa 
				where fa.token = '$obj->token' and  fa.id_estado_contrato = tca.id ";
			$cant_rows = $num_reg;
			$num_pagina = round($inicio / $cant_rows) + 1;
			$data = array("consulta" => $query_estado_contrato, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
			$resultado_estado_contrato = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
			//var_dump($resultado);
			@$json_estado_contrato = json_decode($resultado_estado_contrato)[0];

			$montoFomateado = number_format($obj->precio, 0, '.', '.');


			// Transform each field of the object
			$propietarios_con_saltos = "";
			$propietarios = "";
			$query = "   SELECT vp.nombre_1 ||' '|| vp.nombre_2 ||' | ' || vp.dni as info_propietario ,pc.nivel_propietario,  pc.id_propietario
										from propiedades.propiedad_copropietarios pc, 
										propiedades.vis_propietarios vp ,propiedades.propietario_ctas_bancarias pcb , propiedades.tp_banco tb , propiedades.tp_tipo_persona ttp 
											where pc.id_propietario = vp.id
											and pcb.id_propietario  = pc.id_propietario  
											and pc.id_propiedad = $obj->propiedad_id 
											and pcb.id = pc.id_cta_bancaria
											and tb.id = pcb.id_banco
											and vp.id_tipo_persona = ttp.id 
											and pc.habilitado  = true
										union
										select pb.nombre ||' | ' || pb.rut as info_propietario ,pc.nivel_propietario,  pc.id_propietario from propiedades.propiedad_copropietarios pc, 
										propiedades.persona_beneficiario pb ,propiedades.vis_propietarios vp ,  propiedades.tp_banco tb
										where pc.id_propietario = vp.id
										and pc.id_propiedad = $obj->propiedad_id 
										and pc.id_beneficiario = pb.id 
										and tb.id = pb.cta_id_banco
										and pc.habilitado  = true
										order by id_propietario , nivel_propietario   asc ";
			$cant_rows = $num_reg;
			$num_pagina = round($inicio / $cant_rows) + 1;
			$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
			$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
			//var_dump($query);
			@$json_propietarios = json_decode($resultado);
			//var_dump($json_propietarios);
			if ($json_propietarios != "") {
				foreach ($json_propietarios as $obj_propietarios) {
					if ($obj_propietarios->nivel_propietario == 2) {
						$propietarios = $propietarios . "zzz" . $obj_propietarios->info_propietario;
					} else {
						$propietarios = $propietarios . "xxx" . $obj_propietarios->info_propietario;
					}
				}
			}
			$propietarios_con_saltos = str_replace("xxx", "<br><i class='fa-solid fa-house-user' style='color:#515151;font-size:12px;' title='Propietario' ></i> ", $propietarios);
			$propietarios_con_saltos = str_replace("zzz", "<br>&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa-solid fa-user-large' style='font-size:10px; color:#707070;' title='Beneficiario'></i> ", $propietarios_con_saltos);


			// Transform each field of the object
			$transformedObj = [
				'propiedad' => validateNull($obj->propiedad ?? null),
				'estado_propiedad' => validateNull($obj->estado_propiedad ?? null),
				'estado_contrato' => validateNull($json_estado_contrato->estado_contrato ?? null),
				'Propietario' => validateNull($propietarios_con_saltos ?? null),
				'Arrendatario' => validateNull($obj->nombre_arrendador ?? null),
				'Fecha_Inicio' => validateNull($obj->fecha_ingreso ?? null),
				'Precio' => validateNull($montoFomateado ?? null),
				'ficha_tecnica' => validateNull($obj->id_ficha ?? null),
				'token' => validateNull($obj->token ?? null),

			];

			$botones =  "<a href='index.php?view=arriendo_editar&component=arriendo&token=$obj->token' type='button' class='btn btn-info m-0' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i> </a>";
			$ficha_arriendo = "<a href='index.php?view=arriendo_editar&component=arriendo=$obj->token' class='link-info' > $obj->id_ficha</a>";
			$datos = $datos . "
				$signo_coma
				[
				
				\"$ficha_arriendo\",
			\"<a href='http://localhost:8080/rentdesk/index.php?component=propiedad&view=propiedad_ficha_tecnica&token=$obj->propiedad_token' target='_blank'> #$obj->propiedad_id</a>  - $obj->propiedad \",
				\"$obj->estado_propiedad\",
				\"$json_estado_contrato->estado_contrato\",
				\"$propietarios_con_saltos\",
				\"$obj->nombre_arrendador\",
				\"$obj->fecha_ingreso\",
				\"$montoFomateado\",
				\"$botones\"
				]";


			// Push the transformed object into the array
			$dataTableArriendoActivos[] = $transformedObj;
		}
	}
}



function redirectToPropiedadUrl($token)
{

	$addTokenQuery = "&token=" . urlencode($token);



	$redirectURL = "index.php?view=arriendo_editar&component=arriendo" . $addTokenQuery;


	return $redirectURL;
}


function validateNull($item)
{
	return is_null($item) || $item === "" ? "-" : $item;
}



if ($cantidad_registros == "") {
	$cantidad_registros = 0;
}

echo "
{
  \"draw\": $draw,
  \"recordsTotal\": $cantidad_registros,
  \"recordsFiltered\": $cantidad_registros,
  \"data\": [
    $datos
  ]
}


";
