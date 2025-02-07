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
$fecha = date("Y-m-d");
$carpeta = "upload\arriendo";
$fecha_defecto = "1900-01-01";

//Subida de archivos 
$patronIMG 	= "%\.(xls|XLS|xlsx|XLSX)$%i";

$fis_arch = @$_FILES["archivoEditar"]["name"];
$extension = pathinfo($fis_arch, PATHINFO_EXTENSION);
$nombre_sin_extension = pathinfo($fis_arch, PATHINFO_FILENAME);
$nombre_sin_extension = str_replace('___-___', '', $nombre_sin_extension); //SE REMPLAZA EN EL CASO QUE SE TENGA LA MISMA NOMENCLATURA AL CREAR EL ARCHIVO

var_dump($extension);
$aleatorio = rand(9999,99999999);

/*
if ($fis_arch!="") {
	preg_match($patronIMG, $fis_arch) == 1 ? $archivoValido ="S": $archivoValido ="N";
			if($archivoValido == "S"){
				$doc_ima = $fis_arch;
				$doc_ima_fisico =  date('Ymd_his') . "_santander_$aleatorio." . pathinfo($fis_arch, PATHINFO_EXTENSION);

				move_uploaded_file($_FILES["archivo"]["tmp_name"], "rentdesk\upload\arriendo" . $doc_ima_fisico);


			}
}*/
$nombre_archivo = md5($aleatorio.date('Ymd_his'));

if ($fis_arch!="") {
				$doc_ima = $fis_arch;
				//$doc_ima_fisico =  "arriendo_".$nombre_archivo;
				//var_dump(pathinfo($fis_arch, PATHINFO_EXTENSION));
				$doc_ima_fisico =  $nombre_sin_extension."___-___arriendo".$nombre_archivo;
				move_uploaded_file($_FILES["archivoEditar"]["tmp_name"], "../../../upload/arriendo/" . $doc_ima_fisico.".". pathinfo($fis_arch, PATHINFO_EXTENSION));

				//move_uploaded_file($_FILES["archivo"]["tmp_name"], "rentdesk/upload/arriendo/" . $doc_ima_fisico);
}else{
	var_dump("No se subio archivo");
}
/*	
$nombre_bdd = $doc_ima_fisico.".".$extension;
var_dump($nombre_bdd);
*/
/*=================================================================*/
/*PROCESAMIENTO DE FORMULARIO
/*=================================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$query = "UPDATE propiedades.propiedad
	SET direccion = 'TEST 2 MODIFICADO DESDE PORTAL'
	WHERE token = '18bdc972e1358d7563c1f88e0013006f'";

	$queryParams = array(
		'token_subsidiaria' => $current_subsidiaria->token,

	);

	$data = array(
		"consulta" => $query

	);
	$resultado = $services->sendPut($url_services . '/rentdesk/utils/actualizar', $data, [], $queryParams);
	$json = json_decode($resultado);


	// Accessing form fields
	
	$documentoTitulo = @$_POST['documentoTituloEditar'];
	$documentoFecha = @$_POST['documentoFechaEditar'];
	$tokenRegistro = @$_POST['documentoTokenEditar'];
	
	if ($documentoFecha == "" ) {
		$documentoFecha = $fecha_defecto;
	} 
	
	$num_reg = 10;
     $inicio = 0;
	 
	 
	$query = "SELECT id FROM propiedades.cuenta_usuario cu where token = '$id_usuario' ";
    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    $objUsuarioId = json_decode($resultado)[0];
	
	
	
	// Si no viene un archivo no se actualiza el archivo
	if ($fis_arch!=""){
			$queryCabecera= " UPDATE propiedades.propiedad_archivos
                      SET  archivo='$doc_ima_fisico', titulo='$documentoTitulo', fecha_vencimiento='$documentoFecha',  id_usuario_ultima_modificacion=$objUsuarioId->id, fecha_ultima_actualizacion='$fecha', 
					  extension='$extension' , nombre_archivo = '$nombre_sin_extension'
					WHERE token = '$tokenRegistro' ";
	}else{
			$queryCabecera= " UPDATE propiedades.propiedad_archivos
                    SET   titulo='$documentoTitulo', fecha_vencimiento='$documentoFecha',  id_usuario_ultima_modificacion=$objUsuarioId->id, fecha_ultima_actualizacion='$fecha'
			WHERE token = '$tokenRegistro' ";
		
	}

					
	
		var_dump($queryCabecera);
              $dataCab = array("consulta" => $queryCabecera);
              $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);	
    //var_dump($resultadoCab);
	/*---------------------------- */


	
	
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro realizar la edicion,xxx,-,xxx,";
		return;
	}else{
		echo ",xxx,OK,xxx,Edicion correcta,xxx,-,xxx,";
	}
	

	
	//$services->sendPost($url_services . '/rentdesk/arriendos', $data, [], null);
}
