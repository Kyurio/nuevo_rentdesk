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


if($busqueda!=""){ 
$busqueda = formato_busqueda($busqueda);
$busqueda = " AND (arpis.fn_filtro_busqueda(tp.descripcion) LIKE '%$busqueda%' 
					 OR cast(tp.id_tipo_moneda as varchar) LIKE '$busqueda' OR  cast(tp.orden as varchar)  LIKE '$busqueda')
			  AND tp.id_empresa = '$id_company' ";
}else{
$busqueda = " AND tp.id_empresa = '$id_company'  ";
}

if($inicio=="")
$inicio = 0;


$orderby = " ORDER BY orden asc";
switch ($orden) {
	case 0:
		$orderby = " ORDER BY tp.id_tipo_moneda $direccion ";
		break;
	case 1:
		$orderby = " ORDER BY tp.descripcion $direccion ";
		break;
	case 2:
		$orderby = " ORDER BY p.descripcion $direccion ";
		break;	
	case 3:
		$orderby = " ORDER BY tp.orden $direccion ";
		break;
	case 4:
		$orderby = " ORDER BY tp.activo $direccion ";
		break;	
}	

/*Consulta Cantidad de registros*/
$query_count = "SELECT tp.*, p.descripcion pais from arpis.tipo_moneda tp, arpis.pais p where p.id_pais = tp.id_pais $busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT tp.*, p.descripcion pais from arpis.tipo_moneda tp, arpis.pais p where p.id_pais = tp.id_pais $busqueda $orderby ";
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

$ver = "";
$ver = "<a href='index.php?component=tipoMoneda&view=tipoMoneda&token=$result->token'><i class='fas fa-search'></i></a>";

$eliminar = "";
$eliminar = "<a href='javascript: deleteTipo_moneda(\\\"$result->token\\\");'><i class='far fa-trash-alt'></i></a>";

$datos = $datos ."
     $signo_coma
	 [
      \"$result->id_tipo_moneda\",
      \"$result->descripcion\",
	  \"$result->pais\",
	  \"$result->orden\",
	  \"$result->activo\",
	  \"$ver\",
      \"$eliminar\"
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