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

if($busqueda!=""){ 
$busqueda = formato_busqueda($busqueda);
$busqueda = " AND (cast(e.id_cm_error as varchar)  LIKE '$busqueda' 
					OR arpis.fn_filtro_busqueda(e.contenido) LIKE '%$busqueda%' 
					OR arpis.fn_filtro_busqueda(e.descripcion_error) LIKE '%$busqueda%')
			  ";
}else{
$busqueda = "   ";
}

if($inicio=="")
$inicio = 0;


$orderby = " ORDER BY e.id_cm_error asc";
switch ($orden) {
	case 0:
		$orderby = " ORDER BY e.id_cm_error $direccion ";
		break;
	case 1:
		$orderby = " ORDER BY e.contenido $direccion ";
		break;
	case 2:
		$orderby = " ORDER BY e.descripcion_error $direccion ";	
}	

/*Consulta Cantidad de registros*/
$query_count = "SELECT e.*
				FROM  arpis.cm_proceso_carga cpc,
					  arpis.cm_error e
				WHERE cpc.token = '$token'
				AND e.id_proceso_carga = cpc.id_proceso_carga
				$busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT e.id_cm_error,e.contenido,e.descripcion_error
		FROM  arpis.cm_proceso_carga cpc,
			  arpis.cm_error e
		WHERE cpc.token = '$token'
		AND e.id_proceso_carga = cpc.id_proceso_carga
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


$datos = $datos ."
     $signo_coma
	 [
      \"$result->id_cm_error\",
	  \"$result->contenido\",
	  \"$result->descripcion_error\"
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