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





$usuarioNombre=$_POST["UsuarioNombre"];
$usuarioApellidoPat=$_POST["usuarioApellidoPat"];
$usuarioApellidoMat=$_POST["UsuarioApellidoMat"];
$usuarioCorreo=strtolower($_POST["usuarioCorreo"]);
$usuarioContraseña=md5($_POST["usuarioContraseña"]);
$usuarioRut=$_POST["usuarioRut"];
$UsuarioActivo=$_POST["UsuarioActivo"];
$UsuarioRol=$_POST["tipoRol"];
// $tokenRegistro = $_POST["CtaContableToken"];



// Validamos que correo no exista previamente

$queryUsuario= " SELECT count(*) as cantidad
from  propiedades.cuenta_usuario
where id_empresa = 1  and UPPER(correo) = UPPER('$usuarioCorreo')
";

$num_pagina = round(1 / 9999) + 1;
$data = array("consulta" => $queryUsuario, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objUsuario = json_decode($resultado)[0];

if ($objUsuario->cantidad > 0) {
    echo ",xxx,ERROR,xxx,Ya se encuentra el usuario registrado en sistema,xxx,-,xxx,";
    return;
}

// var_dump("current_usuario",$current_usuario );
//id_tipo_rol = 2, corresponde a roles sólo a nivel sistema rentdesk
$queryCtaContable = "INSERT INTO propiedades.cuenta_usuario 
( id_empresa, dni, id_tipo_dni, nombres, apellido_paterno, apellido_materno, correo, password, habilitado, activo)
VALUES( 1, '$usuarioRut', 1 ,'$usuarioNombre', '$usuarioApellidoPat', '$usuarioApellidoMat', '$usuarioCorreo','$usuarioContraseña', true, '$UsuarioActivo');
";

//var_dump($queryCtaContable);
$dataCab = array("consulta" => $queryCtaContable);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

if ($resultadoCab != "OK") {
    echo ",xxx,ERROR,xxx,No se logró ingresar Usuario,xxx,-,xxx,";
    return;
}

//Buscamos ID para saber 

$queryUsuario= " SELECT id
from  propiedades.cuenta_usuario
where id_empresa = 1  and UPPER(correo) = UPPER('$usuarioCorreo')
";
var_dump($queryUsuario);
$num_pagina = round(1 / 9999) + 1;
$data = array("consulta" => $queryUsuario, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objUsuarioID = json_decode($resultado)[0];


$queryRol = "INSERT INTO propiedades.cuenta_subsidiarias_usuarios 
( id_subsidiaria,id_usuario,id_rol)
VALUES( 1,$objUsuarioID->id, $UsuarioRol);
";

var_dump($queryRol);
$dataCab = array("consulta" => $queryRol);
$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

if ($resultadoCab != "OK") {
    echo ",xxx,ERROR,xxx,No se logró ingresar rol del Usuario,xxx,-,xxx,";
    return;
}

	foreach ($_POST['sucursal'] as $sucursal) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_usuario_sucursales
						( id_usuario , id_sucursal )
						VALUES( '$objUsuarioID->id', $sucursal) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro insertar sucursal,xxx,-,xxx,";
		return;
	    }
    }


echo ",xxx,OK,xxx,Usuario Ingresado Correctamente,xxx,-,xxx,";
