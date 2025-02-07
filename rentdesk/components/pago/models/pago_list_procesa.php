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
$busqueda = " AND (cast(pc.fecha_pago as varchar) LIKE '$busqueda' OR  cast(pc.monto_pago as varchar)  LIKE '$busqueda' OR arpis.fn_filtro_busqueda(tmp.descripcion)  LIKE '$busqueda' OR arpis.fn_filtro_busqueda(pc.liquidado)  LIKE '$busqueda') ";
}else{
$busqueda = " ";
}

if($inicio=="")
$inicio = 0;


$orderby = " ORDER BY pc.fecha_pago desc";
switch ($orden) {
	case 0:
		$orderby = " ORDER BY pc.fecha_pago $direccion ";
		break;
	case 1:
		$orderby = " ORDER BY pc.monto_pago $direccion ";
		break;
	case 2:
		$orderby = " ORDER BY tmp.descripcion $direccion ";
		break;	
	case 3:
		$orderby = " ORDER BY pc.liquidado $direccion ";
		break;		
}	

/*Consulta Cantidad de registros*/
$query_count = "SELECT * 
				FROM arpis.contrato_cab cc,
					 arpis.estado_cta_cab ecc,
					 arpis.pago_cab pc,
					 arpis.tipo_medio_pago tmp
				WHERE cc.token = '$token_contrato'
				AND ecc.id_contrato = cc.id_contrato
				AND ecc.pagado = 'S'
				AND pc.id_estado_cta_cab = ecc.id_estado_cta_cab
				AND tmp.id_tipo_medio_pago = pc.id_tipo_medio_pago
				$busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT pc.*, tmp.descripcion medio_pago
		FROM arpis.contrato_cab cc,
			 arpis.estado_cta_cab ecc,
			 arpis.pago_cab pc,
			 arpis.tipo_medio_pago tmp
		WHERE cc.token = '$token_contrato'
		AND ecc.id_contrato = cc.id_contrato
		AND ecc.pagado = 'S'
		AND pc.id_estado_cta_cab = ecc.id_estado_cta_cab
		AND tmp.id_tipo_medio_pago = pc.id_tipo_medio_pago
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
$ver = "<a href='index.php?component=pago&view=pago&token=$result->token&nav=$nav'><i class='fas fa-search'></i></a>";

$fecha_normal = fecha_postgre_a_normal($result->fecha_pago);

$liquidado = "N";
if(@$result->liquidado != ""){
	$liquidado = $result->liquidado;
}	
$valor_formateado = formatea_number($result->monto_pago,$_SESSION["cant_decimales"],$_SESSION["separador_mil"]);
$datos = $datos ."
     $signo_coma
	 [
      \"$fecha_normal\",
	  \"$valor_formateado\",
      \"$result->medio_pago\",
	  \"$liquidado\",
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