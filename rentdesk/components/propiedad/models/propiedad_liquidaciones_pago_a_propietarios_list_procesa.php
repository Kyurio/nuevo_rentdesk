<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config        = new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;
@$inicio        = $_POST["start"];
@$num_reg        = $_POST["length"];
@$num_reg_principal        = $_POST["length"];

$draw            = @$_POST["draw"];
$inicio            = @$_POST["start"];
@$fin            = @$_POST["length"];
$busqueda         = @$_POST["search"]["value"];

$cantidad_filtrados = 0;
$cantidad_registros = 0;


$orden         = "";
if (!empty($_POST["order"][0]["column"]))
	$orden         = @$_POST["order"][0]["column"];

$direccion = "";
if (!empty($_POST["order"][0]["dir"]))
	$direccion = @$_POST["order"][0]["dir"];


$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];


$coma = 0;
$signo_coma = "";
$datos        = "";

if ($inicio == "") {
	$inicio = 0;
}
if ($num_reg == "") {
	$num_reg = 20;
}

$cant_rows = $num_reg;



// if (isset($_GET["idFicha"])) {
//     $idFicha = $_GET["idFicha"];
$queryCheques = "SELECT ficha.fecha_cobro as fecha_cobro, ficha.razon as razon, ficha.monto,
     ficha.monto as monto, banco.nombre as nombre, ficha.girador as girador, ficha.id as id, 
     ficha.numero_documento as numero_documento, ficha.cantidad as cantidad, ficha.banco as banco
     FROM propiedades.ficha_arriendo_cheques as ficha
     INNER JOIN propiedades.tp_banco as banco
     ON ficha.banco = banco.id 
     where ficha.habilitado=true
     order by ficha.id";
// }

// var_dump("QUERY HISTORIAL: ", $queryCheques);



$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCheques, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);


$objPropLiqPagoPropietarios = json_decode($resultado);


// echo json_encode($objPropLiqPagoPropietarios);


$dataCount = array("consulta" => $queryCheques);
$resultadoCount = $services->sendPostNoToken($url_services . '/util/count', $dataCount);
$cantidad_registros = $resultadoCount;

if ($cantidad_registros  != 0) {

	foreach ($objPropLiqPagoPropietarios as $result) {
		if ($coma == 1)
			$signo_coma = ",";

		$coma = 1;


		$propietario = $result->propietario;
		$propiedad = $result->propiedad;
		$monto = $result->monto;
		$sin_desc_abono = $result->sin_desc_abono;
		$nro_transferencia = $result->nro_transferencia;




		$datos = $datos . "
     $signo_coma
     [
	\"$propietario\",
      \"$propiedad\",
      \"$monto\",
	\"$sin_desc_abono\",
      \"$nro_transferencia\"
    ]";
	}

	echo "
{
  \"draw\": 1,
  \"recordsTotal\": $cantidad_registros,
  \"recordsFiltered\": $cantidad_registros,
  \"data\": [
    $datos
  ]
}";
} else {
	echo "
{
  \"draw\": 0,
  \"recordsTotal\": 0,
  \"recordsFiltered\": 0,
  \"data\": [
    $datos
  ]
}";
}
