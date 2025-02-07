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
				AND ep.habilitado_contrato = 'S'
				AND c.id_comuna = p.id_comuna
				AND NOT EXISTS (SELECT 1 FROM arpis.contrato_cab cc
								WHERE cc.id_propiedad = p.id_propiedad
								AND cc.id_estado_contrato not in (3) )
				$busqueda ";

$data = array("consulta" => $query_count);
$resultado = $services->sendPostNoToken($url_services . '/util/count', $data);
$cantidad_registros = $resultado;

if (!$cantidad_registros) {
	$cantidad_registros = 0;
	$json = json_decode("[]");
} else {
	/*Obtiene Json con objetos*/
	$query = "SELECT p.token,tp.descripcion tipo_propiedad,p.codigo_propiedad,p.rol,p.direccion,p.numero,p.numero_depto,ep.descripcion  estado_propiedad,
		CONCAT(c.descripcion,', ',p.direccion,' ',p.numero,' ',p.numero_depto) propiedad,
		coalesce((select SUM(pp.porcentaje) from arpis.propiedad_propietario pp where pp.id_propiedad = p.id_propiedad),0) porcentaje,
		c.descripcion comuna
		FROM arpis.propiedad p,
			 arpis.tipo_propiedad tp,
			 arpis.estado_propiedad ep,
			 arpis.comuna c
		WHERE tp.id_tipo_propiedad = p.id_tipo_propiedad
		AND ep.id_estado_propiedad = p.id_estado_propiedad
		AND ep.habilitado_contrato = 'S'
		AND c.id_comuna = p.id_comuna
		AND NOT EXISTS (SELECT 1 FROM arpis.contrato_cab cc
								WHERE cc.id_propiedad = p.id_propiedad
								AND cc.id_estado_contrato not in (3) )
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

	$asignar = "";
	if ($result->porcentaje == 100) {
		$asignar = "<a href='javascript: agregarPropiedad(\\\"$result->token\\\",\\\"$result->propiedad\\\");'><i class='fas fa-plus-square'></i></a>";
	}

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
	  \"$result->porcentaje\",
	  \"$asignar\"
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
