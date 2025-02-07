<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");


$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

@$draw			= $_POST["draw"];
@$inicio		= $_POST["start"];
@$num_reg		= $_POST["length"];
@$busqueda 		= $_POST["search"]["value"];
@$orden 		= $_POST["order"][0]["column"];
@$direccion 	= $_POST["order"][0]["dir"];

$id_company 	= $_SESSION["rd_company_id"];

@$token = $_GET["token"];
@$nav 	= $_GET["nav"];
@$n 	= $_GET["n"];

if($busqueda!=""){ 
$busqueda = formato_busqueda($busqueda);
$busqueda = " AND (cast(pc.id_proceso_carga as varchar)  LIKE '$busqueda' 
					OR arpis.fn_filtro_busqueda(u.nombre_usuario) LIKE '%$busqueda%' 
					OR arpis.fn_filtro_busqueda(ec.descripcion) LIKE '%$busqueda%'
					OR arpis.fn_filtro_busqueda(pc.nombre_archivo_ori) LIKE '%$busqueda%'					)
			  ";
}else{
$busqueda = "   ";
}

if($inicio=="")
$inicio = 0;


$orderby = " ORDER BY pc.id_proceso_carga desc";
switch ($orden) {
	case 0:
		$orderby = " ORDER BY pc.id_proceso_carga $direccion ";
		break;
	case 1:
		$orderby = " ORDER BY u.nombre_usuario $direccion ";
		break;
	case 2:
		$orderby = " ORDER BY pc.fecha $direccion ";
		break;	
	case 3:
		$orderby = " ORDER BY ec.descripcion  $direccion ";
		break;	
	case 4:
		$orderby = " ORDER BY pc.nombre_archivo_ori  $direccion ";
		break;			
}	

/*Consulta Cantidad de registros*/
$query_count = "SELECT *
				FROM arpis.cm_proceso_carga pc,
					 arpis.cm_carga_masiva cm,
					 arpis.usuario u,
					 arpis.cm_estado_carga ec
				WHERE cm.id_carga_masiva = pc.id_carga_masiva
				AND cm.token = '$token'
				AND u.id_usuario = pc.id_usuario
				AND ec.id_estado_carga = pc.id_estado_carga
				$busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT pc.id_proceso_carga,
				u.nombre_usuario,
				pc.fecha,
				ec.descripcion,
				pc.archivo,
				pc.token,
				pc.nombre_archivo_ori	
		FROM arpis.cm_proceso_carga pc,
			 arpis.cm_carga_masiva cm,
			 arpis.usuario u,
			 arpis.cm_estado_carga ec
		WHERE cm.id_carga_masiva = pc.id_carga_masiva
		AND cm.token = '$token'
		AND u.id_usuario = pc.id_usuario
		AND ec.id_estado_carga = pc.id_estado_carga
		$busqueda $orderby ";
$cant_rows = $num_reg;
$num_pagina = round($inicio/$cant_rows)+1;	
$data = array("consulta" => $query,"cantRegistros" => $cant_rows,"numPagina" => $num_pagina);	
$resultado = $services->sendPostNoToken($url_services.'/util/paginacion',$data);	
	
$json = json_decode($resultado);
}


/*Proceso para iterar sobre el resultado*/
$coma = 0;
$signo_coma = "";
$datos		= ""; 
foreach($json as $result){
if($coma==1)
$signo_coma = ",";

$coma = 1;
$nav_return = codifica_navegacion("component=cargaMasiva&view=cargaMasiva&t=$token&nav=$nav&n=$n");

$ver = "";
$ver = "<a href='upload/cargaMasiva/$result->archivo' target='_blank'><i class='fas fa-file'></i></a>";

$ver_log = "";
$ver_log = "<a href='index.php?component=cargaMasiva&view=cargaMasiva_list_log&token=$result->token&nav=$nav_return&n=$n'><i class='fas fa-exclamation-circle'></i></a>";


$fecha =  fecha_postgre_a_normal($result->fecha);

$datos = $datos ."
     $signo_coma
	 [
      \"$result->id_proceso_carga\",
	  \"$result->nombre_usuario\",
	  \"$fecha\",
	  \"$result->nombre_archivo_ori\",
	  \"$ver\",
	  \"$result->descripcion\",
	  \"$ver_log\"
    ]";
	
}//foreach($json as $result)




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


?>