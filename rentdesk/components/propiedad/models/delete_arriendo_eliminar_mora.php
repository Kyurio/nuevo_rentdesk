<?php
session_start();
include("../../../includes/sql_inyection.php");
include("../../../configuration.php");
include("../../../includes/funciones.php");
include("../../../includes/services_util.php");

$config = new Config;
$services = new ServicesRestful;
$url_services = $config->url_services;

$id_company = $_SESSION["rd_company_id"];
$id_usuario = $_SESSION["rd_usuario_id"];

$idAutorizador = @$_POST['idAutorizador'];
$idsFichasTecnicas = @$_POST['idsFichasTecnicas'];

// Parse the date and time using DateTime
$dateTime = new DateTime();
date_default_timezone_set("America/Santiago");
$date = $dateTime->format('Y-m-d'); // Date format: YYYY-MM-DD
$time = $dateTime->format('H:i:s'); // Time format: HH:MM:SS
$dateTimeString = $dateTime->format('Y-m-d H:i:s'); // Full date and time string

// Obtener el objeto de sesión y convertirlo en un objeto PHP
$sesion_rd_login = unserialize($_SESSION['sesion_rd_login']);
$correo = $sesion_rd_login->correo;

//Obtener Autorizadores
$queryAutorizadores = "SELECT *
    FROM propiedades.cuenta_usuario
    WHERE id_empresa = $id_company 
    AND autorizador = true 
    and habilitado = true";

$num_pagina =  round(1 / 9999) + 1;
$dataAutorizadores = array("consulta" => $queryAutorizadores, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultadoAutorizadores = $services->sendPostNoToken($url_services . '/util/paginacion', $dataAutorizadores, []);
$usuariosAutorizadores = json_decode($resultadoAutorizadores, true);

// 1. Obtener usuario por correo
$queryUsuario = "SELECT *
    FROM propiedades.cuenta_usuario
    WHERE id_empresa = $id_company AND UPPER(correo) = UPPER('$correo')";

$num_pagina =  round(1 / 9999) + 1;
$data = array("consulta" => $queryUsuario, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);

$objUsuario = json_decode($resultado, true)[0];

if (!$objUsuario) {
    echo "Usuario no encontrado";
    exit;
}

// 2. Elimina (suma misma cantidad con signo +) al ultimo movimiento del id_ficha_arriendo indicado
// 2.1. Busca por id_ficha_arriendo los registros a eliminar
$arrayIdsFichasArriendo = array_map('intval', $idsFichasTecnicas);

// Convert the array to a comma-separated string
$arrayIdsFichasArriendoString = implode(',', $arrayIdsFichasArriendo);

$querySelectMorasAEliminar = "SELECT id,id_propiedad, id_ficha_arriendo, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, razon, id_tipo_movimiento, haber, debe, saldo, signo_saldo FROM (
    SELECT 
        ccm.id, 
        ccm.id_propiedad,
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
    GROUP BY ccm.id, ttm.id
) subquery 
WHERE rn = 1
AND subquery.signo_saldo = '-'
AND subquery.id_ficha_arriendo IS NOT NULL
AND subquery.id_ficha_arriendo IN ($arrayIdsFichasArriendoString)
ORDER BY subquery.id_ficha_arriendo DESC, subquery.fecha_movimiento DESC, subquery.hora_movimiento DESC";



$data = array("consulta" => $querySelectMorasAEliminar, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$morasAEliminar = json_decode($resultado, true);

$idsEliminados = [];
foreach ($morasAEliminar as $mora) {
    $idPropiedad = $mora['id_propiedad'] ?? 'NULL';
    $idFichaArriendo = $mora['id_ficha_arriendo'];
    $ccMonto = abs($mora['saldo']); // Convert monto to its absolute value
    $ccRazon = 'Eliminación de Mora';

    $queryUpdateEliminarMoras = "INSERT INTO propiedades.ficha_arriendo_cta_cte_movimientos
        (id_propiedad, id_ficha_arriendo, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, razon)
        VALUES ($idPropiedad, $idFichaArriendo, '$dateTimeString', '$time', 1, $ccMonto, '$ccRazon')";


    $dataCab = array("consulta" => $queryUpdateEliminarMoras);
    $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

    if (!$resultadoCab) {
        echo "Error al insertar movimiento";
        exit;
    }

    $idsEliminados[] = $idFichaArriendo;
}




//2.2 Se obtienen los movimientos recien insertados para extracción de IDS
$querySelectMovInsertados = "SELECT id,id_propiedad, id_ficha_arriendo, fecha_movimiento, hora_movimiento, id_tipo_movimiento_cta_cte, monto, razon, id_tipo_movimiento, haber, debe, saldo, signo_saldo FROM (
    SELECT 
        ccm.id, 
        ccm.id_propiedad,
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
    GROUP BY ccm.id, ttm.id
) subquery 
WHERE rn = 1
AND subquery.id_ficha_arriendo IS NOT NULL
AND subquery.id_ficha_arriendo IN ($arrayIdsFichasArriendoString)
ORDER BY subquery.id_ficha_arriendo DESC, subquery.fecha_movimiento DESC, subquery.hora_movimiento DESC";

$data = array("consulta" => $querySelectMovInsertados, "cantRegistros" => 99999, "numPagina" => $num_pagina);
$resultado = $services->sendPostNoToken($url_services . '/util/paginacion', $data, []);
$movInsertados = json_decode($resultado, true);
$id_arriendo = $movInsertados[0]["id_ficha_arriendo"];

if (!empty($idsEliminados) && !empty($movInsertados)) {
    // 3. Si se elimina correctamente, se inserta el registro de la acción en tabla historial acciones autorización

    $movInsertadosIds = array_column($movInsertados, 'id');
    $movInsertadosIdsString = implode(", ", $movInsertadosIds);


    $descripcion = "Moras eliminadas de ids ficha arriendo: " . implode(", ", $idsEliminados) . "|Movimientos registrados con IDs: " . $movInsertadosIdsString;

    //tipo de autorización = 1, corresponde a ELIMINACION_MORAS
    $queryInsertAccionAutorizacionUsuario = "INSERT INTO propiedades.historial_acciones_autorizacion
        (id_usuario, id_usuario_autorizador, id_tipo_autorizacion, descripcion)
        VALUES ({$objUsuario['id']}, $idAutorizador, 1, '$descripcion')";

    // jhernandez  una vez se elimina la mora se elimina tambien el codigo
    $queryUpdateHistorialAutorizador = "UPDATE propiedades.historial_autorizadores
        SET codigo_autorizacion = '' WHERE  id_usuario_autorizador = $idAutorizador";

    $dataUpdate = array("consulta" => $queryUpdateHistorialAutorizador);
    $resultadoUpdate = $services->sendPostDirecto($url_services . '/util/dml', $dataUpdate);

    //guarda el historial 
    $queryHistorial = "INSERT INTO propiedades.historial 
        (responsable, accion, item,components, view,descripcion,id_recurso, id_item)
        VALUES ('$correo','Eliminar','Mora','arriendo','arriendo_ficha_tecnica','$descripcion', $id_arriendo,0)";
            $dataCab = array("consulta" => $queryHistorial);
            $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);




    $dataCab = array("consulta" => $queryInsertAccionAutorizacionUsuario);
    $resultadoCab = $services->sendPostDirecto($url_services . '/util/dml', $dataCab);

    if ($resultadoCab) {

        echo "true";
    } else {
        echo "Error al insertar historial de acciones";
    }
} else {
    echo "No se encontraron moras a eliminar";
}
