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

$dni =  @$_GET["dniCliente"];
$propietario = @$_GET["propietario"];
$arrendatario = @$_GET["arrendatario"];
$codeudor = @$_GET["codeudor"];

@$inicio		= $_POST["start"];
@$num_reg		= $_POST["length"];
@$num_reg_principal		= $_POST["length"];

$draw			= @$_POST["draw"];
$inicio			= @$_POST["start"];
//$fin			= @$_POST["length"];
$busqueda 		= @$_POST["search"]["value"];

$cantidad_filtrados = 0;
$cantidad_registros = 0;
$coma = 0;
$datos		= "";
$signo_coma = "";

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
  pp.token as tokenpropietario, pa.token as tokenarrendatario, pc.token as tokencodeudor
  FROM propiedades.persona ps 
  left join propiedades.persona_natural pnt on ps.id  = pnt.id_persona
  left  join propiedades.persona_juridica pj  on ps.id = pj.id_persona
  inner join propiedades.tp_tipo_persona ttp on ttp.id =ps.id_tipo_persona 
  inner join propiedades.persona_direcciones pd on ps.id = pd.id_persona
  inner join propiedades.tp_comuna tc on tc.id = pd.id_comuna
  inner join propiedades.tp_region tr on tc.id_region = tr.id 
  inner join propiedades.tp_pais tp on tr.id_pais = tp.id
  left join propiedades.persona_propietario pp on pp.id_persona  = ps.id 
  left join propiedades.persona_arrendatario pa on pa.id_persona  = ps.id 
  left join propiedades.persona_codeudor pc on pc.id_persona  = ps.id  
  where ps.id_subsidiaria = $id_subsidiaria AND ";


 
  if(isset($dni) && $dni != "" ){
  $query= $query ." dni = '$dni' AND ";
  }
  if(isset($propietario) && $propietario == 1){
  $query= $query ." pp.id_persona is not null AND ";
  }
  if(isset($arrendatario) && $arrendatario == 1){
  $query= $query ." pa.id_persona is not null AND ";
  }
  if(isset($codeudor) && $codeudor == 1){
  $query= $query ." pc.id_persona is not null AND ";
  }
  // Eliminar el "AND" adicional al final de la consulta
    $query = rtrim($query, "AND "); 
	
	$query= $query ." order by  ps.id desc ";
	
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
	$c=0;
$lista = "";
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
          $roles = "<div>-Cliente</div>";
          if ($obj->tokenpropietario != null) {
            $roles = $roles . "<div>-Propietario</div>";
          }
          if ($obj->tokenarrendatario != null) {
            $roles = $roles . "<div>-Arrendatario</div>";
          }
          if ($obj->tokencodeudor != null) {
            $roles = $roles . "<div>-Codeudor</div>";
          }
		
		if($obj->id_tipo_persona == 1){
			$nombre = $obj->nombres." ".$obj->apellido_paterno." ".$obj->apellido_materno;
		}else{
			$nombre = $obj->razon_social;
		}
		
		$ubicacion = $obj->direccion." ".$obj->numero." ".$obj->comuna." ".$obj->pais ;
		

		$botones =  "<a href='index.php?component=persona&view=persona&token=$obj->token' type='button' class='btn btn-info m-0' style='padding: .5rem;' aria-label='Editar' title='Editar'> <i class='fa-regular fa-pen-to-square' style='font-size: .75rem;'></i></a>";
		
		/*
		$datos = $datos ."
				$signo_coma
				[
				\"#$obj->id_persona\",
				\"$nombre\",
				\"$obj->dni\",
				\"$obj->correo_electronico\",
				\"$obj->tipo_persona\",
				\"$ubicacion\",
				\"$roles\",
				\"$botones\"
				]";
		*/	
		$lista = $lista. "
		<tr>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>#$obj->id_persona</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$nombre</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->dni</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->correo_electronico</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$obj->tipo_persona</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$ubicacion</td>
		<td style='background-color:$fondo;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:12px;'>$roles</td>
		</tr>
		";
	}
				
	}
}
	 
	 
	 
	 $tabla =  "<table   border='0' cellspacing='0' cellpadding='1'>
		<tr>
		<td colspan='14' style='background-color:#C0C0C0;  color: #000000; border: 1px solid #363636; font-family:Arial; font-size:14px; text-align:center;'> Excel Personas </td>
		</tr>
		<tr>
		<td colspan='14' style='background-color:$fondo;  color: #000000;  font-family:Arial; font-size:12px; text-align:right;'> </td>
		</tr>
		
		<tr>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Id Ficha</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Nombre</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Rut</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Correo electronico</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Tipo persona</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Direccion</td>
		<td style='background-color:#1f268e; color: #ffffff; border: 1px solid #363636; font-family:Arial; font-size:12px; padding:4px;'>Roles</td>
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
$ruta = "../../../upload/persona/excel/excel_clientes_".$aleatorio.".xls";
//Escritura del archivo excel
@chmod($ruta,  0777);
if ($fp = fopen($ruta ,"wb")) { 
fwrite($fp,$texto_excel,strlen($texto_excel)); 
fclose($fp); 
}

echo $ruta;
	
  
  
  ?>