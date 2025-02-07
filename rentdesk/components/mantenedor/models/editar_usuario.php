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

$usuarioNombreEditar=@$_POST["usuarioNombreEditar"];
$usuarioApellidoPatEditar=@$_POST["usuarioApellidoPatEditar"];
$usuarioApellidoMatEditar=@$_POST["usuarioApellidoMatEditar"];
$usuarioCorreoEditar=@$_POST["usuarioCorreoEditar"];
$usuarioContraseñaEditar=@$_POST["usuarioContraseñaEditar"];
$usuarioRutEditar=@$_POST["usuarioRutEditar"];
$UsuarioActivoEditar=@$_POST["UsuarioActivoEditar"];
$usuarioRolEditar=@$_POST["usuarioRolEditar"];
$usuarioCorreoEditarActual=@$_POST["usuarioCorreoEditarActual"];
// $tokenRegistro = $_POST["CtaContableTokenEditar"];
$ID_usuario_Editar = @$_POST['ID_usuario_Editar'];
$ID_usuario_Editar_Pass = @$_POST['ID_usuario_Editar_Pass'];



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

// Validamos que correo no exista previamente

$queryUsuario= " SELECT count(*) as cantidad
from  propiedades.cuenta_usuario
where id_empresa = 1  and id != $ID_usuario_Editar and UPPER(correo) = UPPER('$usuarioCorreoEditar')
";

$num_pagina = round(1 / 9999) + 1;
$data = array("consulta" => $queryUsuario, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objUsuario = json_decode($resultado)[0];

if ($objUsuario->cantidad > 0) {
    echo ",xxx,ERROR,xxx,Ya se encuentra el usuario registrado en sistema,xxx,-,xxx,";
    return;
}

if ($usuarioContraseñaEditar != "") {
	
	$usuarioContraseñaEditar = md5($usuarioContraseñaEditar);
	
		$queryCabecera = " UPDATE propiedades.cuenta_usuario
					SET
					password='$usuarioContraseñaEditar'
					WHERE id = $ID_usuario_Editar_Pass ";
					
			var_dump($queryCabecera);
	$dataCab = array("consulta" => $queryCabecera);
	$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	
	if ($resultadoCab != "OK") {
		echo ",xxx,ERROR,xxx,No se logro actualizar contraseña,xxx,-,xxx,";
		return;
	} else {
		echo ",xxx,OK,xxx,Se actualizó contraseña,xxx,-,xxx,";
		return;
	}	
}else{
	
		$queryCabecera = " UPDATE propiedades.cuenta_usuario
					SET  
					nombres='$usuarioNombreEditar',
					apellido_paterno='$usuarioApellidoPatEditar',
					apellido_materno='$usuarioApellidoMatEditar', 
					correo='$usuarioCorreoEditar',
					dni='$usuarioRutEditar',
					activo=$UsuarioActivoEditar
					WHERE id = $ID_usuario_Editar ";
	
}





	var_dump($queryCabecera);
	$dataCab = array("consulta" => $queryCabecera);
	$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	//var_dump($resultadoCab);
	/*---------------------------- */
	
		$queryCabecera = " UPDATE propiedades.cuenta_subsidiarias_usuarios
					SET  id_rol = $usuarioRolEditar
					WHERE id_usuario = $ID_usuario_Editar ";




	var_dump($queryCabecera);
	$dataCab = array("consulta" => $queryCabecera);
	$resultadoRol = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	//var_dump($resultadoCab);
	/*---------------------------- */
	
	/*---------------------------- */
	
	$queryCabecera = " DELETE FROM propiedades.cuenta_usuario_sucursales
					WHERE id_usuario = $ID_usuario_Editar ";


	var_dump($queryCabecera);
	$dataCab = array("consulta" => $queryCabecera);
	$resultadoEliminar = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
	//var_dump($resultadoCab);
	/*---------------------------- */
	
	
	foreach ($_POST['usuarioEditar'] as $usuarioEditar) {

		$queryCabecera= " INSERT INTO propiedades.cuenta_usuario_sucursales
						( id_usuario , id_sucursal )
						VALUES( '$ID_usuario_Editar', $usuarioEditar) ";
		var_dump($queryCabecera);
        $dataCab = array("consulta" => $queryCabecera);
        $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
		if($resultadoCab != "OK"){
		echo ",xxx,ERROR,xxx,No se logro insertar sucursal,xxx,-,xxx,";
		return;
	    }
    }

	if ($resultadoCab != "OK" && $resultadoRol != "OK" && $resultadoEliminar  != "OK") {
		echo ",xxx,ERROR,xxx,No se logro actualizar usuario,xxx,-,xxx,";
		return;
	} else {
		echo ",xxx,OK,xxx,Se actualizó usuario,xxx,-,xxx,";
	}



	//$services->sendPost($url_services . '/rentdesk/arriendos', $data, [], null);
}
