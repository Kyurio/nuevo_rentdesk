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
$busqueda = " AND (arpis.fn_filtro_busqueda(u.nombre_usuario) LIKE '%$busqueda%' OR arpis.fn_filtro_busqueda(u.email) LIKE '%$busqueda%' )AND u.id_empresa = '$id_company' ";
}else{
$busqueda = " AND u.id_empresa = '$id_company'  ";
}

if($inicio=="")
$inicio = 0;


$usuarios	= "";
$datos		= "";

$orderby = " ORDER BY orden asc";
switch ($orden) {
	case 0:
		$orderby = " ORDER BY u.nombre_usuario $direccion ";
		break;
	case 1:
		$orderby = " ORDER BY u.email $direccion ";
		break;
	case 2:
		$orderby = " ORDER BY r.nombre $direccion ";
		break;	
}

/*Consulta Cantidad de registros*/
$query_count = "SELECT * from arpis.usuario u, arpis.rol r WHERE r.id_rol = u.id_rol $busqueda ";

$data = array("consulta" => $query_count);							
$resultado = $services->sendPostNoToken($url_services.'/util/count',$data);		
$cantidad_registros =$resultado;

if(!$cantidad_registros){
	$cantidad_registros = 0;
	$json = json_decode("[]");
}else{
/*Obtiene Json con objetos*/
$query= "SELECT u.token,u.nombre_usuario,u.email,r.nombre as nombre_rol from arpis.usuario u, arpis.rol r WHERE r.id_rol = u.id_rol  $busqueda $orderby ";
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

$asignar = "";
$asignar = "<a href='javascript: agregarUser(\\\"$result->token\\\",\\\"$result->nombre_usuario\\\");'><i class='fas fa-plus-square'></i></a>"; 

$datos = $datos ."
     $signo_coma
	 [
      \"$result->nombre_usuario\",
      \"$result->email\",
	  \"$result->nombre_rol\",
      \"$asignar\"
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