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



if (isset($_GET["idFicha"])) {
    $idFicha = $_GET["idFicha"];

    /************************CONSUNLTA INFO DEL ARRENDATARIO************************************* */

    $queryArrendatario = " select va.nombre_1 , va.nombre_2 , va.nombre_3, fa.id  from propiedades.propiedad p 
 inner join propiedades.ficha_arriendo fa  on p.id = fa.id_propiedad 
 left join propiedades.ficha_arriendo_arrendadores faa on faa.id_ficha_arriendo = fa.id 
 left join propiedades.vis_arrendatarios va on va.id = faa.id_arrendatario 
 where p.id =$idFicha and fa.id_estado_contrato =1 ";

    $num_pagina = round($inicio / $cant_rows) + 1;
    $data = array("consulta" => $queryArrendatario, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
    $resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
    $objArrendatario = json_decode($resultado);
    $objetoArrendatario = @$objArrendatario[0];

    $idFichaArriendo = @$objetoArrendatario->id;


    /************************CONSUNLTA INFO DE LOS MOVIMIENTOS************************************* */

    if (isset($idFichaArriendo)) {
        $queryCcMovimientos = "SELECT * from (
            SELECT 
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
                CASE WHEN SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) >= 0 
                THEN '+' ELSE '-' END AS signo_saldo
            FROM 
                propiedades.ficha_arriendo_cta_cte_movimientos ccm
            INNER JOIN 
                propiedades.tp_tipo_movimiento_cta_cte ttmcc ON ccm.id_tipo_movimiento_cta_cte = ttmcc.id
            INNER JOIN 
                propiedades.tp_tipo_movimiento ttm ON ttmcc.id_tipo_movimiento = ttm.id
                where id_ficha_arriendo = $idFichaArriendo
            GROUP BY 
                ccm.id, 
                ccm.id_ficha_arriendo, 
                ccm.fecha_movimiento, 
                ccm.hora_movimiento, 
                ccm.id_tipo_movimiento_cta_cte, 
                ccm.monto,
                ttm.id
        ) subquery 
        where id_ficha_arriendo = $idFichaArriendo 
        order by fecha_movimiento DESC";
    } else {
        $queryCcMovimientos = "SELECT * from (
            SELECT 
                ccm.id, 
                ccm.id_propiedad, 
                ccm.fecha_movimiento, 
                ccm.hora_movimiento, 
                ccm.id_tipo_movimiento_cta_cte, 
                ccm.monto,
                ccm.razon,
                ttm.id AS id_tipo_movimiento,
                SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE 0 END) AS haber,
                SUM(CASE WHEN ttm.id = 2 THEN ccm.monto ELSE 0 END) AS debe,
                SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) OVER (PARTITION BY ccm.id_propiedad ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) AS saldo,
                CASE WHEN SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) OVER (PARTITION BY ccm.id_propiedad ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) >= 0 
                THEN '+' ELSE '-' END AS signo_saldo
            FROM 
                propiedades.ficha_arriendo_cta_cte_movimientos ccm
            INNER JOIN 
                propiedades.tp_tipo_movimiento_cta_cte ttmcc ON ccm.id_tipo_movimiento_cta_cte = ttmcc.id
            INNER JOIN 
                propiedades.tp_tipo_movimiento ttm ON ttmcc.id_tipo_movimiento = ttm.id
                where id_propiedad = $idFicha
            GROUP BY 
                ccm.id, 
                ccm.id_propiedad, 
                ccm.fecha_movimiento, 
                ccm.hora_movimiento, 
                ccm.id_tipo_movimiento_cta_cte, 
                ccm.monto,
                ttm.id
        ) subquery 
        where id_propiedad = $idFicha 
                order by  fecha_movimiento DESC";
    }
}

// var_dump("QUERY CC MOVIMIENTOS PROPIEDAD: ", $queryCcMovimientos);




$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryCcMovimientos, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objCcMovimientos = json_decode($resultado);

// var_dump("DATOS OBTENIDOS CC MOVIMIENTOS PROPIEDAD: ", $resultado);

// echo json_encode($objCcMovimientos);


$dataCount = array("consulta" => $queryCcMovimientos);
$resultadoCount = $services->sendPostNoToken($url_services . '/util/count', $dataCount);
$cantidad_registros = $resultadoCount;


if ($cantidad_registros  != 0) {

    foreach ($objCcMovimientos as $result) {
        if ($coma == 1)
            $signo_coma = ",";

        $coma = 1;


        $fecha_movimiento = $result->fecha_movimiento;
        $razon = $result->razon;
        $debe = $result->debe;
        $haber = $result->haber;
        $saldo = $result->saldo;


        $datos = $datos . "
     $signo_coma
     [
     
      \"$fecha_movimiento\",
      \"$razon\",
      \"$debe\",
      \"$haber\",
      \"$saldo\"

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
