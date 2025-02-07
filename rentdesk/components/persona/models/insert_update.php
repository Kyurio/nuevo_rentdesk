<?php
session_start();
// include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");


$tipo_documento	= @$_POST['tipo_documento'];
$dni	= @$_POST['dni'];
$nombre		 = @$_POST['nombre'];
$apellidoPat = @$_POST['apellidoPat'];
$apellidoMat = @$_POST['apellidoMat'];
$telefonoFijo 		 = @$_POST['telefonoFijo'];
$telefonoMovil	 = @$_POST['telefonoMovil'];
$email     	 = @$_POST['email'];
$personalidadLegal	   = @$_POST['tipo_persona_legal'];
$giro	   = @$_POST['giro'];
$nombreFantasia	   = @$_POST['nombreFantasia'];
$razonSocial	   = @$_POST['razonSocial'];
$estadoCivil	   = @$_POST['estado_civil'];
$direccion	   = @$_POST['direccion'];
$comuna	   = @$_POST['comuna'];
$nroComplemento	   = @$_POST['nroComplemento'];
$correoElectronico = @$_POST['correoElectronico'];
$fechaNacimiento = @$_POST['fechaNacimiento'];
$InformacionAdicional = @$_POST['InformacionAdicional'];
$complemento = @$_POST['complemento'];
$tipoPropiedad = @$_POST['tipoPropiedad'];

$token = @$_POST['token'];

if ($fechaNacimiento == ""){
	$fecha_actual = new DateTime();
	
	$fechaNacimiento = $fecha_actual->format("Y-m-d");
}

// $digitoVerificador	= @$_POST['digitoVerificador'];
// $comuna      = @$_POST['comuna'];
// $direccion   = @$_POST['direccion'];
// $estado_persona = @$_POST['estado_persona'];
// $numCuenta = @$_POST['numCuenta'];
// $banco     = @$_POST['banco'];
// $token	   = @$_POST['token'];

// $comunaCom      = @$_POST['comunacom'];
// $direccionCom   = @$_POST['direccioncom'];

// $id_company 	= $_SESSION["rd_company_id"];
// $id_tipo_persona = 2;

// $id_comuna = explode("|", $comuna);
// $id_comuna = $id_comuna[0];

// $id_comuna_com = explode("|", $comunaCom);
// $id_comuna_com = $id_comuna_com[0];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

// $token_nuevo = md5(rand(99999, 99999999) . $dni . $nombre . date("Y m d H s"));

//var_dump("tipo persona: ", $personalidadLegal);
//var_dump("comuna: ", $comuna);
//var_dump("token: ", $token);

/*---------------------------- */
/*LLAMADO TABLAS PARAMETRICAS*/
/*OBTENER ID DE PERSONA*/


$num_reg = 10;
$inicio = 0;

if (isset($token) && $token != "") {
$query = "SELECT pd.id_persona FROM propiedades.persona p , propiedades.persona_direcciones pd where p.token = '$token'  and pd.id_persona = p.id ";
var_dump($query);
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objPersona = json_decode($resultado)[0];
}
var_dump($objPersona);
/*TIPO PERSONA*/

$num_reg = 10;
$inicio = 0;

$query = "SELECT id, nombre, descripcion FROM propiedades.tp_tipo_persona where id = $personalidadLegal";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objTipoPersona = json_decode($resultado)[0];

/*---------------------------- */
/*TIPO DNI*/

$num_reg = 10;
$inicio = 0;

$query = "SELECT id, nombre, descripcion FROM propiedades.tp_tipo_dni where id = $tipo_documento";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objTipoDni = json_decode($resultado)[0];
/*---------------------------- */
/*TIPO ESTADO CIVIL*/

$num_reg = 10;
$inicio = 0;

$query = "SELECT id, nombre, descripcion, habilitado FROM propiedades.tp_estado_civil where id = $estadoCivil";
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objEstadoCivil = json_decode($resultado)[0];
/*---------------------------- */


/*---------------------------- */
/*CREACIÓN/ACTUALIZACIÓN PERSONA */
if (isset($token) && $token != "") {

	// Update the request object
	$request = array(
		"tipoPersona" => array(
			"id" => $objTipoPersona->id,
			"nombre" => $objTipoPersona->nombre,
		),
		"idSubsidiaria" => $current_subsidiaria->id,
		"tokenSubsidiaria" => $current_subsidiaria->token,
		"dni" => $dni,
		"tipoDni" => array(
			"id" => $objTipoDni->id,
			"nombre" => $objTipoDni->nombre,
		),
		"correoElectronico" => $correoElectronico,
		"telefonoFijo" => $telefonoFijo,
		"telefonoMovil" => $telefonoMovil,
		"direcciones" => array(
			array(
				"comuna"  => array(
					"id" => intval($comuna),
				),
				"direccion" => $direccion,
				"numero" => $nroComplemento,
				"principal" => true,
			),
		),
	);

	/*CONDITIONAL TIPO PERSONA */
	// Define conditional part based on tipoPersona

	if ($personalidadLegal == 2) {
		// tipoPersona is 2, include datosJuridica
		$conditionalTipoPersona = array(
			"datosJuridica" => array(
				"razonSocial" => $razonSocial,
				"giro" => $giro,
				"nombreFantasia" => $nombreFantasia
			)
		);
	} else {
		// tipoPersona is not 2, include other object
		$conditionalTipoPersona = array(
			"datosNatural" => array(
				"apellidoMaterno" => $apellidoMat,
				"apellidoPaterno" => $apellidoPat,
				"nombres" => $nombre,
				"estadoCivil" => $objEstadoCivil,
				"fechaNacimiento" => $fechaNacimiento
			)
		);
	}

	// Merge the base request with the conditional part
	$request = array_merge($request, $conditionalTipoPersona);

	//var_dump("DATOS A ENVIAR PATCH: ", $request);
    var_dump(json_decode($request));
	$resultado =  $services->sendPatch($url_services . '/rentdesk/personas', $request, null, null);
	
	If($complemento != "" || $InformacionAdicional != ""){
		$queryCabecera= " UPDATE propiedades.persona_direcciones SET comentario2 = '$InformacionAdicional' , comentario = '$complemento' , complemento = '$tipoPropiedad'
                WHERE id_persona = $objPersona->id_persona ";
				var_dump($queryCabecera);
                $dataCab = array("consulta" => $queryCabecera);
                $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
	}
	
		$query = "SELECT 'SI' as existe FROM propiedades.persona p  where p.dni = '$dni' ";
        $cant_rows = $num_reg;
        $num_pagina = round($inicio / $cant_rows) + 1;
        $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
        $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
        $objExistePersona = json_decode($resultado)[0];
		var_dump($objExistePersona);

        if ($objExistePersona->existe == "SI"){
		   echo ",xxx,OK,xxx,Persona Actualizada Correctamente,xxx,-,xxx,";
		}else{
			echo ",xxx,ERROR,xxx,No se logro crear Persona,xxx,-,xxx,";
		}
        
} else {
    var_dump("ENTRO EN ELSE");
	
	$query = "SELECT 'SI' as existe FROM propiedades.persona p  where p.dni = '$dni' ";
        $cant_rows = $num_reg;
        $num_pagina = round($inicio / $cant_rows) + 1;
        $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
        $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
        $objExistePersona = json_decode($resultado)[0];
        
        var_dump($objExistePersona);
        if ($objExistePersona->existe == "SI"){
			echo ",xxx,ERROR,xxx,DNI/RUT ya existe,xxx,-,xxx,";
			return;
		}
	
	
	
	// Create the request object
	$request = array(
		"tipoPersona" => array(
			"id" => $objTipoPersona->id,
			"nombre" => $objTipoPersona->nombre,
		),
		"idSubsidiaria" => $current_subsidiaria->id,
		"tokenSubsidiaria" => $current_subsidiaria->token,
		"dni" => $dni,
		"tipoDni" => array(
			"id" => $objTipoDni->id,
			"nombre" => $objTipoDni->nombre,
		),
		"correoElectronico" => $correoElectronico,
		"telefonoFijo" => $telefonoFijo,
		"telefonoMovil" => $telefonoMovil,
		"direcciones" => array(
			array(
				"comuna"  => array(
					"id" => intval($comuna),
				),
				"direccion" => $direccion,
				"numero" => $nroComplemento,
				"principal" => true,
			),
		),
	);
	
	

	/*CONDITIONAL TIPO PERSONA */
	// Define conditional part based on tipoPersona

	if ($personalidadLegal == 2) {
		// tipoPersona is 2, include datosJuridica
		$conditionalTipoPersona = array(

			"datosJuridica" => array(
				"razonSocial" => $razonSocial,
				"giro" => $giro,
				"nombreFantasia" => $nombreFantasia
			)
		);
	} else {
		// tipoPersona is not 2, include other object
		$conditionalTipoPersona = array(

			"datosNatural" => array(
				"apellidoMaterno" => $apellidoMat,
				"apellidoPaterno" => $apellidoPat,
				"nombres" => $nombre,
				"estadoCivil" => $objEstadoCivil,
				"fechaNacimiento" => $fechaNacimiento
			)
		);
	}

	// Merge the base request with the conditional part
	$request = array_merge($request, $conditionalTipoPersona);

	var_dump("DATOS A ENVIAR POST: ", json_encode($request));

	$resultado = $services->sendPost($url_services . '/rentdesk/personas', $request, [], null);
	
	If($complemento != "" || $InformacionAdicional != "" ){
		
		
		$query = "SELECT pd.id_persona FROM propiedades.persona p , propiedades.persona_direcciones pd where p.dni = '$dni'  and pd.id_persona = p.id ";
        $cant_rows = $num_reg;
        $num_pagina = round($inicio / $cant_rows) + 1;
        $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
        $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
        $objPersona = json_decode($resultado)[0];
		
		var_dump($dni);
		$queryCabecera= " UPDATE propiedades.persona_direcciones SET comentario2 = '$InformacionAdicional' , comentario = '$complemento' , complemento = '$tipoPropiedad'
                WHERE id_persona = $objPersona->id_persona ";
				var_dump($queryCabecera);
                $dataCab = array("consulta" => $queryCabecera);
                $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		
	}

		$query = "SELECT 'SI' as existe FROM propiedades.persona p  where p.dni = '$dni' ";
        $cant_rows = $num_reg;
        $num_pagina = round($inicio / $cant_rows) + 1;
        $data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
        $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
        $objExistePersona = json_decode($resultado)[0];

        if ($objExistePersona->existe == "SI"){
		   echo ",xxx,OK,xxx,Persona Actualizada Correctamente,xxx,-,xxx,";
		}else{
			echo ",xxx,ERROR,xxx,No se logro crear Persona,xxx,-,xxx,";
		}
}

//***********************************************************************************************************
