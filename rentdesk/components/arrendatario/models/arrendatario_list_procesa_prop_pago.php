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
@$token			= $_GET["token"];
@$nav		    = $_GET["nav"];

if($busqueda!=""){ 
$busqueda = formato_busqueda($busqueda);
$busqueda = " AND (arpis.fn_filtro_busqueda(p.codigo_propiedad) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(tp.descripcion) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(p.rol) LIKE '%$busqueda%'
					  OR arpis.fn_filtro_busqueda(p.direccion) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(p.numero) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(p.numero_depto) LIKE '%$busqueda%' 
					  OR arpis.fn_filtro_busqueda(ep.descripcion) LIKE '%$busqueda%')
			  AND p.id_empresa = '$id_company' ";
}else{
$busqueda = " AND p.id_empresa = '$id_company'  ";
}

if($inicio=="")
$inicio = 0;


$orderby = " ORDER BY p.codigo_propiedad asc";
switch ($orden) {
	case 0:
		$orderby = " ORDER BY p.codigo_propiedad $direccion ";
		break;
	case 1:
		$orderby = " ORDER BY tp.descripcion $direccion ";
		break;
	case 2:
		$orderby = " ORDER BY p.rol $direccion ";
		break;	
	case 3:
		$orderby = " ORDER BY p.direccion $direccion ";
		break;			
	case 4:
		$orderby = " ORDER BY p.numero $direccion ";
		break;
	case 5:
		$orderby = " ORDER BY p.numero_depto $direccion ";
		break;	
	case 6:
		$orderby = " ORDER BY ep.descripcion $direccion ";
		break;	
}	

/*Consulta Cantidad de registros*/
$query_count = "SELECT * 
				FROM arpis.propiedad p,
					 arpis.tipo_propiedad tp,
					 arpis.estado_propiedad ep,
					 arpis.contrato_cab cc,
					 arpis.persona a
				WHERE tp.id_tipo_propiedad = p.id_tipo_propiedad
				AND ep.id_estado_propiedad = p.id_estado_propiedad
				AND cc.id_propiedad = p.id_propiedad
				AND a.id_persona = cc.id_cliente
				AND a.token = '$token'	
				$busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT p.token,tp.descripcion tipo_propiedad,p.codigo_propiedad,p.rol,p.direccion,p.numero,p.numero_depto,ep.descripcion  estado_propiedad,cc.token token_contrato,p.token token_propiedad,
                (SELECT ecc2.token FROM arpis.estado_cta_cab ecc2 WHERE ecc2.id_estado_cta_cab =(SELECT max(id_estado_cta_cab) FROM arpis.estado_cta_cab ecc where ecc.id_contrato = cc.id_contrato)) estado_cuenta
		FROM arpis.propiedad p,
			 arpis.tipo_propiedad tp,
			 arpis.estado_propiedad ep,
			 arpis.contrato_cab cc,
			 arpis.persona a
		WHERE tp.id_tipo_propiedad = p.id_tipo_propiedad
		AND ep.id_estado_propiedad = p.id_estado_propiedad
		AND cc.id_propiedad = p.id_propiedad
		AND a.id_persona = cc.id_cliente
		AND a.token = '$token'	
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
$ver = "<a href='index.php?component=eecc&view=eecc&token=$result->estado_cuenta&nav=$nav'><i class='fas fa-search'></i></a>";

$hist = "";
$hist = "<a href='index.php?component=eecc&view=eecc_list&token_contrato=$result->token_contrato&nav=$nav'><i class='fas fa-history'></i></a>";

$pagar = "";
$pagar = "<a href='index.php?component=eecc&view=eecc_pago&token=$result->estado_cuenta&token_contrato=$result->token_contrato&nav=$nav'><i class='fas fa-money-bill-wave'></i></a>";

$hist_pago = "";
$hist_pago = "<a href='index.php?component=pago&view=pago_list&token_contrato=$result->token_contrato&nav=$nav'><i class='fas fa-file-invoice-dollar'></i></a>";


$contrato = "";
$contrato = "<a href='index.php?component=contrato&view=contrato&token=$result->token_contrato&nav=$nav'><i class='fas fa-file-signature'></i></a>";

$ver_prop = "";
$ver_prop = "<a href='index.php?component=propiedad&view=propiedad&token=$result->token_propiedad&nav=$nav'><i class='fas fa-store-alt'></i></a>";


$datos = $datos ."
     $signo_coma
	 [
      \"$result->codigo_propiedad\",
      \"$result->tipo_propiedad\",
	  \"$result->direccion\",
	  \"$result->numero\",
	  \"$result->numero_depto\",
	  \"$result->estado_propiedad\",
	  \"$ver\",
	  \"$hist\",
      \"$pagar\",
	  \"$hist_pago\",
	  \"$contrato\",
	  \"$ver_prop\"
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