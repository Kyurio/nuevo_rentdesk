<?php

session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");
$config		= new Config;
$services   = new ServicesRestful;
$url_services = $config->url_services;

    if (isset($_POST["ficha_tecnica_propiedad"])) {
        $ficha_tecnica_propiedad = $_POST["ficha_tecnica_propiedad"];
/*******query si viene la propiedad********************* */
$queryMovimientos ="SELECT * 
FROM (
    SELECT 
        ccm.id, 
        ccm.id_ficha_arriendo, 
        ccm.fecha_movimiento, 
        ccm.hora_movimiento, 
        ccm.id_tipo_movimiento_cta_cte, 
        ccm.monto,
        ccm.razon,
        ccm.cobro_comision,
        ttm.id AS id_tipo_movimiento,
         ccm.id_propiedad,
       SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE 0 END) AS haber,
            SUM(CASE WHEN ttm.id = 2 THEN ccm.monto ELSE 0 END) AS debe,
            SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) AS saldo,
            CASE WHEN SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) >= 0 THEN '+' ELSE '-' END AS signo_saldo
        FROM 
        propiedades.ficha_arriendo_cta_cte_movimientos ccm
    INNER JOIN 
        propiedades.tp_tipo_movimiento_cta_cte ttmcc ON ccm.id_tipo_movimiento_cta_cte = ttmcc.id
    INNER JOIN 
        propiedades.tp_tipo_movimiento ttm ON ttmcc.id_tipo_movimiento = ttm.id
    WHERE 
        ccm.id_propiedad = $ficha_tecnica_propiedad
        and pc.habilitado = true
		and pc.nivel_propietario = 1
    GROUP BY 
        ccm.id, 
        ccm.id_ficha_arriendo, 
        ccm.fecha_movimiento, 
        ccm.hora_movimiento, 
        ccm.id_tipo_movimiento_cta_cte, 
        ccm.monto,
        ccm.razon,
        ccm.cobro_comision,
        ttm.id,
        ccm.id_propiedad
) subquery 
WHERE id_propiedad = $ficha_tecnica_propiedad
ORDER BY id DESC";


    }
     elseif (isset($_POST["token"])) {
        $token = $_POST["token"];
/********************Movimiento con token********************************************* */
$queryMovimientos ="SELECT * 
FROM (
    SELECT 
        ccm.id, 
        ccm.id_ficha_arriendo, 
        ccm.fecha_movimiento, 
        ccm.hora_movimiento, 
        ccm.id_tipo_movimiento_cta_cte, 
        ccm.monto,
        ccm.razon,
        ccm.cobro_comision,
        ttm.id AS id_tipo_movimiento,
        ccm.id_propiedad,
        token_propietario,
        direccion,
        numero,
        comuna, 
        region,
        token_propiedad,
       SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE 0 END) AS haber,
            SUM(CASE WHEN ttm.id = 2 THEN ccm.monto ELSE 0 END) AS debe,
            SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) AS saldo,
            CASE WHEN SUM(CASE WHEN ttm.id = 1 THEN -ccm.monto ELSE ccm.monto END) OVER (PARTITION BY ccm.id_ficha_arriendo ORDER BY ccm.fecha_movimiento, ccm.hora_movimiento) >= 0 THEN '+' ELSE '-' END AS signo_saldo
        FROM 
        propiedades.ficha_arriendo_cta_cte_movimientos ccm
        INNER JOIN 
            propiedades.tp_tipo_movimiento_cta_cte ttmcc ON ccm.id_tipo_movimiento_cta_cte = ttmcc.id
        INNER JOIN 
            propiedades.tp_tipo_movimiento ttm ON ttmcc.id_tipo_movimiento = ttm.id
        INNER JOIN 
            propiedades.propiedad_copropietarios pc on ccm.id_propiedad = pc.id_propiedad
        INNER JOIN 
            propiedades.vis_propietarios vp on vp.id = pc.id_propietario 
        INNER JOIN 
            propiedades.vis_propiedades vpd on vpd.id_propiedad = pc.id_propiedad
		where 
		    vp.token_propietario ='$token' 
            and pc.habilitado = true    
		    and pc.nivel_propietario = 1
        GROUP BY 
        ccm.id, 
        ccm.id_ficha_arriendo, 
        ccm.fecha_movimiento, 
        ccm.hora_movimiento, 
        ccm.id_tipo_movimiento_cta_cte, 
        ccm.monto,
        ccm.razon,
        ccm.cobro_comision,
        ttm.id,
        ccm.id_propiedad,
        vp.token_propietario,
        direccion,
        numero,
        comuna, 
        region,
        token_propiedad
) subquery 
WHERE token_propietario = '$token' 
ORDER BY id desc
";
     }

$num_reg = 10000;
$inicio = 0;
$cant_rows = $num_reg;
$num_pagina = round($inicio / $cant_rows) + 1;
$data = array("consulta" => $queryMovimientos, "cantRegistros" => $cant_rows, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$objMovimientos = json_decode($resultado);
echo json_encode($objMovimientos);
?>