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


if ($busqueda != "") {
	$busqueda = formato_busqueda($busqueda);
	$busqueda = " AND (arpis.fn_filtro_busqueda(p.codigo_propiedad) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(tp.descripcion) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(c.descripcion) LIKE '%$busqueda%'
					  OR arpis.fn_filtro_busqueda(p.direccion) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(p.numero) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(p.numero_depto) LIKE '%$busqueda%' 
					  OR arpis.fn_filtro_busqueda(ep.descripcion) LIKE '%$busqueda%')
			  AND p.id_empresa = '$id_company' ";
} else {
	$busqueda = " AND p.id_empresa = '$id_company'  ";
}

if ($inicio == "")
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
		$orderby = " ORDER BY c.descripcion $direccion ";
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
					 arpis.comuna c
				WHERE tp.id_tipo_propiedad = p.id_tipo_propiedad
				AND ep.id_estado_propiedad = p.id_estado_propiedad
				AND c.id_comuna = p.id_comuna
				$busqueda ";

$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/count', $data);
$cantidad_registros = $resultado;

if (!$cantidad_registros) {
	$cantidad_registros = 0;
	$json = json_decode("[]");
} else {
	/*Obtiene Json con objetos*/
	$query = "SELECT p.token,tp.descripcion tipo_propiedad,p.codigo_propiedad,c.descripcion comuna,p.direccion,p.numero,p.numero_depto,ep.descripcion  estado_propiedad,
		(SELECT c.token FROM arpis.contrato_cab c where c.id_propiedad = p.id_propiedad limit 1) token_contrato
		FROM arpis.propiedad p,
			 arpis.tipo_propiedad tp,
			 arpis.estado_propiedad ep,
			 arpis.comuna c
		WHERE tp.id_tipo_propiedad = p.id_tipo_propiedad
		AND ep.id_estado_propiedad = p.id_estado_propiedad
		AND c.id_comuna = p.id_comuna
				$busqueda $orderby ";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data);
	$json = json_decode($resultado);
}

/*Proceso para iterar sobre el resultado*/
$coma = 0;
$signo_coma = "";
$datos		= "";
foreach ($json as $result) {
	if ($coma == 1)
		$signo_coma = ",";

	$coma = 1;
	$nav_return = codifica_navegacion("component=propiedad&view=propiedad_list");


	$ver = "";
	$ver = "<a href='index.php?component=propiedad&view=propiedad&token=$result->token&nav=$nav_return'><i class='fas fa-search'></i></a>";

	$eliminar = "";
	$eliminar = "<a href='javascript: deletePropiedad(\\\"$result->token\\\");'><i class='far fa-trash-alt'></i></a>";

	$contrato = "";
	$contrato = "<a href='index.php?component=contrato&view=contrato&token=$result->token_contrato&nav=$nav_return'><i class='fas fa-file-signature'></i></a>";

	$hist_contrato = "";
	$hist_contrato = "<a href='index.php?component=contrato&view=contrato_list&token_propiedad=$result->token&nav=$nav_return'><i class='fas fa-file-signature'></i></a>";

	$visita = "";
	$vista = "<a href='index.php?component=visita&view=visita_list&token_propiedad=$result->token&nav=$nav_return'><i class='fas fa-file-signature'></i></a>";




	$datos = $datos . "
     $signo_coma
	 [
      \"$result->codigo_propiedad\",
      \"$result->tipo_propiedad\",
	  \"$result->comuna\",
	  \"$result->direccion\",
	  \"$result->numero\",
	  \"$result->numero_depto\",
	  \"$result->estado_propiedad\",
	  \"$ver\",
      \"$eliminar\",
	  \"$contrato\",
	  \"$hist_contrato\",
	  \"$vista\"
    ]";
} //foreach($json as $result)




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
