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
  $num_reg = 99999;
}

$cant_rows = $num_reg;



// if (isset($_GET["idFicha"])) {
//     $idFicha = $_GET["idFicha"];
$queryEliminarMoras = "SELECT token_arrendatario,  id_arrendatario, token, id, id_ficha_arriendo, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, razon, id_tipo_movimiento, haber, debe, saldo, signo_saldo from (
        SELECT 
        	va.token_arrendatario,
        	va.id as id_arrendatario,
        	fa.token,
            ccm.id, 
            ccm.id_ficha_arriendo, 
            ccm.fecha_movimiento, 
            ccm.hora_movimiento, 
            ccm.id_tipo_movimiento_cta_cte, 
            ccm.monto,
            ccm.razon,
            ttm.id AS id_tipo_movimiento,
            SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE 0 END) AS haber,
            SUM(CASE WHEN ttm.id = 2 THEN ccm.monto ELSE 0 END) AS debe,
            SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) AS saldo,
            CASE WHEN SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) >= 0 THEN '+' ELSE '-' END AS signo_saldo,
            ROW_NUMBER() OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento DESC, ccm.hora_movimiento DESC) AS rn
        FROM 
            propiedades.ficha_arriendo_cta_cte_movimientos ccm
        INNER JOIN 
            propiedades.tp_tipo_movimiento_cta_cte ttmcc ON ccm.id_tipo_movimiento_cta_cte = ttmcc.id
        INNER JOIN 
            propiedades.tp_tipo_movimiento ttm ON ttmcc.id_tipo_movimiento = ttm.id
        INNER JOIN 
            propiedades.ficha_arriendo fa  ON fa.id  = ccm.id_ficha_arriendo 
         inner join 
         propiedades.ficha_arriendo_arrendadores faa on faa.id_ficha_arriendo = ccm.id_ficha_arriendo 
         inner join 
         propiedades.vis_arrendatarios va on va.id = faa.id_arrendatario 
       group by ccm.id, ttm.id, fa.token,va.token_subsidiaria,va.token_arrendatario, va.id
    ) subquery 
    WHERE rn = 1
    and subquery.signo_saldo = '-'
    and subquery.id_ficha_arriendo is not null
    order by saldo desc";
// }

// var_dump("QUERY HISTORIAL: ", $queryEliminarMoras);



$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryEliminarMoras, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);

$objArriendoMoras = json_decode($resultado);



// var_dump("QUERY HISTORIAL: ", $queryEliminarMoras);


// echo json_encode($objArriendoMoras);


$dataCount = array("consulta" => $queryEliminarMoras);
$resultadoCount = $services->sendPostNoToken($url_services . '/util/count', $dataCount);
$cantidad_registros = $resultadoCount;

if ($cantidad_registros  != 0) {

  foreach ($objArriendoMoras as $result) {
    if ($coma == 1)
      $signo_coma = ",";

    $coma = 1;



    $id_ficha_arriendo = $result->id_ficha_arriendo;
    $arriendo = "<a href='index.php?component=arriendo&view=arriendo_ficha_tecnica&token=$result->token' class='link-info' target='_blank'> #$result->id_ficha_arriendo</a>";
    $arrendatario ="#$result->id_arrendatario";

    // $arrendatario = "<a href='index.php?component=arrendatario&view=arrendatario_ficha_tecnica&token=$result->token_arrendatario' class='link-info' target='_blank' > #$result->id_arrendatario</a>";
    $monto_a_saldar = $result->saldo;



    $datos = $datos . "
     $signo_coma
     [
       \"$id_ficha_arriendo\",
      \"$arriendo\",
      \"$arrendatario\",
      \"$monto_a_saldar\"
    ]";
  }

// se comentaron los echos que estaban en el codigo  visibles jhernandez. 



//   echo "
// {
//   \"draw\": 1,
//   \"recordsTotal\": $cantidad_registros,
//   \"recordsFiltered\": $cantidad_registros,
//   \"data\": [
//     $datos
//   ]
// }";
// } else {
//   echo "
// {
//   \"draw\": 0,
//   \"recordsTotal\": 0,
//   \"recordsFiltered\": 0,
//   \"data\": [
//     $datos
//   ]
// }";
}
