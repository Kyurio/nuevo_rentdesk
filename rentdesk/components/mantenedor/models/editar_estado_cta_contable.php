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
$id_usuario = $_SESSION["rd_usuario_id"];
$arrendatarios = "";
$fecha = date("Y-m-d H:i:s");


$activo = $_POST["activo"];
$idCtaContable = @$_POST['idCtaContable'];


/*=================================================================*/
/*PROCESAMIENTO DE FORMULARIO
/*=================================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$num_reg = 10;
	$inicio = 0;





	$queryCabecera = " UPDATE propiedades.tp_cta_contable
					SET  
					activo=$activo
					WHERE id = $idCtaContable ";




	var_dump($queryCabecera);
	$dataCab = array("consulta" => $queryCabecera);
	$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	//var_dump($resultadoCab);
	/*---------------------------- */




	if ($resultadoCab != "OK") {
		echo ",xxx,ERROR,xxx,No se logró actualizar cuenta contable,xxx,-,xxx,";
		return;
	} else {
		echo ",xxx,OK,xxx,Se actualizó cuenta contable,xxx,-,xxx,";
	}



	//$services->sendPost($url_services . '/rentdesk/arriendos', $data, [], null);
}
