<?php

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];
// $current_usuario = unserialize($_SESSION["sesion_rd_usuario"]);
// $current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

$cta_contable_id_ficha = @$_POST["id_ficha"];
$nombreRol = @$_POST["nombreRol"];
$descripcionRol = @$_POST["descripcionRol"];
$rolActivo = @$_POST["rolActivo"];

$component = @$_POST["component"];
$view = @$_POST["view"];
$token = @$_POST["token"];
$item = @$_POST["item"];
$id_recurso = @$_POST["id_recurso"];
$id_item = @$_POST["id_item"];
// Obtener el objeto de sesión y convertirlo en un objeto PHP
$sesion_rd_login = unserialize($_SESSION['sesion_rd_login']);
// Acceder a la dirección de correo electrónico
$correo = $sesion_rd_login->correo;



$num_reg = 10;
$inicio = 0;


// var_dump("current_usuario",$current_usuario );
//id_tipo_rol = 2, corresponde a roles sólo a nivel sistema rentdesk
$queryCtaContable = "INSERT INTO propiedades.cuenta_roles 
(nombre, descripcion, activo, id_tipo_rol)
 VALUES ('$nombreRol', '$descripcionRol',$rolActivo, 2)";


$dataCab = array("consulta" => $queryCtaContable);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);



if ($resultadoCab != "OK") {
    echo ",xxx,ERROR,xxx,No se logró ingresar rol,xxx,-,xxx,";
    return;
}


$num_reg = 30;
$inicio = 0;

$query = " select id from propiedades.cuenta_roles where nombre = '$nombreRol' ";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$json = json_decode($resultado)[0];

	foreach ($_POST['propiedadRol'] as $propiedadRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', $propiedadRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if ($propiedadRol == 3){
			
			$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', 48) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		}
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol propiedad,xxx,-,xxx,";
		return;
	    }
    }
	
	// Se agregar en Dashboard
	if(@$_POST['propiedadRol'] != null && @$_POST['propiedadRol'] != "" ){
		
		
		
				$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', 53) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
				$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', 54) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		

	}
	
	
		foreach ($_POST['administracionRol'] as $administracionRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', $administracionRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol administracion,xxx,-,xxx,";
		return;
	    }
    }
	
	
			foreach ($_POST['clienteRol'] as $clienteRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', $clienteRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		
		if ($clienteRol == 16){
			
		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', 49) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		}
		
		if ($clienteRol == 17){
			
		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
		( id_rol , id_componente_item )
		VALUES( '$json->id', 50) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		}
		

		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol cliente,xxx,-,xxx,";
		return;
	    }
    }
	
		foreach ($_POST['arriendoRol'] as $arriendoRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', $arriendoRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		
		if ($arriendoRol == 17){
			
		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
		( id_rol , id_componente_item )
		VALUES( '$json->id', 51) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		}
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol arriendo,xxx,-,xxx,";
		return;
	    }
    }
	
		// Se agregar en Dashboard
	if(@$_POST['arriendoRol'] != null && @$_POST['arriendoRol'] != "" ){
		

		
				$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', 52) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		

	}
	

		foreach ($_POST['facturacionRol'] as $facturacionRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', $facturacionRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol facturacion,xxx,-,xxx,";
		return;
	    }
    }
	
			foreach ($_POST['accionesRol'] as $accionesRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', $accionesRol) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol reporte,xxx,-,xxx,";
		return;
	    }
    }
	
		foreach ($_POST['reporteRol'] as $reporteRol) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', $reporteRol) ";
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
						VALUES( '$json->id', 40) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol arriendo,xxx,-,xxx,";
		return;
	    }
		
		$queryCabecera= " INSERT INTO propiedades.cuenta_rol_componentes
						( id_rol , id_componente_item )
						VALUES( '$json->id', 39) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro rol arriendo,xxx,-,xxx,";
		return;
	    }

echo ",xxx,OK,xxx,Rol Ingresado Correctamente,xxx,-,xxx,";
