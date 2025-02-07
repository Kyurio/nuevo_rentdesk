<?php
session_start();
//include("../../../includes/sql_inyection.php");
include("../configuration.php");
include("../includes/funciones.php");
include("../includes/services_util.php");

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

////var_dump("ENTRÓ A SELECT SUBSIDIARIA");
////var_dump("POST SUBSIDIARIA: ", json_decode($_POST['subsidiaria']));

// Check if $_SESSION['rd_current_subsidiaria'] is set, otherwise initialize it
if (!isset($_SESSION['rd_current_subsidiaria'])) {
    $_SESSION['rd_current_subsidiaria'] = null; // or initialize it with a default value
}

if (!isset($_SESSION['sesion_rd_sucursales'])) {
    $_SESSION['sesion_rd_sucursales'] = null; // or initialize it with a default value
}

// Assuming $_SESSION['sesion_subsidiarias'] contains the serialized array
if (isset($_SESSION['sesion_rd_subsidiarias'])) {
    // Handle AJAX request to update session with selected subsidiaria
    if (isset($_POST['subsidiaria'])) {
        $_SESSION['rd_current_subsidiaria'] = serialize(json_decode($_POST['subsidiaria']));
        //var_dump("SUBSIDIARIA SELECCIONADA: ", $_SESSION['rd_current_subsidiaria']);

        /*LLAMADO A ENDPOINT SUCURSALES */
        $current_subsidiaria = json_decode($_POST['subsidiaria']);

        //var_dump("current_subsidiaria: ", $current_subsidiaria);
        $queryParamsSuc = array(
            'token_subsidiaria' => $current_subsidiaria->token
        );
		$num_reg = 100;
		$inicio = 0;
		
		$query = "select cs.principal as \"subsidiariaPrincipal\" ,cs.token as \"subsidiariaToken\" ,cs2.casa_matriz as \"sucursalCasaMatriz\" ,
				cs2.habilitada  as \"sucursalHabilitada\" , cs2.nombre as \"sucursalNombre\" , cs2.token  as \"sucursalToken\"
				from propiedades.cuenta_subsidiaria cs,
				propiedades.cuenta_sucursal cs2  
				where cs.token = '$current_subsidiaria->token' and cs2.id_subsidiaria = cs.id ";
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		$jsonSuc = json_decode($resultado);

/*
        $resultadoSuc = $services->sendGet($url_services . '/rentdesk/cuentas/sucursales', null, [], $queryParamsSuc);
		$jsonSuc = json_decode($resultadoSuc);
		var_dump($jsonSuc);
        */

        $_SESSION["sesion_rd_sucursales"] = serialize($jsonSuc);
		//var_dump("_SESSION ",$_SESSION["sesion_rd_sucursales"]);

        exit(0); // Stop further execution
    }
} else {
    echo 'No subsidiarias found in session storage.';
}
