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
$arrendatarios = "";
$current_sucursal = unserialize($_SESSION["rd_current_sucursal"]);
$codeudor_generico = "";



/*=================================================================*/
/*PROCESAMIENTO DE FORMULARIO
/*=================================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {


	// Accessing form fields
	$propiedad = @$_POST['codigo_propiedad'];
	$codeudor = @$_POST['codeudor'];

	$arrendatario = @$_POST['arrendatario'];


	foreach ($arrendatario as $opcion_arrendatarios) {
		$arrendatarios = $arrendatarios . "," . $opcion_arrendatarios;
	}
	//var_dump(substr($arrendatarios, 1));

	$fechaInicio = @$_POST['fechaInicio'];
	$fechaTermino = @$_POST['fechaTermino'];
	$duracionContrato = @$_POST['duracionContrato'];
	$precioContrato = @$_POST['precioContrato'];
	$monedaContrato = @$_POST['monedaContrato'];
	$montoGarantia = @$_POST['montoGarantia'];
	$pagoGarantiaProp = @$_POST['pagoGarantiaProp'];
	$cobroMesCalendario = @$_POST['cobroMesCalendario'];
	$tipoMulta = @$_POST['tipoMulta'];
	$diasPagoUltimoCobro = @$_POST['diasPagoUltimoCobro'];
	$montoMultaAtraso = @$_POST['montoMultaAtraso'];
	$monedaMulta = @$_POST['monedaMulta'];
	$cobroPropPrimerMes = @$_POST['cobroPropPrimerMes'];
	$tipoReajuste = @$_POST['tipoReajuste'];
	$meses = @$_POST['meses'];
	$permiteReajusteNegativo = @$_POST['permiteReajusteNegativo'];
	$diasPagoUltimoCobro = @$_POST['diasPagoUltimoCobro'];
	$fechaTermino = @$_POST['fechaTermino'];
	$diasPagoUltimoCobro = @$_POST['diasPagoUltimoCobro'];
	$cobrarComisionArriendo = @$_POST['cobrarComisionArriendo'];
	$comisionArriendo = @$_POST['comisionArriendo'];
	$monedaComisionArriendo = @$_POST['monedaComisionArriendo'];
	$facturarComisionArriendo = @$_POST['facturarComisionArriendo'];
	$tipoFacturaComisionArriendo = @$_POST['tipoFacturaComisionArriendo'];
	$cobrarComisionAdministracion = @$_POST['cobrarComisionAdministracion'];
	$comisionAdministracion = @$_POST['comisionAdministracion'];
	$monedaComisionAdministracion = @$_POST['monedaComisionAdministracion'];
	$facturarComisionAdministracion = @$_POST['facturarComisionAdministracion'];
	$tipoFacturaComisionAdministracion = @$_POST['tipoFacturaComisionAdministracion'];
	$facturarComisionAdministracion = @$_POST['facturarComisionAdministracion'];
	$amoblado = @$_POST['amoblado'];
	$archivo = @$_POST['monedaRetencion'];
	$archivo_bd = @$_POST['motivoRetencion'];

	// agrega un codeudor por default si no existe uno
	if ($codeudor == "") { // Se utiliza token por defecto por subsidiaria
		$codeudor = 'a25f4546481ca96c9caca53406ff056d';
		$codeudor_generico = 'a25f4546481ca96c9caca53406ff056d';
	}


	// manipulacion de fechas por default
	$fecha_actual = date("Y-m-d");
	$fecha_final = date("Y-m-d", strtotime("+1 year", strtotime($fecha_actual)));



	/*GENERAR OBJETO DE ENVÍO A ENDPOINT */
	$data = array(
		'propiedad' => $propiedad ?? null,
		'codeudor' => $codeudor ?? null,
		'fechaInicio' => $fechaInicio ?? null,
		'fechaTermino' => $fechaTermino ?? null,
		'duracionContrato' => $precioContrato ?? null,
		'monedaContrato' => $monedaContrato ?? null,
		'montoGarantia' => $montoGarantia ?? null,
		'pagoGarantiaProp' => $pagoGarantiaProp ?? null,
		'cobroMesCalendario' => $cobroMesCalendario ?? null,
		'tipoMulta' => $tipoMulta ?? null,
		'diasPagoUltimoCobro' => $diasPagoUltimoCobro ?? null,
		'montoMultaAtraso' => $montoMultaAtraso ?? null,
		'monedaMulta' => $monedaMulta ?? null,
		'cobroPropPrimerMes' => $cobroPropPrimerMes ?? null,
		'tipoReajuste' => $tipoReajuste ?? null,
		'meses' => $meses ?? null,
		'permiteReajusteNegativo' => $permiteReajusteNegativo ?? null,
		'diasPagoUltimoCobro' => $diasPagoUltimoCobro ?? null,
		'cobrarComisionArriendo' => $cobrarComisionArriendo ?? null,
		'comisionArriendo' => $comisionArriendo ?? null,
		'monedaComisionArriendo' => $bodegas ?? null,
		'facturarComisionArriendo' => $facturarComisionArriendo ?? null,
		'tipoFacturaComisionArriendo' => $tipoFacturaComisionArriendo ?? null,
		'cobrarComisionAdministracion' => $cobrarComisionAdministracion ?? null,
		'comisionAdministracion' => $comisionAdministracion ?? null,
		'monedaComisionAdministracion' => $monedaComisionAdministracion ?? null,
		'facturarComisionAdministracion' => $facturarComisionAdministracion ?? null,
		'tipoFacturaComisionAdministracion' => $tipoFacturaComisionAdministracion ?? null,
		'facturarComisionAdministracion' => $facturarComisionAdministracion ?? null,
		'amoblado' => $amoblado ?? null,
		'archivo' => $archivo ?? null,
		'archivo_bd' => $archivo_bd ?? null
	);


	/*---------------------------- */
	/*LLAMADO TABLAS PARAMETRICAS*/
	/*TIPO REAJUSTE*/

	$num_reg = 10;
	$inicio = 0;

	$query = "SELECT id FROM propiedades.tp_tipo_reajuste where nombre = '$tipoReajuste' ";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	//var_dump($resultado);
	$objTipoReajuste = json_decode($resultado)[0];


	$num_reg = 10;
	$inicio = 0;

	$query = "SELECT token FROM propiedades.propiedad where id = $propiedad OR codigo_propiedad = '$propiedad'";

	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
	$objTokenPropiedad = json_decode($resultado)[0];


	/*TIPO MONEDA*/

	$num_reg = 10;
	$inicio = 0;

	if ($monedaContrato == 'Pesos') {
		$tipoMoneda = 'CLP';
	}

	$query = "SELECT id FROM propiedades.tp_tipo_moneda where codigo_externo = '$tipoMoneda' ";
	$cant_rows = $num_reg;
	$num_pagina = round($inicio / $cant_rows) + 1;
	$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
	$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);


	/*---------------------------- */




	$request = array(
		"tokenSubsidiaria" => $current_subsidiaria->token,
		"tokenSucursal" => $current_sucursal->sucursalToken,
		"tokenPropiedad" => $objTokenPropiedad->token,
		"arrendatarios" => $arrendatario,
		"tokenCodeudor" => $codeudor,
		"fechaInicio" => $fecha_actual,
		"fechaTerminoReal" => $fecha_final,
		"precio" => "0",
		"idMonedaPrecio" => 2,
		"cobroMesCalendario" => true,
		"duracionContratoMeses" => "0",
		"idTipoReajuste" => 1,
		"pagoGarantiaPropietario" => true
	);




	$resultadoFinal =  $services->sendPost($url_services . '/rentdesk/arriendos', $request, null, null);


	$objTipoMoneda = json_decode($resultado)[0];

	$arreglo = json_decode($resultadoFinal, true);


	$json_resultado = json_encode($arreglo);
	$id_resultado = $arreglo['id'];
	$token_resultado = $arreglo['token'];



	if ($id_resultado != null || $id_resultado != "") {

		$queryCabecera = "UPDATE propiedades.propiedad set id_estado_propiedad =  2 where token = '$objTokenPropiedad->token' ";

		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		//Cuando se crea un arriendo se deja como vigente 
		$queryCabecera = " UPDATE propiedades.ficha_arriendo set id_estado_contrato =  1 where token = '$token_resultado' ";
		//var_dump($queryCabecera);
		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

		if ($codeudor_generico != "") {
			/*SELECTOR - ID de token resultado - MANTENER PARA RENTDESK */


			$queryCabecera = " DELETE FROM propiedades.ficha_arriendo_codeudores where  id_ficha_arriendo = '$id_resultado' ";
			//var_dump($queryCabecera);
			$dataCab = array("consulta" => $queryCabecera);
			$resultadoBorrar = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		}



		echo ",xxx,OK,xxx,Arriendo base creado,xxx,$token_resultado,xxx,";
	} else {


		echo ",xxx,ERROR,xxx,Error al crear arriendo base,xxx,-,xxx,";
	}





	echo ",xxx,OK,xxx,Arriendo creado,xxx,-,xxx,";



	//$services->sendPost($url_services . '/rentdesk/arriendos', $data, [], null);
}
