<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$arrendatarios = "";

$codigo_propiedad = $_POST['codigo'];



/*=================================================================*/
/*PROCESAMIENTO DE FORMULARIO
/*=================================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {




	// Accessing form fields
	//$codigo_propiedad = @$POST['codigo'];



	/*---------------------------- */
	/*LLAMADO TABLAS PARAMETRICAS*/
	/*TIPO REAJUSTE*/



	$num_reg = 10;
	$inicio = 0;

	// $query = "SELECT 'SI' as existe , fa.token FROM propiedades.propiedad p , propiedades.ficha_arriendo fa  where codigo_propiedad = '$codigo_propiedad' and fa.id_propiedad = p.id";
	// $cant_rows = $num_reg;
	// $num_pagina = round($inicio / $cant_rows) + 1;
	// $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	// $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	// //var_dump($query);
	// $objPropiedad = json_decode($resultado)[0];
	// //var_dump($objPropiedad);


	// 	if(@$objPropiedad->existe == "SI"){
	// 	echo ",xxx,ERROR,xxx,Ya existe propiedad como arriendo,xxx,$objPropiedad->token,xxx,";
	// 	return;
	// }

	//SI LLEGA HASTA ESTE PUNTO SE REALIZA INSERCION CORRECTAMENTE
	//	echo ",xxx,OK,xxx,ARRIENDO ACTUALIZADO,xxx,-,xxx,";



	//$services->sendPost($url_services . '/rentdesk/arriendos', $data, [], null);




	/// codigo agregado por jose hernandez, extrae el ultimo estado de arriendo de la proìedad consultada
	$query = "SELECT id_estado_contrato FROM propiedades.ficha_arriendo WHERE id_propiedad = $codigo_propiedad  ORDER BY id DESC LIMIT 1";
	$data = array("consulta" => $query);
	$resultado  = $services->sendPostDirecto($url_services . '/util/objeto', $data);
	$objPropiedad = json_decode($resultado)[0];


	if (@$objPropiedad->id_estado_contrato == 1) {
		echo ",xxx,ERROR,xxx,Ya existe propiedad como arriendo,xxx,$objPropiedad->token,xxx,";
		return;
	}

	echo ",xxx,OK,xxx,ARRIENDO ACTUALIZADO,xxx,-,xxx,";
}
