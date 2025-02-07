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
$busqueda = " WHERE (arpis.fn_filtro_busqueda(descripcion) LIKE '%$busqueda%' 
					 OR cast(id_tipo_monto as varchar) LIKE '$busqueda' OR  cast(orden as varchar)  LIKE '$busqueda')
			  AND id_empresa = '$id_company' ";
}else{
$busqueda = " WHERE id_empresa = '$id_company'  ";
}

if($inicio=="")
$inicio = 0;


$orderby = " ORDER BY orden asc";
switch ($orden) {
	case 0:
		$orderby = " ORDER BY id_tipo_monto $direccion ";
		break;
	case 1:
		$orderby = " ORDER BY descripcion $direccion ";
		break;
	case 2:
		$orderby = " ORDER BY orden $direccion ";
		break;
	case 3:
		$orderby = " ORDER BY activo $direccion ";
		break;	
}	

/*Consulta Cantidad de registros*/
$query_count = "SELECT * from arpis.tipo_monto $busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT * from arpis.tipo_monto $busqueda $orderby ";
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
$ver = "<a href='index.php?component=tipoMonto&view=tipoMonto&token=$result->token'><i class='fas fa-search'></i></a>";

$eliminar = "";
$eliminar = "<a href='javascript: deleteTipo_monto(\\\"$result->token\\\");'><i class='far fa-trash-alt'></i></a>";

$datos = $datos ."
     $signo_coma
	 [
      \"$result->id_tipo_monto\",
      \"$result->descripcion\",
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