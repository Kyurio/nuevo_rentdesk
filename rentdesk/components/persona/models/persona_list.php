<?php
@include("../../includes/sql_inyection.php");


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;


$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

/************************Busqueda info PErsona********************************** */

/*
$num_reg = 500;
$inicio = 0;
$query = "
  SELECT ps.dni as dni, ps.token as token, ttp.nombre as tipo_persona,
  pnt.nombres as nombres, pnt.apellido_paterno as apellido_paterno, 
  pnt.apellido_paterno as apellido_materno ,pj.razon_social as razon_social, pj.nombre_fantasia  as nombre_fantasia ,
  pd.direccion as direccion, pd.numero, pd.numero_depto, pd.comentario , pd.comentario2,
  ps.telefono_fijo as telefono_fijo , ps.telefono_movil as telefono_movil, ps.correo_electronico,
  tc.nombre as comuna, tr.nombre as region,tp.nombre as pais, ps.id as id_persona
  FROM propiedades.persona ps 
  left join propiedades.persona_natural pnt on ps.id  = pnt.id_persona
  left  join propiedades.persona_juridica pj  on ps.id = pj.id_persona
  inner join propiedades.tp_tipo_persona ttp on ttp.id =ps.id_tipo_persona 
  inner join propiedades.persona_direcciones pd on ps.id = pd.id_persona
  inner join propiedades.tp_comuna tc on tc.id = pd.id_comuna
  inner join propiedades.tp_region tr on tc.id_region = tr.id 
  inner join propiedades.tp_pais tp on tr.id_pais = tp.id";
  $cant_rows = $num_reg;
  $num_pagina = round($inicio / $cant_rows) + 1;
  $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
  $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
  $json = json_decode($resultado);
  echo print_r($json);

*/
/*BUSQUEDA LISTA PERSONAS OLD
$queryParams = array(
	'token_subsidiaria' => $current_subsidiaria->token
);

$resultado = $services->sendGet($url_services . '/rentdesk/personas', null, [], $queryParams);

$json = json_decode($resultado);
 */

/*------------------------ */

/*BUSQUEDA CON FILTRO LISTA PERSONAS - DNI
$dni = $_POST["dni"] ?? null;

$queryParams = array(
	'token_subsidiaria' => $current_subsidiaria->token,
	'dni' => $dni
);

$resultado = $services->sendGet($url_services . '/rentdesk/personas', null, [], $queryParams);

$num_reg = 500;
$inicio = 0;

$query = "SELECT * FROM  propiedades.persona
         where REPLACE(REPLACE(dni,'.',''),'-','') LIKE REPLACE(REPLACE('%$dni%','.',''),'-','') AND id_subsidiaria = '1' ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);

$json = json_decode($resultado);

 */
/*------------------------ */

//$dataTablePersonas = [];
/*
if ($json !== null) {
	// Iterate over each object in the array
	foreach ($json as $obj) {
		// Transform each field of the object
		
		
		if($obj->id_tipo_persona == 1){
		$tipo_persona = "NATURAL";
		$query = "SELECT nombres||' '||apellido_paterno||' '||apellido_materno as nombre FROM propiedades.persona_natural where id_persona = $obj->id ";
        $cant_rows = $num_reg;
        $num_pagina = round($inicio / $cant_rows) + 1;
        $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
        $resultado_nombre = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		@$json_nombre = json_decode($resultado_nombre)[0];
		$nombre	= @$json_nombre->nombre;
		}else{
		$tipo_persona = "JURIDICA";
		$query = "SELECT nombre_fantasia FROM propiedades.persona_juridica where id_persona = $obj->id ";
        $cant_rows = $num_reg;
        $num_pagina = round($inicio / $cant_rows) + 1;
        $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
        $resultado_nombre = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		$json_nombre = json_decode($resultado_nombre)[0];
		$nombre	= $json_nombre->nombre_fantasia;
		}
		
		/*
		$query = "SELECT pd.direccion FROM  propiedades.persona_direcciones pd 
         where pd.id_persona = $obj->id";
         $cant_rows = $num_reg;
         $num_pagina = round($inicio / $cant_rows) + 1;
         $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
         $resultado_direccion = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
         
         $json_direccion = json_decode($resultado_direccion)[0];
		
		
*/
/*
		$transformedObj = [
			// 'codigo_propiedad' => validateNull($obj->propietario ?? null),
			'nombre' =>  $nombre,
			'dni' => validateNull($obj->dni ?? null),
			'correo_electronico' => validateNull($obj->correo_electronico ?? null),
			'tipo_personalidad' => validateNull($tipo_persona ?? null),
			'direccion' => validateNull($json_direccion->direccion ?? null),
			'ficha_tecnica' => validateNull($obj->fichaTecnica ?? null),
			'token' => validateNull($obj->token ?? null),
		];

		// Push the transformed object into the array
		$dataTablePersonas[] = $transformedObj;
	}
}

function validateNull($item)
{
	return is_null($item) || $item === "" ? "-" : $item;
}

function redirectToPersonaUrl($token)
{

	$addTokenQuery = "&token=" . urlencode($token);



	$redirectURL = "index.php?component=persona&view=persona" . $addTokenQuery;


	return $redirectURL;
}
//************************************************************************************************************
//proceso para las navegaciones
// $nav	= @$_GET["nav"];
// $pag_origen = codifica_navegacion("component=propietario&view=propietario_list");
// if (isset($nav)) {
// 	$nav = "index.php?" . decodifica_navegacion($nav);
// } else {
// 	$nav = "index.php?component=apropietario&view=propietario_list";
// }
//************************************************************************************************************

// $dataTablePersonas = array(
// 	array("Propietario", "A. MACARENA IBACETA CERDA", "9.498.957-4",  "macarenaibaceta@fuenzalida.com", "968397414", ""),
// 	array("Propietario", "AARON ELI CHOQUE MEDINA", "16.789.205-1",  "aaronchoque@gmail.com", "95544119", ""),
// 	array("Arrendatario", "Aaron Alejandro Menares Pavez", "10.585.107-3",  "aaronmenares@gmail.com", "90012824", ""),
// 	array("Codeudor", "AARON ISAAC MANQUEO ORMEÑO", "17.100.397-0",  "a.manqueo@gmail.com", "84756560", ""),
// 	array("Codeudor", "AARON MAXIMILIANO TRUJILLO EMBRY", "13.432.442-2",  "atrujilloembry@gmail.com", "61911040", ""),

// );
*/