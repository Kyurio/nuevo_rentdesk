<?php 
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
//$num_reg = 500;
//$inicio = 0;

@$draw			= $_POST["draw"];
@$inicio		= $_POST["start"];
@$num_reg		= $_POST["length"];
@$busqueda 		= $_POST["search"]["value"];

$cantidad_filtrados = 0;
$cantidad_registros = 0;
$coma = 0;
$datos		= "";
$signo_coma = "";

$dni =  @$_GET["dniArrendatario"];
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$id_subsidiaria = $current_subsidiaria->id;

$query = "
  SELECT ps.dni as dni, ps.token as token, ps.id_tipo_persona as id_tipo_persona ,
  ttp.nombre as tipo_persona,
  pnt.nombres as nombres, pnt.apellido_paterno as apellido_paterno, 
  pnt.apellido_materno as apellido_materno ,pj.razon_social as razon_social, pj.nombre_fantasia  as nombre_fantasia ,
  pd.direccion as direccion, pd.numero, pd.numero_depto, pd.comentario , pd.comentario2,
  ps.telefono_fijo as telefono_fijo , ps.telefono_movil as telefono_movil, ps.correo_electronico,
  tc.nombre as comuna, tr.nombre as region,tp.nombre as pais, ps.id as id_persona,
  pa.token as tokenarrendatario, ttd.nombre  as tipo_dni
  FROM propiedades.persona ps 
  left join propiedades.persona_natural pnt on ps.id  = pnt.id_persona
  left  join propiedades.persona_juridica pj  on ps.id = pj.id_persona
  inner join propiedades.tp_tipo_persona ttp on ttp.id =ps.id_tipo_persona 
  inner join propiedades.persona_direcciones pd on ps.id = pd.id_persona
  inner join propiedades.tp_comuna tc on tc.id = pd.id_comuna
  inner join propiedades.tp_region tr on tc.id_region = tr.id 
  inner join propiedades.tp_pais tp on tr.id_pais = tp.id
  inner join propiedades.tp_tipo_dni ttd  on ttd.id = ps.id_tipo_dni 
    left join propiedades.persona_arrendatario pa on pa.id_persona  = ps.id 
  where pa.id_persona is not null and ps.id_subsidiaria = $id_subsidiaria ";


 
  if(isset($dni) && $dni != "" ){
  $query= $query ."AND dni = '$dni' ";
   
  }
  $query= $query ."order by ps.id desc ";
 
 	$data = array("consulta" => $query);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;
 
 
  $cant_rows = $num_reg;
  $num_pagina = round($inicio / $cant_rows) + 1;
  $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
  $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
  $json = json_decode($resultado);
  
  if ($resultado==""){
            	echo "
	{
	\"draw\": $draw,
	\"recordsTotal\": 0,
	\"recordsFiltered\": 0,
	\"data\":[
		$datos
			] 
		
	
	}";
  }else{
		if ($json !== null) {
			// Iterate over each object in the array
			foreach ($json as $obj) {
			if($coma==1)
		$signo_coma = ",";
		
		$coma = 1;	
		$cantidad_filtrados++;

		
		if($obj->id_tipo_persona == 1){
			$nombre = $obj->nombres." ".$obj->apellido_paterno." ".$obj->apellido_materno;
		}else{
			$nombre = $obj->razon_social;
		}
	
		$query_arrendatario = " SELECT 'SI' as existe FROM propiedades.ficha_arriendo_arrendadores 
		 where id_arrendatario = $obj->id_persona ";
		// var_dump($query_arrendatario);
		   $cant_rows = $num_reg;
		$num_pagina = round(1/ 999999) + 1;
		$data = array("consulta" => $query_arrendatario, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		//var_dump($resultado);
		$json_arrendatario = json_decode($resultado)[0];
//var_dump($json_arrendatario->existe);
			$botones =  "<a href='index.php?component=arrendatario&view=arrendatario&token=$obj->tokenarrendatario' type='button' class='btn btn-info m-0' style='padding: .5rem;' aria-label='Editar' title='Editar'> <i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i></a>";

		if (@$json_arrendatario->existe != "SI"){
		$botones = $botones."<button type='button' onclick='eliminarArrendatario($obj->id_persona)' class='btn btn-danger m-0' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i> </button>";
			}else{
		$botones = $botones."<button type='button' onclick='avisoEliminar()' class='btn btn-secondary m-0' style='padding: .5rem;' title='Eliminar'><i class='fa-regular fa-trash-can' style='font-size: .75rem;'></i> </button>";

		}

		//$ficha_propietario = "<a href='index.php?component=propietario&view=propietario_ficha_tecnica&token=$obj->tokenpropietario' class='link-info' > #$obj->id_persona</a>";
		
		$datos = $datos ."
				$signo_coma
				[
				\"#$obj->id_persona\",
				\"$nombre\",
				\"$obj->dni\",
				\"$obj->tipo_persona\",
				\"$botones\"
				]";
	}
	}
	  
	  
	  
	echo "
	{
	\"draw\": $draw,
	\"recordsTotal\": $cantidad_registros,
	\"recordsFiltered\": $cantidad_registros,
	\"data\":[
		$datos
			] 
		
	
	}
	
	
	";   }
  
  
  ?>