<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");

$tipo_documento	= @$_POST['tipo_documento'];
$numDocumento	= @$_POST['numDocumento'];
$digitoVerificador	= @$_POST['digitoVerificador'];
$nombre		 = @$_POST['nombre'];
$apellidoPat = @$_POST['apellidoPat'];
$apellidoMat = @$_POST['apellidoMat'];
$fono 		 = @$_POST['fono'];
$celular	 = @$_POST['celular'];
$email     	 = @$_POST['email'];
$comuna      = @$_POST['comuna'];
$direccion   = @$_POST['direccion'];
$estado_persona = @$_POST['estado_persona'];
$numCuenta = @$_POST['numCuenta'];
$banco     = @$_POST['banco'];
$token	   = @$_POST['token'];
$personalidadLegal	   = @$_POST['tipo_persona_legal'];
$comunaCom      = @$_POST['comunacom'];
$direccionCom   = @$_POST['direccioncom'];

$id_company 	= $_SESSION["rd_company_id"];
$id_tipo_persona = 2;

$id_comuna = explode("|", $comuna);
$id_comuna = $id_comuna[0];

$id_comuna_com = explode("|", $comunaCom);
$id_comuna_com = $id_comuna_com[0];

$config 	= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

$token_nuevo = md5(rand(99999, 99999999) . $numDocumento . $nombre . date("Y m d H s"));

if ($token == "" && $numDocumento != "") {

	$data = array(
		"idTipoDocumento" => $tipo_documento,
		"numDocumento" => $numDocumento,
		"digitoVerificador" => $digitoVerificador,
		"nombre" => $nombre,
		"apellidoPat" => $apellidoPat,
		"apellidoMat" => $apellidoMat,
		"fono" => $fono,
		"celular" => $celular,
		"email" => $email,
		"direccion" => $direccion,
		"idEstadoPersona" => $estado_persona,
		"numCuenta" => $numCuenta,
		"idBanco" => $banco,
		"token" => $token_nuevo,
		"idTipoPersona" => $id_tipo_persona,
		"idEmpresa" => $id_company,
		"idComuna" => $id_comuna,
		"personalidadLegal" => $personalidadLegal,
		"idComunaCom" => $id_comuna_com,
		"direccionCom" => $direccionCom
	);
	$resultado = $services->sendPostNoToken($url_services . '/persona', $data);

	$result = json_decode($resultado);

	echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token_nuevo,xxx,";
}

//***********************************************************************************************************

if ($token != "" && $numDocumento != "") {

	$data = array(
		"idTipoDocumento" => $tipo_documento,
		"numDocumento" => $numDocumento,
		"digitoVerificador" => $digitoVerificador,
		"nombre" => $nombre,
		"apellidoPat" => $apellidoPat,
		"apellidoMat" => $apellidoMat,
		"fono" => $fono,
		"celular" => $celular,
		"email" => $email,
		"direccion" => $direccion,
		"idEstadoPersona" => $estado_persona,
		"numCuenta" => $numCuenta,
		"idBanco" => $banco,
		"token" => $token,
		"idTipoPersona" => $id_tipo_persona,
		"idEmpresa" => $id_company,
		"idComuna" => $id_comuna,
		"personalidadLegal" => $personalidadLegal,
		"idComunaCom" => $id_comuna_com,
		"direccionCom" => $direccionCom
	);
	$resultado = $services->sendPut($url_services . '/persona', $data, [], []);

	$result = json_decode($resultado);

	echo ",xxx,$result->status,xxx,$result->mensaje,xxx,$token,xxx,";
}
