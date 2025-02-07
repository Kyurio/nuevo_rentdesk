<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];


$num_reg = 20;
$inicio = 0;

if(isset($_POST["token"])) {
    $token = $_POST["token"];
	
	/*
	$queryIdArriendo ="select fa.id from propiedades.ficha_arriendo fa where fa.token = '$token' ";
	$cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $queryIdArriendo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    $objIdArriendo = json_decode($resultado)[0];
	*/
	$queryDocumento = " select pa.*,ruta||'\'||archivo||'.'||extension as link , cu.nombres||' '||cu.apellido_paterno as nombre_usuario
	from propiedades.propiedad_archivos pa
	LEFT JOIN propiedades.cuenta_usuario cu ON pa.id_usuario_ultima_modificacion = cu.id
	where token_defecto = '$token'
	and estado = true 
	order by pa.id desc";



	

} 

//$queryDocumento = " select * from propiedades.propiedad_archivos where estado = true order by id desc"
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryDocumento, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objDocumentos = json_decode($resultado);

echo json_encode($objDocumentos);


?>