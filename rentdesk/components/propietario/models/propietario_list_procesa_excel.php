<?php 
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config    = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$num_reg = 500;
$inicio = 0;

$dni =  @$_GET["dniPropietario"];
@$draw			= $_POST["draw"];
@$inicio		= $_POST["start"];
@$num_reg		= $_POST["length"];
@$busqueda 		= $_POST["search"]["value"];

$cantidad_filtrados = 0;
$cantidad_registros = 0;
$coma = 0;
$datos		= "";
$signo_coma = "";

	$c=0;
$lista = "";

$inicio = 1;
$num_reg = 99999;

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
  pp.token as tokenpropietario, ttd.nombre  as tipo_dni, ps.id as id
  FROM propiedades.persona ps 
  left join propiedades.persona_natural pnt on ps.id  = pnt.id_persona
  left  join propiedades.persona_juridica pj  on ps.id = pj.id_persona
  inner join propiedades.tp_tipo_persona ttp on ttp.id =ps.id_tipo_persona 
  inner join propiedades.persona_direcciones pd on ps.id = pd.id_persona
  inner join propiedades.tp_comuna tc on tc.id = pd.id_comuna
  inner join propiedades.tp_region tr on tc.id_region = tr.id 
  inner join propiedades.tp_pais tp on tr.id_pais = tp.id
  inner join propiedades.tp_tipo_dni ttd  on ttd.id = ps.id_tipo_dni 
  left join propiedades.persona_propietario pp on pp.id_persona  = ps.id 
  where pp.id_persona is not null  and ps.id_subsidiaria = $id_subsidiaria ";


 
  if(isset($dni) && $dni != "" ){
  $query= $query ."AND dni = '$dni' ";
  }
  
  $query= $query ."order by id_persona desc ";
 
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
				
							$c++;
			$fondo = " #ffffff ";
		
			if($c % 2 == 0)
			$fondo = " #ffffff ";
				
				
			if($coma==1)
		$signo_coma = ",";
		
		$coma = 1;	
$cantidad_filtrados++;

		
		if($obj->id_tipo_persona == 1){
			$nombre = $obj->nombres." ".$obj->apellido_paterno." ".$obj->apellido_materno;
		}else{
			$nombre = $obj->razon_social;
		}
	
		

		$botones =  "<a href='index.php?component=propietario&view=propietario&token=$obj->tokenpropietario' type='button' class='btn btn-info m-0' style='padding: .5rem;' aria-label='Editar' title='Editar'> <i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i></a>";
		$ficha_propietario = "<a href='index.php?component=propietario&view=propietario_ficha_tecnica&token=$obj->tokenpropietario' class='link-info' > #$obj->id_persona</a>";
		
		$datos = $datos ."
				$signo_coma
				[
				\"$ficha_propietario\",
				\"$nombre\",
				\"$obj->dni\",
				\"$obj->tipo_persona\",
				\"$botones\"
				]";
				
		$lista = $lista. "
		<tr>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$ficha_propietario</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$nombre</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->dni</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->tipo_persona</td>
		</tr>
		";
	}
	}
	  
	  
	 $tabla =  "<table   border='0' cellspacing='0' cellpadding='1'>
		<tr>
		<td colspan='14' style='background-color:#C0C0C0;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:14px; text-align:center;'> Excel Propietario </td>
		</tr>
		<tr>
		<td colspan='14' style='background-color:$fondo;  color: #000000;  font-family:Arial; font-size:12px; text-align:right;'> </td>
		</tr>
		
		<tr>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Id Ficha</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Nombre</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Nro documento</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Tipo persona</td>
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
$ruta = "../../../upload/propietario/excel/excel_propietario_".$aleatorio.".xls";
//Escritura del archivo excel
@chmod($ruta,  0777);
if ($fp = fopen($ruta ,"wb")) { 
fwrite($fp,$texto_excel,strlen($texto_excel)); 
fclose($fp); 
}

echo $ruta;
	
	
  }
  
  ?>