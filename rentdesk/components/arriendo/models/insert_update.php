<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/conexionMysql.php");
include("../../../includes/funciones.php");
include("../../../includes/resize.php");
include("../../../includes/services_util.php");
include("../../../app/model/QuerysBuilder.php");


use app\database\QueryBuilder;


$config 	= new Config;
$services   = new ServicesRestful;
$queryBuilder = new QueryBuilder();

$url_services = $config->url_services;
$current_subsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);
$arrendatarios = "";


$token_arriendo =  @$_POST['token_arrendatario'];




/*=================================================================*/
/*PROCESAMIENTO DE FORMULARIO
/*=================================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	/**
	 * 
	 * 
	 *  validacion para evitar actualizar una propiedad que tiene movimientos debes finalizar y crear una nueva
	 * 
	 * 
	 * 
	 */
	$id_propiedad = @$_POST['id_propiedad'];
	$estadoContrato = @$_POST['estadoContrato'];


	/*=================================================================*/
	/* valida si hay pagos antes de eliminar moviminetos
	/*=================================================================*/



	$conditions = ['token' => $token_arriendo];
	$result = $queryBuilder->select('propiedades.ficha_arriendo', $conditions);
	$id_ficha_arriendo = $result[0]['id'];


	//extrae el ultimo id de ficha_arriendo
	$result = $queryBuilder->selectAdvanced(
		'propiedades.ficha_arriendo_cta_cte_movimientos a', // Tabla principal con alias
		'b.id_tipo_movimiento, a.*', // Columnas seleccionadas
		[
			[
				'type' => 'INNER',
				'table' => 'propiedades.tp_tipo_movimiento_cta_cte b', // Tabla unida con alias
				'on' => 'a.id_tipo_movimiento_cta_cte = b.id'
			]
		],
		[
			'b.id_tipo_movimiento' => ['=', 2],
			'id_ficha_arriendo' => ['=', $id_ficha_arriendo]
		]
	);



	if ($result) {

		echo "No se puede modificar por que hay movimientos en la cuenta corriente.";
	} else {

		// Accessing form fields
		$propiedad = @$_POST['codigo_propiedad'];
		$codeudor = @$_POST['codeudor'];

		$arrendatario = @$_POST['arrendatario'];
		foreach ($arrendatario as $opcion_arrendatarios) {
			$arrendatarios = $arrendatarios . "," . $opcion_arrendatarios;
		}
		//var_dump( substr($arrendatarios, 1));

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
		$permiteReajusteNegativo_new = $permiteReajusteNegativo;
		$cobrarComisionArriendo = @$_POST['cobrarComisionArriendo'];
		$comisionArriendo = @$_POST['comisionArriendo'];
		$num_cuotas_garantia = @$_POST["num_cuotas_garantia"];
		$monedaComisionArriendo = @$_POST['monedaComisionArriendo'];
		$facturarComisionArriendo = @$_POST['facturarComisionArriendo'];
		$tipoFacturaComisionArriendo = @$_POST['tipoFacturaComisionArriendo'];
		$cobrarComisionAdministracion = @$_POST['cobrarComisionAdministracion'];
		$comisionAdministracion = @$_POST['comisionAdministracion'];
		$monedaComisionAdministracion = @$_POST['monedaComisionAdministracion'];
		$facturarComisionAdministracion = @$_POST['facturarComisionAdministracion'];
		$tipoFacturaComisionAdministracion = @$_POST['tipoFacturaComisionAdministracion'];
		//$facturarComisionAdministracion = @$_POST['facturarComisionAdministracion'];
		$amoblado = @$_POST['amoblado'];
		$archivo = @$_POST['monedaRetencion'];
		$archivo_bd = @$_POST['motivoRetencion'];
		$facturarComisionAdministracionLiquidacion = @$_POST['facturarComisionAdministracionLiquidacion'];
		$token_arriendo =  @$_POST['token_arrendatario'];
		$CantidadReajuste = @$_POST['CantidadReajuste'];
		$mesesGarantia = @$_POST['mesesGarantia'];
		$estadoContrato = @$_POST['estadoContrato'];


		//jhernandez captura el array con mese monedas y aplica
		$dataMeses = @$_POST['dataMeses'];
		$datamonedas = @$_POST['datamonedas'];
		$dataaplica = @$_POST['dataaplica'];

		// multa dias crobo jhernandez
		$diascobro = @$_POST['diascobro'];

		if (!$diascobro) {

			$diascobro = 0;
		}

		if (!$tipoMulta) {

			$tipoMulta  = 4;
		}

		if (!$monedaMulta) {

			$monedaMulta = 1;
		}


		//var_dump("Enviar datos comision : ");

		if ($facturarComisionArriendo = "SI") {
			$facturarComisionArriendo = true;
		} else {
			$facturarComisionArriendo = false;
		}

		if ($facturarComisionAdministracion = "SI") {
			$facturarComisionAdministracion = true;
		} else {
			$facturarComisionAdministracion = false;
		}

		if ($facturarComisionAdministracionLiquidacion = "SI") {
			$facturarComisionAdministracionLiquidacion = true;
		} else {
			$facturarComisionAdministracionLiquidacion = false;
		}



		////var_dump($estadoContrato);


		if ($CantidadReajuste == "") {
			$CantidadReajuste = 0;
		}




		$precioContrato = str_replace(",", "", $precioContrato);
		$precioContrato = str_replace(".", "", $precioContrato);


		$montoGarantia = str_replace(",", "", $montoGarantia);
		$montoGarantia = str_replace(".", "", $montoGarantia);

		$CantidadReajuste = str_replace(",", ".", $CantidadReajuste);


		/* Obtencion ajustes de enero a diciembre */
		$diasPagoUltimoCobroEnero = @$_POST['diasPagoUltimoCobroEnero'];
		$diasPagoUltimoCobroFebrero = @$_POST['diasPagoUltimoCobroFebrero'];
		$diasPagoUltimoCobroMarzo = @$_POST['diasPagoUltimoCobroMarzo'];
		$diasPagoUltimoCobroAbril = @$_POST['diasPagoUltimoCobroAbril'];
		$diasPagoUltimoCobroMayo = @$_POST['diasPagoUltimoCobroMayo'];
		$diasPagoUltimoCobroJunio = @$_POST['diasPagoUltimoCobroJunio'];
		$diasPagoUltimoCobroJulio = @$_POST['diasPagoUltimoCobroJulio'];
		$diasPagoUltimoCobroAgosto = @$_POST['diasPagoUltimoCobroAgosto'];
		$diasPagoUltimoCobroSeptiembre = @$_POST['diasPagoUltimoCobroSeptiembre'];
		$diasPagoUltimoCobroOctubre = @$_POST['diasPagoUltimoCobroOctubre'];
		$diasPagoUltimoCobroNoviembre = @$_POST['diasPagoUltimoCobroNoviembre'];
		$diasPagoUltimoCobroDiciembre = @$_POST['diasPagoUltimoCobroDiciembre'];

		$diasPagoUltimoCobroEnero      =     str_replace(",", ".", $diasPagoUltimoCobroEnero);
		$diasPagoUltimoCobroFebrero    =     str_replace(",", ".", $diasPagoUltimoCobroFebrero);
		$diasPagoUltimoCobroMarzo      =     str_replace(",", ".", $diasPagoUltimoCobroMarzo);
		$diasPagoUltimoCobroAbril      =     str_replace(",", ".", $diasPagoUltimoCobroAbril);
		$diasPagoUltimoCobroMayo       =     str_replace(",", ".", $diasPagoUltimoCobroMayo);
		$diasPagoUltimoCobroJunio      =     str_replace(",", ".", $diasPagoUltimoCobroJunio);
		$diasPagoUltimoCobroJulio      =     str_replace(",", ".", $diasPagoUltimoCobroJulio);
		$diasPagoUltimoCobroAgosto     =     str_replace(",", ".", $diasPagoUltimoCobroAgosto);
		$diasPagoUltimoCobroSeptiembre =     str_replace(",", ".", $diasPagoUltimoCobroSeptiembre);
		$diasPagoUltimoCobroOctubre    =     str_replace(",", ".", $diasPagoUltimoCobroOctubre);
		$diasPagoUltimoCobroNoviembre  =     str_replace(",", ".", $diasPagoUltimoCobroNoviembre);
		$diasPagoUltimoCobroDiciembre  =     str_replace(",", ".", $diasPagoUltimoCobroDiciembre);

		$diasPagoTipoMonedaEnero = @$_POST['diasPagoTipoMonedaEnero'];
		$diasPagoTipoMonedaFebrero = @$_POST['diasPagoTipoMonedaFebrero'];
		$diasPagoTipoMonedaMarzo = @$_POST['diasPagoTipoMonedaMarzo'];
		$diasPagoTipoMonedaAbril = @$_POST['diasPagoTipoMonedaAbril'];
		$diasPagoTipoMonedaMayo = @$_POST['diasPagoTipoMonedaMayo'];
		$diasPagoTipoMonedaJunio = @$_POST['diasPagoTipoMonedaJunio'];
		$diasPagoTipoMonedaJulio = @$_POST['diasPagoTipoMonedaJulio'];
		$diasPagoTipoMonedaAgosto = @$_POST['diasPagoTipoMonedaAgosto'];
		$diasPagoTipoMonedaSeptiembre = @$_POST['diasPagoTipoMonedaSeptiembre'];
		$diasPagoTipoMonedaOctubre = @$_POST['diasPagoTipoMonedaOctubre'];
		$diasPagoTipoMonedaNoviembre = @$_POST['diasPagoTipoMonedaNoviembre'];
		$diasPagoTipoMonedaDiciembre = @$_POST['diasPagoTipoMonedaDiciembre'];

		$OpcionAplicarEnero = @$_POST['OpcionAplicarEnero'];
		$OpcionAplicarFebrero = @$_POST['OpcionAplicarFebrero'];
		$OpcionAplicarMarzo = @$_POST['OpcionAplicarMarzo'];
		$OpcionAplicarAbril = @$_POST['OpcionAplicarAbril'];
		$OpcionAplicarMayo = @$_POST['OpcionAplicarMayo'];
		$OpcionAplicarJunio = @$_POST['OpcionAplicarJunio'];
		$OpcionAplicarJulio = @$_POST['OpcionAplicarJulio'];
		$OpcionAplicarAgosto = @$_POST['OpcionAplicarAgosto'];
		$OpcionAplicarSeptiembre = @$_POST['OpcionAplicarSeptiembre'];
		$OpcionAplicarOctubre = @$_POST['OpcionAplicarOctubre'];
		$OpcionAplicarNoviembre = @$_POST['OpcionAplicarNoviembre'];
		$OpcionAplicarDiciembre = @$_POST['OpcionAplicarDiciembre'];

		/* Se arma estructura de Ajustes */
		$obj_ajustes = [];
		$year = date('Y');

		if ($diasPagoUltimoCobroEnero == !null && $diasPagoUltimoCobroEnero > 0 && $diasPagoUltimoCobroEnero == !"") {
			$obj_ajustesEnero = array(
				"idMes" => 1,
				"idMoneda" => $diasPagoTipoMonedaEnero,
				"idPeriodicidad" => $OpcionAplicarEnero,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroEnero
			);
			$obj_ajustes[] = $obj_ajustesEnero;
		}

		if ($diasPagoUltimoCobroFebrero == !null && $diasPagoUltimoCobroFebrero > 0 && $diasPagoUltimoCobroFebrero == !"") {
			$obj_ajustesFebrero = array(
				"idMes" => 2,
				"idMoneda" => 	$diasPagoTipoMonedaFebrero,
				"idPeriodicidad" => $OpcionAplicarFebrero,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroFebrero
			);
			$obj_ajustes[] = $obj_ajustesFebrero;
		}

		if ($diasPagoUltimoCobroMarzo == !null && $diasPagoUltimoCobroMarzo > 0 && $diasPagoUltimoCobroMarzo == !"") {
			$obj_ajustesMarzo = array(
				"idMes" => 3,
				"idMoneda" => $diasPagoTipoMonedaMarzo,
				"idPeriodicidad" => $OpcionAplicarMarzo,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroMarzo
			);
			//var_dump($obj_ajustesMarzo);
			$obj_ajustes[] = $obj_ajustesMarzo;
		}

		if ($diasPagoUltimoCobroAbril == !null && $diasPagoUltimoCobroAbril > 0 && $diasPagoUltimoCobroAbril == !"") {
			$obj_ajustesAbril = array(
				"idMes" => 4,
				"idMoneda" => $diasPagoTipoMonedaAbril,
				"idPeriodicidad" => $OpcionAplicarAbril,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroAbril
			);
			$obj_ajustes[] = $obj_ajustesAbril;
		}

		if ($diasPagoUltimoCobroMayo == !null && $diasPagoUltimoCobroMayo > 0 && $diasPagoUltimoCobroMayo == !"") {
			$obj_ajustesMayo = array(
				"idMes" => 5,
				"idMoneda" => $diasPagoTipoMonedaMayo,
				"idPeriodicidad" => $OpcionAplicarMayo,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroMayo
			);
			$obj_ajustes[] = $obj_ajustesMayo;
		}

		if ($diasPagoUltimoCobroJunio == !null && $diasPagoUltimoCobroJunio > 0 && $diasPagoUltimoCobroJunio == !"") {
			$obj_ajustesJunio = array(
				"idMes" => 6,
				"idMoneda" => $diasPagoTipoMonedaJunio,
				"idPeriodicidad" => $OpcionAplicarJunio,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroJunio
			);
			$obj_ajustes[] = $obj_ajustesJunio;
		}

		if ($diasPagoUltimoCobroJulio == !null && $diasPagoUltimoCobroJulio > 0 && $diasPagoUltimoCobroJulio == !"") {
			$obj_ajustesJulio = array(
				"idMes" => 7,
				"idMoneda" => $diasPagoTipoMonedaJulio,
				"idPeriodicidad" => $OpcionAplicarJulio,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroJulio
			);
			$obj_ajustes[] = $obj_ajustesJulio;
		}

		if ($diasPagoUltimoCobroAgosto == !null && $diasPagoUltimoCobroAgosto > 0 && $diasPagoUltimoCobroAgosto == !"") {
			$obj_ajustesAgosto = array(
				"idMes" => 8,
				"idMoneda" => $diasPagoTipoMonedaAgosto,
				"idPeriodicidad" => $OpcionAplicarAgosto,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroAgosto
			);
			$obj_ajustes[] = $obj_ajustesAgosto;
		}

		if ($diasPagoUltimoCobroSeptiembre == !null && $diasPagoUltimoCobroSeptiembre > 0 && $diasPagoUltimoCobroSeptiembre == !"") {
			$obj_ajustesSeptiembre = array(
				"idMes" => 9,
				"idMoneda" => $diasPagoTipoMonedaSeptiembre,
				"idPeriodicidad" => $OpcionAplicarNoviembre,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroSeptiembre
			);
			$obj_ajustes[] = $obj_ajustesSeptiembre;
		}

		if ($diasPagoUltimoCobroOctubre == !null && $diasPagoUltimoCobroOctubre > 0 && $diasPagoUltimoCobroOctubre == !"") {
			$obj_ajustesOctubre = array(
				"idMes" => 10,
				"idMoneda" => $diasPagoTipoMonedaOctubre,
				"idPeriodicidad" => $OpcionAplicarOctubre,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroSeptiembre
			);
			$obj_ajustes[] = $obj_ajustesOctubre;
		}

		if ($diasPagoUltimoCobroNoviembre == !null && $diasPagoUltimoCobroNoviembre > 0 && $diasPagoUltimoCobroNoviembre == !"") {
			$obj_ajustesNoviembre = array(
				"idMes" => 11,
				"idMoneda" => $diasPagoTipoMonedaNoviembre,
				"idPeriodicidad" => $OpcionAplicarNoviembre,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroNoviembre
			);
			$obj_ajustes[] = $obj_ajustesNoviembre;
		}

		if ($diasPagoUltimoCobroDiciembre == !null && $diasPagoUltimoCobroDiciembre > 0 && $diasPagoUltimoCobroDiciembre == !"") {
			$obj_ajustesDiciembre = array(
				"idMes" => 12,
				"idMoneda" => $diasPagoTipoMonedaDiciembre,
				"idPeriodicidad" => $OpcionAplicarDiciembre,
				"agnoCurso" => $year,
				"monto" => $diasPagoUltimoCobroDiciembre
			);
			$obj_ajustes[] = $obj_ajustesDiciembre;
		}


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


		/*ID FICHA*/



		$num_reg = 10;
		$inicio = 0;

		$query = "SELECT id,id_propiedad FROM propiedades.ficha_arriendo where token = '$token_arriendo' ";
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		//var_dump($resultado);
		$objIdFicha = json_decode($resultado)[0];


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
		//var_dump($resultado);
		$objTipoMoneda = json_decode($resultado)[0];

		$query = "SELECT id FROM propiedades.tp_tipo_moneda where nombre = '$monedaComisionArriendo' ";
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		//var_dump($resultado);
		$objTipoMonedaComisionArriendo = json_decode($resultado)[0];

		$query = "SELECT id FROM propiedades.tp_tipo_moneda where nombre = '$monedaComisionAdministracion' ";
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		//var_dump($resultado);
		$objTipoMonedaComisionAdministracion = json_decode($resultado)[0];

		$query = "SELECT id FROM propiedades.tp_tipo_moneda where ( nombre = '$monedaMulta')";
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		////var_dump($resultado);
		$objTipoMonedaMulta = json_decode($resultado)[0];



		$query = "SELECT id FROM propiedades.tp_tipo_documento where cod_externo = '$tipoFacturaComisionArriendo'  AND habilitado = true ";
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		//var_dump($resultado);
		$objTipoFacturaComisionArriendo = json_decode($resultado)[0];

		$query = "SELECT id FROM propiedades.tp_tipo_documento where cod_externo = '$tipoFacturaComisionAdministracion' AND habilitado = true ";
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		//var_dump($resultado);
		$objTipoFacturaComisionAdministracion = json_decode($resultado)[0];

		$query = "SELECT id FROM propiedades.tp_tipo_multa where (nombre = '$tipoMulta') ";
		//var_dump($query);
		$cant_rows = $num_reg;
		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $query, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);

		$objTipoMulta = json_decode($resultado)[0];





		////var_dump("DATOS A ENVIAR DESDE FORMULARIO ARRIENDO: ", $data);

		$arriendoVacio = []; /* Debido a que solo actuliza no envia arriendos*/

		$request = array(
			"tokenSubsidiaria" => "",
			"tokenSucursal" => "",
			"tokenPropiedad" => "",
			"arrendatarios" => $arriendoVacio,
			"token" => $token_arriendo,
			"tokenCodeudor" => "",
			"fechaInicio" => $fechaInicio,
			"fechaTerminoReal" => $fechaTermino,
			"precio" => $precioContrato,
			"idMonedaPrecio" => $objTipoMoneda->id,
			"cobroMesCalendario" => true,
			"duracionContratoMeses" => $duracionContrato,
			"idTipoReajuste" => $objTipoReajuste->id,
			"pagoGarantiaPropietario" => true
		);





		$queryUpdateRegistroJ = "UPDATE propiedades.ficha_arriendo fa 
			SET id_estado_contrato = 1 , 
			meses_garantia = $mesesGarantia,
			monto_garantia = $montoGarantia,
			monto_multa_atraso = $montoMultaAtraso,
			id_tipo_multa = $tipoMulta,
			id_moneda_multa = $monedaMulta,
			adm_comision_id_moneda = $monedaComisionAdministracion,
			arriendo_comision_id_moneda = $monedaComisionArriendo,
			arriendo_comision_monto = $comisionArriendo,
			adm_comision_monto = $comisionAdministracion,
			arriendo_comision_id_tipo_documento = $tipoFacturaComisionArriendo,
			adm_comision_id_tipo_documento = $tipoFacturaComisionArriendo,
			adm_comision_cobro = $cobrarComisionAdministracion,
			arriendo_comision_cobro = $cobrarComisionArriendo,
			adm_comision_primer_liquidacion = '$facturarComisionAdministracion',
			num_cuotas_garantia = $num_cuotas_garantia,
			fecha_inicio = '$fechaInicio',
			fecha_termino_real = '$fechaTermino', 
			precio = $precioContrato,
			cobro_dias_multa = $diascobro
			WHERE TOKEN = '$token_arriendo'";



		$data = array("consulta" => $queryUpdateRegistroJ);
		$resultado  = $services->sendPostDirecto($url_services . '/util/dml', $data);




		/**************    Envio de datos adicionales    ******************/
		$queryCabecera = "
		UPDATE propiedades.ficha_arriendo fa
		SET 
			id_estado_contrato = $estadoContrato,
			meses_garantia = $mesesGarantia,
			monto_garantia = $montoGarantia,
			monto_multa_atraso = $montoMultaAtraso,
			id_tipo_multa = $tipoMulta,
			id_moneda_multa = $monedaMulta,
			adm_comision_id_moneda = $monedaComisionAdministracion,
			arriendo_comision_id_moneda = $monedaComisionArriendo,
			arriendo_comision_monto = $comisionArriendo,
			adm_comision_monto = $comisionAdministracion,
			arriendo_comision_id_tipo_documento = $tipoFacturaComisionArriendo,
			adm_comision_id_tipo_documento = $tipoFacturaComisionArriendo,
			adm_comision_cobro = '$cobrarComisionAdministracion',
			arriendo_comision_cobro = '$cobrarComisionArriendo',
			adm_comision_primer_liquidacion = '$facturarComisionAdministracion',
			num_cuotas_garantia = $num_cuotas_garantia
		WHERE 
			TOKEN = '$token_arriendo'
	";

		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCab2 = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

		if ($resultadoCab2 != "OK") {
			echo ",xxx,ERROR,xxx,No se logro insertar datos de arriendo 2 ,xxx,-,xxx,";
			return;
		}

		$queryIDFicha = "select id from propiedades.ficha_arriendo fa  where token = '$token_arriendo'";

		$num_pagina = round($inicio / $cant_rows) + 1;
		$data = array("consulta" => $queryIDFicha, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
		$resultadoFichaID = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		$objIDFicha = json_decode($resultadoFichaID);
		$objetoIDFicha = $objIDFicha[0];
		$idFichaArriendo = $objetoIDFicha->id;

		////////////////////////Consulta para saber si hay que limpiar registros y actualizar o  no
		$queryEstadosArriendo = "select count(id_ficha_arriendo) as cantidad_estados 
			from propiedades.ficha_arriendo_cuotas_garantia facg 
			where id_ficha_arriendo =$idFichaArriendo and estado_garantia is not null ";
		$data = array("consulta" => $queryEstadosArriendo, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);


		$resultadoEstadosArriendo = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
		$objEstadosArriendo = json_decode($resultadoEstadosArriendo);
		$objetoEstadosArriendo = $objEstadosArriendo[0];
		$cantidad_estados = $objetoEstadosArriendo->cantidad_estados;


		/**
		 * 
		 * 	ejecuta la funcion para limpiar cuotas garantia
		 *
		 * 
		 */
		$result = $queryBuilder->executeFunction('propiedades.fn_limpia_ctacte_arriendo', [$idFichaArriendo]);





		if ($cantidad_estados == 0) {
			$queryDeleteMesesArriendo = "DELETE FROM propiedades.ficha_arriendo_cuotas_garantia
			WHERE id_ficha_arriendo = $idFichaArriendo";
			$dataCab = array("consulta" => $queryDeleteMesesArriendo);
			$resultadoDelete = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
			if ($resultadoDelete != "") {


				////////////////////////////Insertar Meses
				// Fecha inicial
				$fecha = $fechaInicio;

				//$date = new DateTime($fecha);
				$date = new DateTime();
				// Crear un objeto DateTime con la fecha inicial
				$valor_cuota = round($montoGarantia / $num_cuotas_garantia);
				$diferencia_cuota = 0;
				$total_a_pagar = ($valor_cuota * $num_cuotas_garantia);
				if ($montoGarantia != $total_a_pagar) {
					$diferencia_cuota = $montoGarantia - $total_a_pagar;
				}

				for ($i = 1; $i <= $num_cuotas_garantia; $i++) {
					// Modificar la fecha para obtener el primer día del mes siguiente
					// Obtener el mes y el año por separado
					$mes = $date->format('m'); // Mes
					$anio = $date->format('Y'); // Año
					if ($i == $num_cuotas_garantia) {
						$valor_cuota = $valor_cuota + $diferencia_cuota;
					}


					$queryInsertMesesGarantia = "insert into propiedades.ficha_arriendo_cuotas_garantia (num_cuotas, id_ficha_arriendo, monto_garantia, mes_garantia, garantia_ano, habilitado)
					values ('$i','$idFichaArriendo','$valor_cuota','$mes','$anio','1')";
					$dataCab = array("consulta" => $queryInsertMesesGarantia);


					$resultadoCab1 = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);


					if ($resultadoCab1 != "OK") {
						echo ",xxx,ERROR,xxx,No se logro insertar datos de arriendo 1 ,xxx,-,xxx,";
						return;
					}
					$date->modify('first day of next month');
				}
			}
		}




		//var_dump("Enviar datos Reajuste : ");

		if ($permiteReajusteNegativo = "N") {
			$permiteReajusteNegativo = false;
		} else {
			$permiteReajusteNegativo = true;
		}

		$request_ajustes = array(
			"token" => $token_arriendo,
			"reajustes" => array(
				"idTipoReajuste" => $objTipoReajuste->id,
				"reajusteNegativo" => $permiteReajusteNegativo,
				"cantidadReajuste" => $CantidadReajuste,
				"fijacionArriendo" => $obj_ajustes
			)
		);
		////var_dump("permiteReajusteNegativo ",$permiteReajusteNegativo);


		/*Limpiamos tabla tp_tipo_reajuste para ingresar lo nuevo*/

		$queryCabecera = " DELETE FROM propiedades.ficha_arriendo_reajustes WHERE token_ficha_arriendo = '$token_arriendo' ";
		////var_dump($queryCabecera);
		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

		if ($resultadoCab != "OK") {
			echo ",xxx,ERROR,xxx,Problemas con los meses de ajustes ,xxx,-,xxx,";
			return;
		}

		foreach ($_POST['meses'] as $mes) {

			$queryCabecera = " INSERT INTO propiedades.ficha_arriendo_reajustes
							( token_ficha_arriendo, id_tipo_reajuste, permite_reajuste_negativo, id_mes_reajuste,id_ficha_arriendo, cantidad_reajuste )
							VALUES( '$token_arriendo', $objTipoReajuste->id, '$permiteReajusteNegativo_new', $mes,$objIdFicha->id, $CantidadReajuste) ";
			////var_dump($queryCabecera);
			$dataCab = array("consulta" => $queryCabecera);
			$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

			if ($resultadoCab != "OK") {
				echo ",xxx,ERROR,xxx,No se logro insertar ajustes ,xxx,-,xxx,";
				return;
			}
		}

		/*      FIN INSERCION AJUSTES          */

		//var_dump("DATOS A ENVIAR DESDE FORMULARIO ajustes: ");
		//var_dump(json_encode($request_ajustes));
		$resultadoFinal_ajustes =  $services->sendPutRentdesk($url_services . '/rentdesk/arriendos/reajustes', $request_ajustes, null, null);
		//var_dump("Resultado endpoint");

		$resultado = $resultadoFinal_ajustes['response'];
		//var_dump($resultado);
		$estado_reajuste = $resultadoFinal_ajustes['status_code'];
		//var_dump($estado_reajuste);
		//$id_resultado = $arreglo['id']; 
		//$token_resultado = $arreglo['token']; 
		//var_dump($json_resultado_ajuste);
		//echo ",xxx,OK,xxx,Arriendo creado,xxx,-,xxx,";

		if ($estado_reajuste == !201) {
			echo ",xxx,ERROR,xxx,Error al agregar ajuste,xxx,-,xxx,";
			return;
		}


		if ($estadoContrato == 2) {
			$queryCabecera = " UPDATE propiedades.propiedad set id_estado_propiedad =  5 where id = $objIdFicha->id_propiedad ";
			////var_dump($queryCabecera);
			$dataCab = array("consulta" => $queryCabecera);
			$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);
		}

		$queryCabecera = " UPDATE propiedades.ficha_arriendo set valida_arriendo =  'true' WHERE TOKEN = '$token_arriendo' ";
		////var_dump($queryCabecera);
		$dataCab = array("consulta" => $queryCabecera);
		$resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

		/***
		 * 
		 * 
		 * 	meses especiales
		 * 
		 * 
		 */

		// Función para limpiar y preparar las cadenas JSON
		function limpiarCadenaJSON($cadena)
		{
			// Eliminar los espacios innecesarios alrededor de los dos puntos
			$cadena = preg_replace('/\s*:\s*/', ':', $cadena);

			// Agregar comillas a las claves
			$cadena = preg_replace('/([a-zA-Z0-9_]+):/', '"$1":', $cadena);

			// Agregar comillas a los valores que no las tienen y permitir números con punto o coma
			$cadena = preg_replace('/:\s*([a-zA-Z0-9_.,]+)\s*(,|})/', ':"$1"$2', $cadena); // Valores con contenido numérico con puntos y comas
			$cadena = preg_replace('/:\s*(,|})/', ':null$1', $cadena); // Valores vacíos a `null`

			return $cadena;
		}

		// Limpiar y decodificar las cadenas que vienen en $_POST
		$dataMesesLimpio = limpiarCadenaJSON($_POST['dataMeses']);
		$dataMonedasLimpio = limpiarCadenaJSON($_POST['dataMonedas']);
		$dataAplicaLimpio = limpiarCadenaJSON($_POST['dataAplica']);

		$dataMeses = json_decode($dataMesesLimpio, true);
		$dataMonedas = json_decode($dataMonedasLimpio, true);
		$dataAplica = json_decode($dataAplicaLimpio, true);


		// Definir los meses
		$meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

		// Eliminar datos antiguos antes de la inserción
		$queryDelete = "DELETE FROM propiedades.ficha_arriendo_reajustes_fijacion_mes WHERE id_arriendo = $idFichaArriendo";
		$dataCab = array("consulta" => $queryDelete);
		$resultadoDelete = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

		// Recorrer los meses para insertar los valores en la base de datos
		foreach ($meses as $mes) {
			// Obtener el id del mes correspondiente (1 a 12)
			$idMes = array_search($mes, $meses) + 1;

			// Datos capturados desde el frontend
			// Limpiar el monto eliminando los puntos de miles
			$montoPago = isset($dataMeses['monto' . $mes]) && !empty($dataMeses['monto' . $mes]) ? $dataMeses['monto' . $mes] : 0;  // Monto del mes
			$monedaPago = isset($dataMonedas['diasPagoTipoMoneda' . $mes]) ? $dataMonedas['diasPagoTipoMoneda' . $mes] : null;  // Moneda del mes
			$periocidad = isset($dataAplica['OpcionAplicar' . $mes]) ? $dataAplica['OpcionAplicar' . $mes] : null;  // Periodicidad (aplicar una vez o siempre)


			if ($monedaPago == 3) {

				if (strpos($montoPago, ',')) {
					$montoPago = str_replace(",", ".", $montoPago);
				}
			} else {

				if (strpos($montoPago, '.')) {
					$montoPago = str_replace(".", "", $montoPago);
				} else if (strpos($montoPago, ',')) {
					$montoPago = str_replace(",", ".", $montoPago);
				}
			}

			// Validar que los datos no sean nulos o vacíos antes de insertar
			if ($monedaPago !== null && $periocidad !== null) {
				// Obtener la fecha actual y el año
				$fechaActual = new DateTime();
				$fechaRegistro = $fechaActual->format('Y-m-d H:i:s');
				$year = date('Y');

				// Crear la consulta de inserción para cada mes
				$queryInsertMesesEspeciales = "INSERT INTO propiedades.ficha_arriendo_reajustes_fijacion_mes(
				id_arriendo, id_mes, id_moneda, id_periodicidad, fecha_registro, agno_curso, monto)
				VALUES (
					$idFichaArriendo,
					$idMes,    
					$monedaPago,    
					$periocidad,    
					'$fechaRegistro',
					'$year',
					$montoPago
				)";

				// Enviar la consulta a través de tu servicio
				$data = array("consulta" => $queryInsertMesesEspeciales);
				$resultadoInsert = $services->sendPostDirecto($url_services . '/util/dml', $data);
			}
		}

		/***
		 * 
		 * 
		 * 	elimina los movimientos en cta cte
		 * 
		 * 
		 */


		$eliminaMovimientosCtaCte = "DELETE FROM propiedades.ficha_arriendo_cta_cte_movimientos WHERE  id_ficha_arriendo = $idFichaArriendo";
		$dataCab = array("consulta" => $queryDeleteMesesArriendo);
		$resultadoDelete = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);



		/***
		 * 
		 * 
		 * 	guarda la garantia segun a quien se le 
		 * 
		 * 
		 */

		function GuardarMovimientosCtaCte($services, $url_services, $id_ficha_arriendo, $fecha_movimiento, $hora_movimiento, $id_tipo_movimiento_cta_cte, $monto, $saldo, $razon, $cobro_comision, $nro_cuotas, $id_propiedad, $pago_arriendo, $id_varios_acreedores, $cta_contable, $id_liquidacion, $editar, $eliminar, $mes_imputado, $codigo_propiedad, $id_cierre_conciliacion, $id_responsable, $estado)
		{

			// Consulta preparada
			$query = "INSERT INTO propiedades.ficha_arriendo_cta_cte_movimientos(
				 id_ficha_arriendo, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, saldo, 
				 razon, cobro_comision, nro_cuotas, id_propiedad, pago_arriendo, id_varios_acreedores, cta_contable, 
				 id_liquidacion, editar, eliminar, mes_imputado, codigo_propiedad, id_cierre_conciliacion, id_responsable, estado
			 ) VALUES ($id_ficha_arriendo, '$fecha_movimiento', '$hora_movimiento', $id_tipo_movimiento_cta_cte, $monto, $saldo, 
				 '$razon', $cobro_comision, $nro_cuotas, $id_propiedad, $pago_arriendo, $id_varios_acreedores, $cta_contable, 
				 $id_liquidacion, $editar, $eliminar, '$mes_imputado', '$codigo_propiedad', $id_cierre_conciliacion, $id_responsable, '$estado')";
			// Preparar datos para el envío del servicio

			$data = array("consulta" => $query, "cantRegistros" => 50, "numPagina" => 1);
			$resultado = $services->sendPostDirecto($url_services . '/util/dml', $data, []);

			return json_decode($resultado);
		}

		/***
		 * 
		 * 
		 * 	funcion para crear un cargo la primera vez que se crea la propiedad
		 * 
		 * 
		 */


		//ficha arriendo
		$query = "SELECT fecha_prox_vcto from propiedades.ficha_arriendo where id = $idFichaArriendo ";
		$data = array("consulta" => $query);
		$resultado = json_decode($services->sendPostNoToken($url_services . '/util/objeto', $data));
		$fecha_prox_vcto = $resultado[0]->fecha_prox_vcto;

		// Verificar que $resultado no sea null y tenga al menos un elemento
		if (isset($resultado[0])) {
			$fecha_prox_vcto = $resultado[0]->fecha_prox_vcto;

			// Verificar si la fecha no es null y no está vacía
			if ($fecha_prox_vcto == null) {

				// $query = "SELECT propiedades.fn_genera_cargo_arriendo_manual($objIdFicha->id_propiedad, $idFichaArriendo)";
				// $data = array("consulta" => $query);
				// $resultado = json_decode($services->sendPostNoToken($url_services . '/util/objeto', $data));

				// guarda la garantia una unica vez
				if ($estadoContrato == 1) {

					if ($pagoGarantiaProp == 'SI') {


						if ($num_cuotas_garantia == 1) {



							$resultadoTest = GuardarMovimientosCtaCte(
								$services,
								$url_services,
								$idFichaArriendo,
								date("Y-m-d"),
								date("H:i:s"),
								11, // tipo movimiento
								$montoGarantia,
								0,
								"COBRO GARANTIA",
								'false',
								$num_cuotas_garantia,
								$objIdFicha->id_propiedad,
								'false',
								0,
								0,
								0,
								'false',
								'false',
								date("Y-m-d"),
								0,
								0,
								1,
								"I",
							);
						} else {

							// Calcular el valor base de cada cuota
							$valorBaseCuota = floor($montoGarantia / $num_cuotas_garantia);
							// Calcular el residuo que queda después de dividir el monto total
							$residuo = $montoGarantia % $num_cuotas_garantia;

							// Establecer la fecha inicial
							//$date = new DateTime('first day of this month');
							$date = new DateTime();

							$estado_cargo = 'I';
							for ($i = 1; $i <= $num_cuotas_garantia; $i++) {
								// Inicializamos el valor de la cuota con el valor base
								$valorCuota = $valorBaseCuota;

								// Distribuir el residuo sumando 1 a las primeras cuotas
								if ($i <= $residuo) {
									$valorCuota += 1; // Sumar 1 a las primeras cuotas hasta cubrir el residuo
								}

								// Llamada a la función GuardarMovimientosCtaCte con el valor de la cuota
								$resultado = GuardarMovimientosCtaCte(
									$services,
									$url_services,
									$idFichaArriendo,
									$date->format('Y-m-d'),
									date("H:i:s"),
									11, // tipo de movimiento
									$valorCuota, // valor correcto de la cuota con o sin residuo
									0,
									"COBRO CUOTA GARANTIA " . $i . " " . $date->format("Y-m"),
									'false',
									$i,
									$objIdFicha->id_propiedad,
									'false',
									0,
									0,
									0,
									'false',
									'false',
									$date->format("Y-m-d"),
									0,
									0,
									1,
									$estado_cargo
								);

								// Avanzar un mes en la fecha
								$date->modify('first day of next month');
								$estado_cargo = 'F';
							}
						}
					}
				}
			}
		}
	} // end if validaciones de movimiento
}


/* SI LLEGA HASTA ESTE PUNTO SE REALIZA INSERCION CORRECTAMENTE*/
echo ",xxx,OK,xxx,ARRIENDO ACTUALIZADO,xxx,-,xxx,";
