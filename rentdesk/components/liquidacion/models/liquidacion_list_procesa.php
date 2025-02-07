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
@$dirlcion 	= $_POST["order"][0]["dir"];

$id_company 	 = $_SESSION["rd_company_id"];
@$token_contrato = $_GET["token_contrato"];
@$token_prop 	 = $_GET["token_prop"];
@$nav 	= $_GET["nav"];

if($busqueda!=""){ 
$busqueda = formato_busqueda($busqueda);
$busqueda = " AND (cast(lc.periodo as varchar) LIKE '$busqueda' OR  arpis.fn_filtro_busqueda(el.descripcion)  LIKE '$busqueda' OR  cast(lc.fecha_generacion as varchar)  LIKE '$busqueda') ";
}else{
$busqueda = " ";
}

if($inicio=="")
$inicio = 0;


$orderby = " ORDER BY orden asc";
switch ($orden) {
	case 0:
		$orderby = " ORDER BY lc.periodo $dirlcion ";
		break;
	case 1:
		$orderby = " ORDER BY lc.fecha_generacion $dirlcion ";
		break;
	case 2:
		$orderby = " ORDER BY el.descripcion $dirlcion ";
		break;	
}	

/*Consulta Cantidad de registros*/
$query_count = "SELECT * 
				FROM arpis.liquidacion_cab lc,
					 arpis.contrato_cab cc,
					 arpis.estado_liquidacion el,
					 arpis.persona p	
				WHERE cc.token = '$token_contrato'
				AND lc.id_contrato = cc.id_contrato
				AND el.id_estado_liquidacion = lc.id_estado_liquidacion
				AND p.token = '$token_prop'
				AND lc.id_propietario = p.id_persona
				$busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT el.descripcion estado,lc.*
		FROM arpis.liquidacion_cab lc,
			 arpis.contrato_cab cc,
			 arpis.estado_liquidacion el,
			 arpis.persona p 			 
		WHERE cc.token = '$token_contrato'
		AND lc.id_contrato = cc.id_contrato
		AND el.id_estado_liquidacion = lc.id_estado_liquidacion
		AND p.token = '$token_prop'
		AND lc.id_propietario = p.id_persona
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
$ver = "<a href='index.php?component=liquidacion&view=liquidacion&token=$result->token&nav=$nav'><i class='fas fa-search'></i></a>";


$datos = $datos ."
     $signo_coma
	 [
      \"$result->periodo\",
      \"$result->fecha_generacion\",
	  \"$result->estado\",
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