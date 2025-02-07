<?php

// 1) Mostrar errores (solo para desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../../../app/model/QuerysBuilder.php";

use app\database\QueryBuilder;

// 2) Capturamos los cuatro parámetros
$codigoPropiedad = $_GET['codigo_propiedad'] ?? '';
$propietario     = $_GET['propietario'] ?? '';
$arrendatario    = $_GET['arrendatario'] ?? '';
$codigo_arriendo = $_GET['codigo_arriendo'] ?? '';

// 3) Instanciamos el QueryBuilder
$QueryBuilder = new QueryBuilder();

try {
    // 4) Definir la consulta con las columnas solicitadas
    $table = 'propiedades.propiedad p';
    $columns = "
        p.id AS propiedad_id,
        CONCAT(p.direccion, ' ', p.numero) AS direccion,
        ep.nombre AS estado_propiedad,
        fa.id AS id_arriendo,
        CASE
            WHEN p.habilitado = true THEN 'Vigente'
            ELSE 'Retirada'
        END AS estado,
        CONCAT(
            pnProp.nombres, ' ',
            pnProp.apellido_paterno, ' ',
            pnProp.apellido_materno
        ) AS propietario,
        CONCAT(
            pnArr.nombres, ' ',
            pnArr.apellido_paterno, ' ',
            pnArr.apellido_materno
        ) AS arrendatario,
        p.codigo_propiedad AS codigo_propiedad,
        persArr.dni AS dni_arrendatario,
        persProp.dni AS dni_propietario,
        fa.precio AS precio
    ";

    // 5) Definir los JOINs necesarios
    $joins = [
        ['type' => '',     'table' => 'propiedades.tp_estado_propiedad ep',    'on' => 'p.id_estado_propiedad = ep.id'],
        ['type' => 'LEFT', 'table' => 'propiedades.ficha_arriendo fa',         'on' => 'fa.id_propiedad = p.id'],
        ['type' => 'LEFT', 'table' => 'propiedades.propiedad_copropietarios pcop', 'on' => 'pcop.id_propiedad = p.id'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona_propietario pprop', 'on' => 'pprop.id_persona = pcop.id_propietario'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona persProp',          'on' => 'persProp.id = pprop.id_persona'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona_natural pnProp',    'on' => 'pnProp.id_persona = persProp.id'],
        ['type' => 'LEFT', 'table' => 'propiedades.ficha_arriendo_arrendadores faa', 'on' => 'faa.id_ficha_arriendo = fa.id'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona_arrendatario parr', 'on' => 'parr.id_persona = faa.id_arrendatario'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona persArr',           'on' => 'persArr.id = parr.id_persona'],
        ['type' => 'LEFT', 'table' => 'propiedades.persona_natural pnArr',     'on' => 'pnArr.id_persona = persArr.id'],
    ];

    // 6) Sin condiciones para que no se generen placeholders conflictivos
    $conditions = [];

    // 7) Ejecutar la consulta
    $result = $QueryBuilder->selectAdvanced(
        $table,
        $columns,
        $joins,
        $conditions,
        '',   // orderBy
        '',   // groupBy
        null, // limit
        false,
        false
    );

    // 8) Filtramos manualmente en PHP
    //    Convertimos a minúsculas los valores buscados para comparación case-insensitive
    $codigoPropLower    = mb_strtolower($codigoPropiedad);
    $propietarioLower   = mb_strtolower($propietario);
    $arrendatarioLower  = mb_strtolower($arrendatario);
    $codigoArriendoLower = mb_strtolower($codigo_arriendo);

    // array_filter para quedarnos solo con los registros que cumplan nuestras condiciones “OR”
    $filteredResult = array_filter($result, function ($row) use (
        $codigoPropLower,
        $propietarioLower,
        $arrendatarioLower,
        $codigoArriendoLower
    ) {
        // Convierto a minúsculas cada campo que podría necesitar
        $propiedadCode    = mb_strtolower($row['codigo_propiedad']  ?? '');
        $direccion        = mb_strtolower($row['direccion']         ?? '');
        $idArriendo       = mb_strtolower($row['id_arriendo']       ?? '');
        $propName         = mb_strtolower($row['propietario']       ?? '');
        $dniProp          = mb_strtolower($row['dni_propietario']   ?? '');
        $arrName          = mb_strtolower($row['arrendatario']      ?? '');
        $dniArr           = mb_strtolower($row['dni_arrendatario']  ?? '');

        // 1) Filtrar por 'codigo_propiedad': 
        //    - Debe matchear en 'codigo_propiedad' O 'direccion'
        if (!empty($codigoPropLower)) {
            // Si NO coincide con ninguno de los dos, se descarta
            if (
                strpos($propiedadCode, $codigoPropLower) === false
                && strpos($direccion, $codigoPropLower) === false
            ) {
                return false;
            }
        }

        // 2) Filtrar por 'propietario': 
        //    - Debe matchear en el nombre del propietario O en el dni_propietario
        if (!empty($propietarioLower)) {
            if (
                strpos($propName, $propietarioLower) === false
                && strpos($dniProp, $propietarioLower) === false
            ) {
                return false;
            }
        }

        // 3) Filtrar por 'arrendatario': 
        //    - Debe matchear en el nombre del arrendatario O en el dni_arrendatario
        if (!empty($arrendatarioLower)) {
            if (
                strpos($arrName, $arrendatarioLower) === false
                && strpos($dniArr, $arrendatarioLower) === false
            ) {
                return false;
            }
        }

        // 4) Filtrar por 'codigo_arriendo': 
        //    - Debe matchear en 'id_arriendo' O en la 'direccion' (o la columna que necesites)
        if (!empty($codigoArriendoLower)) {
            if (
                strpos($idArriendo, $codigoArriendoLower) === false
                && strpos($direccion, $codigoArriendoLower) === false
            ) {
                return false;
            }
        }

        // Si pasó todos los filtros, lo dejamos
        return true;
    });

    // Reindexar el array
    $filteredResult = array_values($filteredResult);

    // 9) Devolver en formato JSON
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($filteredResult, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
