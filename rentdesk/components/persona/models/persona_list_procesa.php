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

$orden 		= "";
if (!empty($_POST["order"][0]["column"]))
$orden 		= @$_POST["order"][0]["column"];

$direccion = "";
if (!empty($_POST["order"][0]["dir"]))
$direccion = @$_POST["order"][0]["dir"];



$id_company 	= $_SESSION["rd_company_id"];
$id_tipo_persona = 2;
 

if($busqueda!=""){ 
$busqueda = formato_busqueda($busqueda);
$busqueda = " AND (arpis.fn_filtro_busqueda(td.descripcion) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(p.num_documento) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(p.nombre) LIKE '%$busqueda%'
					  OR arpis.fn_filtro_busqueda(p.apellido_pat) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(p.apellido_mat) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(ep.descripcion) LIKE '%$busqueda%' 
					 OR cast(p.id_persona as varchar) LIKE '$busqueda')
			  AND p.id_empresa = '$id_company' ";
}else{
$busqueda = " AND p.id_empresa = '$id_company'  ";
}

if($inicio=="")
$inicio = 0;


$orderby = " ORDER BY p.id_persona asc";
switch ($orden) {
	case 0:
		$orderby = " ORDER BY p.id_persona $direccion ";
		break;
	case 1:
		$orderby = " ORDER BY td.descripcion $direccion ";
		break;
	case 2:
		$orderby = " ORDER BY p.num_documento $direccion ";
		break;	
	case 3:
		$orderby = " ORDER BY p.nombre $direccion ";
		break;			
	case 4:
		$orderby = " ORDER BY p.apellido_pat $direccion ";
		break;
	case 5:
		$orderby = " ORDER BY p.apellido_mat $direccion ";
		break;	
	case 6:
		$orderby = " ORDER BY ep.descripcion $direccion ";
		break;	
}	

/*Consulta Cantidad de registros*/
$query_count = "SELECT * 
				FROM arpis.persona p,
					 arpis.tipo_documento td,
					 arpis.estado_persona ep
				WHERE td.id_tipo_documento = p.id_tipo_documento
				AND ep.id_estado_persona = p.id_estado_persona
				AND p.id_tipo_persona = $id_tipo_persona
				$busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT p.token,p.id_persona,td.descripcion tipo_documento,
		CASE WHEN digito_verificador IS NULL THEN p.num_documento ELSE CONCAT(p.num_documento,'-',digito_verificador) END num_documento,
		p.nombre,p.apellido_pat,p.apellido_mat,ep.descripcion estado_persona
		FROM arpis.persona p,
			 arpis.tipo_documento td,
			 arpis.estado_persona ep
		WHERE td.id_tipo_documento = p.id_tipo_documento
		AND ep.id_estado_persona = p.id_estado_persona
		AND p.id_tipo_persona = $id_tipo_persona
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
$nav_return = codifica_navegacion("component=propietario&view=propietario_list");

$ver = "";
$ver = "<a href='index.php?component=propietario&view=propietario&token=$result->token&nav=$nav_return'><i class='fas fa-search'></i></a>";

$eliminar = "";
$eliminar = "<a href='javascript: deletePropietario(\\\"$result->token\\\");'><i class='far fa-trash-alt'></i></a>";

$pago = "";
$pago = "<a href='index.php?component=propietario&view=propietario_list_procesa_prop_pago&token=$result->token&nav=$nav_return'><i class='fas fa-file-invoice-dollar'></i></a>";

$datos = $datos ."
     $signo_coma
	 [
      \"$result->id_persona\",
      \"$result->tipo_documento\",
	  \"$result->num_documento\",
	  \"$result->nombre\",
	  \"$result->apellido_pat\",
	  \"$result->apellido_mat\",
	  \"$result->estado_persona\",
	  \"$ver\",
      \"$eliminar\",
	  \"$pago\"
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