<?php

session_start();
@include("../../../includes/sql_inyection.php");
@include("../../../configuration.php");
@include("../../../includes/funciones.php");
@include("../../../includes/services_util.php");
echo "";
//************************************************************************************************************

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
@$inicio		= $_POST["start"];
@$num_reg		= $_POST["length"];
@$num_reg_principal		= $_POST["length"];

$draw			= @$_POST["draw"];
$inicio			= @$_POST["start"];
$fin			= @$_POST["length"];
$busqueda 		= @$_POST["search"]["value"];

$cantidad_filtrados = 0;
$cantidad_registros = 0;

	$c=0;
$lista = "";

@$inicio = 1;
@$num_reg_principal = 99999;
@$num_regs = 99999;


$orden 		= "";
if (!empty($_POST["order"][0]["column"]))
$orden 		= @$_POST["order"][0]["column"];

$direccion = "";
if (!empty($_POST["order"][0]["dir"]))
$direccion = @$_POST["order"][0]["dir"];
 


$current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$current_sucursales = unserialize($_SESSION['sesion_rd_sucursales']);
$current_sucursal = unserialize($_SESSION["rd_current_sucursal"]);

//var_dump("SUCURSAL ACTUAL: ", $current_sucursal);
$_SESSION["sesion_rd_current_propiedad_token"] = null;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
$token	= @$_GET["token"];
$propietarios = "";
$coma = 0;
$datos		= "";
$signo_coma = "";
//$ficha_propiedad = ""




if ($inicio == ""){
	$inicio = 0;
}
if ($num_reg == ""){
	$num_reg = 99999;
}
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

/*PRUEBA OBTENCIÓN LISTADO PROPIEDADES */
/*Consulta Cantidad de registros*/
// $query_count = "SELECT * FROM propiedades.propiedad where id_subsidiaria = $current_subsidiaria->id";

// $data = array("consulta" => $query_count);
// $resultado = $services->sendPostNoToken($url_services . '/util/count', $data);
// $cantidad_registros = $resultado;

// //var_dump("CANTIDAD PROPIEDADES: ", json_decode($cantidad_registros));


$query = "SELECT cs.nombre as nombre_sucursal ,vp.*
FROM propiedades.vis_propiedades vp , propiedades.cuenta_sucursal cs 
WHERE vp.habilitado = true 
and cs.id  = vp.id_sucursal 
AND vp.token_sucursal in (
	select token_sucursal from propiedades.fn_sucursales_por_usuario(
		'$current_usuario->token',
		'$current_subsidiaria->token',
		'$current_sucursal->sucursalToken'
		)
	
)  order by id_propiedad desc	";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


/*BUSQUEDA CON FILTRO LISTA PROPIEDADES */
$filtro_direccion = @$_GET["filtro_direccion"] ?? null;
$filtro_codigo_propiedad = @$_GET["codigo_propiedad"] ?? null;
$filtro_sucursal = @$_GET["filtro_sucursal"] ?? null;
$filtro_tipo_propiedad = @$_GET["tipoPropiedad"] ?? null;
$filtro_tipo_ejecutivo = @$_GET["ejecutivo"] ?? null;
$filtro_estado_ejecutivo = @$_GET["estadoPropiedad"] ?? null;
$filtro_numero = @$_GET["Numero"] ?? null;
$filtro_numero_departamento = @$_GET["Depto"] ?? null;
$filtro_region = @$_GET["region"] ?? null;
$filtro_comuna = @$_GET["comuna"] ?? null;  
$filtro_propietario = @$_GET["propietario"] ?? null; 

//var_dump("FILTROS: " $filtro_codigo_propiedad);

if ($filtro_region != ""){
	
	

	$query = " select * from propiedades.tp_region where token = '$filtro_region' ";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$json_region = json_decode($resultado)[0];	
}

if ($filtro_comuna != "" ){
	
	$query = " select * from propiedades.tp_comuna where id = $filtro_comuna ";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$json_comuna = json_decode($resultado)[0];	
}



$num_reg = 99999;
$inicio = 0;

if ($filtro_propietario == ""){

$query = "SELECT cs.nombre as nombre_sucursal ,vp.* ,cu.*
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
	
) 	
";

}else{
	$query = " select distinct  vp.id_propiedad
FROM propiedades.vis_propiedades vp , propiedades.cuenta_sucursal cs ,propiedades.cuenta_usuario cu , 
propiedades.propiedad_copropietarios pc , propiedades.vis_propietarios vp2 
WHERE vp.habilitado = true 
and cs.id  = vp.id_sucursal 
and cu.id = vp.id_ejecutivo
and vp.id_propiedad  = pc.id_propiedad 
and pc.id_propietario  = vp2.id 
and pc.habilitado  = true
AND vp.token_sucursal in (
	select token_sucursal from propiedades.fn_sucursales_por_usuario(
		'$current_usuario->token',
		'$current_subsidiaria->token',
		'$current_sucursal->sucursalToken'
		)
	
) ";
}






$whereConditions = [];



if (isset($filtro_direccion) && $filtro_direccion !== "") {
	$whereConditions[] = "(LOWER(direccion) LIKE LOWER('%$filtro_direccion%'))";
}


if (isset($filtro_codigo_propiedad)  && $filtro_codigo_propiedad !== "") {
	$whereConditions[] = "codigo_propiedad = '$filtro_codigo_propiedad'";
}
if (isset($filtro_sucursal) && $filtro_sucursal !== "") {
	$whereConditions[] = "token_sucursal = '$filtro_sucursal'";
}


if (isset($filtro_tipo_propiedad) && $filtro_tipo_propiedad !== "") {
	$whereConditions[] = "tipo_propiedad = '$filtro_tipo_propiedad'";
}

if (isset($filtro_tipo_ejecutivo) && $filtro_tipo_ejecutivo !== "") {
	$whereConditions[] = "upper(correo) like UPPER('%$filtro_tipo_ejecutivo%') ";
}

if (isset($filtro_estado_ejecutivo) && $filtro_estado_ejecutivo !== "") {
	$whereConditions[] = "upper(estado_propiedad) = UPPER('$filtro_estado_ejecutivo') ";
}

if (isset($filtro_numero) && $filtro_numero !== "") {
	$whereConditions[] = "upper(numero) like UPPER('%$filtro_numero%') ";
}

if (isset($filtro_numero_departamento) && $filtro_numero_departamento !== "") {
	$whereConditions[] = "upper(numero_depto) like UPPER('%$filtro_numero_departamento%') ";
}

if (isset($filtro_region) && $filtro_region !== "" && $filtro_comuna == "" ) {
	$whereConditions[] = "upper(region) = UPPER('$json_region->nombre') ";
}

if (isset($filtro_comuna) && $filtro_comuna !== "" ) {
	$whereConditions[] = "upper(comuna) = UPPER('$json_comuna->nombre') ";
}

if (isset($filtro_propietario) && $filtro_propietario !== "") {
	$whereConditions[] = " ( UPPER(vp2.nombre_1) LIKE UPPER('%$filtro_propietario%')
OR UPPER(vp2.nombre_2) LIKE ('%$filtro_propietario%') 
OR UPPER(REPLACE(REPLACE(vp2.dni,'-',''),'.','')) LIKE UPPER(REPLACE(REPLACE('%$filtro_propietario%','-',''),'.',''))
) 
	 ";
}


$data = array("consulta" => $query);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;



 

 

if (!empty($whereConditions)) {
	$query .= " AND " . implode(" OR ", $whereConditions) ;
	$query .= " order by id_propiedad desc ";
}else{
	$query .= " order by id_propiedad desc ";
}


// var_dump("QUERY ACTUAL: ", $query);
// //var_dump("FILTRO DNI: ", $filtro_dni);
// //var_dump("FILTRO NOMBRE: ", $filtro_nombre);
// //var_dump("FILTRO CORREO: ", $filtro_correo);








//$cant_rows = $num_reg;
//$num_pagina = round($inicio / $cant_rows) + 1;
//var_dump($query);
//$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
//$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);

$data = array("consulta" => $query, "cantRegistros" => $num_reg_principal, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);



$json = json_decode($resultado);

$dataTablePropiedades = [];



if ($json !== null) {
if ($filtro_propietario == ""){
	// Iterate over each object in the array
	foreach ($json as $obj) {
		
					$c++;
			$fondo = " #ffffff ";
		
			if($c % 2 == 0)
			$fondo = " #ffffff ";
		
		
		if($coma==1)
$signo_coma = ",";

$cantidad_filtrados++;

$coma = 1;
		// Transform each field of the object
		$propietarios_con_saltos = "";
		$propietarios = "";
		    $query = "   SELECT vp.nombre_1 ||' '|| vp.nombre_2 ||' | ' || vp.dni as info_propietario ,pc.nivel_propietario,  pc.id_propietario
							from propiedades.propiedad_copropietarios pc, 
							propiedades.vis_propietarios vp ,propiedades.propietario_ctas_bancarias pcb , propiedades.tp_banco tb , propiedades.tp_tipo_persona ttp 
								where pc.id_propietario = vp.id
								and pcb.id_propietario  = pc.id_propietario  
								and pc.id_propiedad = $obj->id_propiedad 
								and pcb.id = pc.id_cta_bancaria
								and tb.id = pcb.id_banco
								and vp.id_tipo_persona = ttp.id 
								and pc.habilitado  = true
							union
							select pb.nombre ||' | ' || pb.rut as info_propietario ,pc.nivel_propietario,  pc.id_propietario from propiedades.propiedad_copropietarios pc, 
							propiedades.persona_beneficiario pb ,propiedades.vis_propietarios vp ,  propiedades.tp_banco tb
							where pc.id_propietario = vp.id
							and pc.id_propiedad = $obj->id_propiedad 
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
			foreach ($json_propietarios as $obj_propietarios) {
				if ($obj_propietarios->nivel_propietario == 2 ){
					$propietarios = $propietarios."zzz".$obj_propietarios->info_propietario;
				}else{
					$propietarios = $propietarios."xxx".$obj_propietarios->info_propietario;
				}
				
			}
			
			$query2 = "SELECT 'SI' as existe FROM propiedades.ficha_arriendo a where a.id_propiedad = $obj->id_propiedad 
				 ";
			$cant_rows = $num_reg;
			//var_dump($query);
            $num_pagina = round($inicio / $cant_rows) + 1;
            $data = array("consulta" => $query2, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
            $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
            $json_existe_arriendo = json_decode($resultado)[0];
			
			$propietarios_con_saltos = str_replace("xxx", "<br><i class='fa-solid fa-house-user' style='color:#515151;font-size:12px;' title='Propietario' ></i> ", $propietarios);	
			$propietarios_con_saltos = str_replace("zzz", "<br>&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa-solid fa-user-large' style='font-size:10px; color:#707070;' title='Beneficiario'></i> ", $propietarios_con_saltos);	
			
		$transformedObj = [
			'nombre_sucursal' => validateNull($obj->nombre_sucursal ?? null),
			'ejecutivo' => validateNull($obj->correo ?? null),
			'tipo_propiedad' => validateNull($obj->tipo_propiedad ?? null),
			'propietario' => validateNull($propietarios_con_saltos ?? null),
			'comuna' => validateNull($obj->comuna ?? null),
			'region' => validateNull($obj->region ?? null),
			'direccion' => validateNull($obj->direccion ?? null),
			'numero' => validateNull($obj->numero ?? null),
			'numero_depto' => validateNull($obj->numero_depto ?? null),
			'id_estado_propiedad' => validateNull($obj->estado_propiedad ?? null),
			'id_contrato' => validateNull($obj->id_contrato ?? null),
			'historial_contratos' => validateNull($obj->historial_contratos ?? null),
			'historial_visitas' => validateNull($obj->historial_visitas ?? null),
			'ficha_tecnica' => validateNull($obj->id_propiedad ?? null),
			'token' => validateNull($obj->token_propiedad ?? null),

		];
		

		//var_dump("token",$obj->token);
		$botones =  "<a href='index.php?component=propiedad&view=propiedad&token=$obj->token_propiedad' type='button' class='btn btn-info m-0' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i> </a>";
		
		if (@$json_existe_arriendo->existe != "SI"){
		$botones = $botones."<button type='button' onclick='eliminarPropiedad($obj->id_propiedad)' class='btn btn-danger m-0' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i> </button>";
		}
		$ficha_propiedad = "<a href='index.php?component=propiedad&view=propiedad_ficha_tecnica&token=$obj->token_propiedad' class='link-info' > #$obj->id_propiedad</a>";


	 

		$datos = $datos ."
     $signo_coma
	 [
	 
	   \"$ficha_propiedad\",
	  \"$obj->nombre_sucursal\",
	  \"$obj->correo\",
	  	  \"$propietarios_con_saltos\",
	  \"$obj->tipo_propiedad\",
	  \"$obj->direccion\",
	  \"$obj->comuna\",
	  \"$obj->region\",
	  \"$obj->estado_propiedad\",
      \"$botones\"
    ]";
		
		
		$lista = $lista. "
		<tr>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$ficha_propiedad</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->nombre_sucursal</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->correo</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$propietarios_con_saltos</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->tipo_propiedad</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->direccion</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->comuna</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->region</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->estado_propiedad</td>
		</tr>
		";
	}
}else{
	
		foreach ($json as $obj) {
			
						$c++;
			$fondo = " #ffffff ";
		
			if($c % 2 == 0)
			$fondo = " #ffffff ";
			
			
			
if($coma==1)
$signo_coma = ",";

$coma = 1;
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
				 	
				 ) 	and  vp.id_propiedad = $obj->id_propiedad 
				 ";
			$cant_rows = $num_reg;
			//var_dump($query);
            $num_pagina = round($inicio / $cant_rows) + 1;
            $data = array("consulta" => $query2, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
            $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
            $json_propiedades = json_decode($resultado);
		//var_dump($json_propiedades);
		
		    $query = " SELECT vp.nombre_1 ||' '|| vp.nombre_2 ||' | ' || vp.dni as info_propietario ,pc.nivel_propietario,  pc.id_propietario
							from propiedades.propiedad_copropietarios pc, 
							propiedades.vis_propietarios vp ,propiedades.propietario_ctas_bancarias pcb , propiedades.tp_banco tb , propiedades.tp_tipo_persona ttp 
								where pc.id_propietario = vp.id
								and pcb.id_propietario  = pc.id_propietario  
								and pc.id_propiedad = $obj->id_propiedad 
								and pcb.id = pc.id_cta_bancaria
								and tb.id = pcb.id_banco
								and vp.id_tipo_persona = ttp.id 
								and pc.habilitado  = true
							union
							select pb.nombre ||' | ' || pb.rut as info_propietario ,pc.nivel_propietario,  pc.id_propietario from propiedades.propiedad_copropietarios pc, 
							propiedades.persona_beneficiario pb ,propiedades.vis_propietarios vp ,  propiedades.tp_banco tb
							where pc.id_propietario = vp.id
							and pc.id_propiedad = $obj->id_propiedad 
							and pc.id_beneficiario = pb.id 
							and tb.id = pb.cta_id_banco
							and pc.habilitado  = true 
							order by id_propietario , nivel_propietario   asc ";
            $cant_rows = $num_reg;
            $num_pagina = round($inicio / $cant_rows) + 1;
            $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
            $resultado2 = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	        //var_dump($resultado);
            @$json_propietarios = json_decode($resultado2);
			
			foreach ($json_propietarios as $obj_propietarios) {
				if ($obj_propietarios->nivel_propietario == 2 ){
					$propietarios = $propietarios."zzz".$obj_propietarios->info_propietario;
				}else{
					$propietarios = $propietarios."xxx".$obj_propietarios->info_propietario;
				}
				
			}
			
			
					$query2 = "SELECT 'SI' as existe FROM propiedades.ficha_arriendo a where a.id_propiedad = $obj->id_propiedad 
				 ";
			$cant_rows = $num_reg;
			//var_dump($query);
            $num_pagina = round($inicio / $cant_rows) + 1;
            $data = array("consulta" => $query2, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
            $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
            $json_existe_arriendo = json_decode($resultado)[0];

			$propietarios_con_saltos = str_replace("xxx", "<br><i class='fa-solid fa-house-user' style='color:#515151;font-size:12px;' title='Propietario' ></i> ", $propietarios);	
			$propietarios_con_saltos = str_replace("zzz", "<br>&nbsp;&nbsp;&nbsp;&nbsp;<i class='fa-solid fa-user-large' style='font-size:10px; color:#707070;' title='Beneficiario'></i> ", $propietarios_con_saltos);	
 
			
		$transformedObj = [
		    
			'nombre_sucursal' => validateNull($json_propiedades[0]->nombre_sucursal ?? null),
			'ejecutivo' => validateNull($json_propiedades[0]->correo ?? null),
			'tipo_propiedad' => validateNull($json_propiedades[0]->tipo_propiedad ?? null),
			'propietario' => validateNull($propietarios_con_saltos ?? null),
			'comuna' => validateNull($json_propiedades[0]->comuna ?? null),
			'region' => validateNull($json_propiedades[0]->region ?? null),
			'direccion' => validateNull($json_propiedades[0]->direccion ?? null),
			'numero' => validateNull($json_propiedades[0]->numero ?? null),
			'numero_depto' => validateNull($json_propiedades[0]->numero_depto ?? null),
			'id_estado_propiedad' => validateNull($json_propiedades[0]->estado_propiedad ?? null),
			'id_contrato' => validateNull($obj->id_contrato ?? null),
			'historial_contratos' => validateNull($json_propiedades[0]->historial_contratos ?? null),
			'historial_visitas' => validateNull($json_propiedades[0]->historial_visitas ?? null),
			'ficha_tecnica' => validateNull($json_propiedades[0]->id_propiedad ?? null),
			'token' => validateNull($json_propiedades[0]->token_propiedad ?? null),

		];
		$token = $json_propiedades[0]->token;
		$token_propiedad= $json_propiedades[0]->token_propiedad;
		$nombre_sucursal = $json_propiedades[0]->nombre_sucursal;
		$correo = $json_propiedades[0]->correo;
		$tipo_propiedad = $json_propiedades[0]->tipo_propiedad;
		$direccion = $json_propiedades[0]->direccion;
		$comuna = $json_propiedades[0]->comuna;
		$region = $json_propiedades[0]->region;
		$estado_propiedad = $json_propiedades[0]->estado_propiedad;

		$botones =  "<a href='index.php?component=propiedad&view=propiedad&token=$token' type='button' class='btn btn-info m-0' style='padding: .5rem;' aria-label='Editar' title='Editar'><i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i> </a>";
		
		if (@$json_existe_arriendo->existe != "SI"){
			$botones = $botones."<button type='button' onclick='eliminarPropiedad(\\\"$token\\\")' class='btn btn-danger m-0' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i> </button>";

		}else{
			//$botones = $botones."<button type='button' onclick='avisoPropiedad()' class='btn btn-danger m-0' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i> </button>";

		}
		
		$ficha_propiedad = "<a href='index.php?component=propiedad&view=propiedad_ficha_tecnica&token=$token_propiedad' class='link-info' > #$obj->id_propiedad</a>";

		
		$datos = $datos ."
     $signo_coma
	 [
	 
	  \"$ficha_propiedad\",
	  \"$nombre_sucursal\",
	  \"$correo\",
	  \"$propietarios_con_saltos\",
	  \"$tipo_propiedad\",
	  \"$direccion\",
	  \"$comuna\",
	  \"$region\",
	  \"$estado_propiedad\",
      \"$botones\"
    ]";

		// Push the transformed object into the array
		$dataTablePropiedades[] = $transformedObj;
		
		
		$lista = $lista. "
		<tr>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$ficha_propiedad</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$nombre_sucursal</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$correo</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$propietarios_con_saltos</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$tipo_propiedad</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$direccion</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$comuna</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$region</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$estado_propiedad</td>
		</tr>
		";
		
	}
	
}
}

function validateNull($item)
{
	return is_null($item) || $item === "" ? "-" : $item;
}

function redirectToPropiedadUrl($token)
{

	$addTokenQuery = "&token=" . urlencode($token);



	$redirectURL = "index.php?component=propiedad&view=propiedad" . $addTokenQuery;


	return $redirectURL;
}

function redirectToCreatePropiedad()
{

	$redirectURL = "index.php?component=propiedad&view=propiedad";

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

$opcion_tipo_propiedad = "<select id='tipo_propiedad' name='tipo_propiedad' class='form-control  form-select' required >
$opcion_tipo_propiedad
</select>";


// $dataTablePropiedades = array(
// 	array("123456789", "ginaguerra@fuenzalida.com", "Departamento", "INVERSIONES ORION LTDA.", "Metropolitana", "Cerrillos", "-", "840", "203", "Vigente", "-", "-", "-", "#79653"),
// 	array("123456789", "ginaguerra@fuenzalida.com", "Departamento", "INVERSIONES ORION LTDA.", "Metropolitana", "Cerrillos", "-", "840", "203", "Vigente", "-", "-", "-", "#75959"),
// 	array("123456789", "ginaguerra@fuenzalida.com", "Departamento", "INVERSIONES ORION LTDA.", "Metropolitana", "Cerrillos", "-", "840", "203", "Vigente", "-", "-", "-", "#79951"),
// 	array("123456789", "ginaguerra@fuenzalida.com", "Departamento", "ALBERTO JAVIER MORDOJ ARENAS", "Metropolitana", "Cerrillos", "-", "840", "203", "Vigente", "-", "-", "-", "#83334"),
// 	array("123456789", "macarenaibaceta@fuenzalida.com", "Departamento", "MANUEL DAVID LEIVA SOTO", "Metropolitana", "Cerrillos", "-", "840", "203", "Vigente", "-", "-", "-", "#77148"),
// );

//************************************************************************************************************

//************************************************************************************************************

$opcion_sucursal = "<option value=''>Seleccione</option>";

foreach ($current_sucursales as $item) {
	$select_sucursal = "";

	if (@$result->token_sucursal == @$item->sucursalToken) {
		$select_sucursal = " selected ";
	}

	$opcion_sucursal = $opcion_sucursal . "<option value='$item->sucursalToken' $select_sucursal >$item->sucursalNombre</option>";
}


$opcion_sucursal = "<select id='filtro_sucursal' name='filtro_sucursal' class='form-control  form-select' >
$opcion_sucursal
</select>";

//************************************************************************************************************
/*SELECTOR - TIPO PROPIEDAD - MANTENER PARA RENTDESK */
$num_regP = 999;
$inicio = 0;

$query = "SELECT * from propiedades.tp_tipo_propiedad";
$cant_rows = $num_regP;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


$opcion_tipo_propiedad = "<option value='' selected >Seleccione</option>";

foreach ($json as $item) {
	$selected_tipo_propiedad = "";
	$select_tipo_propiedad = "";

/*
	if (@$result->id_tipo_propiedad == @$item->id) {
		$selected_tipo_propiedad = @$item->id;
		$select_tipo_propiedad = " selected ";
	}
*/
	$opcion_tipo_propiedad = $opcion_tipo_propiedad . "<option value='$item->nombre' >$item->nombre</option>";
}

$opcion_tipo_propiedad = "<select id='tipoPropiedad' name='tipoPropiedad' class='form-control  form-select'  >
$opcion_tipo_propiedad
</select>";

//************************************************************************************************************

//************************************************************************************************************
/*SELECTOR - ESTADO PROPIEDAD - MANTENER PARA RENTDESK */
$num_regS = 999;
$inicio = 0;

$query = "SELECT * from propiedades.tp_estado_propiedad where habilitado = true ";
$cant_rows = $num_regS;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado);


$opcion_estado_propiedad = "<option value='' selected >Seleccione</option>";

foreach ($json as $item) {
	$selected_tipo_propiedad = "";
	$select_tipo_propiedad = "";

/*
	if (@$result->id_tipo_propiedad == @$item->id) {
		$selected_tipo_propiedad = @$item->id;
		$select_tipo_propiedad = " selected ";
	}
*/
	$opcion_estado_propiedad = $opcion_estado_propiedad . "<option value='$item->nombre' >$item->nombre</option>";
}

$opcion_estado_propiedad = "<select id='estadoPropiedad' name='estadoPropiedad' class='form-control  form-select'  >
$opcion_estado_propiedad
</select>";

//************************************************************************************************************

$id_comuna = "";
$token_region = "";
$loadPaisComunaRegion = "";
//Token de Chile
if ($id_comuna != "" && $token_region != "") {
	$loadPaisComunaRegion = "
				$(document).ready(function () {
						seteaRegionComuna('3',  'b90a06d886e548a3153f4c9148724ff5',  '$token_region',  '$id_comuna')
				});
";
} else {
	$loadPaisComunaRegion = "
			$(document).ready(function () {
				seteaRegionComuna('3',  'b90a06d886e548a3153f4c9148724ff5',  '',  '')
			});
	";
}



 $tabla =  "<table   border='0' cellspacing='0' cellpadding='1'>
		<tr>
		<td colspan='14' style='background-color:#C0C0C0;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:14px; text-align:center;'> Excel Propiedad </td>
		</tr>
		<tr>
		<td colspan='14' style='background-color:$fondo;  color: #000000;  font-family:Arial; font-size:12px; text-align:right;'> </td>
		</tr>
		
		<tr>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Id Ficha</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Oficina captadora</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Ejecutivo</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Propiedad/Beneficiario</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Tipo Propiedad</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Direccion</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Comuna</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Region</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Estado</td>


		</tr>
 
 $lista
  </table>";
	 
	  $excel = "
		<!doctype html>
		<html>
		<head>
		<meta charset='utf-8'>
		<title>Informacion Clientes</title>
		</head>
		<body>
			$tabla
		</body>
		</html>
		";

$texto_excel =$excel;
$aleatorio = rand(99,999999);
//$ruta = "components/pv_informe_contabilidad/excel/informe_comisiones_".$oficina."_".$periodo."_".$aleatorio.".xls";
//$ruta = "../excel/excel_clientes_".$aleatorio.".xls";
$ruta = "../../../upload/propiedad/excel/excel_propiedad_".$aleatorio.".xls";
//Escritura del archivo excel
@chmod($ruta,  0777);
if ($fp = fopen($ruta ,"wb")) { 
fwrite($fp,$texto_excel,strlen($texto_excel)); 
fclose($fp); 
}

echo $ruta;



