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



	return;
	// Accessing form fields
	$tipoPropiedad = $_POST['tipoPropiedad'];
	$estado = $_POST['estado'];
	$oficina = $_POST['oficina'];
	$fechaIngreso = $_POST['fechaIngreso'];
	$direccion = $_POST['direccion'];
	$complemento = $_POST['complemento'];
	$nroComplemento = $_POST['nroComplemento'];
	$numeroDepto = $_POST['numeroDepto'];
	$piso = $_POST['piso'];
	$coordenadas = $_POST['coordenadas'];
	$pais = $_POST['hiddenpais'];
	$region = $_POST['hiddenregion'];
	$comuna = $_POST['hiddencomuna'];
	$mCuadrados = $_POST['mCuadrados'];
	$edificado = $_POST['edificado'];
	$dormitorios = $_POST['dormitorios'];
	$dormitoriosServicio = $_POST['dormitoriosServicio'];
	$banos = $_POST['banos'];
	$banosVisita = $_POST['banosVisita'];
	$estacionamientos = $_POST['estacionamientos'];
	$bodegas = $_POST['bodegas'];
	$logia = $_POST['logia'];
	$piscina = $_POST['piscina'];
	$rol = $_POST['rol'];
	$avaluoFiscal = $_POST['avaluoFiscal'];
	$amoblado = $_POST['amoblado'];
	$dfl2 = $_POST['dfl2'];
	$destinoArriendo = $_POST['destinoArriendo'];
	$naturaleza = $_POST['naturaleza'];
	$dj1835 = $_POST['dj1835'];
	$pagoContribucion = $_POST['pagoContribucion'];
	$exentoContribucion = $_POST['exentoContribucion'];
	$montoRetencion = $_POST['montoRetencion'];
	$monedaRetencion = $_POST['monedaRetencion'];
	$motivoRetencion = $_POST['motivoRetencion'];
	$retenerHasta = $_POST['retenerHasta'];
	$mostrarCuentasServicio = $_POST['mostrarCuentasServicio'];
	// $sucursal = $_POST['sucursal'];
	// File upload handling
	// $archivoNombre = $_FILES['archivo']['name'];
	// $archivoTipo = $_FILES['archivo']['type'];
	// $archivoTmpNombre = $_FILES['archivo']['tmp_name'];
	// $archivoError = $_FILES['archivo']['error'];
	// $archivoTamano = $_FILES['archivo']['size'];




	/*GENERAR OBJETO DE ENVÍO A ENDPOINT */
	$data = array(
		'tipoPropiedad' => $tipoPropiedad ?? null,
		'estado' => $estado ?? null,
		'oficina' => $oficina ?? null,
		'fechaIngreso' => $fechaIngreso ?? null,
		'direccion' => $direccion ?? null,
		'complemento' => $complemento ?? null,
		'nroComplemento' => $nroComplemento ?? null,
		'numeroDepto' => $numeroDepto ?? null,
		'piso' => $piso ?? null,
		'coordenadas' => $coordenadas ?? null,
		'pais' => $pais ?? null,
		'region' => $region ?? null,
		'comuna' => $comuna ?? null,
		'mCuadrados' => $mCuadrados ?? null,
		'edificado' => $edificado ?? null,
		'dormitorios' => $dormitorios ?? null,
		'dormitoriosServicio' => $dormitoriosServicio ?? null,
		'banos' => $banos ?? null,
		'banosVisita' => $banosVisita ?? null,
		'estacionamientos' => $estacionamientos ?? null,
		'bodegas' => $bodegas ?? null,
		'logia' => $logia ?? null,
		'piscina' => $piscina ?? null,
		'rol' => $rol ?? null,
		'avaluoFiscal' => $avaluoFiscal ?? null,
		'amoblado' => $amoblado ?? null,
		'dfl2' => $dfl2 ?? null,
		'destinoArriendo' => $destinoArriendo ?? null,
		'naturaleza' => $naturaleza ?? null,
		'dj1835' => $dj1835 ?? null,
		'pagoContribucion' => $pagoContribucion ?? null,
		'exentoContribucion' => $exentoContribucion ?? null,
		'montoRetencion' => $montoRetencion ?? null,
		'monedaRetencion' => $monedaRetencion ?? null,
		'motivoRetencion' => $motivoRetencion ?? null,
		'retenerHasta' => $retenerHasta ?? null,
		'mostrarCuentasServicio' => $mostrarCuentasServicio ?? null,
		// 'sucursal' => $sucursal,
		// 'archivo' => $archivo
	);


	// //var_dump("DATOS A ENVIAR DESDE FORMULARIO ARRIENDO: ", $data);
}
