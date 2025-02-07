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


$comentario = $_POST["ComentarioEditar"];
$tokenRegistro = $_POST["InfoComentarioTokenEditar"];
$ID_Info_Comentario_Editar = @$_POST['ID_Info_Comentario_Editar'];


/*=================================================================*/
/*PROCESAMIENTO DE FORMULARIO
/*=================================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_reg = 10;
    $inicio = 0;
	 
	 
	$query = "SELECT id FROM propiedades.cuenta_usuario cu where token = '$id_usuario' ";
    $cant_rows = $num_reg;
    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    $objUsuarioId = json_decode($resultado)[0];




	$queryCabecera= " UPDATE propiedades.propiedad_comentarios
					SET  
					id_usuario_modificacion=$objUsuarioId->id, 
					comentario='$comentario',
					fecha_modificacion='$fecha'
					WHERE id = $ID_Info_Comentario_Editar ";


					
	
		var_dump($queryCabecera);
              $dataCab = array("consulta" => $queryCabecera);
              $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);	
    //var_dump($resultadoCab);
	/*---------------------------- */


	
	
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro registrar documento,xxx,-,xxx,";
		return;
	}else{
		echo ",xxx,OK,xxx,Se registro documento,xxx,-,xxx,";
	}
	

	
	//$services->sendPost($url_services . '/rentdesk/arriendos', $data, [], null);
}
