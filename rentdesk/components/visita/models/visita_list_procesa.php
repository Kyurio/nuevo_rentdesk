<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$url_reportes_export = $config->url_reportes_export;


@$draw			= $_POST["draw"];
@$inicio		= $_POST["start"];
@$num_reg		= $_POST["length"];
@$busqueda 		= $_POST["search"]["value"];
@$orden 		= $_POST["order"][0]["column"];
@$direccion 	= $_POST["order"][0]["dir"];

$id_company 	= $_SESSION["rd_company_id"];
@$token_propiedad 	= $_GET["token_propiedad"];
@$nav 	= $_GET["nav"];

if($busqueda!=""){
$busqueda = formato_busqueda($busqueda);
$busqueda = " AND (arpis.fn_filtro_busqueda(v.tipo) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(v.direccion)  LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(ev.descripcion)  LIKE '%$busqueda%' AND v.id_empresa = '$id_company' ) ";
}

if($inicio=="")
$inicio = 0;


$usuarios	= "";
$datos		= "";

 
$orderby = " ORDER BY v.fecha desc";

if($orden==0)
$orderby = " ORDER BY v.fecha $direccion ";

if($orden==1)
$orderby = " ORDER BY v.tipo $direccion ";

if($orden==2)
$orderby = " ORDER BY v.direccion $direccion ";

if($orden==3)
$orderby = " ORDER BY ev.descripcion $direccion ";
 
/*Consulta Cantidad de registros*/
$query_count = "SELECT v.*,ev.descripcion estado from arpis.visita v, arpis.estado_visita ev
				WHERE ((v.id_propiedad = (select p.id_propiedad from arpis.propiedad p where p.token = '$token_propiedad')
					AND v.id_propiedad IS NOT NULL
					AND '' <> '$token_propiedad'
					)
			  OR ('' = '$token_propiedad' AND v.id_propiedad IS NULL ))
			  AND ev.id_estado_visita = v.id_estado_visita $busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT v.*,ev.descripcion estado from arpis.visita v, arpis.estado_visita ev
		 WHERE ((id_propiedad = (select p.id_propiedad from arpis.propiedad p where p.token = '$token_propiedad')
					AND id_propiedad IS NOT NULL
					AND '' <> '$token_propiedad' )
			  OR ('' = '$token_propiedad' AND id_propiedad IS NULL))
			  AND ev.id_estado_visita = v.id_estado_visita  $busqueda $orderby ";
$cant_rows = $num_reg;
$num_pagina = round($inicio/$cant_rows)+1;	
$data = array("consulta" => $query,"cantRegistros" => $cant_rows,"numPagina" => $num_pagina);	
$resultado = $services->sendPostNoToken($url_services.'/util/paginacion',$data);	
	
$json = json_decode($resultado);
}


$coma = 0;
$signo_coma = "";
foreach($json as $result){

if($coma==1)
$signo_coma = ",";

$coma = 1;

$imprimir = "";
$imprimir = "<a href='javascript: imprimirVisita(\\\"$url_reportes_export\\\",\\\"$result->token\\\",\\\"$id_company\\\",\\\"pdf\\\",\\\"InformeVisita\\\");'><i class='far fa-file-pdf'></i></a>";

$ver = "";
$ver = "<a href='index.php?component=visita&view=visita&token=$result->token&token_propiedad=$token_propiedad&nav=$nav'><i class='fas fa-search'></i></a>";

$eliminar = "";
$eliminar = "<a href='javascript: deleteVisita(\\\"$result->token\\\");'><i class='far fa-trash-alt'></i></a>";
	
$fecha = $result->fecha;
$fecha = fecha_postgre_a_normal($fecha);



$datos = $datos ."
     $signo_coma
	 [
	 \"$fecha\",
	  \"$result->tipo\",
      \"$result->direccion\",
	  \"$result->estado\",
	  \"$imprimir\",
      \"$ver\",
      \"$eliminar\"
    ]";
	
	
}//while($result2 = $mysql->f_obj($sql))


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