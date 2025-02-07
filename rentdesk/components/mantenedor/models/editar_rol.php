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

$nombreRolEditar=$_POST["nombreRolEditar"];
$descripcionRolEditar=$_POST["descripcionRolEditar"];
$rolActivoEditar=$_POST["rolActivoEditar"];
// $tokenRegistro = $_POST["CtaContableTokenEditar"];
$ID_Rol_Editar = @$_POST['ID_Rol_Editar'];


/*=================================================================*/
/*PROCESAMIENTO DE FORMULARIO
/*=================================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$num_reg = 10;
	$inicio = 0;


	// $query = "SELECT id FROM propiedades.cuenta_usuario cu where token = '$id_usuario' ";
	// $cant_rows = $num_reg;
	// $num_pagina = round($inicio / $cant_rows) + 1;
	// $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	// $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	// $objUsuarioId = json_decode($resultado)[0];




	$queryCabecera = " UPDATE propiedades.cuenta_roles
					SET  
					nombre='$nombreRolEditar', 
					descripcion='$descripcionRolEditar',
					activo=$rolActivoEditar
					WHERE id = $ID_Rol_Editar ";




	var_dump($queryCabecera);
	$dataCab = array("consulta" => $queryCabecera);
	$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	
	if ($resultadoCab != "OK"){
	echo	",xxx,ERROR,xxx,No se logro actualizar rol,xxx,-,xxx,";
	}
	
	//var_dump($resultadoCab);
	/*---------------------------- */
	
	//Eliminamos y volvemos a insertar los roles
		$queryCabecera = " DELETE FROM propiedades.cuenta_rol_componentes
					WHERE id_rol = $ID_Rol_Editar ";




	var_dump($queryCabecera);
	$dataCab = array("consulta" => $queryCabecera);
	$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	
	if ($resultadoCab != "OK"){
	echo	",xxx,ERROR,xxx,No se logro editar rol,xxx,-,xxx,";
	}



	foreach ($_POST['propiedadRolEditar'] as $propiedadRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, $propiedadRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		
		if ($propiedadRol == 3){
			
			$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, 48) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		}
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol propiedad,xxx,-,xxx,";
		return;
	    }
    }
	
		foreach (@$_POST['administracionRolEditar'] as $administracionRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, $administracionRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol administracion,xxx,-,xxx,";
		return;
	    }
    }
	
			foreach ($_POST['clienteRolEditar'] as $clienteRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, $clienteRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if ($clienteRol == 16){
			
		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, 49) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		}
		
		if ($clienteRol == 17){
			
		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
		( id_rol , id_componente_item )
		VALUES( $ID_Rol_Editar, 50) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		}
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol cliente,xxx,-,xxx,";
		return;
	    }
    }
	
		foreach ($_POST['arriendoRolEditar'] as $arriendoRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, $arriendoRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if ($arriendoRol == 19){
			
		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
		( id_rol , id_componente_item )
		VALUES( $ID_Rol_Editar, 51) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		}
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol arriendo,xxx,-,xxx,";
		return;
	    }
    }

		foreach ($_POST['facturacionRolEditar'] as $facturacionRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, $facturacionRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol facturacion,xxx,-,xxx,";
		return;
	    }
    }
	
			foreach ($_POST['accionesRolEditar'] as $accionesRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, $accionesRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol reporte,xxx,-,xxx,";
		return;
	    }
    }
	
		foreach ($_POST['reporteRolEditar'] as $reporteRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, $reporteRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol reporte,xxx,-,xxx,";
		return;
	    }
    }
	
		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, 40) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol arriendo,xxx,-,xxx,";
		return;
	    }
		
		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, 39) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol arriendo,xxx,-,xxx,";
		return;
	    }
		
		
		
				// Se agregar en Dashboard
	if(@$_POST['arriendoRolEditar'] != null && @$_POST['arriendoRolEditar'] != "" ){
		

		
				$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, 52) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		

	}
	
	
		// Se agregar en Dashboard
	if(@$_POST['propiedadRolEditar'] != null && @$_POST['propiedadRolEditar'] != "" ){
		
		
		
				$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, 53) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
				$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( $ID_Rol_Editar, 54) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		

	}


	if ($resultadoCab != "OK") {
		echo ",xxx,ERROR,xxx,No se logro actualizar rol,xxx,-,xxx,";
		return;
	} else {
		echo ",xxx,OK,xxx,Se actualizó rol,xxx,-,xxx,";
	}



	//$services->sendPost($url_services . '/rentdesk/arriendos', $data, [], null);
}
