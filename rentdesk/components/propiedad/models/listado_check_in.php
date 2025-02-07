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
@$token_propiedad 	= $_GET["token_propiedad"];

if($busqueda!=""){
$busqueda = formato_busqueda($busqueda);
$busqueda = " AND (arpis.fn_filtro_busqueda(v.direccion) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(v.arrendatario_recibe)  LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(v.rut)  LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(v.correo)  LIKE '%$busqueda%'  OR arpis.fn_filtro_busqueda(v.observaciones)  LIKE '%$busqueda%' ) ";
}

if($inicio=="")
$inicio = 0;

$datos		= "";

$orderby = " ORDER BY v.fecha desc";

if($orden==0)
$orderby = " ORDER BY v.fecha $direccion ";

if($orden==1)
$orderby = " ORDER BY v.direccion $direccion ";

if($orden==2)
$orderby = " ORDER BY v.arrendatario_recibe $direccion ";

if($orden==3)
$orderby = " ORDER BY v.rut $direccion ";

if($orden==4)
$orderby = " ORDER BY v.correo $direccion ";

if($orden==5)
$orderby = " ORDER BY v.observaciones $direccion ";


 
/*Consulta Cantidad de registros*/
$query_count = "SELECT v.* FROM arpis.visita v 
				WHERE v.id_propiedad is null
				AND v.id_empresa = '$id_company'
				AND v.tipo = 'Checkin'
				$busqueda ";
				
$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT v.* FROM arpis.visita v 
				WHERE v.id_propiedad is null
				AND v.id_empresa = '$id_company'
				AND v.tipo = 'Checkin'
				$busqueda  $orderby "; 
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

$asignar = "";
$asignar = "<a href='javascript: agregarCheckIn(\\\"$result->token\\\",\\\"$token_propiedad\\\");'><i class='fas fa-plus-square'></i></a>"; 

$datos = $datos ."
     $signo_coma
	 [
      \"$result->fecha\",
	  \"$result->direccion\",
	  \"$result->arrendatario_recibe\",
	  \"$result->rut\",
	  \"$result->correo\",
	  \"$result->observaciones\",
	  \"$asignar\"
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