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
@$token_contrato 	= $_GET["token_contrato"];
@$nav 	= $_GET["nav"];


if($busqueda!=""){ 
$busqueda = formato_busqueda($busqueda);
$busqueda = " AND (cast(ecc.periodo as varchar) LIKE '$busqueda' OR  cast(ecc.pagado as varchar)  LIKE '$busqueda' OR  cast(ecc.fecha_vencimiento as varchar)  LIKE '$busqueda') ";
}else{
$busqueda = " ";
}

if($inicio=="")
$inicio = 0;


$orderby = " ORDER BY orden asc";
switch ($orden) {
	case 0:
		$orderby = " ORDER BY ecc.periodo $direccion ";
		break;
	case 1:
		$orderby = " ORDER BY ecc.fecha_vencimiento $direccion ";
		break;
	case 2:
		$orderby = " ORDER BY ecc.pagado $direccion ";
		break;	
}	

/*Consulta Cantidad de registros*/
$query_count = "SELECT * 
				FROM arpis.estado_cta_cab ecc,
					 arpis.contrato_cab cc 
				WHERE cc.token = '$token_contrato'
				AND ecc.id_contrato = cc.id_contrato
				$busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT ecc.*
		FROM arpis.estado_cta_cab ecc,
			 arpis.contrato_cab cc 
		WHERE cc.token = '$token_contrato'
		AND ecc.id_contrato = cc.id_contrato
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
$ver = "<a href='index.php?component=eecc&view=eecc&token=$result->token&nav=$nav'><i class='fas fa-search'></i></a>";

$fecha_normal = fecha_postgre_a_normal($result->fecha_vencimiento);

$pagado = "N";
if(@$result->pagado != ""){
	$pagado = $result->pagado;
}	

$datos = $datos ."
     $signo_coma
	 [
      \"$result->periodo\",
      \"$fecha_normal\",
	  \"$pagado\",
	  \"$ver\"
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