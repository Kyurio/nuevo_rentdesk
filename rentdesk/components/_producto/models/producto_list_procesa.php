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
$busqueda = " AND (arpis.fn_filtro_busqueda(p.descripcion_prod) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(tp.descripcion) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(tm.descripcion) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(tr.descripcion) LIKE '%$busqueda%' 
					 OR cast(p.id_producto as varchar) LIKE '$busqueda')
			  AND p.id_empresa = '$id_company' ";
}else{
$busqueda = " AND p.id_empresa = '$id_company'  ";
}

if($inicio=="")
$inicio = 0;


$orderby = " ORDER BY orden asc";
switch ($orden) {
	case 0:
		$orderby = " ORDER BY p.id_producto $direccion ";
		break;
	case 1:
		$orderby = " ORDER BY p.descripcion_prod $direccion ";
		break;
	case 2:
		$orderby = " ORDER BY tp.descripcion $direccion ";
		break;	
	case 3:
		$orderby = " ORDER BY tm.descripcion $direccion ";
		break;			
	case 4:
		$orderby = " ORDER BY tr.descripcion $direccion ";
		break;
	case 5:
		$orderby = " ORDER BY p.activo $direccion ";
		break;	
}	

/*Consulta Cantidad de registros*/
$query_count = "SELECT * 
				FROM arpis.producto p,
					 arpis.tipo_producto tp,
					 arpis.tipo_moneda tm,
					 arpis.tipo_responsable tr
				WHERE tp.id_tipo_producto = p.id_tipo_producto
				AND tm.id_tipo_moneda = p.id_tipo_moneda
				AND tr.id_tipo_responsable = p.id_tipo_responsable
				AND tp.seleccionable = 'S'
				$busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT p.token ,p.id_producto,p.descripcion_prod,tp.descripcion tipo_producto,tm.descripcion tipo_moneda,tr.descripcion tipo_responsable,p.activo,
				(SELECT 'S' FROM arpis.contrato_det cd where cd.id_producto = p.id_producto and cd.activo = 'S' limit 1) existe_en_contrato
				FROM arpis.producto p,
					 arpis.tipo_producto tp,
					 arpis.tipo_moneda tm,
					 arpis.tipo_responsable tr
				WHERE tp.id_tipo_producto = p.id_tipo_producto
				AND tm.id_tipo_moneda = p.id_tipo_moneda
				AND tr.id_tipo_responsable = p.id_tipo_responsable
				AND tp.seleccionable = 'S'
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

$ver = "";
$ver = "<a href='index.php?component=producto&view=producto&token=$result->token'><i class='fas fa-search'></i></a>";

$eliminar = "";
if ($result->existe_en_contrato != 'S'){
$eliminar = "<a href='javascript: deleteProducto(\\\"$result->token\\\");'><i class='far fa-trash-alt'></i></a>";
}

$datos = $datos ."
     $signo_coma
	 [
      \"$result->id_producto\",
      \"$result->descripcion_prod\",
	  \"$result->tipo_producto\",
	  \"$result->tipo_moneda\",
	  \"$result->tipo_responsable\",
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