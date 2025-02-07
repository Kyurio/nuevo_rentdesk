<?php

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
	$num_reg = 99999;
}

$cant_rows = $num_reg;



// if (isset($_GET["idFicha"])) {
//     $idFicha = $_GET["idFicha"];
$queryCheques = "
  SELECT
    ccm.id_propiedad,
    ccm.id_ficha_arriendo,
    vp.direccion,
    vp.numero,
    vp.comuna,
    vp.region,
    SUM(CASE WHEN ttm.id = 1 THEN ccm.monto ELSE 0 END) AS total_haber,
    SUM(CASE WHEN ttm.id = 2 THEN ccm.monto ELSE 0 END) AS total_debe,
    SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) AS saldo,
    CASE WHEN SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) >= 0 THEN '+' ELSE '-' END AS signo_saldo
FROM
    propiedades.ficha_arriendo_cta_cte_movimientos ccm
INNER JOIN
    propiedades.tp_tipo_movimiento_cta_cte ttmcc ON ccm.id_tipo_movimiento_cta_cte = ttmcc.id
INNER JOIN
    propiedades.tp_tipo_movimiento ttm ON ttmcc.id_tipo_movimiento = ttm.id
INNER JOIN  
    propiedades.vis_propiedades vp ON vp.id_propiedad = ccm.id_propiedad
WHERE
    ccm.id_tipo_movimiento_cta_cte != 2
    AND ccm.id_propiedad IS NOT NULL
    AND ccm.id_ficha_arriendo IS NOT null
    and ccm.id_liquidacion is null
GROUP BY 
    ccm.id_propiedad,
    ccm.id_ficha_arriendo,
    vp.direccion,
    vp.numero,
    vp.comuna,
    vp.region
HAVING
    SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) != 0
   ";
// }

// var_dump("QUERY HISTORIAL: ", $queryCheques);



$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCheques, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objPropLiqGenMasiva = json_decode($resultado);
$idFichasPropiedad = [];
if($resultado != ""){
    foreach ($objPropLiqGenMasiva as $info_liq){
    $idFichasPropiedad[] = $info_liq->id_propiedad;
}
}

$idFichasPropiedad = json_encode($idFichasPropiedad);
